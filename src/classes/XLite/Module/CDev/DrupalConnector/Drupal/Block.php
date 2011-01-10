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
 * @subpackage ____sub_package____
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Module\CDev\DrupalConnector\Drupal;

/**
 * Block 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Block extends \XLite\Module\CDev\DrupalConnector\Drupal\ADrupal
{
    /**
     * Already registered resources
     *
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $registeredResources = array('js' => array(), 'css' => array());


    /**
     * Check and prepare JS and CSS files
     *
     * @param array $resources List of resources
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareResources(array $resources)
    {
        foreach ($resources as $type => &$files) {
            $files = array_diff($files, $this->registeredResources[$type]);
            $this->registeredResources[$type] = array_merge($this->registeredResources[$type], $files);
        }

        return $resources;
    }

    /**
     * Get block content from LC (if needed)
     *
     * @param array     &$data An array of data, as returned from the hook_block_view()
     * @param \stdClass $block The block object, as loaded from the database
     *
     * @return null
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function setBlockContent(array &$data, \stdClass $block)
    {
        // Check if current block is an LC one
        if ($blockInfo = \XLite\Module\CDev\DrupalConnector\Drupal\Model::getInstance()->getBlock($block->delta)) {

            // Trying to get widget from LC
            if ($widget = $this->getHandler()->getWidget($blockInfo['lc_class'], $blockInfo['options'])) {

                // Check if widget is visible and its content is not empty
                if ($widget->checkVisibility() && ($content = $widget->getContent())) {

                    // Set content recieved from LC
                    $data['content'] = $content;

                    // Register JS and/or CSS
                    $this->registerWidgetResourcse($widget);

                } else {

                    // Block is not visible
                    $data['content'] = '';
                }
            }
        }
    }

    /**
     * Register LC widget resourcse
     *
     * @param \XLite\View\AView $widget LC widget to get resources list
     *
     * @return null
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function registerWidgetResourcse(\XLite\View\AView $widget)
    {
        \XLite\Module\CDev\DrupalConnector\Drupal\Helper::getInstance()->registerResources(
            $this->prepareResources($widget->getRegisteredResources())
        );
    }
}
