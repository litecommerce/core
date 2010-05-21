{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<span class="Text">
{if:product.enabled}
Product <b>"{product.name}"</b> (#{product.product_id}) has been added to the catalog and is available for sale now.<p> 
{else:}
Product <b>"{product.name}"</b> (#{product.product_id}) has been added to the store database but is not visible in the catalog and not available for sale until you set 'Available for sale' option to 'Yes' in the product details page.<p>
{end:}

<a href="admin.php?target=product&product_id={product.product_id}"><img src="skins/admin/en/images/go.gif" width="13" height="13" border="0" align="absmiddle"> Modify product "{product.name}" (#{product.product_id}) details </a><p>

<a href="admin.php?target=add_product"><img src="skins/admin/en/images/go.gif" width="13" height="13" border="0" align="absmiddle"> Add another product </a>
</span>
