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
 * @subpackage ____sub_package____
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

class XLite_Module_DrupalConnector_View_Pager_OrdersList extends XLite_View_Pager_OrdersList
implements XLite_Base_IDecorator
{
    /**
     * Build page URL by page ID
     *
     * @param int $pageId page ID
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function buildUrlByPageId($pageId)
    {
        $url = parent::buildUrlByPageId($pageId);

        if (preg_match_all('/((pageId|sessionCell)-([^\/]*))(?:\/|$)/S', $url, $matches)) {
            $args = array();
            foreach ($matches[1] as $k => $v) {
                $url = str_replace($v, '', $url);
                $args[] = $matches[2][$k] . '=' . $matches[3][$k];
            }

            $url = preg_replace('/\/+$/Ss', '', $url);
            $url .= (strpos($url, '?') ? '&' : '?') .  implode('&', $args);
        }

        return $url;
    }

}
