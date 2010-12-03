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
     * Common function to filter classes and templates
     * 
     * @param \Includes\DataStructure\Hierarchical\AHierarchical $set set of entities
     *  
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getAnnotatedEntites(\Includes\DataStructure\Hierarchical\AHierarchical $set)
    {
        return $set->findByCallback(array($this, 'filterByListChildTag'));
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
        return $this->getAnnotatedEntites(static::getClassesTree());
    }

    /**
     * Return list of templates to parse 
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getAnnotatedTemplates()
    {
        return $this->getAnnotatedEntites(static::getTemplatesCollection());
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
     * @param array $data tag attributes
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
     * Return all defined "ListChild" tag attributes
     *
     * @param \Includes\Decorator\Data\Classes\Node $node class tree node
     *  
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getAllListChildTagAttributes(array $nodes, $callback)
    {
        $data = array();

        // Iterate over all nodes
        foreach ($nodes as $node) {

            // It's allowed to define several tags per class
            foreach ($node->getTag(self::TAG_LIST_CHILD) as $attrs) {

                // Prepare attributes and save them into the list
                $data[] = $this->prepareListChildTagData($attrs) + $callback($node);
            }
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
        return $this->getAllListChildTagAttributes(
            $this->getAnnotatedPHPClasses(),
            function (\Includes\DataStructure\Cell $node) {
                return array(
                    'child' => \Includes\Decorator\Utils\Operator::getFinalClass($node->getClass()),
                );
            }
        );
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
        return $this->getAllListChildTagAttributes(
            $this->getAnnotatedTemplates(),
            function (\Includes\DataStructure\Cell $node) {
                $tpl = substr($node->{constant(__CLASS__ . '::N_FILE_PATH')}, strlen(LC_SKINS_DIR));

                $zone = substr($tpl, 0, strpos($tpl, LC_DS));

                if ('console' == $zone) {
                    $zone = \XLite\Model\ViewList::INTERFACE_CONSOLE;

                } elseif ('admin' == $zone) {
                    $zone = \XLite\Model\ViewList::INTERFACE_ADMIN;

                } else {
                    $zone = \XLite\Model\ViewList::INTERFACE_CUSTOMER;
                }

                return array(
                    'tpl'  => $tpl,
                    'zone' => $zone,
                );
            }
        );
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
            $this->prepareListByModule($data, $module['author'] . '\\' . $module['name']);
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
        \XLite\Core\Database::getRepo('\XLite\Model\ViewList')->insertInBatch($this->getAllListChildTags());
    }


    /**
     * Method to filter classes and templates
     *
     * @param \Includes\DataStructure\Cell $node current node
     *
     * @return bool
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function filterByListChildTag(\Includes\DataStructure\Cell $node)
    {
        return !is_null($node->getTag(self::TAG_LIST_CHILD));
    }

    /**
     * Execute "run" hook handler
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
}
