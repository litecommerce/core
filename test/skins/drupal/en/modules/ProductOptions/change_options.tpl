{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Change options
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<widget class="XLite_Module_ProductOptions_View_Form_Item_ChangeOptions" name="change_options" className="change-options" />

  <h1>Edit options</h1>

  <widget module="ProductOptions" class="XLite_Module_ProductOptions_View_ProductOptions" product="{getProduct()}" />

  <widget class="XLite_View_Button_Submit" label="Update" />

<widget name="change_options" end />
