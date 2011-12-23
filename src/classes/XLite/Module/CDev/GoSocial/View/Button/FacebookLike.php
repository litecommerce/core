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
 * @since     1.0.15
 */

namespace XLite\Module\CDev\GoSocial\View\Button;

/**
 * Facebook like button
 * 
 * @see   ____class_see____
 * @since 1.0.15
 */
abstract class FacebookLike extends \XLite\View\Button\FacebookLike implements \XLite\Base\IDecorator
{
    /**
     * Get button attributes
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getButtonAttributes()
    {
        $list = parent::getButtonAttributes();

        $list['send']       = \XLite\Core\Config::getInstance()->CDev->GoSocial->fb_like_send_button ? 'true' : 'false';
        $list['layout']     = \XLite\Core\Config::getInstance()->CDev->GoSocial->fb_like_layout;
        $list['show-faces'] = \XLite\Core\Config::getInstance()->CDev->GoSocial->fb_like_show_faces ? 'true' : 'false';
        $list['action']     = \XLite\Core\Config::getInstance()->CDev->GoSocial->fb_like_verb;

        return $list;
    }

    /**
     * Check if widget is visible
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isVisible()
    {
        return parent::isVisible()
            && \XLite\Core\Config::getInstance()->CDev->GoSocial->fb_like_use;
    }

    /**
     * Get defaul width
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getDefaultWidth()
    {
        return 360;
    }

}
