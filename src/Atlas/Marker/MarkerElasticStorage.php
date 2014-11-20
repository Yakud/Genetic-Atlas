<?php
namespace Atlas\Marker;

use Elastica\Document;
use Elastica\Index;
use Elastica\Type;
use Elastica\Type\Mapping;
use Lib\Storage\ElasticStorage;

/**
 *
 * @author akiselev
 */
class MarkerElasticStorage extends ElasticStorage {
    const INDEX = 'atlas_v1';
    const TYPE  = 'marker';

    /**
     * @param Marker $Marker
     */
    public function save(Marker $Marker) {
        $Type = $this->getType();

        $id = $Marker->get('id');
        $point = $this->makeElasticArray($Marker);

        $pointDocument = new Document($id, $point);
        $Type->addDocument($pointDocument);
//        $Type->getIndex()->refresh();
    }

    /**
     * Возвращает индекс
     * @return Index
     */
    public function getIndex() {
        return $this->getClient()->getIndex(static::INDEX);
    }

    /**
     * Возвращает тип
     * @return Type
     */
    public function getType() {
        return $this->getIndex()->getType(static::TYPE);
    }

    /**
     * Создает индекс
     */
    public function createIndex() {
        $Index = $this->getIndex();

        $Index->create([
            'number_of_shards'   => 3,
            'number_of_replicas' => 1,
        ]);
    }

    public function makeElasticArray(Marker $Marker) {
        $contributor = explode(' / ', $Marker->get('contributor'));

        return array(
            'id'                    => $Marker->get('id'),
            'type'                  => $Marker->get('type'),
            'population_id'         => $Marker->get('population_id'),
            'verbose_population_id' => $Marker->get('verbose_population_id'),
            'region'                => $Marker->get('region'),
            'country'               => $Marker->get('country'),
            'town'                  => $Marker->get('town'),
            'sample_id'             => $Marker->get('sample_id'),
            'sex'                   => $Marker->get('sex'),
            'in_analysis_dataset'   => $Marker->get('in_analysis_dataset'),
            'release_conditions'    => $Marker->get('release_conditions'),
            'contributor'           => $contributor,
            'age_from'              => $Marker->get('age_from'),
            'age_to'                => $Marker->get('age_to'),
            'year'                  => $Marker->get('year'),
            'location' => [
                'lat' => $Marker->get('lat'),
                'lon' => $Marker->get('lon'),
            ],
            'coord' => [
                $Marker->get('lon'),
                $Marker->get('lat'),
            ],
        );
    }

    public function getMarker(array $result) {
        $Marker = new Marker([
            'id' => $result['id'],
            'type' => $result['type'],
            'lat' => $result['location']['lat'],
            'lon' => $result['location']['lon'],
            'population_id' => $result['population_id'],
            'verbose_population_id' => $result['verbose_population_id'],
            'region' => $result['region'],
            'country' => $result['country'],
            'town' => $result['town'],
            'sample_id' => $result['sample_id'],
            'sex' => $result['sex'],
            'in_analysis_dataset' => $result['in_analysis_dataset'],
            'release_conditions' => $result['release_conditions'],
            'contributor' => implode(' / ', $result['contributor']),
            'age_from' => $result['age_from'],
            'age_to' => $result['age_to'],
            'year' => $result['year'],
        ]);

        return $Marker;
    }
    
    public function getMapping() {
        return [
            'id' => array(
                'type' => 'integer',
            ),
            'type' => array(
                'type' => 'integer',
            ),
            'location' => array(
                'type' => 'geo_point',
                'lat_lon' => true,
            ),
            'coord' => array(
                'type' => 'geo_point',
            ),
            'population_id' => array(
                'type' => 'string',
                'index' => 'not_analyzed',
            ),
            'verbose_population_id' => array(
                'type' => 'string',
                'index' => 'not_analyzed',
            ),
            'region' => array(
                'type' => 'string',
                'index' => 'not_analyzed',
            ),
            'country' => array(
                'type' => 'string',
                'index' => 'not_analyzed',
            ),
            'town' => array(
                'type' => 'string',
                'index' => 'not_analyzed',
            ),
            'sample_id' => array(
                'type' => 'string',
                'index' => 'not_analyzed',
            ),
            'sex' => array(
                'type' => 'integer',
            ),
            'in_analysis_dataset' => array(
                'type' => 'boolean',
            ),
            'release_conditions' => array(
                'type' => 'boolean',
            ),
            'contributors' => array(
                "type" => "string",
                'index' => 'not_analyzed',
                "index_name" => "contributor",
            ),
            'age_from' => array(
                'type' => 'integer',
            ),
            'age_to' => array(
                'type' => 'integer',
            ),
            'year' => array(
                'type' => 'integer',
            ),
        ];
    }

    public function updateMapping() {
        $elasticType = $this->getType();

        $mapping = new Mapping();
        $mapping->setType($elasticType);
        $mapping->setProperties($this->getMapping());

        $mapping->send();
    }
} 