{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Products list page template
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}

{if:!mode=#confirmation#}

<widget template="common/dialog.tpl" head="Search product" body="product/search.tpl" />

<span class="Text" IF="{mode=#search#}">

  <span IF="{!getProductsFound()}">No products found.</span>

  <span IF="{getProductsFound()}">{getProductsFound()} product<span IF="{!getProductsFound()=#1#}">s</span> found.</span>

</span>

<widget template="common/dialog.tpl" head="Search results" body="product/product_list.tpl" visible="{products&mode=#search#}" />

{else:}

<widget template="common/dialog.tpl" head="Confirmation" body="product/products_delete.tpl" />

{end:}
