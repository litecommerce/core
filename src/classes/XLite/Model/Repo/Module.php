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

namespace XLite\Model\Repo;

/**
 * Module repository
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Module extends \XLite\Model\Repo\ARepo
{
    /**
     * Allowable search params 
     */
    const P_SUBSTRING    = 'substring';
    const P_TAG          = 'tag';
    const P_ORDER_BY     = 'orderBy';
    const P_LIMIT        = 'limit';
    const P_PRICE_FILTER = 'priceFilter';
    const P_STATUS       = 'status';

    /**
     * Param to force update addons 
     */
    const P_FORCE_UPDATE = 'updateAddons';

    /**
     * Price criteria 
     */
    const PRICE_FREE = 'free';
    const PRICE_PAID = 'paid';

    /**
     * Marketplace connect params
     */
    const INFO_SCRIPT_PATH  = 'get_info.php';
    const REQUEST_TYPE_LIST = 'addonsList';
    const PARAM_REQUEST     = 'request';
    const ADDONS_UPDATED    = 'addonsUpdated';
    const LAST_UPDATE_TTL   = 86400;

    /**
     * Fileds that go into update from marketplace
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $updateFields = array(
        'name',
        'author',
        'status',
        'description',
        'moduleName',
        'authorName',
        'date',
        'version',
        'changelog',
        'hash',
        'packHash',
        'price',
        'currency',
        'rating',
        'downloads',
        'iconURL',
        'dependencies',
    );

    /**
     * Repository type 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $type = self::TYPE_INTERNAL;

    /**
     * Update error 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $updateError = null;

    /**
     * Default 'order by' field name
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $defaultOrderBy = array(
        'enabled' => 0,
        'name'    => 1,
    );

    /**
     * Alternative record identifiers
     *
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $alternativeIdentifier = array(
        array('author', 'name'),
    );

    /**
     * Modules enabeld list (cache)
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $modules = null;

    /**
     * Define cache cells 
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineCacheCells()
    {
        $list = parent::defineCacheCells();

        $list['all'] = array();
        $list['names'] = array();
        $list['enabled'] = array(
            self::ATTRS_CACHE_CELL => array('enabled'),
        );

        return $list;
    }

    /**
     * Return list of handling search params 
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getHandlingSearchParams()
    {
        return array(
            self::P_SUBSTRING,
            self::P_TAG,
            self::P_ORDER_BY,
            self::P_LIMIT,
            self::P_PRICE_FILTER,
            self::P_STATUS,
        );
    }

    /**
     * Return conditions parameters that are responsible for substring set of fields.
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getSubstringSearchFields()
    {
        return array(
            'm.moduleName',
            'm.description',
        );
    }

    /**
     * Check if param can be used for search
     * 
     * @param string $param Name of param to check
     *  
     * @return boolean 
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isSearchParamHasHandler($param)
    {
        return in_array($param, $this->getHandlingSearchParams());
    }

    /**
     * Prepare certain search condition
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder to prepare
     * @param array                      $value        Condition data
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareCndLimit(\Doctrine\ORM\QueryBuilder $queryBuilder, array $value)
    {
        call_user_func_array(array($this, 'assignFrame'), array_merge(array($queryBuilder), $value));
    }

    /**
     * Prepare certain search condition
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder to prepare
     * @param string|null                $value        Condition data
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareCndSubstring(\Doctrine\ORM\QueryBuilder $queryBuilder, $value)
    {
        $searchWords = $this->getSearchWords($value);

        $cnd = new \Doctrine\ORM\Query\Expr\Orx();

        foreach ($this->getSubstringSearchFields() as $field) {

            foreach ($searchWords as $index => $word) {

                // Collect OR expressions
                $cnd->add($field . ' LIKE :word' . $index);

                $queryBuilder->setParameter('word' . $index, '%' . $word . '%');

            }
        }

        $queryBuilder->andWhere($cnd);
    }

    /**
     * Return search words for "All" and "Any" INCLUDING parameter
     *
     * @param string $value Search string
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getSearchWords($value)
    {
        $value = trim($value);

        $result = array();

        if (preg_match_all('/"([^"]+)"/', $value, $match)) {

            $result = $match[1];

            $value = str_replace($match[0], '', $value);

        }

        return array_merge(
            (array)$result,
            array_map(
                'trim',
                explode(' ', $value)
            )
        );
    }

    /**
     * Prepare certain search condition
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder to prepare
     * @param string|null                $value        Condition data
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareCndTag(\Doctrine\ORM\QueryBuilder $queryBuilder, $value)
    {
    }

    /**
     * prepareCndOrderBy 
     * 
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder QueryBuilder instance
     * @param mixed                      $value        Searchable value
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareCndOrderBy(\Doctrine\ORM\QueryBuilder $queryBuilder, $value)
    {
        list($sort, $order) = $value;

        $queryBuilder->addOrderBy($sort, $order);

        // FIXME: to remove after implementation of the manage addons page via items list
        $this->defaultOrderBy = array();
    }

    /**
     * prepareCndOrderBy
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder QueryBuilder instance
     * @param mixed                      $value        Searchable value
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareCndPriceFilter(\Doctrine\ORM\QueryBuilder $queryBuilder, $value)
    {
        if (self::PRICE_FREE === $value) {
            $queryBuilder->andWhere($queryBuilder->expr()->eq('m.price', 0));
        } elseif (self::PRICE_PAID === $value) {
            $queryBuilder->andWhere($queryBuilder->expr()->gt('m.price', 0));
        }
    }

    /**
     * prepareCndStatus
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder QueryBuilder instance
     * @param mixed                      $value        Searchable value
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareCndStatus(\Doctrine\ORM\QueryBuilder $queryBuilder, $value)
    {
        $queryBuilder->andWhere('m.status = :status')->setParameter('status', $value);
    }

    /**
     * Call corresponded method to handle a search condition
     * 
     * @param mixed                      $value        Condition data
     * @param string                     $key          Condition name
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder to prepare
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function callSearchConditionHandler($value, $key, \Doctrine\ORM\QueryBuilder $queryBuilder)
    {
        if ($this->isSearchParamHasHandler($key)) {
            $this->{'prepareCnd' . ucfirst($key)}($queryBuilder, $value);
        } else {
            // TODO - add logging here
        }
    }

    /**
     * Common search
     * 
     * @param \XLite\Core\CommonCell $cnd       Search condition
     * @param boolean                $countOnly Return items list or only its size OPTIONAL
     *  
     * @return \Doctrine\ORM\PersistentCollection|integer
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function search(\XLite\Core\CommonCell $cnd, $countOnly = false)
    {
        $queryBuilder = $this->createQueryBuilder();

        $this->currentSearchCnd = $cnd;

        foreach ($this->currentSearchCnd as $key => $value) {
            $this->callSearchConditionHandler($value, $key, $queryBuilder);
        }

        $result = $queryBuilder->getQuery()->getResult();

        return $countOnly ? count($result) : $result;
    }

    /**
     * Find all modules
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findAllModules()
    {
        $data = $this->getFromCache('all');
        if (!isset($data)) {
            $data = $this->defineAllModulesQuery()->getQuery()->getResult();
            $this->saveToCache($data, 'all');
        }

        return $data;
    }

    /**
     * Find all disabled modules
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findInactiveModules()
    {
        return $this->createQueryBuilder()
            ->andWhere('m.enabled = :enabled')
            ->andWhere('m.installed = :installed')
            ->setParameter('enabled', false)
            ->setParameter('installed', true)
            ->getQuery()
            ->getResult();
    }

    /**
     * Find all upgradable modules
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findUpgradableModules()
    {
        $modules = array();

        foreach ($this->findAllModules() as $m) {
            if ($m->isUpdateAvailable()) {
                $modules[] = $m;
            }
        }

        return $modules;
    }

    /**
     * Find all installed modules as names list
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findAllNames()
    {
        $data = $this->getFromCache('names');
        if (!isset($data)) {
            $data = $this->defineAllNamesQuery()->getQuery()->getResult();
            $data = $this->postprocessAllNames($data);
            $this->saveToCache($data, 'names');
        }

        return $data;
    }

    /**
     * Find all by names
     * 
     * @param array $ids Modules ids
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findAllByModuleIds(array $ids)
    {
        return !empty($ids)
            ? $this->defineAllByModuleIdsQuery($ids)->getQuery()->getResult()
            : array();
    }

    /**
     * Find all enabled (or disabled) modules
     * 
     * @param boolean $enabled Enabled flag OPTIONAL
     *  
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findAllEnabled($enabled = true)
    {
        $data = $this->getFromCache('enabled', array('enabled' => $enabled));
        if (!isset($data)) {
            $data = $this->defineAllEnabledQuery($enabled)->getQuery()->getResult();
            $this->saveToCache($data, 'enabled', array('enabled' => $enabled));
        }

        return $data;
    }

    /**
     * Find all by names
     * 
     * @param array $names Modules names
     *  
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findAllByNames(array $names)
    {
        return $this->defineAllByNamesQuery($names)->getQuery()->getResult();
    }

    /**
     * Find by actual name
     * 
     * @param string $name   Module name
     * @param string $author Module author
     *  
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findByActualName($name, $author)
    {
        return $this->findOneBy(
            array(
                'name'   => $name,
                'author' => $author
            )
        );
    }

    /**
     * Get enabled modules list
     * FIXME - remove cycle
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getActiveModules()
    {
        if (!isset($this->modules)) {
            $this->modules = array();
            foreach ($this->findAllEnabled() as $module) {
                $this->modules[$module->getActualName()] = $module;
            }
        }

        return $this->modules;
    }

    /**
     * Check - specified module is active or not
     * 
     * @param string $name Key as: {name}\{author}
     *  
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isModuleActive($name)
    {
        $list = $this->getActiveModules();

        return isset($list[$name]);
    }

    /**
     * Initialize modules subsystem
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function initialize()
    {
        $changed = false;

        foreach ($this->getActiveModules() as $name => $module) {

            $mainClass = $module->getMainClass();

            if (!$mainClass) {
                $changed = true;
                $this->uninstallEmergency($module);

            } else {

                $mainClass::init();

                if (false === $mainClass::check()) {
                    $changed = true;
                    $this->disableEmergency($module);
                }
            }
        }

        if ($changed) {
            \XLite\Core\Database::getEM()->flush();
            \XLite::setCleanUpCacheFlag(true);
        }
    }

    /**
     * Check new modules and delete removed
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function checkModules()
    {
        // Step 1: check installed modules

        $list = $this->findAllNames();
        $changed = false;
        $needRebuild = false;

        foreach (glob(LC_MODULES_DIR . '*' . LC_DS . '*' . LC_DS . 'Main.php') as $f) {

            $parts  = explode(LC_DS, $f);
            $name   = $parts[count($parts) - 2];
            $author = $parts[count($parts) - 3];

            $key = $author . '\\' . $name;

            if (in_array($key, $list)) {

                unset($list[array_search($key, $list)]);

            } else {

                $module = $this->findByActualName($name, $author);

                if (!$module) {
                    $module = new \XLite\Model\Module();
                    $module->create($name, $author);
                }

                $module->installed = true;

                \XLite\Core\Database::getEM()->persist($module);
                $changed = true;
            }
        }

        // Step 2: Emergency modules uninstall

        foreach ($list as $key) {

            list($author, $name) = explode('\\', $key);

            $module = $this->findByActualName($name, $author);

            if ($module) {

                if ($module->getEnabled()) {

                    $module->disable();

                    $needRebuild = true;

                }

                $this->uninstallEmergency($module);

                $changed = true;
            }
        }

        if ($changed) {

            \XLite\Core\Database::getEM()->flush();

            if ($needRebuild) {

                \XLite::setCleanUpCacheFlag(true);
            }
        }

        // Step 3: update modules list from the market place

        if ($this->isUpdateNeeded()) {
            $result = $this->updateAddonsList();
            if ($result) {
                \XLite\Core\Session::getInstance()->set(static::ADDONS_UPDATED, LC_START_TIME);
            }
        }
    }

    /**
     * Check if the addons data needs to be updated 
     * from the market place
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function isUpdateNeeded()
    {
        return !static::isAddonsInfoActual()
            || \XLite\Core\Request::getInstance()->{static::P_FORCE_UPDATE};
    }

    /**
     * Returns timestamp of the last addons update
     * from the marketplace
     * 
     * @return integer
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function isAddonsInfoActual()
    {
        return \XLite\Core\Session::getInstance()->{static::ADDONS_UPDATED}
            && \XLite\Core\Session::getInstance()->{static::ADDONS_UPDATED} + static::LAST_UPDATE_TTL > LC_START_TIME;
    }

    /**
     * Grab modules XML from the market place
     * TODO: possibly, adjust this to support xml download via insecure connection
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getAddonsXML()
    {
        $response = '';

        $request = new \XLite\Model\HTTPS();
        $request->url = \XLite\Model\Module::getMarketplaceURL() . static::INFO_SCRIPT_PATH
            . '?' . static::PARAM_REQUEST . '=' . static::REQUEST_TYPE_LIST;
        $request->method = 'GET';

        if (
            $request::HTTPS_SUCCESS == $request->request()
            && $request->response
        ) {
            // Success
            $response = $request->response;

        } else {

            // Error occured
            $this->updateError = $request->error;
        }

        return $response;
    }

    /**
     * Process modules XML from the market place
     *
     * @param string $xmlData XML content
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function processXMLResponse($xmlData)
    {
        $result = true;

        $xml = new \XLite\Model\XML();
  
        $parsed = $xml->parse($xmlData);

        if ($xml->error) {
            $this->updateError = $xml->error;
            $result = false;
        }

        if (is_array($parsed) && $parsed['ADDONSLISTRESPONSE']) {

            if ('OK' === $parsed['ADDONSLISTRESPONSE']['STATUS']) {

                // Mark non-installed addons
                $q = $this->getEntityManager()->createQuery(
                    'update \XLite\Model\Module m set m.status = '
                    . \XLite\Model\Module::NOT_EXIST . ' where m.enabled = false'
                );

                $updated = $q->execute();

                // Process rows
                foreach ($parsed['ADDONSLISTRESPONSE']['ADDONSLIST'] as $v) {

                    $data = array();

                    foreach ($this->updateFields as $f) {
                        $val = $v[strtoupper($f)];
                        if (!is_null($val)) {
                            $data[$f] = $val;
                        }
                    }

                    $data['status'] = \XLite\Model\Module::EXISTS;

                    $module = $this->findByActualName($data['name'], $data['author']);

                    if (!$module) {
                        $module = new \XLite\Model\Module();
                    }

                    $module->map($data);

                    \XLite\Core\Database::getEM()->persist($module);
                    \XLite\Core\Database::getEM()->flush();
                }

            } else {

                // Process error
                $this->updateError = $parsed['STATUSDESC'];
            }

        } else {

            // did not receive data or incorrect format
            $this->updateError = 'Received XML is empty or contains incorrect format';
        }

        return $result;
    }

    /**
     * Update Addons List
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function updateAddonsList()
    {
        $xmlData = $this->getAddonsXML();

        if (!$this->updateError && $xmlData) {
            $processed = $this->processXMLResponse($xmlData);
        }

        if ($this->updateError) {
            \XLite\Logger::getInstance()->log(
                'The following error occured during add-ons update procedure:'
                . PHP_EOL . $this->updateError
            );
            \XLite\Core\TopMessage::getInstance()->addWarning(
                'Error occured when getting addons data from the LiteCommerce Market place. '
                . 'Check log file for details.'
            );
        } else {
            \XLite\Logger::getInstance()->log(
                'Add-ons data has been successfully updated from the Market place'
            );
        }

        return !$this->updateError;
    } 

    /**
     * Define query builder for findAllModules()
     * 
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineAllModulesQuery()
    {
        return $this->createQueryBuilder()
            ->andWhere('m.installed = :installed')
            ->setParameter('installed', true);
    }

    /**
     * Define query builder for findAllNames()
     * 
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineAllNamesQuery()
    {
        return $this->createQueryBuilder()
            ->andWhere('m.installed = :installed')
            ->setParameter('installed', true);
    }

    /**
     * Define query builder for findAllByNames()
     *
     * @param array $ids Module ids
     *
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineAllByModuleIdsQuery(array $ids)
    {
        $qb = $this->createQueryBuilder();

        $ids = \XLite\Core\Database::buildInCondition($qb, $ids, 'moduleId');

        return $qb->andWhere('m.moduleId IN (' . implode(', ', $ids) . ')');
    }

    /**
     * Postprocess all modules names list
     * 
     * @param array $data Initial data
     *  
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function postprocessAllNames(array $data)
    {
        $result = array();

        foreach ($data as $module) {
            $result[] = $module->getActualName();
        }

        return array_unique($result);
    }

    /**
     * Define query builder for findAllEnabled()
     *
     * @param boolean $enabled Enabled flag
     *
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineAllEnabledQuery($enabled)
    {
        return $this->createQueryBuilder()
            ->andWhere('m.enabled = :enabled')
            ->setParameter('enabled', $enabled);
    }

    /**
     * Define query builder for findAllByNames()
     *
     * @param array $names Modules names
     *
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineAllByNamesQuery(array $names)
    {
        $qb = $this->createQueryBuilder();

        $ids = \XLite\Core\Database::buildInCondition($qb, $names, 'name');

        return $qb->andWhere('m.name IN (' . implode(', ', $ids) . ')');
    }

    /**
     * Uninstall module emergency 
     * 
     * @param \XLite\Model\Module $module Module
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function uninstallEmergency(\XLite\Model\Module $module)
    {
        \XLite\Logger::getInstance()->log(
            \XLite\Core\Translation::lbl(
                'The X module has been uninstalled in an abnormal way',
                array('module' => $module->getActualName())
            ),
            PEAR_LOG_ERR
        );

        \XLite\Core\Database::getEM()->remove($module);
    }

    /**
     * Disable module emergency 
     * 
     * @param \XLite\Model\Module $module Module
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function disableEmergency(\XLite\Model\Module $module)
    {
        \XLite\Logger::getInstance()->log(
            \XLite\Core\Translation::lbl(
                'The X module has been disabled in an abnormal way',
                array('module' => $module->getActualName())
            ),
            PEAR_LOG_ERR
        );

        $module->setEnabled(false);
        $module->disableDepended();
        \XLite\Core\Database::getEM()->persist($module);
    }

}

