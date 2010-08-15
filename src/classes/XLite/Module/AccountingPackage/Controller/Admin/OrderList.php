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

namespace XLite\Module\AccountingPackage\Controller\Admin;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class OrderList extends \XLite\Controller\Admin\OrderList implements \XLite\Base\IDecorator
{
    public $delimiter  = "\t";
    public $crlf       = "\r\n";
    public $dateParams = array(
            'startDateMonth',
            'startDateDay',
            'startDateYear',
            'endDateMonth',
            'endDateDay',
            'endDateYear'
            );

    public function __construct(array $params) 
    {
        parent::__construct($params);
        $this->params = array_merge($this->params, $this->dateParams);
    }

    function initView() 
    {
        parent::initView();
        if ($this->get('mode') == "export_myob" || $this->get('mode') == "export_pt") {
            $this->setComplex('searchOrdersForm.visible', false);
        }
    }
    
    function updateConfig() 
    {
        foreach (func_get_args() as $name) {
            if (isset($this->$name)) {
                $value = $this->$name;
                \XLite\Core\Database::getRepo('\XLite\Model\Config')->createOption(
                    array(
                        'category' => 'ImportExport',
                        'name'     => $name,
                        'value'    => $value
                    )
                );
            }
        }
    }
    
    function isQuickSearch()
    {
    	if ($this->action == "export_qb" || $this->action == "export_myob" || $this->action == "export_pt") {
    		return false;
    	}

    	return parent::isQuickSearch();
    }

    function action_export_qb() 
    {
        require_once LC_MODULES_DIR . 'AccountingPackage' . LC_DS . 'encoded.php';
        AccountingPackage_export_qb($this);
    }
    
    function action_export_myob() 
    {
        if (is_null($this->get('export_result'))) {
            // redirect to export dialog
            $this->set('mode', "export_myob");
        } else {
            // export data
            $this->updateConfig('income_account', "deposit_account");
            $this->export('myob');
        }
    }

    function action_export_pt() 
    {
        if (is_null($this->get('export_result'))) {
            // redirect to export dialog
            $this->set('mode', "export_pt");
        } else {
            // export data
            $this->updateConfig('receivable_account', "sales_account", "cash_account");
            $this->export('pt');
        }
    }
    
    function addDistribution($order, $itemType = "item") 
    {
        $orderID = $order->get('order_id');
        if (isset($this->distributions[$orderID])) {
            $this->distributions[$orderID]++;
        } else {
            $this->distributions[$orderID] = 1;
        }
    }

    function getTotalDistribution($order) 
    {
        $orderID = $order->get('order_id');
        return isset($this->distributions[$orderID]) ? $this->distributions[$orderID] : 0;
    }
    
    function getDateDue($date, $format = null) 
    {
        if (is_null($format)) {
            $format = $this->config->General->date_format;
        }
        return strftime($format, $date);
    }

    function getCurrentDistribution($order) 
    {
        static $lines;

        if (!isset($lines)) $lines = array();

        $orderID = $order->get('order_id');
        if (isset($lines[$orderID])) {
            $lines[$orderID]++;
        } else {
            $lines[$orderID] = 1;
        }
        return $lines[$orderID];
    }
    
    function export($format) 
    {
        $price_format = $this->config->General->price_format;
        $this->config->General->price_format = '%s';
        require_once LC_MODULES_DIR . 'AccountingPackage' . LC_DS . 'encoded.php';
        AccountingPackage_export($this, $format);
        $this->config->General->price_format = $price_format;
    }

    function found($order, $name) 
    {
        return !is_null($order->get($name)) && $order->get($name) > 0;
    }

    function getExportFormats() 
    {
        $formats = parent::getExportFormats();
        $formats['export_qb'] = "QuickBooks 2003";
        $formats['export_myob'] = "MYOB Accounting 2005 (v14)";
        $formats['export_pt'] = "Peachtree Complete Accounting 2004";
        return $formats;
    }

    function CSVQuoting($string)
    {
    	$string = str_replace("\"", "\"\"", $string);
    	return $string;
    }
}
