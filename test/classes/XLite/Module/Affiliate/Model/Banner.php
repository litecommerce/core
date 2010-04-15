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
class XLite_Module_Affiliate_Model_Banner extends XLite_Model_Abstract
{	
    public $fields = array(
            "banner_id"    => 0,
            "name"         => "",
            "banner_source"=> "D",
            "banner_type"  => "image/jpeg", // content (image) type
            "type"         => "image",      // banner_type ::= image | text | rich
            "body"         => "",
            "align"        => "bottom",
            "alt"          => "",
            "link_target"  => "_blank", 
            "enabled"      => 1,
            "width"        => 0,
            "height"       => 0,
            "banner_width" => 0,
            "banner_height"=> 0,
            );	

    public $autoIncrement = "banner_id";	
    public $alias = "banners";	
    public $defaultOrder = "banner_id";	
    public $image = null;    

    function getImage()
    {
        if (is_null($this->image)) {
            $this->image = new XLite_Model_Image("banner_image", $this->get("banner_id"));
        }
        return $this->image;
    }

	function getDefaultSource()
	{
		return 'D';
	}

    function filter()
    {
        if (!$this->xlite->is("adminZone")) {
            return (boolean) $this->get("enabled");
        }
        return parent::filter();
    }
}
