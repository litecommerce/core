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
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace Includes\Decorator\Plugin\Doctrine\Utils;

/**
 * EntityManager 
 * 
 * @package    XLite
 * @see        ____class_see____
 * @since      3.0.0
 */
abstract class EntityManager extends \Includes\Decorator\Plugin\Doctrine\ADoctrine
{
    /**
     * Entity manager
     * 
     * @var    Doctrine\ORM\EntityManager
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected static $handler;


    /**
     * Retur DSN as params array
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getDSN()
    {
        return \Includes\Utils\Database::getConnectionParams(true) + array('driver' => 'pdo_mysql');
    }

    /**
     * Set metadata driver for Doctrine config
     * 
     * @param \Doctrine\ORM\Configuration $config config object
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function setMetadataDriver(\Doctrine\ORM\Configuration $config)
    {
        $chain = new \Doctrine\ORM\Mapping\Driver\DriverChain();
        $chain->addDriver(
            $config->newDefaultAnnotationDriver(LC_MODEL_CACHE_DIR),
            'XLite\Model'
        );

        $iterator = new \RecursiveDirectoryIterator(LC_CLASSES_CACHE_DIR . 'XLite' . LC_DS . 'Module');

        foreach ($iterator as $dir) {

            if (
                \Includes\Utils\FileManager::isDir($dir->getPathName())
                && \Includes\Utils\FileManager::isDir($dir->getPathName() . LC_DS . 'Model')
            ) {
                $chain->addDriver(
                    $config->newDefaultAnnotationDriver($dir->getPathName() . LC_DS . 'Model'),
                    'XLite\Module\\' . $dir->getBaseName() . '\Model'
                );
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
     * @since  3.0.0
     */
    protected static function getConfig()
    {
        $config = new \Doctrine\ORM\Configuration();

        static::setMetadataDriver($config);

        // Set proxy settings
        $config->setProxyDir(LC_PROXY_CACHE_DIR);
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
     * @since  3.0.0
     */
    protected static function getHandler()
    {
        if (!isset(static::$handler)) {
            static::$handler = \Doctrine\ORM\EntityManager::create(static::getDSN(), static::getConfig());
        }

        return static::$handler;
    }

    /**
     * Return instance of the proxy factory 
     *
     * @return Doctrine\ORM\Proxy\ProxyFactory
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getProxyFactory()
    {
        // NOTE: it's the hack
        \Includes\Decorator\Plugin\Doctrine\Utils\ProxyFactory::modifyCodeTemplate(
            $factory = static::getHandler()->getProxyFactory()
        );

        return $factory;
    }

    /**
     * Return instance of the metadata factory 
     * 
     * @return Doctrine\ORM\Mapping\ClassMetadataFactory
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getMetadataFactory()
    {
        return static::getHandler()->getMetadataFactory();
    }

    /**
     * Return all classes metadata
     * FIXME - change to protected 
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getAllMetadata()
    {
        return static::getMetadataFactory()->getAllMetadata();
    }

    /**
     * Return list of callbacks to configure the EntityGenerator
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getEntityGeneratorConfigCallbacks()
    {
        return array(
            array('setGenerateAnnotations', array(true)),
            array('setGenerateStubMethods', array(true)),
            array('setRegenerateEntityIfExists', array(false)),
            array('setUpdateEntityIfExists', array(true)),
            array('setNumSpaces', array(4)),
            array('setClassToExtend', array('\XLite\Model\AEntity')),
        );
    }

    /**
     * Return the Doctrine tools
     * 
     * @return \Doctrine\ORM\Tools\EntityGenerator
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getEntityGenerator()
    {
        $generator = new \Doctrine\ORM\Tools\EntityGenerator();

        foreach (static::getEntityGeneratorConfigCallbacks() as $data) {
            list($method, $args) = $data;
            call_user_func_array(array($generator, $method), $args);
        }

        return $generator;
    }


    /**
     * Generate models
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function generateModels()
    {
        static::getEntityGenerator()->generate(static::getAllMetadata(), LC_CLASSES_CACHE_DIR);
    }

    /**
     * Generate proxies
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function generateProxies()
    {
        static::getProxyFactory()->generateProxyClasses(static::getAllMetadata(), LC_PROXY_CACHE_DIR);
    }
}
