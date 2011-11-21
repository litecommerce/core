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
 * @subpackage Decorator
 * @author     Creative Development LLC <info@cdev.ru>
 * @copyright  Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      1.0.0
 */

namespace Includes\Decorator\Plugin\StaticRoutines;

/**
 * Main
 *
 * @package XLite
 * @see     ____class_see____
 * @since   1.0.0
 */
class Main extends \Includes\Decorator\Plugin\APlugin
{
    /**
     * Name of the so called "static constructor"
     */
    const STATIC_CONSTRUCTOR_METHOD = '__constructStatic';


    // ------------------------------ Hook handlers -

    /**
     * Execute certain hook handler
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function executeHookHandlerStepSecond()
    {
        static::getClassesTree()->walkThrough(array($this, 'addStaticConstructorCall'));
    }


    // ------------------------------ Auxiliary methods -

    /**
     * Add static constructor calls
     * NOTE: method is public since it's used as a callback in external class
     *
     * @param \Includes\Decorator\DataStructure\Graph\Classes $node   Current node
     * @param \Includes\Decorator\DataStructure\Graph\Classes $parent Current node parent
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function addStaticConstructorCall(
        \Includes\Decorator\DataStructure\Graph\Classes $node,
        \Includes\Decorator\DataStructure\Graph\Classes $parent = null
    ) {
        if ($this->checkForStaticConstructor($node) && !($parent && $this->checkForStaticConstructor($parent))) {
            $this->writeCallToSourceFile($node);
        }
    }

    /**
     * Check if node has the static constructor defined
     *
     * @param \Includes\Decorator\DataStructure\Graph\Classes $node Node to check
     *
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function checkForStaticConstructor(\Includes\Decorator\DataStructure\Graph\Classes $node)
    {
        return $node->getReflection()->hasStaticConstructor;
    }

    /**
     * Modify class source
     *
     * @param \Includes\Decorator\DataStructure\Graph\Classes $node Current node
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function writeCallToSourceFile(\Includes\Decorator\DataStructure\Graph\Classes $node)
    {
        $content = \Includes\Utils\FileManager::read($path = LC_DIR_CACHE_CLASSES . $node->getPath());

        $content .= PHP_EOL . '// Call static constructor' . PHP_EOL;
        $content .= '\\' . $node->getClass() . '::' . self::STATIC_CONSTRUCTOR_METHOD . '();';

        \Includes\Utils\FileManager::write($path, $content);
    }
}
