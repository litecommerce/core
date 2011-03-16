{**
 * Product classes list page
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}

<div class="product-classes-list-form">

<form method="POST" action="admin.php" id="product-class-form" >

<input type="hidden" name="target" value="product_classes" />
<input type="hidden" name="action" value="update" />

<table class="product-classes-list">

<tr FOREACH="getData(),idx,class">
{displayViewListContent(#productClasses.list.columns#,_ARRAY_(#class#^class))}
</tr>

<tr>
{displayViewListContent(#productClasses.list.columns.new#)}
</tr>

</table>


<button type="submit">Update</button>

</form>

</div>
