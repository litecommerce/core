{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Products list
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<div class="products-list {getSessionCell()}">

  <widget name="{getPagerName()}" template="products_list/pager.tpl" />

  <div IF="isDisplayModeAdjustable()&isSortBySelectorVisible()" class="list-head">

    {displayViewListContent(#productsList.head#)}

  </div>

  <widget template="{getPageBodyTemplate()}" />

  <widget name="{getPagerName()}" onlyPages />

  {displayViewListContent(#productsList.base#)}

</div>

<script type="text/javascript">
new ProductsList('{getSessionCell()}', {getURLParamsJS():h}, {getURLAJAXParamsJS():h});
</script>
