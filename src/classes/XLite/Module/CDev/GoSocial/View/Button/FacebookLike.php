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
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\Module\CDev\GoSocial\View\Button;

/**
 * Facebook Like button
 *
 * @ListChild (list="buttons.share", weight="100")
 */
class FacebookLike extends \XLite\View\AView
{
    /**
     * Widget parameters
     */
    const PARAM_WIDTH  = 'width';

    /**
     * Register JS files
     *
     * @return array
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();

        $list[] = 'modules/CDev/GoSocial/button/js/facebook_like.js';

        return $list;
    }

    /**
     * Get width
     *
     * @return integer
     */
    protected function getWidth()
    {
        return $this->getParam(self::PARAM_WIDTH);
    }

    /**
     * Return widget default template
     *
     * @return string
     */
    protected function getDefaultTemplate()
    {
        return 'modules/CDev/GoSocial/button/facebook_like.tpl';
    }

    /**
     * Define widget parameters
     *
     * @return void
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_WIDTH => new \XLite\Model\WidgetParam\Int('Width', $this->getDefaultWidth()),
        );
    }

    /**
     * Get defaul width
     *
     * @return integer
     */
    protected function getDefaultWidth()
    {
        return 360;
    }

    /**
     * Get button attributes
     *
     * @return array
     */
    protected function getButtonAttributes()
    {
        return array(
            'width'         => $this->getWidth(),
            'send'          => \XLite\Core\Config::getInstance()->CDev->GoSocial->fb_like_send_button ? 'true' : 'false',
            'layout'        => \XLite\Core\Config::getInstance()->CDev->GoSocial->fb_like_layout,
            'show-faces'    => \XLite\Core\Config::getInstance()->CDev->GoSocial->fb_like_show_faces ? 'true' : 'false',
            'action'        => \XLite\Core\Config::getInstance()->CDev->GoSocial->fb_like_verb,
        );
    }

    /**
     * Check if widget is visible
     *
     * @return boolean
     */
    protected function isVisible()
    {
        return parent::isVisible()
            && \XLite\Core\Config::getInstance()->CDev->GoSocial->fb_like_use;
    }
}
