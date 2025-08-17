<?php

namespace PasqualePellicani\GeoLocalitaBundle\Command;

use PasqualePellicani\GeoLocalitaBundle\Entity\Nazione;
use PasqualePellicani\GeoLocalitaBundle\Entity\Regione;
use PasqualePellicani\GeoLocalitaBundle\Entity\Provincia;
use PasqualePellicani\GeoLocalitaBundle\Entity\Citta;
use PasqualePellicani\GeoLocalitaBundle\Entity\Cap;
use PasqualePellicani\GeoLocalitaBundle\Entity\Coordinate;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'geo:import:all',
    description: 'Importa tutti i dati geografici da file (senza servizi custom)'
)]
class GeoImportAllCommand extends Command
{
    private string $basePath;

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly Connection $connection,
        private readonly LoggerInterface $logger,
        #[\Symfony\Component\DependencyInjection\Attribute\Autowire('%kernel.project_dir%')]
        string $projectDir
    ) {
        parent::__construct();
        $this->basePath = $projectDir . '/var/data/geo_localita/';
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Avvio importazione geografica completa (no servizi custom)');

        $this->connection->beginTransaction();
        try {
            $this->importNazioni($io);
            $this->importCodiciZ($io);
            $this->importRegioni($io);
            $this->importProvince($io);
            $this->importCitta($io);
            $this->importCap($io);
            $this->importCoordinate($io);
            $this->importForeignCities($io);

            $this->em->flush();
            $this->connection->commit();

            $io->success('Importazione completata con successo.');
            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $this->connection->rollBack();
            $io->error('Errore durante l\'importazione: ' . $e->getMessage());
            $this->logger->error($e->getMessage(), ['exception' => $e]);
            return Command::FAILURE;
        }
    }

    private function importNazioni(SymfonyStyle $io): void
    {
        $filePath = $this->basePath . 'countryInfo.txt';
        if (!file_exists($filePath)) {
            $io->warning('File countryInfo.txt non trovato, skip.');
            return;
        }

        $io->section('Import nazioni...');
        $file = new \SplFileObject($filePath);

        while (!$file->eof()) {
            $line = trim($file->fgets());
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }
            $columns = explode("\t", $line);
            if (count($columns) < 15) {
                continue;
            }
            $nazione = new Nazione();
            $nazione->setCodiceIso2($columns[0])
                ->setCodiceIso3($columns[1])
                ->setCodiceNumerico((int)$columns[2])
                ->setNome($columns[4])
                ->setCapitale($columns[5] ?: null)
                ->setArea(is_numeric($columns[6]) ? (int)$columns[6] : null)
                ->setPopolazione(is_numeric($columns[7]) ? (int)$columns[7] : null)
                ->setContinente($columns[8] ?: null)
                ->setTld($columns[9] ?: null)
                ->setValuta($columns[10] ?: null)
                ->setNomeValuta($columns[11] ?: null)
                ->setPrefissoTel($columns[12] ?: null)
                ->setGeonameId(is_numeric($columns[16] ?? null) ? (int)$columns[16] : null);

            $this->em->persist($nazione);
        }
        $io->success('Import nazioni ok!');
    }

    private function importRegioni(SymfonyStyle $io): void
    {
        $filePath = $this->basePath . 'italy_regions.xlsx';
        if (!file_exists($filePath)) {
            $io->warning('File italy_regions.xlsx non trovato, skip.');
            return;
        }

        $io->section('Import regioni...');
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, true);

        foreach ($rows as $index => $row) {
            if ($index === 1 || empty($row['A']) || empty($row['B'])) {
                continue; // skip header or incomplete row
            }
            $regione = new Regione();
            $regione->setNome(trim($row['B']));
            $regione->setCapoluogo(trim($row['C']));
            $regione->setSuperficie(floatval(str_replace(',', '.', $row['D'])));
            $regione->setNumResidenti(intval($row['E']));
            $regione->setNumComuni(intval($row['F']));
            $regione->setNumProvincie(intval($row['G']));
            $regione->setCodIstat(trim($row['A']));

            $this->em->persist($regione);
        }

        $this->em->flush();
        $io->success('Import regioni ok!');
    }

    private function importProvince(SymfonyStyle $io): void
    {
        $filePath = $this->basePath . 'italy_provincies.xlsx';
        if (!file_exists($filePath)) {
            $io->warning('File italy_provincies.xlsx non trovato, skip.');
            return;
        }

        $io->section('Import province...');
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, true);

        foreach ($rows as $index => $row) {
            if ($index === 1 || empty($row['A']) || empty($row['B'])) {
                continue;
            }
            // Trova la regione per foreign key
            $idRegione = intval($row['F']);
            $regione = $this->em->getRepository(Regione::class)->find($idRegione);
            if (!$regione) {
                $io->warning("Regione non trovata per id_regione: $idRegione (provincia {$row['B']})");
                continue;
            }

            $provincia = new Provincia();
            $provincia->setSigla(trim($row['A']));
            $provincia->setNome(trim($row['B']));
            $provincia->setSuperficie(floatval(str_replace(',', '.', $row['C'])));
            $provincia->setResidenti(intval($row['D']));
            $provincia->setNumComuni(intval($row['E']));
            $provincia->setRegione($regione);
            $provincia->setCodIstat(trim($row['G']));

            $this->em->persist($provincia);
        }
        $this->em->flush();
        $io->success('Import province ok!');
    }

    private function importCitta(SymfonyStyle $io): void
    {
        $filePath = $this->basePath . 'italy_cities.xlsx';
        if (!file_exists($filePath)) {
            $io->warning('File italy_cities.xlsx non trovato, skip.');
            return;
        }

        $io->section('Import città...');
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, true);

        foreach ($rows as $index => $row) {
            if ($index === 1 || empty($row['A']) || empty($row['B'])) {
                continue;
            }
            $regione = $this->em->getRepository(Regione::class)->findOneBy(['nome' => $row['C']]);
            $provincia = $this->em->getRepository(Provincia::class)->findOneBy(['sigla' => $row['D']]);
            $nazione = $this->em->getRepository(Nazione::class)->find('IT');
            if (!$regione || !$provincia) continue;

            $citta = new Citta();
            $citta->setNazione($nazione);
            $citta->setIstat(trim($row['A']));
            $citta->setNome(trim($row['B']));
            $citta->setRegione($regione);
            $citta->setProvincia($provincia);
            $citta->setPrefisso(trim($row['E'] ?? ''));
            $citta->setCodFisco(trim($row['F'] ?? ''));
            $citta->setNumResidenti(intval($row['G']));
            $citta->setSuperficie(floatval(str_replace(',', '.', $row['H'])));
            $citta->setCf(trim($row['I'] ?? ''));

            $this->em->persist($citta);
        }
        $this->em->flush();
        $io->success('Import città ok!');
    }

    private function importCap(SymfonyStyle $io): void
    {
        // --- Prima passata: MULTI-CAP ---
        $filePathMulti = $this->basePath . 'italy_multicap.xlsx';
        if (file_exists($filePathMulti)) {
            $io->section('Import CAP multipli...');
            $spreadsheet = IOFactory::load($filePathMulti);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, true, true, true);

            foreach ($rows as $index => $row) {
                if ($index === 1 || empty($row['A']) || empty($row['C'])) continue;

                $istat = trim($row['A']);
                $capVal = trim($row['C']);

                $citta = $this->em->getRepository(Citta::class)->findOneBy(['istat' => $istat]);
                if (!$citta) {
                    $this->logger->warning("Comune non trovato per ISTAT (multicap): $istat");
                    continue;
                }

                $cap = new Cap();
                $cap->setCap($capVal);
                $cap->setCitta($citta);

                $this->em->persist($cap);
            }
            $this->em->flush();
            $io->success('Import CAP multipli ok!');
        } else {
            $io->warning('File italy_multicap.xlsx non trovato, skip.');
        }

        // --- Seconda passata: SINGOLI-CAP ---
        $filePathSingle = $this->basePath . 'italy_cap.xlsx';
        if (file_exists($filePathSingle)) {
            $io->section('Import CAP singoli...');
            $spreadsheet = IOFactory::load($filePathSingle);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, true, true, true);

            foreach ($rows as $index => $row) {
                if ($index === 1 || empty($row['A']) || empty($row['B'])) continue;

                // SALTA se la cella CAP contiene un trattino
                if (strpos($row['B'] ?? '', '-') !== false) continue;

                $istat = trim($row['A']);
                $capVal = trim($row['B']);

                // Salta se la città ha già almeno un CAP associato!
                $citta = $this->em->getRepository(Citta::class)->findOneBy(['istat' => $istat]);
                if (!$citta) {
                    $this->logger->warning("Comune non trovato per ISTAT (singolo cap): $istat");
                    continue;
                }
                // controlla se ci sono già CAP associati
                if ($citta->getCap()->count() > 0) continue;

                $cap = new Cap();
                $cap->setCap($capVal);
                $cap->setCitta($citta);

                $this->em->persist($cap);
            }

            $this->em->flush();
            $io->success('Import CAP singoli ok!');
        } else {
            $io->warning('File italy_cap.xlsx non trovato, skip.');
        }
    }


    private function importCoordinate(SymfonyStyle $io): void
    {
        $filePath = $this->basePath . 'italy_geo.xlsx';
        if (!file_exists($filePath)) {
            $io->warning('File italy_geo.xlsx non trovato, skip.');
            return;
        }

        $io->section('Import coordinate...');
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, true);

        foreach ($rows as $index => $row) {
            if ($index === 1 || empty($row['A']) || empty($row['B']) || empty($row['C'])) {
                continue;
            }
            $istat = trim($row['A']);
            $lng = floatval(str_replace(',', '.', $row['B']));
            $lat = floatval(str_replace(',', '.', $row['C']));
            $citta = $this->em->getRepository(Citta::class)->findOneBy(['istat' => $istat]);
            if (!$citta) continue;

            $coord = new Coordinate();
            $coord->setCitta($citta)
                ->setLng($lng)
                ->setLat($lat);

            $this->em->persist($coord);
        }
        $io->success('Import coordinate ok!');
    }

    private function importForeignCities(SymfonyStyle $io): void
    {
        $filePath = $this->basePath . 'cities5000.txt';
        if (!file_exists($filePath)) {
            $io->warning('File cities5000.txt non trovato, skip.');
            return;
        }

        $io->section('Import città estere...');
        $file = new \SplFileObject($filePath);

        while (!$file->eof()) {
            $line = trim($file->fgets());
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }
            $columns = explode("\t", $line);
            if (count($columns) < 19) {
                continue;
            }
            // Trova la nazione
            $countryCode = strtoupper($columns[8]);

            $nazione = $this->em->getRepository(Nazione::class)->find($countryCode);

            if (!$nazione) continue;

            if ($nazione->getCodiceIso2() === 'IT') {
                continue; // Skippa le città italiane!
            }

            $citta = new Citta();
            $citta->setNome($columns[1]);
            $citta->setGeonameid((int)$columns[0]);
            $citta->setNazione($nazione);
            // NB: Non valorizzo regione/provincia/prefisso/cod_fisco/istat

            $this->em->persist($citta);
        }
        $io->success('Import città estere ok!');
    }

    private function importCodiciZ(SymfonyStyle $io): void
    {
        $io->section('Import codici Zxxx (ANPR) ...');

        $fileName = 'tabella_2_statiesteri.xlsx';
        $filePath = $this->basePath . $fileName;

        // 1) Se manca il file locale, prova a scaricarlo dalla fonte ufficiale
        if (!file_exists($filePath)) {
            $io->text('File ANPR non trovato in var/data, tentativo di download ...');
            $url = 'https://www.anagrafenazionale.interno.it/wp-content/uploads/tabella_2_statiesteri.xlsx';

            if (!is_dir($this->basePath)) {
                @mkdir($this->basePath, 0775, true);
            }

            try {
                // scarica in modo semplice senza dipendenze aggiuntive
                $data = @file_get_contents($url);
                if ($data === false) {
                    $io->warning('Download fallito (non blocco l\'import). URL: ' . $url);
                    return;
                }
                file_put_contents($filePath, $data);
                $io->text("Scaricato: $filePath");
            } catch (\Throwable $e) {
                $io->warning('Download fallito: ' . $e->getMessage());
                return;
            }
        }

        // 2) Parse XLSX (colonne attese: CODISO3166_1_ALPHA3, CODAT)
        try {
            $spreadsheet = IOFactory::load($filePath);
        } catch (\Throwable $e) {
            $io->warning('Impossibile leggere XLSX ANPR: ' . $e->getMessage());
            return;
        }

        $sheet = $spreadsheet->getActiveSheet();
        $rows  = $sheet->toArray(null, true, true, true);

        if (count($rows) < 2) {
            $io->warning('File ANPR vuoto o non valido.');
            return;
        }

        // 2.a. individua riga header entro le prime 5 righe
        $headerRowIndex = null;
        $iso3Col = null;
        $codatCol = null;

        $norm = function ($v) {
            return strtoupper(trim((string)$v));
        };

        for ($r = 1; $r <= min(5, count($rows)); $r++) {
            $head = array_map($norm, $rows[$r] ?? []);
            if (!$head) continue;

            // cerca le intestazioni più comuni
            $foundIso3 = null;
            $foundCodat = null;
            foreach ($head as $col => $name) {
                if (in_array($name, ['CODISO3166_1_ALPHA3', 'ISO3', 'CODISO3'], true)) $foundIso3 = $col;
                if (in_array($name, ['CODAT', 'CODICE AT', 'COD.AT', 'CODICE_AT'], true)) $foundCodat = $col;
            }
            if ($foundIso3 && $foundCodat) {
                $headerRowIndex = $r;
                $iso3Col = $foundIso3;
                $codatCol = $foundCodat;
                break;
            }
        }

        if (!$headerRowIndex) {
            $io->warning('Header con colonne ISO3/CODAT non trovato nell\'XLSX ANPR.');
            return;
        }

        // 3) Applica mapping ISO3 -> CODAT sulle Nazioni
        $updated = 0;
        $skipped = 0;
        $notFound = 0;

        // percorri le righe dati
        for ($r = $headerRowIndex + 1; $r <= count($rows); $r++) {
            $row = $rows[$r] ?? null;
            if (!$row) continue;

            $iso3  = $norm($row[$iso3Col] ?? '');
            $codat = $norm($row[$codatCol] ?? '');

            if ($iso3 === '' || $codat === '') {
                $skipped++;
                continue;
            }
            if (!preg_match('/^Z\d{3}$/', $codat)) {
                $skipped++;
                continue;
            }

            // trova la nazione per ISO3
            $nazione = $this->em->getRepository(Nazione::class)->findOneBy(['codiceIso3' => $iso3]);
            if (!$nazione) {
                $notFound++;
                continue;
            }

            // evita scritture ridondanti
            if ($nazione->getCodiceZ() !== $codat) {
                $nazione->setCodiceZ($codat);
                $this->em->persist($nazione);
                $updated++;
            }

            // flush batch
            if (($updated % 250) === 0) {
                $this->em->flush();
                $this->em->clear();
            }
        }

        $this->em->flush();

        $io->success("Codici Zxxx applicati. Aggiornati={$updated}, Skippati={$skipped}, ISO3 non trovati={$notFound}");
    }
}
