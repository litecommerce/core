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

namespace Includes\Decorator\Plugin\Templates\Plugin\Patcher;

/**
 * Main 
 *
 */
class Main extends \Includes\Decorator\Plugin\Templates\Plugin\APlugin
{
    /**
     * Interface for so called "patcher" classes
     */
    const INTERFACE_PATCHER = '\XLite\Base\IPatcher';

    /**
     * List of pather classes
     *
     * @var array
     */
    protected $patchers;

    /**
     * Execute certain hook handler
     *
     * @return void
     */
    public function executeHookHandler()
    {
        // Truncate old
        $this->clearAll();

        // Save pathes info in DB
        $this->collectPatches();
    }

    /**
     * Callback to collect patchers
     *
     * @param \Includes\Decorator\DataStructure\Graph\Classes $node Current node
     *
     * @return void
     */
    public function checkClassForPatcherInterface(\Includes\Decorator\DataStructure\Graph\Classes $node)
    {
        if ($node->isImplements(static::INTERFACE_PATCHER)) {
            $this->patchers[] = $node;
        }
    }

    /**
     * Remove existing lists from database
     *
     * @return void
     */
    protected function clearAll()
    {
        \XLite\Core\Database::getRepo('\XLite\Model\TemplatePatch')->clearAll();
    }

    /**
     * Save pathes info in DB
     *
     * @return void
     */
    protected function collectPatches()
    {
        $data = array();

        // List of all "patcher" classes
        foreach ($this->getPatchers() as $node) {
            $class = $node->getClass();

            // List of patches defined in class
            foreach (call_user_func(array($class, 'getPatches')) as $patch) {

                // Prepare model class properties
                $data[] = new \XLite\Model\TemplatePatch(
                    $this->getCommonData($patch, $class) 
                    + $this->{'get' . ucfirst($patch[$class::PATCHER_CELL_TYPE]) . 'Data'}($patch, $class)
                );
            }
        }

        \XLite\Core\Database::getRepo('\XLite\Model\TemplatePatch')->insertInBatch($data);
    }

    /**
     * Return list of the "patcher" classes
     *
     * @return array
     */
    protected function getPatchers()
    {
        if (!isset($this->patchers)) {
            $this->patchers = array();

            static::getClassesTree()->walkThrough(array($this, 'checkClassForPatcherInterface'));
        }

        return $this->patchers;
    }

    /**
     * Prepare common properties
     *
     * @param array  $data  Data describe the patch
     * @param string $class Patcher class
     *
     * @return array
     */
    protected function getCommonData(array $data, $class)
    {
        return array('patch_type' => $class::PATCHER_CELL_TYPE)
            + array_combine(array('zone', 'lang', 'tpl'), explode(':', $patch[$class::PATCHER_CELL_TPL], 3));
    }

    /**
     * Prepare properties for certain patch type
     *
     * @param array  $data  Data describe the patch
     * @param string $class Patcher class
     *
     * @return array
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
     * @param array  $data  Data describe the patch
     * @param string $class Patcher class
     *
     * @return array
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
     * @param array $data Data describe the patch
     *
     * @return array
     */
    protected function getCustomData(array $data)
    {
        return array(
            'custom_callback' => $class . '::' . $patch[$class::CUSTOM_CELL_CALLBACK],
        );
    }
}
