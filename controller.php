<?php
namespace Concrete\Package\EmbedBlock;

use Concrete\Core\Backup\ContentImporter;
use Concrete\Core\Package\Package;

class Controller extends Package
{
    protected $pkgHandle = 'embed_block';
    protected $appVersionRequired = '8.5.4';
    protected $pkgVersion = '0.1';

    public function getPackageName()
    {
        return t('Embed Block');
    }

    public function getPackageDescription()
    {
        return t('A package to add Embed block type.');
    }

    /**
     * Register autoloader.
     */
    protected function registerAutoload()
    {
        if (file_exists($this->getPackagePath().'/vendor/autoload.php')) {
            require $this->getPackagePath().'/vendor/autoload.php';
        }
    }

    public function install()
    {
        $this->registerAutoload();

        if (!class_exists('\Embed\Embed')) {
            throw new \RuntimeException(t('Required libraries not found.'));
        }

        if (PHP_VERSION_ID < 50500) {
            throw new \RuntimeException(t('This package requires PHP 5.5+.'));
        }

        $pkg = parent::install();

        $ci = new ContentImporter();
        $ci->importContentFile($pkg->getPackagePath() . '/config/install.xml');
    }

    public function on_start()
    {
        $this->registerAutoload();
    }
}