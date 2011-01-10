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
 * Model 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Model extends \XLite\Module\CDev\DrupalConnector\Drupal\ADrupal
{
    /**
     * Blocks cache
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  1.0.0
     */
    protected $blocks;


    /**
     * Get LC-related block(s)
     * 
     * @param integer $blockId Block ID OPTIONAL
     *  
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getBlocks($blockId = null)
    {
        if (!isset($this->blocks)) {

            // Fetch all LC blocks ("isNotNull('lc_class')")
            $this->blocks = db_select('block_custom')
                ->fields('block_custom', array('bid', 'lc_class'))
                ->isNotNull('lc_class')
                ->execute()
                ->fetchAllAssoc('bid', \PDO::FETCH_ASSOC | \PDO::FETCH_GROUP);

            // Fetch all settings for LC blocks
            $settings = db_select('block_lc_widget_settings')
                ->fields('block_lc_widget_settings')
                ->execute()
                ->fetchAll(\PDO::FETCH_ASSOC | \PDO::FETCH_GROUP);

            // Merge results
            foreach ($this->blocks as $id => &$block) {

                $block['options'] = array();

                foreach ((array) @$settings[$id] as $data) {
                    $block['options'][$data['name']] = $data['value'];
                }
            }
        }

        return isset($blockId) ? @$this->blocks[$blockId] : $this->blocks;
    }

    /**
     * Alias
     *
     * @param integer $blockId Block ID OPTIONAL
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getBlock($blockId)
    {
        return $this->getBlocks($blockId);
    }
}
