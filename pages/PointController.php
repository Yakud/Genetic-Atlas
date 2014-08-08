<?php
namespace pages;
use Atlas\Point\Point;
use Atlas\Point\PointRedisStorage;
use Controller\Controller;
use Response\ResponseHandler;

/**
 *
 * @author akiselev
 */
class PointController extends Controller {
    public function getPoints() {
        $Storage   = new PointRedisStorage();
        $pointsIds = $Storage->getPointsIds();
        $Points    = array();

        foreach ($pointsIds as $pointId) {
            $Point = $Storage->getPointById($pointId);
            $pointData = $Point->export(array(
                'id',
                'lat',
                'lon',
                'population_id'
            ));

            if(array_key_exists($pointData['lat'].$pointData['lon'], $Points)) {
                $pointData['lat'] += 0.005 + rand(0, 0.005);
                $pointData['lon'] += 0.005 + rand(0, 0.005);
            }

//            $Points[] = $pointData;
            $Points[$pointData['lat'].$pointData['lon']] = $pointData;
        }

        $Points = array_values($Points);

        return ResponseHandler::json([
            'points' => $Points,
        ]);
    }

    /**
     * Запускает выполнение контроллера
     * Действие по умолчанию
     */
    public function run() {
    }
}