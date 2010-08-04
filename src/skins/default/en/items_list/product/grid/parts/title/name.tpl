{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Item name
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="itemsList.product.customer.grid.title", weight="40")
 *}
<a href="{buildURL(#product#,##,_ARRAY_(#product_id#^product.product_id,#category_id#^category_id))}" class="url fn product-name">{product.name:h}</a>
