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
<a href="admin.php?target=categories&category_id=0">Categories</a><br>
<a href="admin.php?target=product_list">Products</a><br>
<a href="admin.php?target=add_product">Add product</a><br>
<hr>
<a href="admin.php?target=extra_fields">Global extra fields</a><br>
<widget module="WholesaleTrading" template="modules/WholesaleTrading/catalog.tpl">
<a href="admin.php?target=import_catalog">Import catalog</a><br>
<a href="admin.php?target=export_catalog">Export catalog</a><br>
<a href="admin.php?target=update_inventory">Inventory management</a><br>
<a IF="xlite.mm.activeModules.ProductOptions" href="admin.php?target=global_product_options">Global product options<br></a>
