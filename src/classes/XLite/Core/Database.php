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

namespace XLite\Core;

/**
 * Database
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Database extends \XLite\Base implements \XLite\Base\ISingleton
{
    /**
     * Doctrine config object
     * 
     * @var    \Doctrine\ORM\Configuration
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $doctrineConfig = null;

    /**
     * Doctrine entity manager 
     * 
     * @var    \Doctrine\ORM\EntityManager
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected static $em = null;

    /**
     * Doctrine cache driver 
     * 
     * @var    \Doctrine\Common\Cache\AbtractCache
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected static $cacheDriver = null;

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

        // Initialize DB connection and entity manager
        self::$em = \Doctrine\ORM\EntityManager::create($this->getDSN(), $this->config);

        if (\XLite\Model\Profiler::getInstance()->enabled) {
            self::$em->getConnection()->getConfiguration()->setSQLLogger(\XLite\Model\Profiler::getInstance());
        }

        // Bind events
        $events = array(\Doctrine\ORM\Events::loadClassMetadata);
        if (self::$cacheDriver) {

            // Bind cache chekers
            $events[] = \Doctrine\ORM\Events::postPersist;
            $events[] = \Doctrine\ORM\Events::postUpdate;
            $events[] = \Doctrine\ORM\Events::postRemove;
        }

        self::$em->getEventManager()->addEventListener($events, $this);
    }

    /**
     * Get entity manager 
     * 
     * @return \Doctrine\ORM\EntityManager
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getEntityManager()
    {
        // FIXME
        if (!isset(self::$em)) {
            \XLite\Core\Database::getInstance();
        }

        return self::$em;
    }

    /**
     * Get entity manager (short method)
     * 
     * @return \Doctrine\ORM\EntityManager
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getEM()
    {
        return self::getEntityManager();
    }

    /**
     * Get repository (short method)
     *
     * @param string $repository Entity class name
     * 
     * @return \Doctrine\ORM\EntityRepository
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getRepo($repository)
    {
        return self::getEntityManager()->getRepository($repository);
    }

    /**
     * Get repository (short method)
     * 
     * @return \Doctrine\ORM\EntityRepository
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getQB()
    {
        return self::getEntityManager()->createQueryBuilder();
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
        $cache = self::getCacheDriverByOptions(\XLite::getInstance()->getOptions('cache'));

        if ($cache) {
            self::$cacheDriver = $cache;
        }

        $this->config->setMetadataCacheImpl($cache);
        $this->config->setQueryCacheImpl($cache);
        $this->config->setResultCacheImpl($cache);
    }

    /**
     * Get cache driver by options list
     * 
     * @param mixed $options Options from config.ini
     *  
     * @return \Doctrine\Common\Cache\Cache
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getCacheDriverByOptions($options)
    {
        if (!isset($options) || !is_array($options)) {
            $options = array('type' => null);
        }

        if ('apc' == $options['type']) {

            // APC
            $cache = new \Doctrine\Common\Cache\ApcCache;

        } elseif ('memcache' == $options['type'] && isset($options['servers'])) {

            // Memcache
            $servers = explode(';', $options['servers']);
            if ($servers) {
                $memcache = new \Memcache();
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

        } elseif ('file' == $options['type']) {

            $cache = new \XLite\Core\FileCache(LC_DATACACHE_DIR);

        } else {

            $cache = new \Doctrine\Common\Cache\ArrayCache;
        }

        if (isset($options['namespace']) && $options['namespace']) {
            $cache->setNamespace($options['namespace']);
        }

        return $cache;
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
        $options = \XLite::getInstance()->getOptions('database_details');

        $dsnFields = array(
            'host'        => 'hostspec',
            'port'        => 'port',
            'unix_socket' => 'socket',
            'dbname'      => 'database',
        );
        $dsnList = array(
            'driver'       => 'pdo_mysql',
            'wrapperClass' => 'XLite\Core\Connection',
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
        return isset(self::$cacheDriver);
    }

    /**
     * Get cache driver
     *
     * @return \Doctrine\Common\Cache\AbstractCache
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getCacheDriver()
    {
        return self::$cacheDriver;
    }

    /**
     * postPersist event handler
     * 
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $arg Event argument
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function postPersist(\Doctrine\ORM\Event\LifecycleEventArgs $arg)
    {
        $arg->getEntity()->checkCache();
    }

    /**
     * postUpdate event handler
     * 
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $arg Event argument
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function postUpdate(\Doctrine\ORM\Event\LifecycleEventArgs $arg)
    {
        $arg->getEntity()->checkCache();
    }
    /**
     * postRemove event handler
     * 
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $arg Event argument
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function postRemove(\Doctrine\ORM\Event\LifecycleEventArgs $arg)
    {
        $arg->getEntity()->checkCache();
    }

    /**
     * loadClassMetadata event handler
     * 
     * @param \Doctrine\ORM\Event\LoadClassMetadataEventArgs $eventArgs Event arguments
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function loadClassMetadata(\Doctrine\ORM\Event\LoadClassMetadataEventArgs $eventArgs)
    {
        $classMetadata = $eventArgs->getClassMetadata();

        // Set table name prefix
        $classMetadata->setTableName(
            sprintf(
                \XLite::getInstance()->getOptions(array('database_details', 'table_pattern')),
                $classMetadata->getTableName()
            )
        );

        // Set repository
        if (!$classMetadata->customRepositoryClassName) {
            $class = str_replace('\Model\\', '\Model\Repo\\', $classMetadata->getReflectionClass()->getName());
            if (\XLite\Core\Operator::isClassExists($class)) {
                $classMetadata->setCustomRepositoryClass($class);

            } else {
                $classMetadata->setCustomRepositoryClass('XLite\Model\Repo\Base\Common');
            }
        }
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

    /**
     * Prepare array for IN () DQL function
     * 
     * @param array  $data   Hash array
     * @param string $prefix Placeholder prefix
     *  
     * @return array (keys for IN () function & parameters hash array)
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function prepareArray(array $data, $prefix = 'arr')
    {
        $keys = array();
        $parameters = array();

        foreach ($data as $k => $v) {
            $k = $prefix . $k;
            $keys[] = ':' . $k;
            $parameters[$k] = $v;
        }

        return array($keys, $parameters);
    }

    /**
     * Build IN () condition 
     * 
     * @param \Doctrine\ORM\QueryBuilder $qb     Query builder
     * @param array                     $data   Hash array
     * @param string                    $prefix Placeholder prefix
     *  
     * @return array keys for IN () function
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function buildInCondition(\Doctrine\ORM\QueryBuilder $qb, array $data, $prefix = 'arr')
    {
        list($keys, $data) = self::prepareArray($data, $prefix);

        foreach ($data as $k => $v) {
            $qb->setParameter($k, $v);
        }

        return $keys;
    }
}
