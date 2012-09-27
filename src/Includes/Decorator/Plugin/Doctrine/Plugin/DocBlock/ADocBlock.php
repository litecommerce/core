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

namespace Includes\Decorator\Plugin\Doctrine\Plugin\DocBlock;

/**
 * ADocBlock
 *
 */
abstract class ADocBlock extends \Includes\Decorator\Plugin\Doctrine\Plugin\APlugin
{
    /**
     * Execute certain hook handler
     *
     * @return void
     */
    public function executeHookHandler()
    {
        static::getClassesTree()->walkThrough(array($this, 'correctTags'));
    }

    /**
     * Check and correct (if needed) class doc block comment
     *
     * @param \Includes\Decorator\DataStructure\Graph\Classes $node Current node
     *
     * @return void
     */
    public function correctTags(\Includes\Decorator\DataStructure\Graph\Classes $node)
    {
        if ($this->checkRewriteCondition($node)) {
            $this->correctTagsOnElement($node);
        }
    }

    /**
     * Correct (if needed) class doc block comment. Works for one element from the queue
     *
     * @param \Includes\Decorator\DataStructure\Graph\Classes $node Current node
     *
     * @return void
     */
    protected function correctTagsOnElement(\Includes\Decorator\DataStructure\Graph\Classes $node)
    {
        $path = LC_DIR_CACHE_CLASSES . $node->getPath();

        \Includes\Utils\FileManager::write(
            $path,
            \Includes\Decorator\Utils\Tokenizer::getSourceCode(
                $path,
                null,
                null,
                null,
                call_user_func_array(array($node, 'addLinesToDocBlock'), $this->getTagsToAdd($node)),
                $node->isDecorator() ? 'abstract' : null
            )
        );
    }

    /**
     * Condition to check for rewrite
     *
     * @param \Includes\Decorator\DataStructure\Graph\Classes $node Current node
     *
     * @return boolean
     */
    protected function checkRewriteCondition(\Includes\Decorator\DataStructure\Graph\Classes $node)
    {
        return is_subclass_of($node->getClass(), '\XLite\Model\AEntity');
    }

    /**
     * Return DocBlock tags
     *
     * @param \Includes\Decorator\DataStructure\Graph\Classes $node Current node
     *
     * @return array
     */
    protected function getTagsToAdd(\Includes\Decorator\DataStructure\Graph\Classes $node)
    {
        $result = array();

        if ($node->getTag('HasLifecycleCallbacks')) {
            $result[] = 'HasLifecycleCallbacks';
        }

        return array($result, false);
    }
}
