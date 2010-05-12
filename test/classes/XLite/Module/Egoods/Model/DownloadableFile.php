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
class XLite_Module_Egoods_Model_DownloadableFile extends XLite_Model_Abstract
{
    public $alias = "downloadable_files";
    public $autoIncrement = "file_id";

    public $primaryKey = array("file_id");
    public $defaultOrder = "file_id";

    /**
     * @var array $fields downloadable files properties.
     * @access private
     */	
    public $fields = array(
            "file_id"				=> 0,
            "product_id"			=> 0,
            "store_type"			=> 'F',
            "delivery"				=> 'L', //L - link, M - mail
            "data"					=> '',
            );
            
    function getLinks()
    {
        $links = new XLite_Module_Egoods_Model_DownloadableLink();
        return $links->findAll('file_id=' . $this->get('file_id'));
    }

    function getManualLinks()
    {
        $links = new XLite_Module_Egoods_Model_DownloadableLink();
        if (!isset($this->_manual_links)) {
            $this->_manual_links = $links->findAll('file_id=' . $this->get('file_id') . " and link_type='M'");
        }
        return $this->_manual_links;
    }
    
    function hasManualLinks()
    {
        $ml = $this->get('manualLinks');
        if (count($ml) > 0) {
            return true;
        }
        return false;
    }
    
    function getActiveLinks()
    {
        $l = new XLite_Module_Egoods_Model_DownloadableLink();
        $links = $l->findAll('file_id=' . $this->get('file_id'));
        $active_links = array();
        for ($i = 0; $i < count($links); $i ++) {
            if ($links[$i]->is('Active')) {
                $active_links [] = $links[$i];
            }
        }
        return $active_links;
    }

    function getFileName()
    {
        return basename($this->get('data'));
    }
}
