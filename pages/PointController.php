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
            $Points[] = $Storage->getPointById($pointId)->export();
        }

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