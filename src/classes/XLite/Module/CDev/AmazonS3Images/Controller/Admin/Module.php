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

namespace XLite\Module\CDev\AmazonS3Images\Controller\Admin;

/**
 * Module settings
 *
 */
abstract class Module extends \XLite\Controller\Admin\Module implements \XLite\Base\IDecorator
{
    /**
     * handleRequest
     *
     * @return void
     */
    public function handleRequest()
    {
        if (
            $this->getModuleID()
            && 'CDev\AmazonS3Images' == $this->getModule()->getActualName()
            && \XLite\Core\Request::getInstance()->isGet()
            && !\XLite\Core\TopMessage::getInstance()->getPreviousMessages()
        ) {
            $this->checkAmazonS3Settings();
        }

        parent::handleRequest();
    }

    /**
     * Check amazon S3 settings 
     * 
     * @return void
     */
    protected function checkAmazonS3Settings()
    {
        $config = \XLite\Core\Config::getInstance()->CDev->AmazonS3Images;

        if (!function_exists('curl_init')) {
            \XLite\Core\TopMessage::addError(
                'This module uses PHP\'s cURL functions which are disabled on your web server'
            );

        } elseif (
            $config->access_key
            && $config->secret_key
            && !\XLite\Module\CDev\AmazonS3Images\Core\S3::getInstance()->isValid()
        ) {
            \XLite\Core\TopMessage::addWarning(
                'Connection to Amazon S3 failed.'
                . ' Check whether the AWS Access key и AWS Secret key specified in the module settings are correct.'
            );
        }
    }
}
