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
class XLite_Module_UPSOnlineTools_Model_Subspace extends XLite_Base
{
    public $width, $length;
    public $left, $top;
    public $upper_limit;

    function init($_width, $_length, $_left=0, $_top=0)
    {
        $this->width = $_width;
        $this->length = $_length;
        $this->left = $_left;
        $this->top = $_top;
    }

    function getSquare()
    {
        return $this->width * $this->length;
    }

    function getEpsilon()
    {
        return ($this->width > $this->length) ? $this->length / $this->width : $this->width / $this->length;
    }

    function isNull()
    {
        return ($this->width == 0 || $this->length == 0) ? true : false;
    }

    function isPlaceable($_width, $_length)
    {
        if ($_width <= $this->width && $_length <= $this->length)
            return true;

        return false;
    }

    function placeBox($_width, $_length)
    {
        require_once LC_MODULES_DIR . 'UPSOnlineTools' . LC_DS . 'encoded.php';
        return UPSOnlineTools_placeBox($this, $_width, $_length);
    }

    function getUpperLimit()
    {
        return $this->upper_limit;
    }

    function setUpperLimit($_lim)
    {
        $this->upper_limit = $_lim;
    }

    function export()
    {
        $vars = array();
        $vars["left"] = $this->left;
        $vars["top"] = $this->top;
        $vars["width"] = $this->width;
        $vars["length"] = $this->length;

        return $vars;
    }

}
