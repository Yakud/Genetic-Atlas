<?php
namespace Atlas\Point\Parser;
use Atlas\Point\Point;
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

        $Storage = new PointRedisStorage();

        while ($buffer = fgets($handle)) {
            list($populationId, , $lat, $lon) = explode("\t", trim($buffer));

            if ($populationId == 'Population_ID') {
                continue;
            }

            $Point = new Point([
                'type' => (int)1,
                'lat' => (double)$lat,
                'lon' => (double)$lon,
                'population_id' => $populationId,
            ]);
            var_export($Point);
            $Storage->save($Point);
        }

        fclose($handle);
    }
} 