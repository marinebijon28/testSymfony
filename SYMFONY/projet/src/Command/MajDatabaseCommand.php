<?php

namespace App\Command;

use App\Entity\AppLog;
use App\Entity\RefCommune;
use App\Entity\RefDepartement;
use App\Entity\RefPays;
use App\Entity\RefRegion;
use App\Service\Sir\Entity\SirCommune;
use App\Service\Sir\Entity\SirDepartement;
use App\Service\Sir\Entity\SirPays;
use App\Service\Sir\Entity\SirRegion;
use DateTime;
use DateTimeZone;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Uid\Uuid;

#[AsCommand(
    name: 'MajDatabase',
    description: 'database update',
)]
class MajDatabaseCommand extends Command
{
    private ObjectManager $_objectManagerSir;
    private ObjectManager $_objectManagerRef;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct();
        $this->_objectManagerSir = $managerRegistry->getManager('sir');
        $this->_objectManagerRef = $managerRegistry->getManager('default');
    }

    /*   protected function configure(): void
       {
           $this
               ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
               ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
           ;
       }
   */

    protected function loopNameTable(OutputInterface $output, string $nameTable): void {
        $output->writeln([
            "<fg=#E3CF30>Mise à jour de la table ref_" . $nameTable . ".",
            "---------------------------------</>",
            "<info>vérification des données : sir_" . $nameTable . " -> ref_" . $nameTable . ".</info>",
        ]);
    }

    protected function loopNewDataInserted(OutputInterface $output, string $nameTable, int $nbData)
    {
        $output->writeln([
            "Ajout des nouvelles données (" . $nbData . ")",
            $nbData . " données insérées\n",
            "<info>Vérification : ref_" . $nameTable . "</info>"
        ]);
    }

    protected function loopToSummarize(OutputInterface $output, object $toSummarize): void
    {
        $output->writeln([
            "<info>Résumé : </info>",
            "Ajout des nouvelles données (" . $toSummarize->numberOfAdditions . ")",
            "Date heure début : " . $toSummarize->dateStartTime->format("Y-m-d H:i:s"),
            "Date heure fin : " . $toSummarize->dateEndTime->format("Y-m-d H:i:s"),
            "Durée : " . $toSummarize->duration,
            "Table : ref_" . $toSummarize->tableRef,
            "Nombre d'enregistrement total : " . $toSummarize->totalRecordNumber,
            "Nombre d'ajouts : " . $toSummarize->numberOfAdditions,
            "Nombre de modifications : " . $toSummarize->numberOfChanges,
            "Nombre d'archivages : " . $toSummarize->numberOfArchives . "\n"
        ]);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln([
            '<fg=#E3CF30>Mise à jour des tables de référence.',
            '====================================</>'
        ]);

// permet de lancer les commandes consoles de symfony
//        $greetInput = new ArrayInput([
//            'command' => 'doctrine:schema:create',
//        ]);
//        $returnCode = $this->getApplication()->Run($greetInput);


        // Pays
        $toSummarize = (object)array(
            "dateStartTime" => new DateTime("now", new DateTimeZone('Europe/Dublin')),
            "dateEndTime" => NULL,
            "duration" => NULL,
            "tableRef" => "pays",
            "totalRecordNumber" => NULL,
            "numberOfAdditions" => 0,
            "numberOfChanges" => NULL,
            "numberOfArchives" => NULL,
        );
        $timeStartDuration = microtime(true);
        $sir = $this->_objectManagerSir->getRepository(SirPays::class);
        $resultSir = $sir->findAll();
        $toSummarize->totalRecordNumber = count($resultSir);
        $this->loopNameTable($output, "pays");
        $ref = $this->_objectManagerRef->getRepository(RefPays::class);
        $appLog = $this->_objectManagerRef->getRepository(AppLog::class);
        $appLog->ifExistTable();
        $ref->ifExistTable();
        $progressBarRef = new ProgressBar($output, count($resultSir));
        $progressBarRef->start();
        for ($index = 0; $index < count($resultSir); $index++) {
            $toSummarize->numberOfAdditions += $ref->existsData($resultSir[$index]);
            $progressBarRef->advance();
        }
        $progressBarRef->finish();
        printf("\n\n");

        $resultRef = $ref->findAll();
        $this->loopNewDataInserted($output, "pays", count($resultRef));
        $progressBarVerification = new ProgressBar($output, count($resultRef));
        $progressBarVerification->start();
        for ($index = 0; $index < count($resultRef); $index++) {
            $time_start = microtime(true);
            $time_end = microtime(true);
            $time = $time_end - $time_start;
            $newAppLog = new AppLog();
            $appLog->fillingTheLogTableRef($resultRef[$index]->getUuid(), $newAppLog, (string)$time,
                "CREATION_ENREGISTREMENT", "ref_pays");
            $appLog->dataRefPays($newAppLog, $resultRef[$index]);
            if ($resultRef[$index]->isArchivage() === TRUE) {
                $newAppLog = new AppLog();
                $appLog->fillingTheLogTableRef($resultRef[$index]->getUuid(), $newAppLog, (string)$time,
                    "ARCHIVAGE_ENREGISTREMENT", "ref_pays");
                $appLog->dataRefPays($newAppLog, $resultRef[$index]);
            }
            $progressBarVerification->advance();
        }
        $progressBarVerification->finish();
        printf("\n\n");
        $timeEndDuration = microtime(true);
        $toSummarize->dateEndTime = new DateTime("now", new DateTimeZone('Europe/Dublin'));
        $timeduration = $timeEndDuration - $timeStartDuration;
        $hours = (int)($timeduration / 60 / 60);
        $minutes = (int)($timeduration / 60) - $hours * 60;
        $seconds = (int)$timeduration - $hours * 60 * 60 - $minutes * 60;
        $toSummarize->duration = ($hours == 0 ? "00" : $hours) . ":" .
            ($minutes == 0 ? "00" : ($minutes < 10 ? "0" . $minutes : $minutes)) . ":" .
            ($seconds == 0 ? "00" : ($seconds < 10 ? "0" . $seconds : $seconds));

        $toSummarize->numberOfChanges = $ref->findByNbModifications();
        $toSummarize->numberOfArchives = $ref->findByNbOfArchives();
        $this->loopToSummarize($output, $toSummarize);



        $refPays = $this->_objectManagerRef->getRepository(RefPays::class)
            ->findOneBy(["libellePaysMaj" => "FRANCE"]);


        // region
        $this->loopNameTable($output, "region");
        $ref = $this->_objectManagerRef->getRepository(RefRegion::class);
        $sir = $this->_objectManagerSir->getRepository(SirRegion::class);
        $ref->ifExistTable();
        $resultSir = $sir->findAll();
        $progressBar = new ProgressBar($output, count($resultSir));
        $progressBar->start();
        for ($index = 0; $index < count($resultSir); $index++) {
            $time_start = microtime(true);
            $refRegion = $ref->existsData($resultSir[$index], $refPays);
            $progressBar->advance();
            $time_end = microtime(true);
            $time = $time_end - $time_start;
            $newAppLog = new AppLog();
            $appLog->fillingTheLogTableRef($refRegion->getUuid(), $newAppLog, (string)$time,
                "CREATION_ENREGISTREMENT", "ref_region");
            $appLog->dataRefRegion($newAppLog, $refRegion);
            if ($refRegion->isArchivage() === TRUE) {
                $newAppLog = new AppLog();
                $appLog->fillingTheLogTableRef($refRegion->getUuid(), $newAppLog, (string)$time,
                    "ARCHIVAGE_ENREGISTREMENT", "ref_region");
                $appLog->dataRefRegion($newAppLog, $refRegion);
            }
        }
        $progressBar->finish();
        printf("\n\n");

        // departement
        $ref = $this->_objectManagerRef->getRepository(RefDepartement::class);
        $sir = $this->_objectManagerSir->getRepository(SirDepartement::class);
        $ref->ifExistTable();
        $this->loopNameTable($output, "departement");
        $resultSir = $sir->findAll();
        $progressBar = new ProgressBar($output, count($resultSir));
        $progressBar->start();
        for ($index = 0; $index < count($resultSir); $index++) {
            $time_start = microtime(true);
            $refRegion = $this->_objectManagerRef->getRepository(RefRegion::class)
                ->findOneBy(["idRegionSir" => $resultSir[$index]->getIdRegion()]);
            $refDepartement = $ref->existsData($resultSir[$index], $refRegion);
            $progressBar->advance();
            $time_end = microtime(true);
            $time = $time_end - $time_start;
            $newAppLog = new AppLog();
            $appLog->fillingTheLogTableRef($refRegion->getUuid(), $newAppLog, (string)$time,
                "CREATION_ENREGISTREMENT", "ref_departement");
            $appLog->dataRefDepartement($newAppLog, $refDepartement);
            if ($refRegion->isArchivage() === TRUE) {
                $newAppLog = new AppLog();
                $appLog->fillingTheLogTableRef($refRegion->getUuid(), $newAppLog, (string)$time,
                    "ARCHIVAGE_ENREGISTREMENT", "ref_departement");
                $appLog->dataRefDepartement($newAppLog, $refDepartement);
            }
        }
        $progressBar->finish();
        printf("\n\n");

        // commune
        $ref = $this->_objectManagerRef->getRepository(RefCommune::class);
        $sir = $this->_objectManagerSir->getRepository(SirCommune::class);
        $ref->ifExistTable();
        $this->loopNameTable($output, "commune");
        $resultSir = $sir->findAll();
        $progressBar = new ProgressBar($output, count($resultSir));
        $progressBar->start();
        for ($index = 0; $index < count($resultSir); $index++) {
            $time_start = microtime(true);
            $refRegion = $this->_objectManagerRef->getRepository(RefRegion::class)
                ->findOneBy(["idRegionSir" => $resultSir[$index]->getIdRegion()]);
            $refDepartement = $this->_objectManagerRef->getRepository(RefDepartement::class)
                ->findOneBy(["idDepartementSir" => $resultSir[$index]->getIdDepartement()]);
            $refCommune = $ref->existsData($resultSir[$index], $refPays, $refRegion, $refDepartement);
            $progressBar->advance();
            $time_end = microtime(true);
            $time = $time_end - $time_start;
            $newAppLog = new AppLog();
            $appLog->fillingTheLogTableRef($refRegion->getUuid(), $newAppLog, (string)$time,
                "CREATION_ENREGISTREMENT", "ref_commune");
            $appLog->dataRefCommune($newAppLog, $refCommune);
            if ($refCommune->isArchivage() === TRUE) {
                $newAppLog = new AppLog();
                $appLog->fillingTheLogTableRef($refRegion->getUuid(), $newAppLog, (string)$time,
                    "ARCHIVAGE_ENREGISTREMENT", "ref_commune");
                $appLog->dataRefCommune($newAppLog, $refCommune);
            }
        }
        $progressBar->finish();
        printf("\n\n");




        // base création command
       // $io = new SymfonyStyle($input, $output);
       /* $arg1 = $input->getArgument('arg1');

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        if ($input->getOption('option1')) {
            // ...
        }*/

      //  $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
