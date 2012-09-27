{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Product details title main block
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="product.details.quicklook.info", weight="12")
 *}

<a href="{buildURL(#product#,##,_ARRAY_(#product_id#^product.product_id,#category_id#^getCategoryId()))}" class="product-more-link">{t(#More details#)}</a>
