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
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite\Model\Repo;

/**
 * Module repository
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Module extends \XLite\Model\Repo\ARepo
{
    /**
     * Allowable search params
     */
    const P_SUBSTRING        = 'substring';
    const P_TAG              = 'tag';
    const P_ORDER_BY         = 'orderBy';
    const P_LIMIT            = 'limit';
    const P_PRICE_FILTER     = 'priceFilter';
    const P_INSTALLED        = 'installed';
    const P_INACTIVE         = 'inactive';
    const P_CORE_VERSION     = 'coreVersion';
    const P_FROM_MARKETPLACE = 'fromMarketplace';

    /**
     * Price criteria
     */
    const PRICE_FREE = 'free';
    const PRICE_PAID = 'paid';


    /**
     * Repository type
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $type = self::TYPE_INTERNAL;

    /**
     * Alternative record identifiers
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $alternativeIdentifier = array(
        array('author', 'name'),
    );


    // {{{ The Searchable interface

    /**
     * Common search
     *
     * @param \XLite\Core\CommonCell $cnd       Search condition
     * @param boolean                $countOnly Return items list or only its size OPTIONAL
     *
     * @return \Doctrine\ORM\PersistentCollection|integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function search(\XLite\Core\CommonCell $cnd, $countOnly = false)
    {
        $queryBuilder = $this->createQueryBuilder();

        $this->currentSearchCnd = $cnd;

        foreach ($this->currentSearchCnd as $key => $value) {
            $this->callSearchConditionHandler($value, $key, $queryBuilder);
        }

        $this->addGroupByCondition($queryBuilder);
        $result = $queryBuilder->getResult();

        return $countOnly ? count($result) : $result;
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
     * @since  1.0.0
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
     * Check if param can be used for search
     *
     * @param string $param Name of param to check
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isSearchParamHasHandler($param)
    {
        return in_array($param, $this->getHandlingSearchParams());
    }

    /**
     * Return list of handling search params
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getHandlingSearchParams()
    {
        return array(
            self::P_SUBSTRING,
            self::P_TAG,
            self::P_ORDER_BY,
            self::P_LIMIT,
            self::P_PRICE_FILTER,
            self::P_INSTALLED,
            self::P_INACTIVE,
            self::P_CORE_VERSION,
            self::P_FROM_MARKETPLACE,
        );
    }

    /**
     * Return conditions parameters that are responsible for substring set of fields.
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getSubstringSearchFields()
    {
        return array(
            'm.moduleName',
            'm.description',
        );
    }

    /**
     * Return search words for "All" and "Any" INCLUDING parameter
     *
     * @param string $value Search string
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getSearchWords($value)
    {
        $value  = trim($value);
        $result = array();

        if (preg_match_all('/"([^"]+)"/', $value, $match)) {
            $result = $match[1];
            $value = str_replace($match[0], '', $value);
        }

        return array_merge((array) $result, array_map('trim', explode(' ', $value)));
    }

    /**
     * Prepare query builder to get modules list
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder to prepare
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function addGroupByCondition(\Doctrine\ORM\QueryBuilder $queryBuilder)
    {
        $queryBuilder
            ->addGroupBy('m.name')
            ->addGroupBy('m.author');
    }

    /**
     * Prepare certain search condition
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder to prepare
     * @param array                      $value        Condition data
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
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
     * @since  1.0.0
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
     * Prepare certain search condition
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder to prepare
     * @param string|null                $value        Condition data
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function prepareCndTag(\Doctrine\ORM\QueryBuilder $queryBuilder, $value)
    {
        // :TODO: add code here
    }

    /**
     * prepareCndOrderBy
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder QueryBuilder instance
     * @param array                      $value        Order by info
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function prepareCndOrderBy(\Doctrine\ORM\QueryBuilder $queryBuilder, array $value)
    {
        list($sort, $order) = $value;

        if (!empty($sort)) {
            $queryBuilder->addOrderBy($sort, $order);
        }
    }

    /**
     * Prepare certain search condition
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder to prepare
     * @param string                     $value        Condition
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
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
     * Prepare certain search condition
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder to prepare
     * @param boolean                    $value        Condition
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function prepareCndInstalled(\Doctrine\ORM\QueryBuilder $queryBuilder, $value)
    {
        $queryBuilder
            ->andWhere('m.installed = :installed')
            ->setParameter('installed', $value);
    }

    /**
     * Prepare certain search condition
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder to prepare
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function prepareCndInactive(\Doctrine\ORM\QueryBuilder $queryBuilder)
    {
        $queryBuilder
            ->andWhere('m.enabled = :enabled')
            ->setParameter('enabled', false);
    }

    /**
     * Prepare certain search condition
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder to prepare
     * @param string                     $value        Condition
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function prepareCndCoreVersion(\Doctrine\ORM\QueryBuilder $queryBuilder, $value)
    {
        $queryBuilder
            ->andWhere('m.majorVersion = :majorVersion')
            ->setParameter('majorVersion', $value);
    }

    /**
     * Prepare certain search condition
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder to prepare
     * @param boolean                    $value        Condition
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function prepareCndFromMarketplace(\Doctrine\ORM\QueryBuilder $queryBuilder, $value)
    {
        if ($value) {
            $queryBuilder
                ->andWhere('m.fromMarketplace = :fromMarketplace')
                ->setParameter('fromMarketplace', true);
        }
    }

    // }}}

    // {{{ Markeplace-related routines

    /**
     * One time in session we update list of marketplace modules
     *
     * @param array $data Data received from marketplace
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function updateMarketplaceModules(array $data)
    {
        // Clear previously saved data
        $this->defineDeleteNotInstalledModulesQuery()->execute();
        $this->flushChanges();

        // Save received data
        $this->insertInBatch($data);
    }

    /**
     * Define the Doctrine query
     *
     * @return \Doctrine\ORM\QueryBuilder
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineDeleteNotInstalledModulesQuery()
    {
        $queryBuilder = $this->getQueryBuilder()->delete($this->_entityName, 'm');
        $this->prepareCndInstalled($queryBuilder, false);

        return $queryBuilder;
    }

    // }}}

    // {{{ Version-related routines

    /**
     * Search for modules having an elder version
     *
     * @param \XLite\Model\Module $module Module to get info from
     *
     * @return \XLite\Model\Module
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getModuleForUpdate(\XLite\Model\Module $module)
    {
        return $this->defineModuleForUpdateQuery($module)->getSingleResult();
    }

    /**
     * Search for modules having an elder version
     *
     * @param \XLite\Model\Module $module Module to get info from
     *
     * @return \XLite\Model\Module
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getModuleFromMarketplace(\XLite\Model\Module $module)
    {
        return $this->defineModuleFromMarketplaceQuery($module)->getSingleResult();
    }

    /**
     * Search for installed module
     *
     * @param \XLite\Model\Module $module Module to get info from
     *
     * @return \XLite\Model\Module
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getModuleInstalled(\XLite\Model\Module $module)
    {
        return $this->defineModuleInstalledQuery($module)->getSingleResult();
    }

    /**
     * Search module for upgrade
     *
     * @param \XLite\Model\Module $module       Currently installed module
     * @param string              $majorVersion Core version
     *
     * @return \XLite\Model\Module
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getModuleForUpgrade(\XLite\Model\Module $module, $majorVersion)
    {
        return $this->defineModuleForUpgradeQuery($module, $majorVersion)->getSingleResult();
    }

    /**
     * Query to search for modules having an elder version
     *
     * @param \XLite\Model\Module $module Module to get info from
     *
     * @return \Doctrine\ORM\QueryBuilder
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineModuleForUpdateQuery(\XLite\Model\Module $module)
    {
        $queryBuilder = $this->createQueryBuilder();
        $this->prepareCndSingleModuleSearch($queryBuilder, $module);

        $queryBuilder
            ->andWhere('m.majorVersion = :majorVersion')
            ->andWhere('m.minorVersion > :minorVersion')
            ->setParameter('majorVersion', $module->getMajorVersion())
            ->setParameter('minorVersion', $module->getMinorVersion());

        return $queryBuilder;
    }

    /**
     * Query to search for modules having an elder version
     *
     * @param \XLite\Model\Module $module Module to get info from
     *
     * @return \Doctrine\ORM\QueryBuilder
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineModuleFromMarketplaceQuery(\XLite\Model\Module $module)
    {
        $queryBuilder = $this->createQueryBuilder();
        $this->prepareCndSingleModuleSearch($queryBuilder, $module);

        $queryBuilder
            ->addOrderBy('m.majorVersion', 'ASC')
            ->addOrderBy('m.minorVersion', 'DESC');

        $this->prepareCndFromMarketplace($queryBuilder, true);

        return $queryBuilder;
    }

    /**
     * Query to search for modules having an elder version
     *
     * @param \XLite\Model\Module $module       Module to get info from
     * @param string              $majorVersion Core version
     *
     * @return \Doctrine\ORM\QueryBuilder
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineModuleForUpgradeQuery(\XLite\Model\Module $module, $majorVersion)
    {
        $queryBuilder = $this->createQueryBuilder();
        $this->prepareCndSingleModuleSearch($queryBuilder, $module);

        $queryBuilder
            ->andWhere('m.majorVersion = :majorVersion')
            ->setParameter('majorVersion', $majorVersion)
            ->addOrderBy('m.minorVersion', 'DESC');

        return $queryBuilder;
    }

    /**
     * Query to search for installed modules
     *
     * @param \XLite\Model\Module $module Module to get info from
     *
     * @return \Doctrine\ORM\QueryBuilder
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineModuleInstalledQuery(\XLite\Model\Module $module)
    {
        $queryBuilder = $this->createQueryBuilder();
        $this->prepareCndSingleModuleSearch($queryBuilder, $module);
        $this->prepareCndInstalled($queryBuilder, true);

        return $queryBuilder;
    }

    /**
     * Helper to search module with the same name and author
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder to prepare
     * @param \XLite\Model\Module        $module       Module to get info from
     *
     * @return \Doctrine\ORM\QueryBuilder
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function prepareCndSingleModuleSearch(\Doctrine\ORM\QueryBuilder $queryBuilder, \XLite\Model\Module $module)
    {
        $queryBuilder
            ->andWhere('m.name = :name')
            ->andWhere('m.author = :author')
            ->setParameter('name', $module->getName())
            ->setParameter('author', $module->getAuthor())
            ->setMaxResults(1);
    }

    // }}}

    // {{{ Search for dependencies

    /**
     * Search dependent modules by their class names
     *
     * @param array $classes List of class names
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.15
     */
    public function getDependencyModules(array $classes)
    {
        $result = $this->getDependencyModulesCommon($classes, false);

        foreach ($result as $module) {
            unset($classes[$module->getActualName()]);
        }

        if (!empty($classes)) {
            $result = array_merge($result, $this->getDependencyModulesCommon($classes, true));
        }

        return $result;
    }

    /**
     * Common method to search modules by list of class names
     *
     * @param array   $classes         List of class names
     * @param boolean $fromMarketplace Flag OPTIONAL
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getDependencyModulesCommon(array $classes, $fromMarketplace)
    {
        $cnds = array();
        $queryBuilder = $this->createQueryBuilder('m');

        foreach (array_keys($classes) as $idx => $class) {
            list($author, $name) = explode('\\', $class);

            $cnds[] = new \Doctrine\ORM\Query\Expr\Andx(array('m.name = :name' . $idx, 'm.author = :author' . $idx));
            $queryBuilder->setParameters(array('name' . $idx => $name, 'author' . $idx => $author));
        }

        return $queryBuilder
            ->andWhere(new \Doctrine\ORM\Query\Expr\Orx($cnds))
            ->andWhere('m.fromMarketplace = :fromMarketplace')
            ->setParameter('fromMarketplace', $fromMarketplace)
            ->addGroupBy('m.author', 'm.name')
            ->getResult();
    }

    // }}}
}
