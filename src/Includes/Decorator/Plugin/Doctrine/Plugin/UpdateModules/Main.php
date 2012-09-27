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

namespace Includes\Decorator\Plugin\Doctrine\Plugin\UpdateModules;

/**
 * Main 
 *
 */
class Main extends \Includes\Decorator\Plugin\Doctrine\Plugin\APlugin
{
    /**
     * Execute certain hook handler
     *
     * @return void
     */
    public function executeHookHandler()
    {
        // To cache data
        \Includes\Utils\ModulesManager::getActiveModules();

        // Walk through the "XLite/Module" directory
        foreach ($this->getModuleMainFileIterator()->getIterator() as $path => $data) {
            $dir    = $path;
            $name   = basename($dir = dirname($dir));
            $author = basename($dir = dirname($dir));
            $class  = \Includes\Utils\ModulesManager::getClassNameByAuthorAndName($author, $name);

            if (!\Includes\Utils\Operator::checkIfClassExists($class)) {
                require_once ($path);
            }

            \Includes\Utils\ModulesManager::switchModule($author, $name);
        }

        \Includes\Utils\ModulesManager::removeFile();
    }

    /**
     * Get iterator for module files
     *
     * @return \Includes\Utils\FileFilter
     */
    protected function getModuleMainFileIterator()
    {
        return new \Includes\Utils\FileFilter(LC_DIR_MODULES, $this->getModulesPathPattern());
    }

    /**
     * Pattern to use for paths in "Module" directory
     *
     * @return string
     */
    protected function getModulesPathPattern()
    {
        return '|^' . preg_quote(LC_DIR_MODULES) . '(\w)+' . LC_DS_QUOTED . '(\w)+' . LC_DS_QUOTED . 'Main.php$|Si';
    }
}
