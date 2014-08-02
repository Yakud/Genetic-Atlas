<?php
namespace View;

use View\Exception\ViewIncludeException;

/**
 * Класс для рендеринга шаблонов
 *
 * @author akiselev
 */
class View {
    /**
     * Макет для вьюхи
     * @var string
     */
    protected $viewLayout = 'layout/main';

    /**
     * Папка, где хранятся вьюхи
     * @var string
     */
    protected $viewFolder = PATH_VIEW;

    /**
     * Расширение вьюхи
     * @var string
     */
    protected $viewExtension = 'php';

    /**
     * Глобальные данные для всех шаблонов
     * @var array
     */
    protected $globalData = array();

    /**
     * Локальные данные для вьюхи
     * @var array
     */
    protected $localData = array();

    /**
     * Заголовок страницы
     * @var string
     */
    protected $title = '';

    /**
     * Контент страницы
     * @var string
     */
    protected $content = '';

    /**
     * Рендерит вьюху
     * @param string $view
     * @param array $data
     * @throws Exception\ViewIncludeException
     * @return string
     */
    public function make($view, array $data = array()) {
        $renderedView = $this->makeWithoutLayout($view, $data);
        $this->setContent($renderedView);

        $layout  = $this->getViewLayout();
        $title   = $this->getTitle();
        $content = $this->getContent();

        $renderedLayout = $this->makeWithoutLayout($layout, [
            'content' => $content,
            'title'   => $title,
        ]);

        return $renderedLayout;
    }

    /**
     * Рендерит вьюху без макета
     * @param string $view
     * @param array $data
     * @throws Exception\ViewIncludeException
     * @return string
     */
    public function makeWithoutLayout($view, array $data = array()) {
        $this->appendLocalData($data);
        $viewData = $this->makeViewDataArray();
        $viewPath = $this->makeViewPath($view);
        $this->clearLocalData();

        ob_start();
        foreach ($viewData as $variable => $value) {
            $$variable = $value;
        }

        try {
            if((@include $viewPath) === false) {
                throw new ViewIncludeException("templates \"{$view}\" not found at path \"{$viewPath}\"");
            }
        } catch (ViewIncludeException $Ex) {
            ob_clean();
            throw $Ex;
        }

        return ob_get_clean();
    }

    /**
     * @param string $view
     * @throws Exception\ViewIncludeException
     */
    protected function viewInclude($view) {

    }

    /**
     * Возвращает путь до вьюхи
     * @param string $view
     * @return string
     */
    protected function makeViewPath($view) {
        $viewFolder    = $this->getViewFolder();
        $viewExtension = $this->getViewExtension();

        $viewPath = [$viewFolder, DIRECTORY_SEPARATOR, $view, '.', $viewExtension];

        return implode('', $viewPath);
    }

    /**
     * Возвращает массив данных для шаблона
     * @return array
     */
    protected function makeViewDataArray() {
        return array_merge($this->getGlobalData(), $this->getLocalData());
    }

    /**
     * Добавляет глобальные данные для шаблонов
     * @param array $data
     * @return $this
     */
    public function appendGlobalData(array $data) {
        $this->globalData = array_merge($this->globalData, $data);
        return $this;
    }

    /**
     * Возвращает массив глобальных данных
     * @return array
     */
    public function getGlobalData() {
        return $this->globalData;
    }

    /**
     * Чистит глобальные данные для шаблонов
     * @return $this
     */
    public function clearGlobalData() {
        $this->globalData = array();
        return $this;
    }

    /**
     * Добавляет локальные данные для следующего рендеринга шаблона
     * Очищаются после рендеринга
     * @param array $data
     * @return $this
     */
    public function appendLocalData(array $data) {
        $this->localData = array_merge($this->localData, $data);
        return $this;
    }

    /**
     * Возвращает массив локальных данных
     * @return array
     */
    public function getLocalData() {
        return $this->localData;
    }

    /**
     * Чистит локальные данные для следующего шаблона
     * @return $this
     */
    public function clearLocalData() {
        $this->localData = array();
        return $this;
    }

    /**
     * Возвращает текущий макет
     * @return string
     */
    public function getViewLayout() {
        return $this->viewLayout;
    }

    /**
     * Устанавливает макет
     * @param string $layout
     * @return $this
     */
    public function setViewLayout($layout) {
        $this->viewLayout = $layout;
        return $this;
    }

    /**
     * Возвращает папку с вьюхами
     * @return string
     */
    public function getViewFolder() {
        return $this->viewFolder;
    }

    /**
     * Устанавливает папку с вьюхами
     * @param string $viewFolder
     * @return $this
     */
    public function setViewFolder($viewFolder) {
        $this->viewFolder = $viewFolder;
        return $this;
    }

    /**
     * Возвращает расширение вьюхи
     * @return string
     */
    public function getViewExtension() {
        return $this->viewExtension;
    }

    /**
     * Устанавливает расширение вьюхи
     * @param string $viewExtension
     * @return $this
     */
    public function setViewExtension($viewExtension) {
        $this->viewExtension = $viewExtension;
        return $this;
    }

    /**
     * Возвращает заголовок страницы
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Устанавливает заголовок страницы
     * @param string $title
     * @return $this
     */
    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }

    /**
     * Возвращает контент страницы
     * @return string
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * Устанавливает контект страницы
     * @param string $content
     * @return $this
     */
    public function setContent($content) {
        $this->content = $content;
        return $this;
    }
}