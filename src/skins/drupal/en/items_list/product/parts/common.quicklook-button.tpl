{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Overlapping box
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * 
 * @ListChild (list="itemsList.product.grid.customer.info.photo", weight="999")
 * @ListChild (list="itemsList.product.list.customer.photo", weight="999")
 *}
<div class="quicklook"><a href="{buildURL(#product#,##,_ARRAY_(#product_id#^product.product_id,#category_id#^category_id))}" class="quicklook-link quicklook-link-{product.product_id}"><img src="images/spacer.gif" alt="{t(#Quick look#)}" /></a></div>
