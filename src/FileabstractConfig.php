<?php

namespace elementary\config\Fileabstract;

use elementary\config\Fileabstract\Exceptions\FileNotExistException;
use elementary\config\Fileabstract\Exceptions\LoadException;
use elementary\config\Runtime\RuntimeConfig;
use UnexpectedValueException;

/**
 * @package elementary\config
 */
abstract class FileabstractConfig extends RuntimeConfig
{
    /**
     * @var string
     */
    protected static $dir = '';

    /**
     * @var string
     */
    protected static $serverType = 'Default';

    /**
     * @param string $fileName
     * @param string $separate
     *
     * @return $this
     * @throws FileNotExistException
     */
    public static function load($fileName, $separate='/')
    {
        $instance = new static($fileName);
        $instance->setSeparate($separate);

        $filePath = $instance->getFilePath(self::getDir(), $fileName);

        if ($filePath) {
            $instance->setAll($instance->loadFile($filePath));
        } else {
            throw new FileNotExistException('File '. $fileName .' does not exist');
        }

        return $instance;
    }

    /**
     * @param string $fileName
     * @param string $separate
     *
     * @return $this
     */
    public static function me($fileName='', $separate='/')
    {
        if (self::$instance === null) {
            self::$instance = self::load($fileName, $separate);
        }

        return self::$instance;
    }

    public function __construct()
    {
    }

    /**
     * @param string $filePath
     *
     * @return array
     * @throws LoadException|UnexpectedValueException
     */
    abstract public function loadFile($filePath);

    /**
     * @param string $path
     * @param string $fileName
     *
     * @return bool|string
     */
    protected function getFilePath($path, $fileName)
    {
        $returnValue = $this->checkFile($path, 'Custom', $fileName);

        if (!$returnValue) {
            $returnValue = $this->checkFile($path, self::getServerType(), $fileName);
        }

        return $returnValue;
    }

    /**
     * @param string $path
     * @param string $type
     * @param string $file
     *
     * @return bool|string
     */
    protected function checkFile($path, $type, $file)
    {
        $filePath = implode('/', [$path, $type, $file]);

        if (is_file($filePath)) {
            return $filePath;
        } else {
            return false;
        }
    }

    /**
     * @return string
     */
    public static function getDir()
    {
        return self::$dir;
    }

    /**
     * @param string $dir
     */
    public static function setDir($dir)
    {
        self::$dir = $dir;
    }

    /**
     * @return string
     */
    public static function getServerType()
    {
        return self::$serverType;
    }

    /**
     * @param string $serverType
     */
    public static function setServerType($serverType)
    {
        self::$serverType = $serverType;
    }
}