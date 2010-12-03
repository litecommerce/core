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
 * @subpackage Model
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Module\CDev\UPSOnlineTools\Model;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class ContainerItem extends \XLite\Base
{
    public $item_id;
    public $global_id;

    public $left, $top;
    public $width, $length, $height;
    public $weight;

    function setPosition($_left, $_top)
    {
        $this->left = $_left;
        $this->top = $_top;
    }
    
    function setDimensions($_width, $_length, $_height)
    {
        $this->width = $_width;
        $this->length = $_length;
        $this->height = $_height;
    }

    function setWeight($_weight)
    {
        $this->weight = round($_weight, 2);
    }

    function getWeight()
    {
        return round($this->weight, 2);
    }

    function getHeight()
    {
        return $this->height;
    }

    function getOrderItem()
    {
        $item_id = $this->item_id;
        $oi = new \XLite\Model\OrderItem();
        $oi->find("item_id='".addslashes($item_id)."'");

        return $oi;
    }

    function export()
    {
        $vars = array();
        $vars['left'] = $this->left;
        $vars['top'] = $this->top;
        $vars['width'] = $this->width;
        $vars['length'] = $this->length;
        $vars['height'] = $this->height;
        $vars['weight'] = $this->weight;

        $vars['item_id'] = $this->item_id;
        $vars['global_id'] = $this->global_id;

        return $vars;
    }

}
