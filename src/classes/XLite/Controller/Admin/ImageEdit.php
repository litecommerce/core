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
 * @subpackage Controller
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Controller\Admin;

/**
 * Image edit
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class ImageEdit extends AAdmin
{
    protected $locale = null;

    protected $zone = null;

    function getLocale() 
    {
        if (is_null($this->locale)) {
            $this->locale = \XLite::getInstance()->getOptions(array('skin_details', 'locale'));
        }
        return $this->locale;
    }

    function getZone()
    {
        if (is_null($this->zone)) {
            $this->zone = \XLite::getInstance()->getOptions(array('skin_details', 'skin'));
        }
        return $this->zone;
    }

    function getEditor()
    {
        $zone   = $this->get('zone');
        $locale = $this->get('locale');
        if (!isset($this->editor)) {
            $this->editor = new \XLite\Model\ImageEditor\ImageEditor("skins/$zone/$locale/images.ini");
        }
        return $this->editor;
    }
    
    /**
     * Change image
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionChange()
    {
        $image_field_name = 'new_image_' . $this->current_image;
        $editor = $this->get('editor');
        $code = $editor->uploadImage($image_field_name, $this->current_image);
        if ($code !== LC_UPLOAD_OK) {
            $this->set('valid', false);
        }
    }
}
