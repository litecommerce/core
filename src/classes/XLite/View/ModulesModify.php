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
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\View;

/**
 * Modules modify widget
 *
 * @package XLite
 * @see     ____class_see____
 * @since   3.0
 */
class ModulesModify extends Dialog
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
        return 'Modules';
    }

    /**
     * Return templates directory name
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDir()
    {
        return 'modules_modify';
    }

    /**
     * Return list of targets allowed for this widget
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getAllowedTargets()
    {
        $result = parent::getAllowedTargets();
        $result[] = 'modules';
    
        return $result;
    }

    /**
     * Register CSS files
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();

        $list[] = $this->getDir() . LC_DS . 'style.css';

        return $list;
    }

    /**
     * Get module human-readable status 
     * 
     * @param \XLite\Model\Module $module Module
     *  
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getModuleStatus(\XLite\Model\Module $module)
    {
        $statuses = array();

        switch ($module->getInstalled()) {
            case $module::INSTALLED:
                $statuses[] = '<div class="good">' . $this->t('Installed') . '</div>';
                break;

            case $module::INSTALLED_WO_SQL:
                $statuses[] = '<div class="poor">' . $this->t('Installed partially (w/o SQL)') . '</div>';
                break;

            case $module::INSTALLED_WO_PHP:
                $statuses[] = '<div class="poor">' . $this->t('Installed partially') . '</div>';
                break;

            case $module::INSTALLED_WO_CTRL:
                $statuses[] = '<div class="poor">' . $this->t('Installed, but broken') . '</div>';
                break;

            default:
                $statuses[] = '<div class="poor">' . $this->t('Not installed') . '</div>';
        }

        $statuses[] = $module->getEnabled()
            ? '<div class="good">' . $this->t('Enabled')  . '</div>'
            : '<div class="none">' . $this->t('Disabled') . '</div>';

        return implode('', $statuses);
    }

    /**
     * Check - can module nable or not
     * 
     * @param \XLite\Model\Module $module Module
     *  
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function canEnable(\XLite\Model\Module $module)
    {
        return $module->getEnabled()
            || $module->canEnable();
    }

}
