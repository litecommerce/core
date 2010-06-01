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
 * @subpackage Model
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * Database
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Core_Database extends XLite_Base implements XLite_Base_ISingleton
{
    const DBTABLE_PATTERN = 'xlite_%s';

    /**
     * Doctrine config object
     * 
     * @var    Doctrine\ORM\Configuration
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $doctrineConfig = null;

    /**
     * Doctrine entity manager 
     * 
     * @var    Doctrine\ORM\EntityManager
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected static $em = null;

    /**
     * Doctrine cache driver 
     * 
     * @var    Doctrine\Common\Cache\AbtractCache
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected static $cacheDriver = null;

    /**
     * Get instance 
     * 
     * @return XLite_Model_Database
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getInstance()
    {
        return self::getInternalInstance(__CLASS__);
    }

    /**
     * Constructor
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __construct()
    {
        if (!$this->connected) {
            $this->connect();
        }
    }

    /**
     * Connect and set-up Doctrine
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function connect()
    {
        $this->config = new \Doctrine\ORM\Configuration;

        // Setup cache
        $this->setDoctrineCache();

        // Set metadata driver
        $this->config->setMetadataDriverImpl(
            $this->config->newDefaultAnnotationDriver(LC_MODEL_CACHE_DIR)
        );

        // Set proxy settings
        $this->config->setProxyDir(LC_PROXY_CACHE_DIR);
        $this->config->setProxyNamespace(LC_MODEL_PROXY_NS);

        self::$em = \Doctrine\ORM\EntityManager::create($this->getDSN(), $this->config);

        // Bind events
        if (self::$cacheDriver) {

            // Bind cache chekers
            self::$em->getEventManager()->addEventListener(
                array(\Doctrine\ORM\Events::postUpdate, \Doctrine\ORM\Events::postRemove),
                $this
            );
        }
    }

    /**
     * Get entity manager 
     * 
     * @return Doctrine\ORM\EntityManager
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getEntityManager()
    {
        if (is_null(self::em)) {
            XLite_Core_Database::getInstance();
        }

        return self::$em;
    }

    /**
     * Setup doctrine cache 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function setDoctrineCache()
    {
        $cache = false;

        $options = XLite::getInstance()->getOptions('cache');
        if (!$options || !is_array($options)) {
            $options = array('type' => false);
        }

        if ('apc' == $options['type']) {

            // APC
            $cache = new \Doctrine\Common\Cache\ApcCache;

        } elseif ('memcache' == $options['type'] && isset($options['servers'])) {

            // Memcache
            $servers = explode(';', $options['servers']);
            if ($servers) {
                $memcache = new Memcache();
                foreach ($servers as $row) {
                    $row = trim($row);
                    $tmp = explode(':', $row, 2);
                    if ('unix' == $tmp[0]) {
                        $memcache->addServer($row, 0);

                    } elseif (isset($tmp[1])) {
                        $memcache->addServer($tmp[0], $tmp[1]);

                    } else {
                        $memcache->addServer($tmp[0]);
                    }
                }

                $cache = new \Doctrine\Common\Cache\MemcacheCache;
                $cache->setMemcache($memcache);
            }
            
        } elseif ('xcache' == $options['type']) {
            $cache = new \Doctrine\Common\Cache\XcacheCache;
        }

        if ($cache) {
            self::$cacheDriver = $cache;

        } else {
            $cache = new \Doctrine\Common\Cache\ArrayCache;
        }

        $this->config->setMetadataCacheImpl($cache);
        $this->config->setQueryCacheImpl($cache);
        $this->config->setResultCacheImpl($cache);
    }

    /**
     * Get DSN in Doctrine style
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDSN()
    {
        $options = XLite::getInstance()->getOptions('database_details');

        $dsnFields = array(
            'host'        => 'hostspec',
            'port'        => 'port',
            'unix_socket' => 'socket',
            'dbname'      => 'database',
        );
        $dsnList = array(
            'driver' => 'pdo_mysql',
        );
        $dsnString = array();

        foreach ($dsnFields as $pdoOption => $lcOption) {

            if (!empty($options[$lcOption])) {
                $dsnList[$pdoOption] = $options[$lcOption];
                $dsnString[] = $pdoOption . '=' . $options[$lcOption];
            }
        }

        $dsnList['path'] = 'mysql:' . implode(';', $dsnString);
        $dsnList['user'] = $options['username'];
        $dsnList['password'] = $options['password'];

        return $dsnList;
    }

    /**
     * Check - cache is enabled or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function isCacheEnabled()
    {
        return !is_null(self::$cacheDriver);
    }

    /**
     * Get cache driver
     *
     * @return Doctrine\Common\Cache\AbstractCache
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getCacheDriver()
    {
        return self::$cacheDriver;
    }

    /**
     * postUpdate event handler
     * 
     * @param Doctrine\ORM\Event\LifecycleEventArgs $arg Event argument
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function postUpdate(Doctrine\ORM\Event\LifecycleEventArgs $arg)
    {
        $arg->getEntity()->checkCache();
    }

    /**
     * postRemove event handler
     * 
     * @param Doctrine\ORM\Event\LifecycleEventArgs $arg Event argument
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function postRemove(Doctrine\ORM\Event\LifecycleEventArgs $arg)
    {
        $arg->getEntity()->checkCache();
    }

    /**
     * Get last query length
     *
     * @return integer
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getLastQueryLength()
    {
        $length = self::getEntityManager()
            ->getConnection()
            ->executeQuery('SELECT FOUND_ROWS()', array())
            ->fetchColumn();

        return intval($length);
    }
}
