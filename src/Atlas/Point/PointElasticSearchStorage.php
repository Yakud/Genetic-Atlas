<?php
namespace Atlas\Point;

use Elastica\Client;
use Elastica\Document;
use Elastica\Index;
use Elastica\Type\Mapping;
use Elastica\Type;


/**
 * Сторадж точек на основе ElasticSearch
 * @author akiselev
 */
class PointElasticSearchStorage {
    const INDEX = 'atlas';
    const TYPE  = 'point';

    /**
     * @var Client
     */
    protected $Client = null;

    public function updateMapping() {
        $elasticType = $this->getType();

        $mapping = new Mapping();
        $mapping->setType($elasticType);
        $mapping->setProperties(array(
            'id' => array(
                'type' => 'integer',
            ),
            'type' => array(
                'type' => 'integer',
                'index' => 'not_analyzed',
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
            ),
            'verbose_population_id' => array(
                'type' => 'string',
            ),
            'region' => array(
                'type' => 'string',
            ),
            'country' => array(
                'type' => 'string',
            ),
            'town' => array(
                'type' => 'string',
            ),
            'sample_id' => array(
                'type' => 'string',
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
            'contributor' => array(
                'type' => 'string',
            ),
        ));

        $mapping->send();
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
            'number_of_shards' => 4,
            'number_of_replicas' => 1,
        ]);
    }

    /**
     * Сохраняет точку в ES
     * @param Point $Point
     */
    public function save(Point $Point) {
        $Type = $this->getType();

        $id = $Point->getFieldValue('id');
        $point = $this->makeElasticArray($Point);

        $pointDocument = new Document($id, $point);
        $Type->addDocument($pointDocument);
        $Type->getIndex()->refresh();
    }

    public function makeElasticArray(Point $Point) {
        return array(
            'id' => $Point->getFieldValue('id'),
            'type' => $Point->getFieldValue('type'),
            'location' => array(
                'lat' => $Point->getFieldValue('lat'),
                'lon' => $Point->getFieldValue('lon'),
            ),
            'coord' => [
                $Point->getFieldValue('lon'),
                $Point->getFieldValue('lat'),
            ],
            'population_id' => $Point->getFieldValue('population_id'),
            'verbose_population_id' => $Point->getFieldValue('verbose_population_id'),
            'region' => $Point->getFieldValue('region'),
            'country' => $Point->getFieldValue('country'),
            'town' => $Point->getFieldValue('town'),
            'sample_id' => $Point->getFieldValue('sample_id'),
            'sex' => $Point->getFieldValue('sex'),
            'in_analysis_dataset' => $Point->getFieldValue('in_analysis_dataset'),
            'release_conditions' => $Point->getFieldValue('release_conditions'),
            'contributor' => $Point->getFieldValue('contributor'),
        );
    }

    public function clear() {
        $Type = $this->getType();
        $Type->delete();
    }

    /**
     * Возвращает клиент ElasticSearch
     * @return Client
     */
    public function getClient() {
        if (is_null($this->Client)) {
            $this->Client = new Client();
        }

        return $this->Client;
    }

    /**
     * Возвращает конфиг для создания клиента
     * @return array
     */
    protected function getClientConfig() {
        return array(
            'host' => '192.168.1.5',
            'port' => 9200
        );
    }
} 