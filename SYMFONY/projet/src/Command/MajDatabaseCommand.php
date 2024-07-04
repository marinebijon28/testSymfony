<?php

namespace App\Command;

use App\Entity\RefCommune;
use App\Entity\RefDepartement;
use App\Entity\RefPays;
use App\Entity\RefRegion;
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

    protected function loopNameTable(OutputInterface $output, String $nameTable): void {
        $output->writeln([
            'Mise à jour de la table ref_' . $nameTable . '.',
            '---------------------------------',
            'vérification des données : sir_' . $nameTable . ' -> ref_' . $nameTable . '.'
        ]);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln([
            'Mise à jour des tables de référence.',
            '===================================='
        ]);

// permet de lancer les commandes consoles de symfony
//        $greetInput = new ArrayInput([
//            'command' => 'doctrine:schema:create',
//        ]);
//        $returnCode = $this->getApplication()->Run($greetInput);

        // Pays
        $this->loopNameTable($output, "pays");
        $ref = $this->_objectManagerRef->getRepository(RefPays::class);
        $sir = $this->_objectManagerSir->getRepository(SirPays::class);
        $ref->ifExistTable();
        $resultSir = $sir->findAll();
        $progressBar = new ProgressBar($output, count($resultSir));
        $progressBar->start();
        for ($index = 0; $index < count($resultSir); $index++) {
            $ref->existsData($resultSir[$index]);
            $progressBar->advance();
        }
        $progressBar->finish();
        printf("\n\n");

        // region
        $this->loopNameTable($output, "region");
        $ref = $this->_objectManagerRef->getRepository(RefRegion::class);
        $sir = $this->_objectManagerSir->getRepository(SirRegion::class);
        $ref->ifExistTable();
        $resultSir = $sir->findAll();
        $progressBar = new ProgressBar($output, count($resultSir));
        $progressBar->start();
        $refPays = $this->_objectManagerRef->getRepository(RefPays::class)
            ->findOneBy(["libellePaysMaj" => "FRANCE"]);
        for ($index = 0; $index < count($resultSir); $index++) {
            $ref->existsData($resultSir[$index], $refPays);
            $progressBar->advance();
        }
        $progressBar->finish();
        printf("\n\n");

        // departement
        $ref = $this->_objectManagerRef->getRepository(RefDepartement::class);
        $sir = $this->_objectManagerSir->getRepository(SirDepartement::class);
        $ref->ifExistTable();
        // region
        $this->loopNameTable($output, "departement");
        $resultSir = $sir->findAll();
        $progressBar = new ProgressBar($output, count($resultSir));
        $progressBar->start();


        $date = new DateTime("now", new DateTimeZone('Europe/Dublin') );
        for ($index = 0; $index < count($resultSir); $index++) {



            $refRegion = $this->_objectManagerRef->getRepository(RefRegion::class)
                ->findOneBy(["idRegionSir" => $resultSir[$index]->getIdRegion()]);
            $ref->existsData($resultSir[$index], $refRegion);
            $progressBar->advance();
        }
        $progressBar->finish();
        printf("\n\n");


        $refCommune = $this->_objectManagerRef->getRepository(RefCommune::class)->ifExistTable();




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
