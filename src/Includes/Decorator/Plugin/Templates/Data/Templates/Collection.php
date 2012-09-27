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

namespace Includes\Decorator\Plugin\Templates\Data\Templates;

/**
 * Collection
 *
 * @package XLite
 */
class Collection
{
    /**
     * Templates list cache
     *
     * @var array
     */
    protected $list = array();


    /**
     * Get iterator for class files
     *
     * @return \Includes\Utils\FileFilter
     */
    protected function getFileIterator()
    {
        return new \Includes\Utils\FileFilter(
            LC_DIR_SKINS,
            \Includes\Utils\ModulesManager::getPathPatternForTemplates()
        );
    }

    /**
     * Check and prepare current element data
     *
     * @param \SplFileInfo $fileInfo file descriptor
     *
     * @return array
     */
    protected function prepareNodeData($fileinfo)
    {
        return \Includes\Decorator\Plugin\Templates\Utils\Parser::parse($fileinfo);
    }

    /**
     * Create new node
     *
     * @param array $node node data
     *
     * @return \Includes\Decorator\Plugin\Templates\Data\Templates\Node
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
     */
    public function findByCallback($callback)
    {
        return array_filter($this->getList(), $callback);
    }

    /**
     * Return templates list
     *
     * @return array
     */
    public function getList()
    {
        return $this->list;
    }

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        // Walk through the .tpl files and collect info
        $this->create($this->getFileIterator()->getIterator());
    }
}
