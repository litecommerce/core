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
 * @subpackage Core
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Module\WishList;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class Main extends \XLite\Module\AModule
{
    /**
     * Module type
     *
     * @var    int
     * @access protected
     * @since  3.0
     */
    public static function getModuleType()
    {
        return self::MODULE_GENERAL;
    }

    /**
     * Module version
     *
     * @var    string
     * @access protected
     * @since  3.0
     */
    public static function getVersion()
    {
        return '1.0';
    }

    /**
     * Module description
     *
     * @var    string
     * @access protected
     * @since  3.0
     */
    public static function getDescription()
    {
        return 'This module introduces "Wish List" and "Send to Friend" features';
    }

    /**
     * Perform some actions at startup
     *
     * @return void
     * @access public
     * @since  3.0
     */
    public static function init()
    {
        parent::init();
        \XLite::getInstance()->set('WishListEnabled', true);
    }

    /**
     * Modify view lists 
     * FIXME - to remove
     * 
     * @param array $data Annotation data
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function modifyViewLists(array &$data)
    {
        $tpls = array(  
            'shopping_cart/parts/item.name.tpl',
            'shopping_cart/parts/item.sku.tpl',
            'shopping_cart/parts/item.weight.tpl',
        );

        foreach ($tpls as $tpl) {

            try {
                $list = \XLite\Core\Database::getQB()
                    ->select('v')
                    ->from('\XLite\Model\ViewList', 'v')
                    ->where('v.tpl LIKE :tpl AND v.list = :list')
                    ->setParameters(array('tpl' => '%' . $tpl, 'list' => 'cart.item.info'))
                    ->getQuery()
                    ->getSingleResult();

                $newList = new \XLite\Model\ViewList();
                $newList->list = 'wishlist.item.info';
                $newList->tpl = $list->tpl;
                $newList->weight = $list->weight;

                \XLite\Core\Database::getEM()->persist($newList);

            } catch (\Doctrine\ORM\NoResultException $exception) {
            }
        }

        \XLite\Core\Database::getEM()->flush();
    }
}
