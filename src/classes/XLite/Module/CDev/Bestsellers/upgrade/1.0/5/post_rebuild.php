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

return function()
{
    // Apply config changes
    $repo = \XLite\Core\Database::getRepo('XLite\Model\Config');

    $option = $repo->findOneBy(array('name' => 'bestsellers_menu'));
    if ($option) {
        $option->setType('XLite\\Module\\CDev\\Bestsellers\\View\\FormField\\Select\\Menu');
    }

    $option = $repo->findOneBy(array('name' => 'number_of_bestsellers'));
    if ($option) {
        $option->setType('XLite\\View\\FormField\\Input\\Text\\Integer');
        $option->setWidgetParameters(array('min' => 1));
    }

    \XLite\Core\Database::getEM()->flush();
    \XLite\Core\Database::getCacheDriver()->deleteAll();
};
