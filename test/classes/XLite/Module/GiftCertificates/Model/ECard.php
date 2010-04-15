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
class XLite_Module_GiftCertificates_Model_ECard extends XLite_Model_Abstract
{	
    public $alias = "ecards";	
    public $fields = array(
        'ecard_id' => '',
        'template' => '', // use this template as e-mail body
        'order_by' => 0,
        'enabled' => 1
	);	
    public $autoIncrement = 'ecard_id';	
    public $defaultOrder = 'order_by';	
    public $thumbnail = null;	
    public $image = null;

    function getThumbnail()
    {
        if (is_null($this->thumbnail)) {
            $this->thumbnail = new XLite_Model_Image("ecard_thumbnail", $this->get("ecard_id"));
        }

        return $this->thumbnail;
    }

    function getImage()
    {
        if (is_null($this->image)) {
            $this->image = new XLite_Model_Image('ecard_image', $this->get("ecard_id"));
        }

        return $this->image;
    }

    function getAllTemplates()
    {
        $templates = array();
        $layout = XLite_Model_Layout::getInstance();

        // "skins/mail/" . $layout->get("locale") .
        // $layout->set("skin", "mail");
        // $path = $layout->getPath() . DIRECTORY_SEPARATOR . "modules" . DIRECTORY_SEPARATOR . "GiftCertificates" . DIRECTORY_SEPARATOR . "ecards";

        $path = LC_ROOT_DIR . 'skins/mail/' . $layout->get('locale') . '/modules/GiftCertificates/ecards';

		$dh = opendir($path);
        if ($dh) { 
            while (($file = readdir($dh)) !== false) { 
                if (
					is_file($path . DIRECTORY_SEPARATOR . $file)
					&& substr($file, -4) == ".tpl"
				) {
                    $templates[] = substr($file, 0, strlen($file) - 4);
                } 
            } 
            closedir($dh); 

        } else {
            $this->doDie("Cannot read directory $path");
        }

        return $templates;
    }

    function getAllBorders()
    {
        $borders = array();
        $layout = XLite_Model_Layout::getInstance();

        // $layout->set("skin","mail");
        // $path = $layout->getPath() . DIRECTORY_SEPARATOR . "modules" . DIRECTORY_SEPARATOR . "GiftCertificates" . DIRECTORY_SEPARATOR . "ecards" . DIRECTORY_SEPARATOR . "borders";

        $path = LC_ROOT_DIR . 'skins/mail/' . $layout->get('locale') . '/modules/GiftCertificates/ecards/borders';

		$dh = opendir($path);
        if ($dh) {
            while (($file = readdir($dh)) !== false) {
                if (
					is_file($path . DIRECTORY_SEPARATOR . $file)
					&& substr($file, -4) == ".gif"
					&& substr($file, -11) != "_bottom.gif"
				) {
                    $borders[] = substr($file, 0, strlen($file) - 4);
                }
            }
            closedir($dh);

        } else {
            $this->doDie("Cannot read directory $path");
        }

        return $borders;
    }

    function delete()
    {
        $thumbnail = $this->getThumbnail();
        $thumbnail->delete();

        $image = $this->getImage();
        $image->delete();

        parent::delete();
    }

    /**
    * The border image must be chosen for this e-Card
    */
    function isNeedBorder()
    {
        $layout = XLite_Model_Layout::getInstance();
        $template = LC_ROOT_DIR . 'skins/mail/' . $layout->get('locale') . '/modules/GiftCertificates/ecards/' . $this->get('template') . '.tpl';

        // does the e-Card template use the border?
        return preg_match('/gc\.border/', file_get_contents($template));
    }

}
