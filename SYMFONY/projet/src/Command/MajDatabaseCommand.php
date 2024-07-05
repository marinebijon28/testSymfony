<?php

namespace App\Command;

use App\Entity\AppLog;
use App\Entity\RefCommune;
use App\Entity\RefDepartement;
use App\Entity\RefMaj;
use App\Entity\RefPays;
use App\Entity\RefRegion;
use App\Repository\AppLogRepository;
use App\Repository\RefCommuneRepository;
use App\Repository\RefDepartementRepository;
use App\Repository\RefMajRepository;
use App\Repository\RefPaysRepository;
use App\Repository\RefRegionRepository;
use App\Service\Sir\Entity\SirCommune;
use App\Service\Sir\Entity\SirDepartement;
use App\Service\Sir\Entity\SirPays;
use App\Service\Sir\Entity\SirRegion;
use DateTime;
use DateTimeZone;
use Doctrine\DBAL\Exception;
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

    /** loopNameTable
     *
     * output standard of all names tables
     *
     * @param OutputInterface $output
     * @param string $nameTable
     * @return void
     */
    protected function loopNameTable(OutputInterface $output, string $nameTable): void {
        $output->writeln([
            "<fg=#E3CF30>Mise à jour de la table ref_" . $nameTable . ".",
            "---------------------------------</>",
            "<info>vérification des données : sir_" . $nameTable . " -> ref_" . $nameTable . ".</info>",
        ]);
    }

    /** loopNewDataInserted
     *
     * output standard of update table
     *
     * @param OutputInterface $output
     * @param string $nameTable
     * @param int $nbData
     * @return void
     */
    protected function loopNewDataInserted(OutputInterface $output, string $nameTable, int $nbData)
    {
        $output->writeln([
            "Ajout des nouvelles données (" . $nbData . ")",
            $nbData . " données insérées\n",
            "<info>Vérification : ref_" . $nameTable . "</info>"
        ]);
    }

    /** loopToSummarize
     *
     * summary of updates tables
     *
     * @param OutputInterface $output
     * @param object $toSummarize
     * @return void
     */
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

    /** fillingContryTable
     *
     * insert data in contry table
     *
     * @param OutputInterface $output
     * @param object $toSummarize
     * @param RefPaysRepository $ref
     * @param array $resultSir
     * @return void
     * @throws \Exception
     */
    protected function fillingContryTable(OutputInterface $output, object $toSummarize, RefPaysRepository $ref,
                                          array $resultSir): array {
        $this->loopNameTable($output, "pays");
        $progressBarRef = new ProgressBar($output, count($resultSir));
        $progressBarRef->start();
        $time = array();
        for ($index = 0; $index < count($resultSir); $index++) {
            $time_start = microtime(true);
            $toSummarize->numberOfAdditions += $ref->existsData($resultSir[$index]);
            $progressBarRef->advance();
            $time_end = microtime(true);
            $time[] = ($time_end - $time_start);
        }
        $progressBarRef->finish();
        printf("\n\n");
        return $time;
    }

    /** fillingRegionTable
     *
     * insert data in region table
     *
     * @param OutputInterface $output
     * @param object $toSummarize
     * @param RefRegionRepository $ref
     * @param array $resultSir
     * @param RefPays $refPays
     * @return void
     */
    protected function fillingRegionTable(OutputInterface $output, object $toSummarize, RefRegionRepository $ref,
                                          array $resultSir, RefPays $refPays): array {
        $this->loopNameTable($output, "region");
        $progressBarRef = new ProgressBar($output, count($resultSir));
        $progressBarRef->start();
        $time = array();
        for ($index = 0; $index < count($resultSir); $index++) {
            $time_start = microtime(true);
            $toSummarize->numberOfAdditions += $ref->existsData($resultSir[$index], $refPays);
            $progressBarRef->advance();
            $time_end = microtime(true);
            $time[] = ($time_end - $time_start);
        }
        $progressBarRef->finish();
        printf("\n\n");
        return $time;
    }

    /** fillingDepartementTable
     *
     * insert data in departement table
     *
     * @param OutputInterface $output
     * @param object $toSummarize
     * @param RefDepartementRepository $ref
     * @param array $resultSir
     * @return void
     * @throws \Exception
     */
    protected function fillingDepartementTable(OutputInterface $output, object $toSummarize,
                                               RefDepartementRepository $ref, array $resultSir): array {
        $this->loopNameTable($output, "departement");
        $progressBarRef = new ProgressBar($output, count($resultSir));
        $progressBarRef->start();
        $time = array();
        for ($index = 0; $index < count($resultSir); $index++) {
            $time_start = microtime(true);
            $refRegion = $this->_objectManagerRef->getRepository(RefRegion::class)
                ->findOneBy(["idRegionSir" => $resultSir[$index]->getIdRegion()]);
            $toSummarize->numberOfAdditions += $ref->existsData($resultSir[$index], $refRegion);
            $progressBarRef->advance();
            $time_end = microtime(true);
            $time[] = ($time_end - $time_start);
        }
        $progressBarRef->finish();
        printf("\n\n");
        return $time;
    }

    /** fillingCommuneTable
     *
     * insert data in commune table
     *
     * @param OutputInterface $output
     * @param object $toSummarize
     * @param RefDepartementRepository $ref
     * @param array $resultSir
     * @param RefPays $refPays
     * @return void
     * @throws \Exception
     */
    protected function fillingCommuneTable(OutputInterface $output, object $toSummarize,
                                           RefCommuneRepository $ref, array $resultSir, RefPays $refPays): array{
        $this->loopNameTable($output, "commune");
        $progressBarRef = new ProgressBar($output, count($resultSir));
        $progressBarRef->start();
        $time = array();
        for ($index = 0; $index < count($resultSir); $index++) {
            $time_start = microtime(true);
            $refRegion = $this->_objectManagerRef->getRepository(RefRegion::class)
                ->findOneBy(["idRegionSir" => $resultSir[$index]->getIdRegion()]);
            $refDepartement = $this->_objectManagerRef->getRepository(RefDepartement::class)
                ->findOneBy(["idDepartementSir" => $resultSir[$index]->getIdDepartement()]);
            $toSummarize->numberOfAdditions += $ref->existsData($resultSir[$index], $refPays, $refRegion,
                $refDepartement);
            $progressBarRef->advance();
            $time_end = microtime(true);
            $time[] = ($time_end - $time_start);
        }
        $progressBarRef->finish();
        printf("\n\n");
        return $time;
    }

    /** fillingAppLogContryTable
     *
     * insert data contry table in app_log
     *
     * @param OutputInterface $output
     * @param RefPaysRepository $ref
     * @param AppLogRepository $appLog
     * @return void
     */
    protected function fillingAppLogContryTable(OutputInterface  $output, RefPaysRepository $ref,
                                                AppLogRepository $appLog, array $time) {
        $resultRef = $ref->findAll();
        $this->loopNewDataInserted($output, "pays", count($resultRef));
        $progressBarVerification = new ProgressBar($output, count($resultRef));
        $progressBarVerification->start();
        for ($index = 0; $index < count($resultRef); $index++) {
            $newAppLog = new AppLog();
            $appLog->fillingTheLogTableRef($resultRef[$index]->getUuid(), $newAppLog, (string)$time[$index],
                "CREATION_ENREGISTREMENT", "ref_pays");
            $appLog->dataRefPays($newAppLog, $resultRef[$index]);
            if ($resultRef[$index]->isArchivage() === TRUE) {
                $newAppLog = new AppLog();
                $appLog->fillingTheLogTableRef($resultRef[$index]->getUuid(), $newAppLog, (string)$time[$index],
                    "ARCHIVAGE_ENREGISTREMENT", "ref_pays");
                $appLog->dataRefPays($newAppLog, $resultRef[$index]);
            }
            $progressBarVerification->advance();
        }
        $progressBarVerification->finish();
        printf("\n\n");
    }

    /** fillingAppLogRegionTable
     *
     * insert data region table in app_log
     *
     * @param OutputInterface $output
     * @param RefRegionRepository $ref
     * @param AppLogRepository $appLog
     * @return void
     */
    protected function fillingAppLogRegionTable(OutputInterface  $output, RefRegionRepository $ref,
                                                AppLogRepository $appLog, array $time) {
        $resultRef = $ref->findAll();
        $this->loopNewDataInserted($output, "region", count($resultRef));
        $progressBarVerification = new ProgressBar($output, count($resultRef));
        $progressBarVerification->start();
        for ($index = 0; $index < count($resultRef); $index++) {
            $newAppLog = new AppLog();
            $appLog->fillingTheLogTableRef($resultRef[$index]->getUuid(), $newAppLog, (string)$time[$index],
                "CREATION_ENREGISTREMENT", "ref_region");
            $appLog->dataRefRegion($newAppLog, $resultRef[$index]);
            if ($resultRef[$index]->isArchivage() === TRUE) {
                $newAppLog = new AppLog();
                $appLog->fillingTheLogTableRef($resultRef[$index]->getUuid(), $newAppLog, (string)$time[$index],
                    "ARCHIVAGE_ENREGISTREMENT", "ref_region");
                $appLog->dataRefRegion($newAppLog, $resultRef[$index]);
            }
            $progressBarVerification->advance();
        }
        $progressBarVerification->finish();
        printf("\n\n");
    }

    /** fillingAppLogDepartementTable
     *
     * insert data departement table in app_log
     *
     * @param OutputInterface $output
     * @param RefDepartementRepository $ref
     * @param AppLogRepository $appLog
     * @return void
     * @throws \Exception
     */
    protected function fillingAppLogDepartementTable(OutputInterface $output, RefDepartementRepository $ref,
                                                     AppLogRepository $appLog, array $time) {
        $resultRef = $ref->findAll();
        $this->loopNewDataInserted($output, "departement", count($resultRef));
        $progressBarVerification = new ProgressBar($output, count($resultRef));
        $progressBarVerification->start();
        for ($index = 0; $index < count($resultRef); $index++) {
            $newAppLog = new AppLog();
            $appLog->fillingTheLogTableRef($resultRef[$index]->getUuid(), $newAppLog, (string)$time[$index],
                "CREATION_ENREGISTREMENT", "ref_departement");
            $appLog->dataRefDepartement($newAppLog, $resultRef[$index]);
            if ($resultRef[$index]->isArchivage() === TRUE) {
                $newAppLog = new AppLog();
                $appLog->fillingTheLogTableRef($resultRef[$index]->getUuid(), $newAppLog, (string)$time[$index],
                    "ARCHIVAGE_ENREGISTREMENT", "ref_departement");
                $appLog->dataRefDepartement($newAppLog, $resultRef[$index]);
            }
            $progressBarVerification->advance();
        }
        $progressBarVerification->finish();
        printf("\n\n");
    }

    /** fillingAppLogCommuneTable
     *
     * insert data commune table in app_log
     *
     * @param OutputInterface $output
     * @param RefCommuneRepository $ref
     * @param AppLogRepository $appLog
     * @return void
     * @throws \Exception
     */
    protected function fillingAppLogCommuneTable(OutputInterface  $output, RefCommuneRepository $ref,
                                                 AppLogRepository $appLog, array $time) {
        $resultRef = $ref->findAll();
        $this->loopNewDataInserted($output, "commune", count($resultRef));
        $progressBarVerification = new ProgressBar($output, count($resultRef));
        $progressBarVerification->start();
        for ($index = 0; $index < count($resultRef); $index++) {
            $newAppLog = new AppLog();
            $appLog->fillingTheLogTableRef($resultRef[$index]->getUuid(), $newAppLog, (string)$time[$index],
                "CREATION_ENREGISTREMENT", "ref_commune");
            $appLog->dataRefCommune($newAppLog, $resultRef[$index]);
            if ($resultRef[$index]->isArchivage() === TRUE) {
                $newAppLog = new AppLog();
                $appLog->fillingTheLogTableRef($resultRef[$index]->getUuid(), $newAppLog, (string)$time[$index],
                    "ARCHIVAGE_ENREGISTREMENT", "ref_commune");
                $appLog->dataRefCommune($newAppLog, $resultRef[$index]);
            }
            $progressBarVerification->advance();
        }
        $progressBarVerification->finish();
        printf("\n\n");
    }

    /** summary
     *
     * insert data of update table in maj_ref
     *
     * @param OutputInterface $output
     * @param object $toSummarize
     * @param float $timeStartDuration
     * @param RefMajRepository $refMaj
     * @return void
     * @throws Exception
     */
    protected function summary(OutputInterface $output, object $toSummarize,
                                     float $timeStartDuration, RefMajRepository $refMaj) {
        $timeEndDuration = microtime(true);
        $toSummarize->dateEndTime = new DateTime("now", new DateTimeZone('Europe/Dublin'));
        $timeduration = $timeEndDuration - $timeStartDuration;
        $hours = (int)($timeduration / 60 / 60);
        $minutes = (int)($timeduration / 60) - $hours * 60;
        $seconds = (int)$timeduration - $hours * 60 * 60 - $minutes * 60;
        $toSummarize->duration = ($hours == 0 ? "00" : $hours) . ":" .
            ($minutes == 0 ? "00" : ($minutes < 10 ? "0" . $minutes : $minutes)) . ":" .
            ($seconds == 0 ? "00" : ($seconds < 10 ? "0" . $seconds : $seconds));

        $refMaj->insertValue($toSummarize);
        $this->loopToSummarize($output, $toSummarize);
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        ini_set('memory_limit', '4967296M');

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


        // init
        $toSummarize = (object)array(
            "dateStartTime" => new DateTime("now", new DateTimeZone('Europe/Dublin')),
            "dateEndTime" => NULL,
            "duration" => NULL,
            "tableRef" => "pays",
            "totalRecordNumber" => 0,
            "numberOfAdditions" => 0,
            "numberOfChanges" => 0,
            "numberOfArchives" => 0,
        );
        $timeStartDuration = microtime(true);

        $sir = $this->_objectManagerSir->getRepository(SirPays::class);
        $ref = $this->_objectManagerRef->getRepository(RefPays::class);
        $appLog = $this->_objectManagerRef->getRepository(AppLog::class);
        $refMaj = $this->_objectManagerRef->getRepository(RefMaj::class);


        // add table if not exist
        $refMaj->ifExistTable();
        $appLog->ifExistTable();
        $ref->ifExistTable();

        $resultSir = $sir->findAll();
        $toSummarize->totalRecordNumber = count($resultSir);

        // Pays
        $time = $this->fillingContryTable($output, $toSummarize, $ref, $resultSir);
        $this->fillingAppLogContryTable($output, $ref, $appLog, $time);
        $toSummarize->numberOfChanges = $ref->findByNbModifications();
        $toSummarize->numberOfArchives = $ref->findByNbOfArchives();
        $this->summary($output, $toSummarize, $timeStartDuration, $refMaj);


        $refPays = $this->_objectManagerRef->getRepository(RefPays::class)
            ->findOneBy(["libellePaysMaj" => "FRANCE"]);

        // init
        $toSummarize = (object)array(
            "dateStartTime" => new DateTime("now", new DateTimeZone('Europe/Dublin')),
            "dateEndTime" => NULL,
            "duration" => NULL,
            "tableRef" => "region",
            "totalRecordNumber" => 0,
            "numberOfAdditions" => 0,
            "numberOfChanges" => 0,
            "numberOfArchives" => 0,
        );
        $timeStartDuration = microtime(true);

        // add table if not exist
        $this->loopNameTable($output, "region");
        $ref = $this->_objectManagerRef->getRepository(RefRegion::class);
        $sir = $this->_objectManagerSir->getRepository(SirRegion::class);
        $ref->ifExistTable();
        $resultSir = $sir->findAll();


        // region
        $time = $this->fillingRegionTable($output, $toSummarize, $ref, $resultSir, $refPays);
        $this->fillingAppLogRegionTable($output, $ref, $appLog, $time);
        $toSummarize->numberOfChanges = $ref->findByNbModifications();
        $toSummarize->numberOfArchives = $ref->findByNbOfArchives();
        $this->summary($output, $toSummarize, $timeStartDuration, $refMaj);

        // init
        $toSummarize = (object)array(
            "dateStartTime" => new DateTime("now", new DateTimeZone('Europe/Dublin')),
            "dateEndTime" => NULL,
            "duration" => NULL,
            "tableRef" => "departement",
            "totalRecordNumber" => 0,
            "numberOfAdditions" => 0,
            "numberOfChanges" => 0,
            "numberOfArchives" => 0,
        );
        $timeStartDuration = microtime(true);

        // add table if not exist
        $ref = $this->_objectManagerRef->getRepository(RefDepartement::class);
        $sir = $this->_objectManagerSir->getRepository(SirDepartement::class);
        $ref->ifExistTable();
        $this->loopNameTable($output, "departement");
        $resultSir = $sir->findAll();

        // departement
        $time = $this->fillingDepartementTable($output, $toSummarize, $ref, $resultSir);
        $this->fillingAppLogDepartementTable($output, $ref, $appLog, $time);
        $toSummarize->numberOfChanges = $ref->findByNbModifications();
        $toSummarize->numberOfArchives = $ref->findByNbOfArchives();
        $this->summary($output, $toSummarize, $timeStartDuration, $refMaj);

        // init
        $toSummarize = (object)array(
            "dateStartTime" => new DateTime("now", new DateTimeZone('Europe/Dublin')),
            "dateEndTime" => NULL,
            "duration" => NULL,
            "tableRef" => "commune",
            "totalRecordNumber" => 0,
            "numberOfAdditions" => 0,
            "numberOfChanges" => 0,
            "numberOfArchives" => 0,
        );
        $timeStartDuration = microtime(true);

        // commune
        $ref = $this->_objectManagerRef->getRepository(RefCommune::class);
        $sir = $this->_objectManagerSir->getRepository(SirCommune::class);
        $ref->ifExistTable();
        $this->loopNameTable($output, "commune");
        $resultSir = $sir->findAll();

        $time = $this->fillingCommuneTable($output, $toSummarize, $ref, $resultSir, $refPays);
        $this->fillingAppLogCommuneTable($output, $ref, $appLog, $time);
        $toSummarize->numberOfChanges = $ref->findByNbModifications();
        $toSummarize->numberOfArchives = $ref->findByNbOfArchives();
        $this->summary($output, $toSummarize, $timeStartDuration, $refMaj);

        return Command::SUCCESS;
    }
}
