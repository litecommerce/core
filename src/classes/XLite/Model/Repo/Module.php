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
 * PHP version 5.3.0
 *
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     3.0.0
 */

namespace XLite\Model\Repo;

/**
 * Module repository
 *
 * :FIXME: must be completely refactored
 * 
 * @see   ____class_see____
 * @since 3.0.0
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
     * Fileds that go into update from marketplace
     * 
     * @var   string
     * @see   ____var_see____
     * @since 3.0.0
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
     * @var   string
     * @see   ____var_see____
     * @since 3.0.0
     */
    protected $type = self::TYPE_INTERNAL;

    /**
     * Update error 
     * 
     * @var   string
     * @see   ____var_see____
     * @since 3.0.0
     */
    protected $updateError = null;

    /**
     * Default 'order by' field name
     * 
     * @var   string
     * @see   ____var_see____
     * @since 3.0.0
     */
    protected $defaultOrderBy = array(
        'enabled' => 0,
        'name'    => 1,
    );

    /**
     * Alternative record identifiers
     *
     * @var   array
     * @see   ____var_see____
     * @since 3.0.0
     */
    protected $alternativeIdentifier = array(
        array('author', 'name'),
    );

    /**
     * Modules enabeld list (cache)
     * 
     * @var   array
     * @see   ____var_see____
     * @since 3.0.0
     */
    protected $modules = null;

    /**
     * Find dependency modules by module 
     * 
     * @param \XLite\Model\Module $module Module
     *  
     * @return array
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findDependenciesByModule(\XLite\Model\Module $module)
    {
        return $this->defineFindDependenciesByModuleQuery($module)->getResult();
    }

    /**
     * Define query for findDependenciesByModule() method
     * 
     * @param \XLite\Model\Module $module Module
     *  
     * @return \Doctrine\ORM\QueryBuilder
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineFindDependenciesByModuleQuery(\XLite\Model\Module $module)
    {
        $qb = $this->createQueryBuilder()
            ->setParameter('delimiter', '\\');;
        $names = \XLite\Core\Database::buildInCondition($qb, $module->getCalculatedDependencies(), 'classNames');
        $expr  = $qb->expr()->concat('m.author', $qb->expr()->concat(':delimiter', 'm.name'));

        foreach ($names as $k => $dp) {
            $qb->orWhere($qb->expr()->eq($expr, ':classNames' . $k));
        }

        return $qb;
    }

    /**
     * Define cache cells 
     * 
     * @return array
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
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareCndOrderBy(\Doctrine\ORM\QueryBuilder $queryBuilder, $value)
    {
        list($sort, $order) = $value;

        $queryBuilder->orderBy($sort, $order);
    }

    /**
     * prepareCndOrderBy
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder QueryBuilder instance
     * @param mixed                      $value        Searchable value
     *
     * @return void
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

        $result = $queryBuilder->getResult();

        return $countOnly ? count($result) : $result;
    }

    /**
     * Find all modules
     * 
     * @return array
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findAllModules()
    {
        $data = $this->getFromCache('all');
        if (!isset($data)) {
            $data = $this->defineAllModulesQuery()->getResult();
            $this->saveToCache($data, 'all');
        }

        return $data;
    }

    /**
     * Find all disabled modules
     * 
     * @return array
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findInactiveModules()
    {
        return $this->defineFindInactiveModulesQuery()->getResult();
    }

    /**
     * Find all upgradable modules
     *
     * :TODO: add search
     * 
     * @return array
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findUpgradableModules()
    {
        return array();
    }

    /**
     * Find all installed modules as names list
     * 
     * @return array
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findAllNames()
    {
        $data = $this->getFromCache('names');
        if (!isset($data)) {
            $data = $this->defineAllNamesQuery()->getResult();
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
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findAllByModuleIds(array $ids)
    {
        return empty($ids)
            ? array()
            : $this->defineAllByModuleIdsQuery($ids)->getResult();
    }

    /**
     * Find all enabled (or disabled) modules
     * 
     * @param boolean $enabled Enabled flag OPTIONAL
     *  
     * @return array
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findAllEnabled($enabled = true)
    {
        $data = $this->getFromCache('enabled', array('enabled' => $enabled));
        if (!isset($data)) {
            $data = $this->defineAllEnabledQuery($enabled)->getResult();
            $this->saveToCache($data, 'enabled', array('enabled' => $enabled));
        }

        return $data;
    }

    /**
     * Find by actual name
     * 
     * @param string $name   Module name
     * @param string $author Module author
     *  
     * @return array
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

                $module->setInstalled(true);

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
                // :FIXME: [MARKETPLACE]
                // \XLite\Core\TmpVars::getInstance()->{\XLite\RemoteModel\Marketplace::ADDONS_UPDATED} = LC_START_TIME;
            }
        }
    }

    /**
     * Define query for findInactiveModules() method
     * 
     * @return \Doctrine\ORM\QueryBuilder
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineFindInactiveModulesQuery()
    {
        return $this->createQueryBuilder()
            ->andWhere('m.enabled = :enabled')
            ->andWhere('m.installed = :installed')
            ->setParameter('enabled', false)
            ->setParameter('installed', true);
    }

    /**
     * Check if the addons data needs to be updated 
     * from the market place
     * 
     * @return string
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
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function isAddonsInfoActual()
    {
        return true;

        // :FIXME: [MARKETPLACE]
        /*return \XLite\Core\TmpVars::getInstance()->{\XLite\RemoteModel\Marketplace::ADDONS_UPDATED}
            && (
                \XLite\Core\TmpVars::getInstance()->{\XLite\RemoteModel\Marketplace::ADDONS_UPDATED} 
                + \XLite\RemoteModel\Marketplace::LAST_UPDATE_TTL
            ) > LC_START_TIME;*/
    }

    /**
     * Process modules XML from the market place
     *
     * @param string $xmlData XML content
     * 
     * @return array
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
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function updateAddonsList()
    {
        return true;

        // :FIXME: [MARKETPLACE]
        /*$xmlData = \XLite\RemoteModel\Marketplace::getInstance()->getAddonsXML();

        $this->updateError = \XLite\RemoteModel\Marketplace::getInstance()->getError();

        if (!$this->updateError && $xmlData) {
            $processed = $this->processXMLResponse($xmlData);
        }

        if ($this->updateError) {

            \XLite\Logger::getInstance()->log(
                'The following error occured during add-ons update procedure:'
                . PHP_EOL . $this->updateError,
                LOG_ERR
            );

            \XLite\Core\TopMessage::addWarning(
                'Error occured when getting addons data from the LiteCommerce Market place. '
                . 'Check log file for details.'
            );
        }

        return !$this->updateError;*/
    } 

    /**
     * Define query builder for findAllModules()
     * 
     * @return \Doctrine\ORM\QueryBuilder
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
     * Define query builder for findAllByModuleIds()
     *
     * @param array $ids Module ids
     *
     * @return \Doctrine\ORM\QueryBuilder
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
     * Uninstall module emergency 
     * 
     * @param \XLite\Model\Module $module Module
     *  
     * @return void
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

        $module->setInstalled(false);

        \XLite\Core\Database::getEM()->flush();
    }

    /**
     * Disable module emergency 
     * 
     * @param \XLite\Model\Module $module Module
     *  
     * @return void
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

