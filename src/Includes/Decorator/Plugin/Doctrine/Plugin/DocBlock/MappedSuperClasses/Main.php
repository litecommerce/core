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

namespace Includes\Decorator\Plugin\Doctrine\Plugin\DocBlock\MappedSuperClasses;

/**
 * Main 
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Main extends \Includes\Decorator\Plugin\Doctrine\Plugin\DocBlock\ADocBlock
{
    /**
     * Condition to check for rewrite
     *
     * @param \Includes\Decorator\DataStructure\Graph\Classes $node Current node
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.22
     */
    protected function checkRewriteCondition(\Includes\Decorator\DataStructure\Graph\Classes $node)
    {
        return parent::checkRewriteCondition($node) && ($node->isDecorator() || $node->isLowLevelNode());
    }

    /**
     * Return DocBlock tags
     *
     * @param \Includes\Decorator\DataStructure\Graph\Classes $node Current node
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.22
     */
    protected function getTagsToAdd(\Includes\Decorator\DataStructure\Graph\Classes $node)
    {
        list($result, $flag) = parent::getTagsToAdd($node);
        $result[] = 'MappedSuperClass';

        return array($result, $flag || $node->isLowLevelNode());
    }
}
