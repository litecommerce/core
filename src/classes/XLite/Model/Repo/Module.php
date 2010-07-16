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

        $list['all'] = array(
            self::TTL_CACHE_CELL => self::INFINITY_TTL,
        );

        $list['names'] = array(
            self::TTL_CACHE_CELL => self::INFINITY_TTL,
        );

        $list['enabled'] = array(
            self::TTL_CACHE_CELL => self::INFINITY_TTL,
            self::ATTRS_CACHE_CELL => array('enabled'),
        );

        return $list;
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
        return $this->assignQueryCache($this->defineAllModulesQuery()->getQuery(), 'all')
            ->getResult();
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
        return $this->createQueryBuilder();
    }

    /**
     * Find all modules as names list
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
     * Define query builder for findAllNames()
     * 
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineAllNamesQuery()
    {
        return $this->createQueryBuilder();
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
            $result[] = $module->getName();
        }

        return array_unique($result);
    }

    /**
     * Find all enabled (or disabled) modules
     * 
     * @param boolean $enabled Enabled flag
     *  
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findAllEnabled($enabled = true)
    {
        return $this->assignQueryCache(
            $this->defineAllEnabledQuery($enabled)->getQuery(),
            'enabled',
            array('enabled' => $enabled)
        )
            ->getResult();
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
     * Find by type 
     * 
     * @param integer $type Type
     *  
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findByType($type)
    {
        return $this->defineByTypeQuery($type)->getQuery()->getResult();
    }

    /**
     * Define query builder for findByType()
     * 
     * @param integer $type Type
     *  
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineByTypeQuery($type)
    {
        return $this->createQueryBuilder()
            ->andWhere('m.type = :type')
            ->setParameter('type', $type);
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
     * Find all module by depend module name
     * 
     * @param string $depend Module name
     *  
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findAllByDepend($depend)
    {
        return $this->defineAllByDependQuery($depend)->getQuery()->getResult();
    }

    /**
     * Define query builder for findByType()
     *
     * @param string $depend Depend module name
     *
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineAllByDependQuery($depend)
    {
        return $this->createQueryBuilder()
            ->andWhere('(m.dependencies LIKE :dbegin OR m.dependencies LIKE :dmiddle OR m.dependencies LIKE :dend OR m.dependencies = :depend)')
            ->setParamater('depend', $depend)
            ->setParamater('dbegin', $depend . ',')
            ->setParamater('dmiddle', ',' . $depend . ',')
            ->setParamater('dend', ',' . $depend);
    }

    /**
     * Get enabled modules list
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
                $this->modules[$module->getName()] = $module;
            }
        }

        return $this->modules;
    }

    /**
     * Check - specified module is active or not
     * 
     * @param string $name Module name
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

                $module->getMainClass()->init();

                if (false === $module->getMainClass()->check()) {
                    $changed = true;
                    $this->disableEmergency($module);
                }
            }
        }

        if ($changed) {
            \XLite\Core\Database::getEM()->flush();
            \XLite::getInstance()->rebuildCacheEmergency();
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
        $list = $this->findAllNames();
        $changed = false;
        $needRebuild = false;

        foreach (glob(LC_MODULES_DIR . '*' . LC_DS . 'Main.php') as $f) {
            $parts = explode(LC_DS, $f);
            $name = $parts[count($parts) - 2];
            if (in_array($name, $list)) {
                unset($list[array_search($name, $list)]);

            } else {
                $module = new \XLite\Model\Module();
                $module->create($name);
                if ($module::INSTALLED == $module->getInstalled()) {
                    \XLite\Core\TopMessage::getInstance()->add(
                        $module->getMainClass()->getPostInstallationNotes()
                    );

                } else {
                    \XLite\Core\TopMessage::getInstance()->addError(
                        'The X module has been installed incorrectly. Please see the logs for more information'
                    );
                }

                \XLite\Core\Database::getEM()->persist($module);
                $changed = true;
            }
        }

        // Emergency modules uninstall
        foreach ($list as $name) {
            $module = $this->findOneByName($name);
            if ($module) {
                if ($module->getEnabled()) {
                    $module->disableDepended();
                    $needRebuild = true;
                }
                $this->uninstallEmergency($module);
                $changed = true;
            }
        }

        if ($changed) {
            \XLite\Core\Database::getEM()->flush();
            if ($needRebuild) {
                \XLite::rebuildCacheEmergency();
            }
        }
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
                array('module' => $module->getName())
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
                array('module' => $module->getName())
            ),
            PEAR_LOG_ERR
        );

        $module->setEnabled(false);
        $module->disableDepended();
        \XLite\Core\Database::getEM()->persist($module);
    }

}

