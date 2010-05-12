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
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_Egoods_Controller_Admin_DownloadStatistics extends XLite_Controller_Admin_Stats
{
    function getStat()
    {
        if (!isset($this->stats)) {
            $ds = new XLite_Module_Egoods_Model_DownloadsStatistics();
            $this->stats = $ds->findAll();
        }
        return $this->stats;
    }

    function getProductName($file_id, $trim=25)
    {
        $df = new XLite_Module_Egoods_Model_DownloadableFile($file_id);
        $product = new XLite_Model_Product($df->get('product_id'));
        $name = $product->get('name');
        if (strlen($name) <= $trim) {
            return $name;
        } else {
            return substr($name, 0, $trim) . "...";
        }
    }

    function getProductHref($file_id)
    {
        $df = new XLite_Module_Egoods_Model_DownloadableFile($file_id);
        $product = new XLite_Model_Product($df->get('product_id'));
        return "admin.php?target=product&product_id=" . $product->get('product_id') . "&page=downloadable_files";
    }

    function getPageTemplate()
    {
        return "modules/Egoods/download_statisics.tpl";
    }
}
