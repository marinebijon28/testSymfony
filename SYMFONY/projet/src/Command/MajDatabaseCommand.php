<?php

namespace App\Command;

use App\Entity\RefPays;
use App\Service\Sir\Entity\SirPays;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln([
            'Mise à jour des tables de référence.',
            '====================================',
            'Mise à jour de la table ref_pays.',
            '---------------------------------',
            'vérification des données : sir_pays -> ref_pays'
        ]);
// permet de lancer les commandes consoles de symfony
//        $greetInput = new ArrayInput([
//            'command' => 'doctrine:schema:create',
//        ]);
//        $returnCode = $this->getApplication()->Run($greetInput);


        // base de données
        $sirPays = $this->_objectManagerSir->getRepository(SirPays::class)->findAll();


        $refPays = $this->_objectManagerRef->getRepository(RefPays::class)->ifExistTableRefPays();




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
