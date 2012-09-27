{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Product classes list page
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

{* Refactor to ItemsList*}

<div class="product-classes-list-form">

<form method="POST" action="admin.php" id="product-class-form" >

<input type="hidden" name="target" value="product_classes" />
<input type="hidden" name="action" value="update" />

<table class="product-classes-list">

<widget FOREACH="getData(),idx,class" class="\XLite\View\ProductClass\MainInput" classId="{class.getId()}" className="{class.getName()}" />

<tr>
<list name="productClasses.list.columns.new" />
</tr>

</table>

<noscript>
<button type="submit">{t(#Update#)}</button>
</noscript>

</form>
</div>
