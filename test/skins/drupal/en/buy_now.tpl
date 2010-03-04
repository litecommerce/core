{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Buy now button
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<widget IF="config.General.buynow_button_enabled" class="XLite_View_Button_Link" label="Buy Now" location="{buildURL(#product#,#buynow#,_ARRAY_(#product_id#^product.product_id,#category_id#^product.category.category_id))}" />

