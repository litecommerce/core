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

/**
 * UPS container
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_UPSOnlineTools_Model_Container extends XLite_Base
{
    const OPTIMIZE_DIVIDE_VER = 1;
    const OPTIMIZE_DIVIDE_HOR = 2;
    const OPTIMIZE_REVERSE = 4;
    const OPTIMIZE_COMBINE = 8;
    const OPTIMIZE_COMBINE_INTERMEDIATE = 16;

    const OPTIMIZE_VERTICAL = 9; // self::OPTIMIZE_DIVIDE_VER | self::OPTIMIZE_COMBINE;
    const OPTIMIZE_HORIZONTAL = 10; // self::OPTIMIZE_DIVIDE_HOR | self::OPTIMIZE_COMBINE;
    const OPTIMIZE_ALL = 27; // self::OPTIMIZE_VERTICAL | self::OPTIMIZE_HORIZONTAL | self::OPTIMIZE_COMBINE_INTERMEDIATE;
    const OPTIMIZE_ALL_REVERSE = 31; // self::OPTIMIZE_ALL | self::OPTIMIZE_REVERSE;

    const OPTIMIZE_PRESET_1 = self::OPTIMIZE_ALL;
    const OPTIMIZE_PRESET_2 = self::OPTIMIZE_ALL_REVERSE;
    const OPTIMIZE_PRESET_3 = self::OPTIMIZE_HORIZONTAL;
    const OPTIMIZE_PRESET_4 = self::OPTIMIZE_VERTICAL;
    const OPTIMIZE_PRESET_5 = 11; // self::OPTIMIZE_HORIZONTAL | self::OPTIMIZE_VERTICAL;
    const OPTIMIZE_PRESET_6 = 15; // self::OPTIMIZE_HORIZONTAL | self::OPTIMIZE_VERTICAL | self::OPTIMIZE_REVERSE;
 
    public $container_id;
    public $width, $length, $height;
    public $weight_limit;
    public $weight;
    public $levels;

    public $threshold;
    public $optimize_method;

    public $container_type;

    // shipping params    
    public $additional_handling = false;
    public $declared_value = 0;
    public $declared_value_set = false;

    public $extra_item_ids = null;

    public function __construct()
    {
        $this->setThreshold(5);
        $this->getWeightLimit(0);
        $this->setOptimizeMethod(self::OPTIMIZE_ALL);

        require_once LC_MODULES_DIR . 'UPSOnlineTools' . LC_DS . 'encoded.php';
    }


    function progressive_solve(&$items)
    {
        require_once LC_MODULES_DIR . 'UPSOnlineTools' . LC_DS . 'encoded.php';
        return UPSOnlineTools_progressive_solve($this, $items);
    }

    function progressive_placeItem(&$level, &$items, $item_weight_limit)
    {
        require_once LC_MODULES_DIR . 'UPSOnlineTools' . LC_DS . 'encoded.php';
        return UPSOnlineTools_progressive_placeItem($this, $level, $items, $item_weight_limit);
    }

    function setDimensions($_width, $_length, $_height)
    {
        $this->width = round($_width, 2);
        $this->length = round($_length, 2);
        $this->height = round($_height, 2);

        $threshold = (($_width + $_length + $_height) / 60);
        $this->setThreshold(sprintf("%.02f", doubleval($threshold)));
    }

    function setWeightLimit($_weight)
    {
        $this->weight_limit = round($_weight, 2);
    }

    function getDimensions()
    {
        return array($this->width, $this->length, $this->height);
    }

    function getWeightLimit()
    {
        return round($this->weight_limit, 2);
    }

    function getWeight()
    {
        if ($this->getLevelsCount() <= 0)
            return $this->weight;

        $weight = 0;

        foreach ($this->getLevels() as $level) {
            $weight += $level->getWeight();
        }

        return round($weight, 2);
    }

    function setWeight($_weight)
    {
        $this->weight = round($_weight, 2);
    }

    function addLevel($_level)
    {
        if (!is_array($this->levels))
            $this->levels = array();

        $_level->finalize($this->getLevelsCount());
        $this->levels[] = $_level;
    }

    function getLevels()
    {
        if (!is_array($this->levels))
            $this->levels = array();

        return $this->levels;
    }


    function getNextLevel($use_overlaped=true)
    {
        require_once LC_MODULES_DIR . 'UPSOnlineTools' . LC_DS . 'encoded.php';
        return UPSOnlineTools_getNextLevel($this, $use_overlaped);
    }


    function getLevelsCount()
    {
        return count($this->getLevels());
    }

    function setThreshold($val)
    {
        $this->threshold = $val;
    }

    function setOptimizeMethod($val)
    {
        $this->optimize_method = $val;
    }


    function setContainerType($type)
    {
//        $this->container_type = sprintf("%02d", intval($type));
        $this->container_type = intval($type);
    }

    function getContainerType()
    {
        return $this->container_type;
    }

    function setAdditionalHandling($value=true)
    {
        $this->additional_handling = $value;
    }

    function isAdditionalHandling()
    {
        return $this->additional_handling;
    }

    function setDeclaredValue($value)
    {
        $this->declared_value = round($value, 2);
        $this->declared_value_set = true;
    }

    function getDeclaredValue()
    {
        if ($this->declared_value_set || $this->getLevelsCount() <= 0)
            return $this->declared_value;
            
        $summ = 0;
        foreach ((array)$this->getLevels() as $level) {
            foreach ((array)$level->getItems() as $item) {
                $summ += $item->getComplex('orderItem.product.declaredValue');
            }
        }

        return $summ;
    }

    function setExtraItemIds($_item_ids)
    {
        if (is_array($_item_ids) && count($_item_ids) > 0) {
            $this->extra_item_ids = array_unique($_item_ids);
        }
    }

    function addExtraItemIds($item_id)
    {
        if (!is_array($this->extra_item_ids))
            $this->extra_item_ids = array();

        $this->extra_item_ids[] = $item_id;
        $this->extra_item_ids = array_unique($this->extra_item_ids);
    }

    function export()
    {
        $vars = array();
        $vars['container_id'] = $this->container_id;
        $vars['width'] = $this->width;
        $vars['length'] = $this->length;
        $vars['height'] = $this->height;
        $vars['weight'] = $this->getWeight();
        $vars['weight_limit'] = $this->weight_limit;
        $vars['container_type'] = $this->container_type;
        $vars['additional_handling'] = (($this->isAdditionalHandling()) ? 1 : 0);
        $vars['declared_value'] = $this->getDeclaredValue();

        $_levels = array();
        foreach ((array)$this->levels as $level) {
            $_levels[] = $level->export();
        }
        $vars['levels'] = $_levels;

        if (!is_null($this->extra_item_ids)) {
            $vars['extra_item_ids'] = $this->extra_item_ids;
        }

        return $vars;
    }

}
