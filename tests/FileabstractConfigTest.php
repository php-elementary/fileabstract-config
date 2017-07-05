<?php

namespace elementary\config\Test;

use PHPUnit\Framework\TestCase;
use elementary\config\Fileabstract\FileabstractConfig;

/**
 * @coversDefaultClass \elementary\config\Fileabstract\FileabstractConfig
 */
class FileabstractConfigTest extends TestCase
{
    protected $path    = '/tmp/FileConfigTest';
    protected $default = 'Default';
    protected $custom  = 'Custom';
    protected $file    = 'array.php';

    /**
     * @var FileabstractConfig
     */
    protected $config = null;

    /**
     * @test
     * @covers ::load()
     * @covers ::me()
     * @covers ::__construct()
     */
    public function load()
    {
        fwrite(STDOUT, "\n". __METHOD__);

        file_put_contents($this->getDefault() .'/array2.php', '<?php return ["test" => 123];');

        $this->assertInstanceOf('\elementary\config\Fileabstract\FileabstractConfig', $this->getConfig());
        $this->assertInstanceOf('\elementary\config\Fileabstract\FileabstractConfig', FileConfig::me('array2.php'));
        $this->assertEquals($this->getConfig(), FileConfig::me());

        unlink($this->getDefault() .'/array2.php');
    }

    /**
     * @test
     * @expectedException \elementary\config\Fileabstract\Exceptions\FileNotExistException
     * @covers ::load()
     * @covers ::getFilePath()
     * @covers ::__construct()
     */
    public function getFilePath()
    {
        fwrite(STDOUT, "\n". __METHOD__);

        FileConfig::load('array2.php');
    }

    /**
     * @test
     * @covers ::checkFile()
     * @covers ::all()
     */
    public function checkCustomFile()
    {
        fwrite(STDOUT, "\n". __METHOD__);

        mkdir($this->getCustom());
        file_put_contents($this->getCustom() .'/'. $this->file, '<?php return ["test" => 123];');

        $this->assertEquals(['test' => 123], FileConfig::load($this->file)->all());
    }

    /**
     * @test
     * @covers ::getDir()
     * @covers ::setDir()
     */
    public function path()
    {
        fwrite(STDOUT, "\n". __METHOD__);

        FileConfig::setDir('/test23/');
        $this->assertEquals('/test23/', FileConfig::getDir());
    }

    /**
     * @test
     * @covers ::getServerType()
     * @covers ::setServerType()
     */
    public function serverType()
    {
        fwrite(STDOUT, "\n". __METHOD__);

        $this->assertEquals('Default', FileConfig::getServerType());

        FileConfig::setServerType('Prod');
        $this->assertEquals('Prod', FileConfig::getServerType());
    }

    public function setUp()
    {
        $this->setPath(time());

        mkdir($this->getPath());
        mkdir($this->getDefault());
        file_put_contents($this->getDefault() .'/array.php', '<?php return ["test" => 123];');

        FileConfig::setDir($this->getPath());

        $this->setConfig(FileConfig::load($this->file));
    }

    public function tearDown()
    {
        if (is_file($this->getDefault() .'/'. $this->file)) {
            unlink($this->getDefault() .'/'. $this->file);
        }

        if (is_dir($this->getDefault())) {
            rmdir($this->getDefault());
        }

        if (is_file($this->getCustom() .'/'. $this->file)) {
            unlink($this->getCustom() .'/'. $this->file);
        }

        if (is_dir($this->getCustom())) {
            rmdir($this->getCustom());
        }

        rmdir($this->getPath());
    }

    /**
     * @return FileabstractConfig
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param FileabstractConfig $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path.= $path;
    }

    /**
     * @return string
     */
    public function getDefault()
    {
        return $this->getPath() .'/'. $this->default;
    }

    /**
     * @return string
     */
    public function getCustom()
    {
        return $this->getPath() .'/'. $this->custom;
    }
}

class FileConfig extends FileabstractConfig {
    /**
     * @param string $filePath
     *
     * @return array
     */
    public function loadFile($filePath)
    {
        return ['test' => 123];
    }
}