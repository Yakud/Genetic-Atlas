<?php
namespace Atlas\Point\Parser;
use Atlas\Point\Point;
use Atlas\Point\PointElasticSearchStorage;
use Atlas\Point\PointRedisStorage;
use Exception;

/**
 * Парсер первого набо
 * @author akiselev
 */
class SecondDataSetParser {
    protected $filePath;

    public function __construct() {
        $this->filePath = PATH_ROOT . '/23_03_2014 Sampl and population data.csv';
    }

    public function parse() {
        $handle = @fopen($this->filePath, "r");
        if (!$handle) {
            throw new Exception('Error opening file ' . $this->filePath);
        }

        $StorageRedis = new PointRedisStorage();
        $StorageES    = new PointElasticSearchStorage();

        $line = 0;
        while ($buffer = fgets($handle)) {
            list(
                $sample_id,
                $sex,
                $verbose_population_id,
                $simple_population_id,
                $region,
                $country,
                $town,
                $lat,
                $lon,
                $in_analysis_dataset,
                $release_conditions,
                $contributor,
            ) = explode("\t", trim($buffer));

            if ($sample_id == 'Sample ID') {
                // Первая строка нам не нужна
                continue;
            }

            if (!$town || $town == '?' || $town == 'n/a') {
                $town = null;
            }

            $Point = new Point([
                'type' => 2,
                'sample_id' => $sample_id,
                'lat' => (double)$lat,
                'lon' => (double)$lon,
                'sex' => $sex == 'M' ? 1 : 0,
                'verbose_population_id' => $verbose_population_id,
                'population_id' => $simple_population_id,
                'region' => $region,
                'country' => $country,
                'town' => $town,
                'in_analysis_dataset' => (bool)$in_analysis_dataset,
                'release_conditions' => (bool)$release_conditions,
                'contributor' => $contributor,
            ]);

            $StorageRedis->save($Point);
            $StorageES->save($Point);

            echo "Save point {$Point->getFieldValue('id')}\n";
        }

        fclose($handle);
    }
} 