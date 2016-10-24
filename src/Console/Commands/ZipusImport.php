<?php

namespace Dpovshed\Zipus\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Output\OutputInterface;
use League\Flysystem\Exception;

class ZipusImport extends Command
{
    const CSVFILE = "http://federalgovernmentzipcodes.us/free-zipcode-database-Primary.csv";

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zipus-import {--filename=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import the primary locations only CSV file from federalgovernmentzipcodes.us';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $filename = (($this->option('filename')) ?: self::CSVFILE);
        try {
            $lines = file($filename);
            $headers = explode(',', trim($lines[0]));
            $headers = str_replace('"', '', $headers);
            $countColumns = count($headers);
            $countMalformed = 0;
            $zipusCity = [];
            $zipusAll = [];
            $this->comment("Columns found: $countColumns" . PHP_EOL, OutputInterface::VERBOSITY_VERBOSE);
            // Skip first line and first column.
            unset($lines[0]);
            unset($headers[0]);
            foreach ($lines as $line) {
                $item = explode(',', trim($line));
                $item = str_replace('"', '', $item);
                if (count($item) != $countColumns) {
                    $this->comment("Malformed row, bad number of columns: $line", OutputInterface::VERBOSITY_VERY_VERBOSE);
                    $countMalformed++;
                    continue;
                }
                $zipcode = str_replace('"', '', $item[0]);
                $zipusCity[$zipcode] = ucwords(strtolower(str_replace('"', '', $item[2])));
                $itemNamed = [];
                foreach ($headers as $i => $header) {
                    $itemNamed[$header] = $item[$i];
                }
                $zipusAll[$zipcode] = $itemNamed;
            }
            $filenameCity = storage_path('framework/cache/zipus_city.json');
            $filenameAll = storage_path('framework/cache/zipus_all.json');
            $jsonCity = json_encode($zipusCity);
            $jsonAll = json_encode($zipusAll);
            file_put_contents($filenameCity, $jsonCity);
            file_put_contents($filenameAll, $jsonAll);
            $count = count($lines) - $countMalformed;
            $this->comment("Zip data cached locally, $count items.");
        }
        catch (Exception $e) {
            $this->comment(PHP_EOL . 'Import error: ' . $e->getMessage());
        }
    }
}
