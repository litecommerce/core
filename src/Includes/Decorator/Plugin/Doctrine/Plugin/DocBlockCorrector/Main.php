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
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace Includes\Decorator\Plugin\Doctrine\Plugin\DocBlockCorrector;

/**
 * Doctrine tags support for Decorator
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Main extends \Includes\Decorator\Plugin\Doctrine\Plugin\APlugin
{
    /**
     * Comments to set for decorated entities
     */
    const DOC_BLOCK_FOR_PLUGINS = '/**
 * @Entity
 */';
    const DOC_BLOCK_FINAL       = '/**
 * @MappedSuperClass
 */';

    // {{{ Hook handlers

    /**
     * Execute certain hook handler
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function executeHookHandlerStepFirst()
    {
        static::getClassesTree()->walkThrough(array($this, 'setMappedSuperClassTagStepFirst'));
    }

    /**
     * Execute certain hook handler
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function executeHookHandlerStepSecond()
    {
        static::getClassesTree()->walkThrough(array($this, 'setMappedSuperClassTagStepSecond'));
    }

    // }}}

    // {{{ Methods to apply for class tree nodes

    /**
     * Check and correct (if needed) class doc block comment
     *
     * @param \Includes\Decorator\DataStructure\Graph\Classes $node Current node
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function setMappedSuperClassTagStepFirst(\Includes\Decorator\DataStructure\Graph\Classes $node)
    {
        // Only perform the action if node has been decorated, and it's a Doctrine entity
        if ($node->isDecorator() && is_subclass_of($node->getClass(), '\XLite\Model\AEntity')) {
            $this->writeCorrectedDockBlock($node, static::DOC_BLOCK_FOR_PLUGINS);
        }
    }

    /**
     * Check and correct (if needed) class doc block comment
     *
     * @param \Includes\Decorator\DataStructure\Graph\Classes $node Current node
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function setMappedSuperClassTagStepSecond(\Includes\Decorator\DataStructure\Graph\Classes $node)
    {
        // Only perform the action if node has been decorated, and it's a Doctrine entity
        if (
            ($node->isLowLevelNode() || $node->isDecorator()) 
            && is_subclass_of($node->getClass(), '\XLite\Model\AEntity')
        ) {
            $this->writeCorrectedDockBlock($node, static::DOC_BLOCK_FINAL);
        }
    }

    // }}}

    // {{{ Auxiliarry methods

    /**
     * Write corrected DockBlock
     *
     * @param \Includes\Decorator\DataStructure\Graph\Classes $node     Current node
     * @param string                                          $docBlock DOC block to set
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.22
     */
    protected function writeCorrectedDockBlock(\Includes\Decorator\DataStructure\Graph\Classes $node, $docBlock)
    {
        $path = LC_DIR_CACHE_CLASSES . $node->getPath();

        \Includes\Utils\FileManager::write(
            $path,
            \Includes\Decorator\Utils\Tokenizer::getSourceCode(
                $path,
                null,
                null,
                null,
                $docBlock,
                $node->isDecorator() ? 'abstract' : null
            )
        );
    }

    // }}}
}
