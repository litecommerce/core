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

namespace XLite\Module\CDev\XPaymentsConnector\Controller\Admin;

/**
 * Module settings
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
abstract class Module extends \XLite\Controller\Admin\Module implements \XLite\Base\IDecorator
{
    /**
     * Deploy
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionDeployConfiguration()
    {
        if (
            $this->getModuleID()
            && 'CDev\XPaymentsConnector' == $this->getModule()->getActualName()
        ) {
            $core = \XLite\Module\CDev\XPaymentsConnector\Core\XPayments::getInstance();

            $xpcConfig = $core->getConfiguration(\XLite\Core\Request::getInstance()->deploy_configuration);

            if (true === $core->checkDeployConfiguration($xpcConfig)) {

                $core->setConfiguration($xpcConfig);
                \XLite\Core\TopMessage::addInfo('Configuration has been successfully deployed');

            } else {
                \XLite\Core\TopMessage::addError('Your configuration string is not correct');    
            }
        }
    }

}
