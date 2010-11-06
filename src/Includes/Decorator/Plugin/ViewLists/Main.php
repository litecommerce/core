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
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace Includes\Decorator\Plugin\ViewLists;

/**
 * Decorator plugin to generate widget lists
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Main extends \Includes\Decorator\Plugin\APlugin
{
    /**
     * Predefined tag names
     */

    const TAG_LIST_CHILD = 'ListChild';


    /**
     * Parameters for the tags
     */

    const PARAM_TAG_LIST_CHILD_CLASS      = 'class';
    const PARAM_TAG_LIST_CHILD_LIST       = 'list';
    const PARAM_TAG_LIST_CHILD_WEIGHT     = 'weight';
    const PARAM_TAG_LIST_CHILD_FIRST      = 'first';
    const PARAM_TAG_LIST_CHILD_LAST       = 'last';
    const PARAM_TAG_LIST_CHILD_CONTROLLER = 'controller';
    

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

    /**
     * Get list of classes defined the "ListChild" tag
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getAnnotatedPHPClasses()
    {
        return static::getClassesTree()->findByCallback(
            function (\Includes\Decorator\DataStructure\ClassData\Node $node) {
                return !is_null($node->getTag(constant(__CLASS__ . '::TAG_LIST_CHILD')));
            }
        );
    }

    /**
     * Return list of templates to parse 
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getTemplates()
    {
        return array();
    }

    /**
     * Return the model repository for the "\XLite\Model\ViewList" class
     * 
     * @return \XLite\Model\Repo\ViewList
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getRepo()
    {
        return \XLite\Core\Database::getRepo('\XLite\Model\ViewList');
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
        $this->getRepo()->deleteInBatch($this->getRepo()->findAll());
    }

    /**
     * Check the weight-related attributes
     * 
     * @param array &$data data to prepare
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
        }

        // Set default value
        if (!isset($data[self::PARAM_TAG_LIST_CHILD_WEIGHT])) {
            $data[self::PARAM_TAG_LIST_CHILD_WEIGHT] = \XLite\Model\ViewList::POSITION_LAST;
        }
    }

    /**
     * Check for so called list "preprocessors"
     *
     * @param array &$data data to use
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
     * Check for so called list "preprocessors" in module
     *
     * @param array  &$data  data to use
     * @param string $module module name
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareListByModule(array &$data, $module)
    {
        $class  = \Includes\Decorator\Utils\ModulesManager::getClassNameByModuleName($module);
        $method = 'modifyViewLists';

        if (method_exists($class, $method)) {
            $class::$method($data);
        }
    }

    /**
     * Prepare attributes of the "ListChild" tag
     * 
     * @param array  $data  tag attributes
     * @param string $class widget class
     *  
     * @return array
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareListChildTagData(array $data, $class)
    {
        // Set "child" attribute
        if (isset($class)) {
            $data['child'] = $class;
        }

        // Check the weight-related attributes
        $this->prepareWeightAttrs($data);

        // Check for preprocessors
        $this->preparePreprocessors($data);

        return $data;
    }

    /**
     * Return all defined "ListChild" tag attributes
     *
     * @param \Includes\Decorator\DataStructure\ClassData\Node $node class tree node
     *  
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getAllListChildTagAttributes(\Includes\Decorator\DataStructure\ClassData\Node $node)
    {
        $data = array();

        // It's allowed to define several tags per class
        foreach ($node->getTag(self::TAG_LIST_CHILD) as $attrs) {

            // Prepare attributes and save them into the list
            $data[] = $this->prepareListChildTagData(
                $attrs,
                \Includes\Decorator\Utils\ClassData\Operator::getFinalClass($node->getClass())
            );
        }

        return $data;
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
        $data = array();

        // Iterate over all classes which define the "ListChild" tag
        foreach ($this->getAnnotatedPHPClasses() as $node) {

            // Add tag attributes to the list
            $data = array_merge($data, $this->getAllListChildTagAttributes($node));
        }

        return $data;
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
        $data = array();

        // Iterate over all templates
        foreach ($this->getTemplates() as $template) {
        }

        return $data;
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
        // Collect all "ListChild" tags
        $data = array_merge($this->getListChildTagsFromPHP(), $this->getListChildTagsFromTemplates());

        // Check modules for the list modifiers
        // TODO: check if it's really useful
        foreach (\Includes\Decorator\Utils\ModulesManager::getActiveModules() as $module) {
            $this->prepareListByModule($data, $module['name']);
        }

        return $data;
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
        $this->getRepo()->insertInBatch($this->getAllListChildTags());
    }


    /**
     * Generate widget lists
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function executeHookHandlerRun()
    {
        // Truncate old
        $this->clearAll();

        // Create new
        $this->createLists();
    }

    /**
     * Hook handler to prepare class tags
     * 
     * @param array &$matches parser result
     *  
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function executeHookHandlerPrepareTags(array &$matches)
    {
        // Find all self::TAG_LIST_CHILD tags in the class definition
        if ($keys = array_keys($matches[1], self::TAG_LIST_CHILD)) {
            $lists = array();

            // preg_match_all() returns numeric keys
            foreach ($keys as $key) {

                // Parse tag and convert string into hash array
                $data = \Includes\Decorator\Utils\ClassData\Parser::parseTagValue($matches[2][$key]);

                // Check attributes
                if (!isset($data[self::PARAM_TAG_LIST_CHILD_LIST])) {
                    throw new \Exception(
                        'The "' . self::PARAM_TAG_LIST_CHILD_LIST . '" is required for the "' . self::TAG_LIST_CHILD . '" tag'
                    );
                }

                // This tag will contain several defenitions
                $lists[] = $data;

                // To prevent usage of these keys in "array_combine()" function
                unset($matches[1][$key], $matches[2][$key]);
            }

            // So, these are new values to use in "array_combine()" function
            $matches[1][] = self::TAG_LIST_CHILD;
            $matches[2][] = $lists;
        }
    }
}
