<?php
namespace Atlas\Marker;

use Elastica\Request;
/**
 *
 * @author akiselev
 */
class MarkerSearchStorage extends MarkerElasticStorage {
    /**
     * Полнотекстовый поиск по всем полям
     * @param string $queryLucene
     * @param int $ageFrom
     * @param int $ageTo
     * @return Marker[]
     */
    public function searchByTextQuery($queryLucene, $ageFrom, $ageTo) {
        if ($queryLucene == '') {
            $queryLucene = '*';
        }

        $query = [
            'size' => 1000000,
            'query' => [
                'query_string' => [
                    'query' => "($queryLucene) AND (age_from:[{$ageFrom} TO {$ageTo}] OR age_to:[{$ageFrom} TO {$ageTo}] OR age:[{$ageFrom} TO {$ageTo}])",
                ],
            ],
        ];

        $path = $this->getIndex()->getName() . '/' . $this->getType()->getName() . '/_search';

        $response = $this->getClient()->request($path, Request::GET, $query);
        $responseArray = $response->getData();

        if (!isset($responseArray['hits']) || !isset($responseArray['hits']['hits'])) {
            return [];
        }

        $results = $responseArray['hits']['hits'];
        $Markers = [];
        foreach ($results as $hit) {
            $Marker = $this->getMarker($hit['_source']);
            $Markers[] = $Marker;
        }

        return $Markers;
    }

    /**
     * Возвращает массив максимального и минимального возраста
     * @return array
     */
    public function getAgesRange() {
        $query = array(
            "aggs" => [
                "min_age" => [
                    "min" => [
                        "field" => "age",
                    ],
                ],
                "max_age" => [
                    "max" => [
                        "field" => "age",
                    ],
                ],
                "min_age_from" => [
                    "min" => [
                        "field" => "age_from",
                    ],
                ],
                "max_age_to" => [
                    "max" => [
                        "field" => "age_to",
                    ],
                ],
            ],
        );

        $path = $this->getIndex()->getName() . '/' . $this->getType()->getName() . '/_search';

        $response = $this->getClient()->request($path, Request::GET, $query);
        $responseArray = $response->getData();

        if (is_null($responseArray['aggregations']['min_age']['value']) || is_null($responseArray['aggregations']['max_age']['value'])) {
            $ages = [
                'min' => $responseArray['aggregations']['min_age_from']['value'],
                'max' => $responseArray['aggregations']['max_age_to']['value'],
            ];
        } else {
            $ages = [
                'min' => min([$responseArray['aggregations']['min_age']['value'], $responseArray['aggregations']['min_age_from']['value']]),
                'max' => max([$responseArray['aggregations']['max_age']['value'], $responseArray['aggregations']['max_age_to']['value']]),
            ];
        }

        return $ages;
    }
} 