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

namespace XLite\Module\CDev\Paypal\Model\Payment;

/**
 * Payment method model
 *
 */
class Method extends \XLite\Model\Payment\Method implements \XLite\Base\IDecorator
{
    /**
     * Get payment method setting by its name
     * 
     * @param string $name Setting name
     *
     * @return string
     */
    public function getSetting($name)
    {
        if (\XLite\Module\CDev\Paypal\Main::PP_METHOD_EC == $this->getServiceName() && $this->isForcedEnabled()) {
            $parentMethod = $this->getProcessor()->getParentMethod();
            $result = $parentMethod->getSetting($name);

        } else {
            $result = parent::getSetting($name);
        }

        return $result;
    }

    /**
     * Additional check for PPS 
     * 
     * @return boolean
     */
    public function isEnabled()
    {
        $result = parent::isEnabled();

        if ($result && \XLite\Module\CDev\Paypal\Main::PP_METHOD_PPS == $this->getServiceName()) {
            $result = !$this->getProcessor()->isExpressCheckoutEnabled();
        }

        return $result;
    }
}
