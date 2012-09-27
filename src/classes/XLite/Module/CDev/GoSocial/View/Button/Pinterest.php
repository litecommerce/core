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
 * Pinterest button
 *
 *
 * @ListChild (list="buttons.share.bar", weight="300")
 */
class Pinterest extends \XLite\View\AView
{
    /**
     * Button URL
     */
    const BUTTON_URL = 'http://pinterest.com/pin/create/button/?';

    /**
     * Return widget default template
     *
     * @return string
     */
    protected function getDefaultTemplate()
    {
        return 'modules/CDev/GoSocial/button/pinterest.tpl';
    }

    /**
     * Get button attributes
     *
     * @return array
     */
    protected function getButtonAttributes()
    {
        return array(
            'count-layout' => 'horizontal',
        );
    }

    /**
     * Get button URL (query  part)
     *
     * @return array
     */
    protected function getButtonURL()
    {
        $query = array();
        foreach ($this->getButtonURLQuery() as $name => $value) {
            $query[] = $name . '=' . urlencode($value);
        }

        return static::BUTTON_URL . implode('&amp;', $query);
    }

    /**
     * Get button URL (query  part)
     *
     * @return array
     */
    protected function getButtonURLQuery()
    {
        $image = $this->getModelObject()->getImage();

        return array(
            'url'         => \XLite::getInstance()->getShopURL($this->getURL()),
            'media'       => isset($image) ? $image->getFrontURL() : null,
            'description' => $this->getModelObject()->getName(),
        );
    }

    /**
     * Check if widget is visible
     *
     * @return boolean
     */
    protected function isVisible()
    {
        $image = $this->getModelObject()->getImage();

        return parent::isVisible()
            && isset($image)
            && $image->isExists()
            && \XLite\Core\Config::getInstance()->CDev->GoSocial->pinterest_use;
    }

}
