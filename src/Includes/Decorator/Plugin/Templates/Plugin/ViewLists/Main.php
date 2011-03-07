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
 * @since      3.0.0
 */

namespace Includes\Decorator\Plugin\Templates\Plugin\ViewLists;

/**
 * Decorator plugin to generate widget lists
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Main extends \Includes\Decorator\Plugin\Templates\Plugin\APlugin
{
    /**
     * Parameters for the tags
     */

    const PARAM_TAG_LIST_CHILD_CLASS      = 'class';
    const PARAM_TAG_LIST_CHILD_LIST       = 'list';
    const PARAM_TAG_LIST_CHILD_WEIGHT     = 'weight';
    const PARAM_TAG_LIST_CHILD_ZONE       = 'zone';
    const PARAM_TAG_LIST_CHILD_FIRST      = 'first';
    const PARAM_TAG_LIST_CHILD_LAST       = 'last';
    const PARAM_TAG_LIST_CHILD_CONTROLLER = 'controller';
    

    /**
     * List of PHP classes with the "ListChild" tags
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $annotatedPHPCLasses;


    /**
     *  Execute certain hook handler
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function executeHookHandlerStepThird()
    {
        // Truncate old
        $this->clearAll();

        // Create new
        $this->createLists();
    }

    /**
     * Callback to search annotated PHP classes
     * 
     * @param \Includes\Decorator\DataStructure\Graph\Classes $node Current node
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function checkClassForListChildTag(\Includes\Decorator\DataStructure\Graph\Classes $node)
    {
        if ($lists = $node->getTag(self::TAG_LIST_CHILD)) {
            $data = array('child' => $node->getTopLevelNode()->getClass());

            foreach ($lists as $tags) {
                $this->annotatedPHPCLasses[] = $data + $tags;
            }
        }
    }

    /**
     * Remove existing lists from database
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function clearAll()
    {
        \XLite\Core\Database::getRepo('\XLite\Model\ViewList')->clearAll();
        \XLite\Core\Database::getRepo('\XLite\Model\TemplatePatch')->clearAll();
    }

    /**
     * Create lists
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function createLists()
    {
        \XLite\Core\Database::getRepo('\XLite\Model\ViewList')->insertInBatch($this->getAllListChildTags());
    }

    /**
     * Return all defined "ListChild" tags
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getAllListChildTags()
    {
        return array_merge($this->getListChildTagsFromPHP(), $this->getListChildTagsFromTemplates());
    }

    /**
     * Return list of PHP classes with the "ListChild" tag
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getAnnotatedPHPCLasses()
    {
        if (!isset($this->annotatedPHPCLasses)) {
            $this->annotatedPHPCLasses = array();
            static::getClassesTree()->walkThrough(array($this, 'checkClassForListChildTag'));
        }

        return $this->annotatedPHPCLasses;
    }

    /**
     * Return all "ListChild" tags defined in PHP classes
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getListChildTagsFromPHP()
    {
        return $this->getAllListChildTagAttributes($this->getAnnotatedPHPCLasses());
    }

    /**
     * Return all "ListChild" tags defined in templates
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getListChildTagsFromTemplates()
    {
        return $this->getAllListChildTagAttributes($this->getAnnotatedTemplates());
    }

    /**
     * Return all defined "ListChild" tag attributes
     *
     * @param array $nodes List of nodes
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getAllListChildTagAttributes(array $nodes)
    {
        return array_map(array($this, 'prepareListChildTagData'), $nodes);
    }

    /**
     * Prepare attributes of the "ListChild" tag
     *
     * @param array $data Tag attributes
     *
     * @return array
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareListChildTagData(array $data)
    {
        // Check the weight-related attributes
        $this->prepareWeightAttrs($data);

        // Check for preprocessors
        $this->preparePreprocessors($data);

        return $data;
    }

    /**
     * Check the weight-related attributes
     *
     * @param array &$data Data to prepare
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareWeightAttrs(array &$data)
    {
        // The "weight" attribute has a high priority
        if (!isset($data[self::PARAM_TAG_LIST_CHILD_WEIGHT])) {

            // "First" and "last" - the reserved keywords for the "weight" attribute values
            foreach ($this->getReservedWeightValues() as $origKey => $modelKey) {

                if (isset($data[$origKey])) {
                    $data[self::PARAM_TAG_LIST_CHILD_WEIGHT] = $modelKey;
                }
            }
        } else {

            $data[self::PARAM_TAG_LIST_CHILD_WEIGHT] = intval($data[self::PARAM_TAG_LIST_CHILD_WEIGHT]);
        }

        // Set default value
        if (!isset($data[self::PARAM_TAG_LIST_CHILD_WEIGHT])) {
            $data[self::PARAM_TAG_LIST_CHILD_WEIGHT] = \XLite\Model\ViewList::POSITION_LAST;
        }
    }

    /**
     * Check for so called list "preprocessors"
     *
     * @param array &$data Data to use
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function preparePreprocessors(array &$data)
    {
        if (isset($data[self::PARAM_TAG_LIST_CHILD_CONTROLLER])) {
            // ...
        }
    }

    /**
     * There are some reserved words for the "weight" param of the "ListChild" tag
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getReservedWeightValues()
    {
        return array(
            self::PARAM_TAG_LIST_CHILD_FIRST => \XLite\Model\ViewList::POSITION_FIRST,
            self::PARAM_TAG_LIST_CHILD_LAST  => \XLite\Model\ViewList::POSITION_LAST,
        );
    }
}
