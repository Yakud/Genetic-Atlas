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
class FirstDataSetParser {
    protected $filePath;

    public function __construct() {
        $this->filePath = PATH_ROOT . '/C_team.txt';
    }

    public function parse() {
        $handle = @fopen($this->filePath, "r");
        if (!$handle) {
            throw new Exception('Error opening file ' . $this->filePath);
        }

        $StorageRedis = new PointRedisStorage();
        $StorageES    = new PointElasticSearchStorage();

        while ($buffer = fgets($handle)) {
            list($populationId, $region, $lat, $lon) = explode("\t", trim($buffer));

            if ($populationId == 'Population_ID') {
                continue;
            }

            $Point = new Point([
                'type' => (int)1,
                'lat' => (double)$lat,
                'lon' => (double)$lon,
                'region' => $region,
                'population_id' => $populationId,
            ]);

            $StorageRedis->save($Point);
            $StorageES->save($Point);

            echo "Save point {$Point->getFieldValue('id')}\n";
        }

        fclose($handle);
    }
} 