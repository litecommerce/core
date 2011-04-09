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

namespace Includes\Decorator\Plugin\Doctrine\Plugin\DocBlockCorrector;

/**
 * Doctrine tags support for Decorator
 *
 * @package XLite
 * @see     ____class_see____
 * @since   1.0.0
 */
class Main extends \Includes\Decorator\Plugin\Doctrine\Plugin\APlugin
{
    /**
     * Comment to set for decorated entities 
     */
    const DOC_BLOCK = '/**
 * @MappedSuperClass
 */';


    /**
     * Execute certain hook handler
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function executeHookHandlerStepFirst()
    {
        static::getClassesTree()->walkThrough(array($this, 'setMappedSuperClassTag'));
    }

    /**
     * Check and correct (if needed) class doc block comment
     * 
     * @param \Includes\Decorator\DataStructure\Graph\Classes $node Current node
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function setMappedSuperClassTag(\Includes\Decorator\DataStructure\Graph\Classes $node)
    {
        // Only perform the action if node has been decorated, and it's a Doctrine entity
        if ($node->isLowLevelNode() && $node->getTag('Entity')) {

            // Write changes to FS
            \Includes\Utils\FileManager::write(
                $path = LC_CLASSES_CACHE_DIR . $node->getPath(), 
                \Includes\Decorator\Utils\Tokenizer::getSourceCode($path, null, null, null, self::DOC_BLOCK)
            );
        }
    }
}
