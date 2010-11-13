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

namespace Includes\Decorator\Plugin\Templates\Data\Templates;

/**
 * List 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Collection extends \Includes\DataStructure\Hierarchical\AHierarchical
{
    /**
     * Templates list cache
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $list = array();


    /**
     * Get iterator for class files
     *
     * @return \Includes\Utils\FileFilter\FilterIterator
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getFileIterator()
    {
        $filter = new \Includes\Utils\FileFilter(LC_SKINS_DIR);

        $filter->filterBy('extension', 'tpl');
        $filter->filterBy('pattern', \Includes\Decorator\Utils\ModulesManager::getPathPatternForTemplates());

        return $filter->getIterator();
    }

    /**
     * Check and prepare current element data
     *
     * @param \SplFileInfo $fileInfo file descriptor
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareNodeData($fileinfo)
    {
        // return null;
        return \Includes\Decorator\Plugin\Templates\Utils\Parser::parse($fileinfo);
    }

    /**
     * Create new node
     *
     * @param array $node node data
     *
     * @return \Includes\Decorator\Plugin\Templates\Data\Templates\Node
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function createNode(array $data = array())
    {
        return new \Includes\Decorator\Plugin\Templates\Data\Templates\Node($data);
    }

    /**
     * Walk over the skins and create templates list
     * 
     * @param \Includes\Utils\FileFilter\FilterIterator $iterator FS iterator
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function create(\Includes\Utils\FileFilter\FilterIterator $iterator)
    {
        foreach ($iterator as $data) {

            // Check and prepare current element data
            if ($data = $this->prepareNodeData($data)) {

                // Add node to the list
                $this->list[] = $this->createNode($data);
            }
        }
    }


    /**
     * Find nodes using a callback function
     *
     * @param mixed $callback callback to execute
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findByCallback($callback)
    {
        return array_filter($this->getList(), $callback);
    }

    /**
     * Return templates list
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getList()
    {
        return $this->list;
    }

    /**
     * Constructor
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __construct()
    {
        // Walk through the .tpl files and collect info
        $this->create($this->getFileIterator());
    }
}
