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

namespace XLite\Module\ProductOptions\Controller\Admin;

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
        $this->pages['export_product_options'] = "Export product options";
        $this->pageTemplates['export_product_options'] = "modules/ProductOptions/export_product_options.tpl";
    }

    function action_export_product_options()
    {
        global $DATA_DELIMITERS;

        // save layout & export
        $dlg = new \XLite\Controller\Admin\ImportCatalog();
        $dlg->action_layout('product_options_layout');
        $this->startDownload('product_options.csv');
        $po = new \XLite\Module\ProductOptions\Model\ProductOption();
        $po->export($this->product_options_layout, $DATA_DELIMITERS[$this->delimiter], $where = null, $orderby = "product_id");
        exit();
    }
}
