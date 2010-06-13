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
 * View list repository
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Model_Repo_ViewList extends XLite_Model_Repo_AbstractRepo
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
        'weight' => true,
        'child'  => true,
    );

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

        $list['class_list'] = array(
            self::TTL_CACHE_CELL => self::INFINITY_TTL,
            self::ATTRS_CACHE_CELL => array('class', 'list'),
        );

        return $list;
    }

    /**
     * Find class list 
     *
     * @param string $class Class name
     * @param string $list  Class list name
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findClassList($class, $list)
    {
        $qb = $this->assignQueryCache(
            $this->defineClassListQuery($class, $list)->getQuery(),
            'class_list',
            array('class' => $class, 'list' => $list)
        );

        return $qb->getResult();
    }

    /**
     * Define query builder for findClassList()
     *
     * @param string $class Class name
     * @param string $list  Class list name
     * 
     * @return Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineClassListQuery($class, $list)
    {
        $qb = XLite_Core_Database::getQB()
            ->select('v')
            ->from('XLite_Model_ViewList', 'v')
            ->where('v.class = :class AND v.list = :list')
            ->setParameters(array('class' => $class, 'list' => $list));

        return $this->assignDefaultOrderBy($qb, 'v');
    }
}

