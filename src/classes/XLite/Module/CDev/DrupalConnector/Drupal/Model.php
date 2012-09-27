<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * LiteCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the GNU General Pubic License (GPL 2.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-2.0.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to licensing@litecommerce.com so we can send you a copy immediately.
 *
 * PHP version 5.3.0
 *
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU General Pubic License (GPL 2.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\Module\CDev\DrupalConnector\Drupal;

/**
 * Model
 *
 */
class Model extends \XLite\Module\CDev\DrupalConnector\Drupal\ADrupal
{
    /**
     * Blocks cache
     *
     * @var array
     */
    protected $blocks;


    /**
     * Get LC-related block(s)
     *
     * @param integer $blockId Block ID OPTIONAL
     *
     * @return array
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
     */
    public function getBlock($blockId)
    {
        return $this->getBlocks($blockId);
    }
}
