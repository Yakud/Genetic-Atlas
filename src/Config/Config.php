<?php
namespace Config;

use Cache\MemoryCache;
use Config\Exception\ConfigNotFoundException;
use Traits\Singleton;

/**
 * Класс для взаимодействия с конфигами
 * @author akiselev
 */
class Config {
    use Singleton;

    /**
     * Путь к папке конфигов
     * @var string
     */
    protected $path = PATH_CONFIG;

    /**
     * Расширение файла конфига
     * @var string
     */
    protected $ext = '.php';

    /**
     * Кэш конфигов
     * @var MemoryCache
     */
    protected $configCache = null;

    /**
     * Возвращает даннные конфига
     * Имя конфига без расшираения
     * Если не указан ключ, то возвращаются все данные конфига
     *
     * @param string $config   Имя конфига
     * @param string|null $key Ключ который достать из конфига
     * @param mixed $default   По умолчанию
     *
     * @throws ConfigNotFoundException
     * @return mixed
     */
    public function get($config, $key = null, $default = null) {
        $configPath  = $this->makeConfigPath($config);
        $ConfigCache = $this->getCache();

        if ($ConfigCache->has($config)) {
            // Нашли в кэше конфиг
            $configData = $ConfigCache->get($config);
        } else {
            // Подгружаем конфиг
            $configData = (@include $configPath);
            if ($configData === false) {
                $errorStr = "Config \"{$config}\" not found in \"{$configPath}\"";
                throw new ConfigNotFoundException($errorStr);
            }

            // Поместим конфиг в кэш
            $ConfigCache->set($config, $configData);
        }

        if (is_null($key)) {
            return $configData;
        }

        if (array_key_exists($key, $configData)) {
            return $configData[$key];
        }

        return $default;
    }

    /**
     * Возвращет путь до конкретного конфига
     * @param string $config
     * @return string
     */
    protected function makeConfigPath($config) {
        return $this->path . DIRECTORY_SEPARATOR . $config . $this->ext;
    }

    /**
     * Возвращает инстанс кэша
     * @return MemoryCache
     */
    protected function getCache() {
        if (!$this->configCache) {
            $this->configCache = new MemoryCache();
        }

        return $this->configCache;
    }
} 