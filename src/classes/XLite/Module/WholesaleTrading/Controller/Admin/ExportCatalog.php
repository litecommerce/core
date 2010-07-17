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

namespace XLite\Module\WholesaleTrading\Controller\Admin;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class ExportCatalog extends \XLite\Controller\Admin\ExportCatalog implements \XLite\Base\IDecorator
{
    public function __construct(array $params)
    {
        parent::__construct($params);
        $this->pages['export_wholesale_pricing'] = "Export wholesale pricing";
        $this->pageTemplates['export_wholesale_pricing'] = "modules/WholesaleTrading/export_wholesale_pricing.tpl";
        $this->pages['export_product_access'] = "Export product access";
        $this->pageTemplates['export_product_access'] = "modules/WholesaleTrading/export_product_access.tpl";
        $this->pages['export_purchase_limit'] = "Export purchase limit";
        $this->pageTemplates['export_purchase_limit'] = "modules/WholesaleTrading/export_purchase_limit.tpl";
    }

    function init()
    {
    	parent::init();

    	switch ($this->get('page')) {
    		case "export_wholesale_pricing":
    			$wp = new \XLite\Module\WholesaleTrading\Model\WholesalePricing();
                $wp->collectGarbage();
    		break;
    		case "export_product_access":
    			$pa = new \XLite\Module\WholesaleTrading\Model\ProductAccess();
                $pa->collectGarbage();
    		break;
            case "export_purchase_limit":
                $pl = new \XLite\Module\WholesaleTrading\Model\PurchaseLimit();
                $pl->collectGarbage();
            break;
    	}
    }

    function handleRequest()
    {
        $name = '';
        $layout = '';

        switch ($this->action) {

            case 'export_wholesale_pricing':
                $layout = 'wholesale_pricing_layout';
                break;

            case 'export_product_access':
                $layout = 'product_access_layout';
                break;

            case 'export_purchase_limit':
                $layout = 'purchase_limit_layout';
                break;

            default:
                // ...
        }

        if (!\Includes\Utils\ArrayManager::isArrayUnique($this->$layout, $name, array('NULL'))) {
            $this->set('valid', false);
            $this->set('invalid_field_order', true);
            $this->set('invalid_field_name', $name);
        }

        parent::handleRequest();
    }

    function action_export_wholesale_pricing()
    {
        global $DATA_DELIMITERS;

        // save layout & export
        $dlg = new \XLite\Controller\Admin\ImportCatalog();
        $dlg->action_layout('wholesale_pricing_layout');
        $this->startDownload('wholesale_pricing.csv');
        $wp = new \XLite\Module\WholesaleTrading\Model\WholesalePricing();
        $wp->export($this->wholesale_pricing_layout, $DATA_DELIMITERS[$this->delimiter], null, "product_id");
        exit();
    }
    
    function action_export_product_access()
    {
        global $DATA_DELIMITERS;

        // save layout & export
        $dlg = new \XLite\Controller\Admin\ImportCatalog();
        $dlg->action_layout('product_access_layout');
        $this->startDownload('product_access.csv');
        
        $pa = new \XLite\Module\WholesaleTrading\Model\ProductAccess();
        $pa->export($this->product_access_layout, $DATA_DELIMITERS[$this->delimiter], null, 'product_id');
        exit();
    }
    
    function action_export_purchase_limit() {
        global $DATA_DELIMITERS;

        // save layout & export
        $dlg = new \XLite\Controller\Admin\ImportCatalog();
        $dlg->action_layout('purchase_limit_layout');
        $this->startDownload('purchase_limit.csv');
        $pl = new \XLite\Module\WholesaleTrading\Model\PurchaseLimit();
        $pl->export($this->purchase_limit_layout, $DATA_DELIMITERS[$this->delimiter], null, "product_id");
        exit();
    }

    /**
    * @param int    $i          field number
    * @param string $value      current value
    * @param bolean $default    default state
    */
    function isOrderFieldSelected($id, $value, $default)
    {
        if ($this->action == 'export_wholesale_pricing' && $id < count($this->wholesale_pricing_layout)) {
            return ($this->wholesale_pricing_layout[$id] === $value);
        } elseif ($this->action == 'export_product_access' && $id < count($this->product_access_layout)) {
            return ($this->product_access_layout[$id] === $value);
        } elseif ($this->action == 'export_purchase_limit' && $id < count($this->purchase_limit_layout)) {
            return ($this->purchase_limit_layout[$id] === $value);
        } else {
            return $default;
        }
    }
}
