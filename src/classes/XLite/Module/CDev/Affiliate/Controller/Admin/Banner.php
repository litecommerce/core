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

namespace XLite\Module\CDev\Affiliate\Controller\Admin;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Banner extends \XLite\Controller\Admin\AAdmin
{
    public $params = array('target', 'banner_id', 'mode', 'type');
    
    function initView()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $this->set('properties', $this->getComplex('banner.properties'));
        }
    }

    function action_save_banner()
    {
        $banner = new \XLite\Module\CDev\Affiliate\Model\Banner();
        $banner->set('properties', $_POST);
        $banner->create();
        
        $img = $banner->get('image');
        $img->handleRequest();

        // switch to modify banner mode
        $this->set('banner_id', $banner->get('banner_id'));
        $this->set('mode', "modify");
    }

    function action_update_banner()
    {
        $banner = $this->get('banner');
        $banner->set('properties', $_POST);
        $banner->update();
        $img = $banner->get('image');
        $img->handleRequest();
    }

    function getBanner()
    {
        if (is_null($this->banner)) {
            $this->banner = new \XLite\Module\CDev\Affiliate\Model\Banner($this->get('banner_id'));
        }
        return $this->banner;
    }
}
