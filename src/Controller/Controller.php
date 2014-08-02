<?php
namespace Controller;
use View\View;
use View\ViewFacade;

/**
 *
 * @author akiselev
 */
abstract class Controller {
    /**
     * Событие контроллера по умолчанию
     */
    const DEFAULT_EVENT_CONTROLLER = 'run';

    /**
     * Событие, которое нужно выполнить
     * @var string
     */
    protected $event = null;

    /**
     * Запускает выполнение контроллера
     * Действие по умолчанию
     */
    abstract public function run();

    /**
     * @param string $event
     */
    public function __construct($event = null) {
        $this->setEvent($event);
    }

    /**
     * Вызывает событие
     * @return mixed
     */
    public function runEvent() {
        if (!$this->event) {
            $this->event = static::DEFAULT_EVENT_CONTROLLER;
        }

        $event = $this->getEvent();
        return $this->$event();
    }

    /**
     * Возращает событие которое нужно вызвать
     * @return string
     */
    public function getEvent() {
        return $this->event;
    }

    /**
     * Устанавливает событие, которое нужно вызвать
     * @param string $event
     * @return $this
     */
    public function setEvent($event) {
        $this->event = $event;
        return $this;
    }

    /**
     * Возвращает объект для работы с шаблонами
     * @return View
     */
    public function getView() {
        return ViewFacade::getInstance();
    }
} 