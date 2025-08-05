<?php
namespace PasqualePellicani\GeoLocalitaBundle\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

#[AsCommand(
    name: 'geo:setup-dirs',
    description: 'Crea la cartella var/data/geo_localita se non esiste'
)]
class GeoSetupDirsCommand extends Command
{
    public function __construct(
        #[Autowire('%kernel.project_dir%')]
        private readonly string $projectDir
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $dir = $this->projectDir . '/var/data/geo_localita';

        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
            $io->success("Cartella $dir creata!");
        } else {
            $io->note("La cartella $dir esiste già.");
        }

        return Command::SUCCESS;
    }
}
