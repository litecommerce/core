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

namespace Includes\Decorator\Plugin\Doctrine\Plugin\ProxyGenerator;

/**
 * Main 
 *
 */
class Main extends \Includes\Decorator\Plugin\Doctrine\Plugin\APlugin
{
    /**
     * Execute certain hook handle
     *
     * @return void
     */
    public function executeHookHandler()
    {
        if (!$this->areProxiesExist()) {

            // Create the proxies folder
            \Includes\Utils\FileManager::mkdirRecursive(LC_DIR_CACHE_PROXY);

            // Create model proxy classes (second step of cache generation)
            \Includes\Decorator\Plugin\Doctrine\Utils\EntityManager::generateProxies();
        }
    }

    /**
     * Check if proxy classes are already generated
     *
     * @return boolean
     */
    protected function areProxiesExist()
    {
        return \Includes\Utils\FileManager::isDirReadable(LC_DIR_CACHE_PROXY);
    }
}
