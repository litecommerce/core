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

namespace XLite\Controller\Admin;

/**
 * Measure
 *
 */
class Measure extends \XLite\Controller\Admin\AAdmin
{
    /**
     * Measure action
     *
     * @return void
     */
    protected function doActionMeasure()
    {
        if (!\XLite\Core\Config::getInstance()->General->probe_key) {
            $key = md5(strval(microtime(true) * 1000000) . uniqid(true));
            \XLite\Core\Database::getRepo('XLite\Model\Config')->createOption(
                array(
                    'category' => 'General',
                    'name'     => 'probe_key',
                    'value'    => $key,
                )
            );
            \XLite\Core\Config::getInstance()->General->probe_key = $key;
        }

        $this->requestProbe();

        $this->redirect(\XLite\Core\Converter::buildURL());
    }

    /**
     * Request probe script
     *
     * @return void
     */
    protected function requestProbe()
    {
        $url = \XLite::getInstance()->getShopURL(
            \XLite\Core\Converter::buildURL(
                '',
                '',
                array('key' => \XLite\Core\Config::getInstance()->General->probe_key),
                'probe.php'
            )
        );

        set_time_limit(0);

        $request = new \XLite\Core\HTTP\Request($url);
        $response = $request->sendRequest();

        if (200 != $response->code) {
            \XLite\Core\TopMessage::addError('Measuring productivity in manual mode failed.');
        }
    }
}
