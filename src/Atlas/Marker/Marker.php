<?php
namespace Atlas\Marker;

use Lib\Storage\Storage;

/**
 *
 * @author akiselev
 */
class Marker extends Storage {
    public function getConfig() {
        return [
            'id' => array(
                'default' => 0,
                'description' => 'ID',
            ),
            'type' => array(
                'default' => null,
                'description' => 'Тип',
            ),
            'lat' => array(
                'default' => 0.0,
                'description' => 'Широта',
            ),
            'lon' => array(
                'default' => 0.0,
                'description' => 'Долгота',
            ),
            'population_id' => array(
                'default' => '',
                'description' => 'ID популяции',
            ),
            'verbose_population_id' => array(
                'default' => '',
                'description' => 'ID популяции (полный)',
            ),
            'region' => array(
                'default' => '',
                'description' => 'Регион',
            ),
            'country' => array(
                'default' => '',
                'description' => 'Страна',
            ),
            'town' => array(
                'default' => '',
                'description' => 'Город',
            ),
            'sample_id' => array(
                'default' => '',
                'description' => 'ID образца',
            ),
            'sex' => array(
                'default' => 0,
                'description' => 'Пол',
            ),
            'in_analysis_dataset' => array(
                'default' => 0,
                'description' => 'В анализе набора данных',
            ),
            'release_conditions' => array(
                'default' => 0,
                'description' => 'Кандидат',
            ),
            'contributor' => array(
                'default' => '',
                'description' => 'Источник',
            ),
            'age' => array(
                'default' => 0,
                'description' => 'Возраст',
            ),
            'age_from' => array(
                'default' => 0,
                'description' => 'Возраст от ',
            ),
            'age_to' => array(
                'default' => 0,
                'description' => 'Возраст до',
            ),
        ];
    }
} 