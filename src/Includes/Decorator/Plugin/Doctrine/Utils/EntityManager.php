<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * LiteCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to licensing@litecommerce.com so we can send you a copy immediately.
 *
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage Includes_Decorator_Utils
 * @author     Creative Development LLC <info@cdev.ru>
 * @copyright  Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      1.0.0
 */

namespace Includes\Decorator\Plugin\Doctrine\Utils;

/**
 * EntityManager
 *
 * @package XLite
 * @see     ____class_see____
 * @since   1.0.0
 */
abstract class EntityManager extends \Includes\Decorator\Plugin\Doctrine\ADoctrine
{
    /**
     * Entity manager
     *
     * @var    Doctrine\ORM\EntityManager
     * @access protected
     * @see    ____var_see____
     * @since  1.0.0
     */
    protected static $handler;

    /**
     * Model classes metadata
     *
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  1.0.0
     */
    protected static $metadata;


    /**
     * Return all classes metadata
     *
     * @param string $class Class name OPTIONAL
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getAllMetadata($class = null)
    {
        if (!isset(static::$metadata)) {
            static::$metadata = array();

            // Create hash array to quick access its elements
            foreach (static::getHandler()->getMetadataFactory()->getAllMetadata() as $data) {
                static::$metadata[$data->name] = $data;
            }
        }

        return \Includes\Utils\ArrayManager::getIndex(static::$metadata, $class, false);
    }

    /**
     * Generate models
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function generateModels()
    {
        static::getEntityGenerator()->generate(static::getAllMetadata(), LC_DIR_CACHE_CLASSES);
    }

    /**
     * Generate proxies
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function generateProxies()
    {
        static::getHandler()->getProxyFactory()->generateProxyClasses(static::getAllMetadata(), LC_DIR_CACHE_PROXY);
    }

    /**
     * Retur DSN as params array
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function getDSN()
    {
        return \Includes\Utils\Database::getConnectionParams(true) + array('driver' => 'pdo_mysql');
    }

    /**
     * Set metadata driver for Doctrine config
     * FIXME: to revise
     *
     * @param \Doctrine\ORM\Configuration $config config object
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function setMetadataDriver(\Doctrine\ORM\Configuration $config)
    {
        $chain = new \Doctrine\ORM\Mapping\Driver\DriverChain();
        $chain->addDriver(
            $config->newDefaultAnnotationDriver(static::getClassesDir() . 'XLite' . LC_DS . 'Model'),
            'XLite\Model'
        );

        $iterator = new \RecursiveDirectoryIterator(
            static::getClassesDir() . 'XLite' . LC_DS . 'Module',
            \FilesystemIterator::SKIP_DOTS
        );

        foreach ($iterator as $dir) {
            if (\Includes\Utils\FileManager::isDir($dir->getPathName())) {
                $iterator2 = new \RecursiveDirectoryIterator($dir->getPathName(), \FilesystemIterator::SKIP_DOTS);

                foreach ($iterator2 as $dir2) {
                    if (
                        \Includes\Utils\FileManager::isDir($dir2->getPathName())
                        && \Includes\Utils\FileManager::isDir($dir2->getPathName() . LC_DS . 'Model')
                    ) {
                        $chain->addDriver(
                            $config->newDefaultAnnotationDriver($dir2->getPathName() . LC_DS . 'Model'),
                            'XLite\Module\\' . $dir->getBaseName() . '\\' . $dir2->getBaseName() . '\Model'
                        );
                    }
                }
            }

        }

        $config->setMetadataDriverImpl($chain);
    }

    /**
     * Return the Doctrine config object
     *
     * @return Doctrine\ORM\Configuration
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function getConfig()
    {
        $config = new \Doctrine\ORM\Configuration();

        static::setMetadataDriver($config);

        // Set proxy settings
        $config->setProxyDir(rtrim(LC_DIR_CACHE_PROXY, LC_DS));
        $config->setProxyNamespace(LC_MODEL_PROXY_NS);

        $cache = new \Doctrine\Common\Cache\ArrayCache();
        $config->setMetadataCacheImpl($cache);
        $config->setQueryCacheImpl($cache);

        return $config;
    }

    /**
     * Return instance of the entity manager
     *
     * @return Doctrine\ORM\EntityManager
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function getHandler()
    {
        if (!isset(static::$handler)) {
            static::$handler = \Doctrine\ORM\EntityManager::create(static::getDSN(), static::getConfig());
            \XLite\Core\Database::registerCustomTypes(static::$handler);
        }

        return static::$handler;
    }

    /**
     * Return the Doctrine tools
     *
     * @return \Doctrine\ORM\Tools\EntityGenerator
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function getEntityGenerator()
    {
        $generator = new \Includes\Decorator\Plugin\Doctrine\Utils\ModelGenerator();
        $generator->setGenerateAnnotations(true);
        $generator->setRegenerateEntityIfExists(false);
        $generator->setUpdateEntityIfExists(true);
        $generator->setGenerateStubMethods(true);
        $generator->setNumSpaces(4);
        $generator->setClassToExtend('\XLite\Model\AEntity');

        return $generator;
    }
}
