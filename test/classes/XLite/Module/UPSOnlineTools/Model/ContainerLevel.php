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
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_UPSOnlineTools_Model_ContainerLevel extends XLite_Base
{
    public $level_id;
    public $bottom;
    public $height;

    public $subspaces;
    public $items;

    public $used_spaces;
    public $dirt_spaces;

    // Initialize level
    function init($_bottom, $_width, $_length, $_height)
    {
        $subspace = new XLite_Module_UPSOnlineTools_Model_Subspace();
        $subspace->init($_width, $_length);

        $this->subspaces = array();
        $this->subspaces[] = $subspace;

        $this->bottom = $_bottom;
        $this->height = $_height;
    }


    ////////////////////////////////////////////////
    // Subspace work functions
    function getSubspaces()
    {
        if (!is_array($this->subspaces))
            $this->subspaces = array();

        return $this->subspaces;
    }

    function setSubspaces($_subspaces)
    {
        $this->subspaces = array();

        if (!is_array($_subspaces)) {
            return;
        }

        // refine subspaces - remove all 'null'
        foreach ($_subspaces as $subspace) {
            if (!$subspace->isNull())
                $this->subspaces[] = $subspace;
        }
    }



    //////////////////////////////////////////////////
    // Items work functions
    function addItem($_item)
    {
        if (!is_array($this->items))
            $this->items = array();

        $this->items[] = $_item;
    }

    function getItems()
    {
        if (!is_array($this->items))
            $this->items = array();

        return $this->items;
    }

    function getItemsCount()
    {
        return count($this->getItems());
    }


    ////////////////////////////////////////////////
    // Level dimensional functions
    function getHeight()
    {
        return $this->height;
    }

    function getMaxHeight()
    {
        $max = 0;
        foreach ($this->getItems() as $item) {
            $max = max($max, $item->getHeight());
        }

        return $max;
    }

    function getMediumHeight()
    {
        $count = 0;
        $height = 0;

        foreach ($this->getItems() as $item) {
            $count++;
            $height += $item->getHeight();
        }

//		return ceil($height / $count); // << round to 1 inch - not useful
        return round(($height / $count), 1);
    }

    function getBottomHeight()
    {
        return $this->bottom;
    }

    function getWeight()
    {
        $weight = 0;

        foreach ($this->getItems() as $item) {
            $weight += $item->getWeight();
        }

        return round($weight, 2);
    }


    //////////////////////////////////////////////
    // finalize level
    function finalize($_id)
    {
        $this->level_id = $_id;
        $this->height = $this->getMaxHeight();
    }



    /////////////////////////////////////////////
    // dirt spaces work functions
    // dirt - space that used by ContainerItem(s) from lower level(s)
    function getDirtSpaces()
    {
        if (!is_array($this->dirt_spaces)) {
            $this->dirt_spaces = array();
        }

        return $this->dirt_spaces;
    }

    function setDirtSpaces($_spaces)
    {
        $this->dirt_spaces = (array)$_spaces;
    }



    /////////////////////////////////////////////
    // used spaces work functions
    function getUsedSpaces()
    {
        if (!is_array($this->used_spaces)) {
            $this->used_spaces = array();
        }

        return $this->used_spaces;
    }

    function setUsedSpaces($_spaces)
    {
        $this->used_spaces = (array)$_spaces;
    }

    function addUsedSpace($space)
    {
        if (!is_array($this->used_spaces)) {
            $this->used_spaces = array();
        }

        $this->used_spaces[] = $space;
    }

    function optimizeSubspaces($method)
    {
        require_once LC_MODULES_DIR . 'UPSOnlineTools' . LC_DS . 'encoded.php';

        // optimize: try to combine subspaces
        UPSOnlineTools_optimize_subspaces($this->subspaces, $method);
    }


    function export()
    {
        $vars = array();
        $vars["level_id"] = $this->level_id;
        $vars["bottom"] = $this->getBottomHeight();
        $vars["height"] = $this->height;

/*
        foreach((array)$this->subspaces as $subspace) {
            $vars["subspaces"][] = $subspace->export();
        }
//*/

        foreach ((array)$this->items as $item) {
            $vars["items"][] = $item->export();
        }

        // ignore, do not export: used_spaces;

        foreach ((array)$this->dirt_spaces as $dirt_space) {
            $vars["dirt_spaces"][] = $dirt_space->export();
        }

        return $vars;
    }

}
