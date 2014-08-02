<?php
namespace pages;
use Atlas\Point\Point;
use Controller\Controller;

/**
 *
 * @author akiselev
 */
class MainController extends Controller {

    /**
     * Запускает выполнение контроллера
     * Действие по умолчанию
     */
    public function run() {
        $Points = array(
            new Point(['lat' => 0, 'lon' => 0]),
            new Point(['lat' => 1, 'lon' => 0]),
            new Point(['lat' => 2, 'lon' => 0]),
            new Point(['lat' => 3, 'lon' => 0]),
            new Point(['lat' => 4, 'lon' => 0]),
            new Point(['lat' => 5, 'lon' => 0]),
            new Point(['lat' => 6, 'lon' => 0]),
            new Point(['lat' => 7, 'lon' => 0]),
            new Point(['lat' => 8, 'lon' => 0]),
        );

        return $this->getView()->make('map');
    }
}