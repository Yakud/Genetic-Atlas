<?php

use Atlas\Marker\MarkerRedisStorage;

require_once __DIR__ . '/../../bootstrap/bootstrap.php';

$filePath = PATH_DATA . '/population_data_23_03_2014.csv';

$handle = @fopen($filePath, "r");
if (!$handle) {
    throw new Exception('Error opening file ' . $this->filePath);
}

$StorageRedis = new MarkerRedisStorage();
$StorageRedis->clearStorage();

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

    $age_from = rand(1000, 50000);

    $Marker = new \Atlas\Marker\Marker([
        'type' => 1,
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
        'age_from' => $age_from,
        'age_to' => $age_from + rand(1000, 3000),
    ]);

    $StorageRedis->save($Marker);
    echo "Save point {$Marker->get('id')}\n";

//    var_export($Marker->export());
//    echo PHP_EOL;
}

fclose($handle);