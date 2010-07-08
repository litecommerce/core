{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Product details sibliding links
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="productDetails.base", weight="10")
 *}
<div IF="previousProduct|nextProduct" class="sibliding-links">
  <a IF="previousProduct" class="previous" href="{buildURL(#product#,##,_ARRAY_(#product_id#^previousProduct.product_id))}" title="{previousProduct.name}">{t(#Previous product#)}</a>
  <span IF="previousProduct&nextProduct">|</span>
  <a IF="nextProduct" class="next" href="{buildURL(#product#,##,_ARRAY_(#product_id#^nextProduct.product_id))}" title="{nextProduct.name}">{t(#Next product#)}</a>
</div>
