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

/**
 * Detailed images controller
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_DetailedImages_Controller_Admin_Product extends XLite_Controller_Admin_Product
implements XLite_Base_IDecorator
{
    /**
     * Constructor
     * 
     * @param array $params ____param_comment____
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __construct(array $params)
    {
        parent::__construct($params);

        $this->pages['detailed_images'] = 'Detailed images';
        $this->pageTemplates['detailed_images'] = 'modules/DetailedImages/detailed_images.tpl';
    }
    
    /**
     * Add detailed image
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionAddDetailedImage()
    {
        $dImg = new XLite_Module_DetailedImages_Model_DetailedImage();

        $data = XLite_Core_Request::getInstance()->getData();
        $data['is_zoom'] = isset($data['is_zoom']) ? 'Y' : '';

        $dImg->set('properties', $data); 
        $dImg->create();

        $dImg->getImage()->handleRequest();
    }

    /**
     * Delete detailed image
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionDeleteDetailedImage()
    {
        $dImg = new XLite_Module_DetailedImages_Model_DetailedImage($this->image_id);
        $dImg->delete();
    }

    /**
     * Update detailed image
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionUpdateDetailedImages()
    {
        foreach ($this->alt as $imageId => $alt) {
            $img = new XLite_Module_DetailedImages_Model_DetailedImage($imageId);

            $img->set('alt', $alt);
            $img->set('order_by', $this->order_by[$imageId]);
            $img->set('is_zoom', (isset($this->is_zoom) && isset($this->is_zoom[$imageId])) ? 'Y' : '');

            $img->update();
        }    
    }
}
