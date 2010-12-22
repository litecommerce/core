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
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class ExportCatalog extends \XLite\Controller\Admin\AAdmin
{
    public $params = array('target', 'page');
    public $pages = array('products' => 'Export products',
                       );
    public $pageTemplates = array('products' => 'product/export.tpl',
                               );
    public $page = "products";

    function handleRequest()
    {
        $name = "";
        if 
        (
            ( 
                ($this->action == "export_products" || $this->action == "layout")
                && 
                !\Includes\Utils\ArrayManager::isArrayUnique($this->product_layout, $name, array("NULL"))
            ) 
        ) {
            $this->set('valid', false);
            $this->set('invalid_field_order', true);
            $this->set('invalid_field_name', $name);
        }
        
        parent::handleRequest();
    }

    function action_export_products()
    {
        $this->set('silent', true);

        global $DATA_DELIMITERS;

        $this->startDownload('products.csv');
        $product = new \XLite\Model\Product();
        $product->export($this->product_layout, $DATA_DELIMITERS[$this->delimiter]);
        exit();
    }

    function action_layout()
    {
        $dlg = new \XLite\Controller\Admin\ImportCatalog();
        $dlg->action_layout();
    }

    function action_fields_layout()
    {
        $layout_name = "fields_layout";
        $layout = implode(',', \XLite\Core\Request::getInstance()->$layout_name);
        \XLite\Core\Database::getRepo('\XLite\Model\Config')->createOption(
            array(
                'category' => 'ImportExport',
                'name'     => $layout_name,
                'value'    => $layout
            )
        );
    }

    /**
    * @param integer    $i          Field number
    * @param string $value      Current value
    * @param bolean $default    Default state
    */
    function isOrderFieldSelected($id, $value, $default)
    {
        if (($this->action == "export_products" || $this->action == "layout") && $id < count($this->product_layout)) {
            return ($this->product_layout[$id] === $value);
        }

        return $default;
    }
}
