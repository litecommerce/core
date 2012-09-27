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

namespace XLite\Module\CDev\JoomlaConnector\Core;

/**
 * Handler
 *
 */
abstract class Handler extends \XLite\Core\Handler implements \XLite\Base\IDecorator
{
    /**
     * Compose URL from target, action and additional params
     *
     * @param string $target Page identifier OPTIONAL
     * @param string $action Action to perform OPTIONAL
     * @param array  $params Additional params OPTIONAL
     *
     * @return string
     */
    public function buildURL($target = '', $action = '', array $params = array())
    {
        return \XLite\Module\CDev\JoomlaConnector\Handler::getInstance()->checkCurrentCMS()
            ? self::getJoomlaURL($target, $action, $params)
            : parent::buildURL($target, $action, $params);
    }


    /**
     * Compose URL from target, action and additional params
     *
     * @param string $target Page identifier
     * @param string $action Action to perform OPTIONAL
     * @param array  $params Additional params OPTIONAL
     *
     * @return string
     */
    protected function getJoomlaURL($target, $action = '', array $params = array())
    {
        // TODO - this function must not be called!
        return parent::buildURL($target, $action, $params) . '&============';
    }
}
