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

/**
 * Membership modify widget
 *
 * @package XLite
 * @see     ____class_see____
 * @since   3.0
 */
class XLite_View_Memberships extends XLite_View_Dialog
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
        return 'Memberships';
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
        return 'memberships';
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
        $result[] = 'memberships';
    
        return $result;
    }

    /**
     * Get memberships 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getMemberships()
    {
        $list = XLite_Core_Database::getRepo('XLite_Model_Membership')->findAllMemberships();

        // TODO - add linked profiles calculataion

        $language = $this->getLanguage();

        $result = array();
        foreach ($list as $m) {
            $result[$m->membership_id] = array(
                'name'    => $m->getSoftTranslation($language)->name,
                'orderby' => $m->orderby,
                'active'  => $m->active,
            );
        }

        return $result;
    }

    /**
     * Get current language code
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getLanguage()
    {
        $language = XLite_Core_Request::getInstance()->language;

        return $language ? $language : XLite_Core_Translation::getCurrentLanguageCode();
    }

    /**
     * Get next orderby 
     * 
     * @return integer
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getNextOrderBy()
    {
        $orderby = 0;

        foreach ($this->getMemberships() as $m) {
            $orderby = max($orderby, $m['orderby'] + 1);
        }

        return $orderby;
    }
}
