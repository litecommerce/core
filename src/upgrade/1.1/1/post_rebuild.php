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
 */

return function()
{
    // Enable all payment methods
    \XLite\Core\Database::getRepo('XLite\Model\Payment\Method')
        ->getQueryBuilder()
        ->update('XLite\Model\Payment\Method', 'e')
        ->set('e.moduleEnabled', ':enabled')
        ->setParameter('enabled', $enabled)
        ->execute();

    // Disable payment methods from disabled modules
    $qb = \XLite\Core\Database::getRepo('XLite\Model\Payment\Method')
        ->getQueryBuilder()
        ->update('XLite\Model\Payment\Method', 'e')
        ->set('e.moduleEnabled', ':enabled')
        ->where('LOCATE(:class, e.class) > 0')
        ->setParameter('enabled', false);

    $cnd = new \XLite\Core\CommonCell;
    $cnd->inactive = true;
    foreach (\XLite\Core\Database::getRepo('XLite\Model\Module')->search($cnd) as $module) {
        $qb->setParameter('class', $module->getActualName())->execute();
    }
};
