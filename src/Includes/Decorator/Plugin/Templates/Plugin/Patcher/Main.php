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

namespace Includes\Decorator\Plugin\Templates\Plugin\Patcher;

/**
 * Decorator plugin to patch templates
 *
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Main extends \Includes\Decorator\Plugin\Templates\Plugin\APlugin
{
    /**
     * Interface for so called "patcher" classes
     */
    const INTERFACE_PATCHER = '\XLite\Base\IPatcher';


    /**
     * Return list of the "patcher" classes
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getPatchers()
    {
        return static::getClassesTree()->findByCallback(array($this, 'filterByPatcherInterface'));
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
        \XLite\Core\Database::getRepo('\XLite\Model\TemplatePatch')->clearAll();
    }

    /**
     * Prepare common properties
     * 
     * @param array  $data  data describe the patch
     * @param string $class patcher class
     *  
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getCommonData(array $data, $class)
    {
        return array('patch_type' => $class::PATCHER_CELL_TYPE)
            + array_combine(array('zone', 'lang', 'tpl'), explode(':', $patch[$class::PATCHER_CELL_TPL], 3));
    }

    /**
     * Prepare properties for certain patch type
     *
     * @param array  $data  data describe the patch
     * @param string $class patcher class
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getXpathData(array $data, $class)
    {
        return array(
            'xpath_query'       => $data[$class::XPATH_CELL_QUERY],
            'xpath_insert_type' => $data[$class::XPATH_CELL_INSERT_TYPE],
            'xpath_block'       => $data[$class::XPATH_CELL_BLOCK],
        );
    }

    /**
     * Prepare properties for certain patch type
     *
     * @param array  $data  data describe the patch
     * @param string $class patcher class
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getRegexpData(array $data, $class)
    {
        return array(
            'regexp_pattern' => $data[$class::REGEXP_CELL_PATTERN],
            'regexp_replace' => $data[$class::REGEXP_CELL_REPLACE],
        );
    }

    /**
     * Prepare properties for certain patch type
     *
     * @param array $data data describe the patch
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getCustomData(array $data)
    {
        return array(
            'custom_callback' => $class . '::' . $patch[$class::CUSTOM_CELL_CALLBACK],
        );
    }

    /**
     * Save pathes info in DB
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function collectPatches()
    {
        $data = array();

        // List of all "patcher" classes
        foreach ($this->getPatchers() as $node) {

            // List of patches defined in class
            foreach (call_user_func(array($class = $node->getClass(), 'getPatches')) as $patch) {

                // Prepare model class properties
                $data[] = $this->getCommonData($patch, $class) 
                    + $this->{'get' . ucfirst($patch[$class::PATCHER_CELL_TYPE]) . 'Data'}($patch, $class);
            }
        }

        \XLite\Core\Database::getRepo('\XLite\Model\TemplatePatch')->insertInBatch($data);
    }


    /**
     * Method to filter classes by the interface
     * 
     * @param \Includes\Decorator\DataStructure\Node\ClassInfo $node current node
     *  
     * @return bool
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function filterByPatcherInterface(\Includes\Decorator\DataStructure\Node\ClassInfo $node)
    {
        return $node->isImplements(self::INTERFACE_PATCHER);
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

        // Save pathes info in DB
        $this->collectPatches();
    }
}
