<?php
namespace Atlas\Marker;
use Elastica\Request;
use Lib\Storage\ElasticStorage;

/**
 *
 * @author akiselev
 */
class MarkerSearchStorage extends MarkerElasticStorage {
    /**
     * Полнотекстовый поиск по всем полям
     * @param string $query
     * @return Marker[]
     */
    public function searchByTextQuery($query) {
        $query = array(
            'size' => 10000,
            'query' => array(
                'query_string' => array(
                    'query' => $query,
                )
            )
        );

        $path = $this->getIndex()->getName() . '/' . $this->getType()->getName() . '/_search';

        $response = $this->getClient()->request($path, Request::GET, $query);
        $responseArray = $response->getData();

//        var_export($responseArray);

        if (!isset($responseArray['hits']) || !isset($responseArray['hits']['hits'])) {
            return [];
        }

        $results = $responseArray['hits']['hits'];
        $Markers = [];
        foreach ($results as $hit) {
            $Marker = $this->getMarker($hit['_source']);
//            $Markers[$hit['_score']][] = $Marker;
            $Markers[] = $Marker;
        }

        return $Markers;
    }
} 