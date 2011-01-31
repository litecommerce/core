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
 * @subpackage View
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    GIT: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\View\ModulesManager;

/**
 * Addons search and installation widget
 *
 * @package XLite
 * @see     ____class_see____
 * @since   3.0
 */
class AModulesManager extends \XLite\View\Dialog
{
    /**
     * Return title
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getHead()
    {
        return '';
    }

    /**
     * Return templates directory
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDir()
    {
        return 'modules_manager';
    }

    /**
     * Return marketplace URL
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getMarketPlaceURL()
    {
        return \XLite\Model\Module::MARKETPLACE_URL;
    }

    /**
     * Return upgradable modules flag label:
     * - empty string if no any
     * - number of upgradable modules in brackets
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getUpgradableModulesFlag()
    {
        $upgadeables = count(\Xlite\Core\Database::getRepo('XLite\Model\Module')->findUpgradableModules());

        return 0 < $upgadeables ? ' (' . $upgadeables . ')' : '';
    }
}
