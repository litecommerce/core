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

namespace Includes\Decorator\Plugin\Doctrine\Plugin\DocBlock\ReplaceTopEntity;

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
        return parent::checkRewriteCondition($node) && ($node->getTag('Entity') || $node->getTag('MappedSuperClass'));
    }

    /**
     * Correct (if needed) class doc block comment. Works for one element from the queue
     *
     * @param \Includes\Decorator\DataStructure\Graph\Classes $node Current node
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function correctTagsOnElement(\Includes\Decorator\DataStructure\Graph\Classes $node)
    {
        \Includes\Utils\FileManager::replace(
            LC_DIR_CACHE_CLASSES . $node->getPath(),
            '@$1 ' . $this->getRepositoryCustomClassParameter($node),
            \Includes\Decorator\Utils\Operator::getTagPattern(array('Entity', 'MappedSuperClass'))
        );
    }

    /**
     * Return doctrine specified custom repository parameter for the current node
     *
     * @param \Includes\Decorator\DataStructure\Graph\Classes $node Current node
     *
     * @return string Doctrine specified parameter for @Entity and @MappedSuperClass
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getRepositoryCustomClassParameter(\Includes\Decorator\DataStructure\Graph\Classes $node)
    {
        $repositoryClass = $this->getRepositoryClass($node);

        return $repositoryClass ? '(repositoryClass="' . $repositoryClass . '")' : '';
    }

    /**
     * Returns repository class for model class of current node
     *
     * @param \Includes\Decorator\DataStructure\Graph\Classes $node Current node
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getRepositoryClass(\Includes\Decorator\DataStructure\Graph\Classes $node)
    {
        $repositoryClass = null;

        if (!($node->isLowLevelNode() || $node->isTopLevelNode() || !$node->isDecorator())) {

            $children = $node->getChildren();

            $repositoryClass = isset($children[0]) ? $this->getRepositoryClass($children[0]) : $this->getDefaultRepositoryClass('');;

        } else {

            $repositoryClass = \Includes\Utils\Converter::getPureClassName($node->getClass());
            $repositoryClass = \Includes\Utils\Converter::prepareClassName(str_replace('\Model\\', '\Model\Repo\\', $repositoryClass), false);

            if (!\XLite\Core\Operator::isClassExists($repositoryClass)) {
                $repositoryClass = $this->getDefaultRepositoryClass($repositoryClass);
            }
        }

        return $repositoryClass;
    }

    /**
     * Returns class name for the default model repository class
     *
     * @param string $class Model class name
     *
     * @return string Default model repository class name
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultRepositoryClass($class)
    {
        return '\XLite\Model\Repo\Base\\' . (preg_match('/\wTranslation$/Ss', $class) ? 'Translation' : 'Common');
    }
}
