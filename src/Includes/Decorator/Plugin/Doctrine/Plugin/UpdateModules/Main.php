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
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage Includes
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    GIT: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      1.0.0
 */

namespace Includes\Decorator\Plugin\Doctrine\Plugin\UpdateModules;

/**
 * Routines for Doctrine library
 *
 * @package XLite
 * @see     ____class_see____
 * @since   1.0.0
 */
class Main extends \Includes\Decorator\Plugin\Doctrine\Plugin\APlugin
{
    /**
     * Execute certain hook handler
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function executeHookHandlerStepThird()
    {
        // To cache data
        \Includes\Decorator\Utils\ModulesManager::getActiveModules();

        // Walk through the "XLite/Module" directory
        foreach ($this->getModuleMainFileIterator()->getIterator() as $path => $data) {

            $dir    = $path;
            $name   = basename($dir = dirname($dir));
            $author = basename($dir = dirname($dir));
            $class  = \Includes\Decorator\Utils\ModulesManager::getClassNameByAuthorAndName($author, $name);

            if (!\Includes\Utils\Operator::checkIfClassExists($class)) {
                require_once ($path);
            }

            \Includes\Decorator\Utils\ModulesManager::switchModule($author, $name);
        }

        \Includes\Decorator\Utils\ModulesManager::removeFile();
    }

    /**
     * Get iterator for module files
     *
     * @return \Includes\Utils\FileFilter
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getModuleMainFileIterator()
    {
        return new \Includes\Utils\FileFilter(LC_MODULES_DIR, $this->getModulesPathPattern());
    }

    /**
     * Pattern to use for paths in "Module" directory
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getModulesPathPattern()
    {
        return '|^' . LC_MODULES_DIR . '(\w)+' . LC_DS . '(\w)+' . LC_DS . 'Main.php$|Si';
    }
}
