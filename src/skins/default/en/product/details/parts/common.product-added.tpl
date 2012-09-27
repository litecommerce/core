{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Product details buttons block
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="product.details.page.info.buttons-added", weight="5")
 * @ListChild (list="product.details.quicklook.info.buttons-added", weight="5")
 *}
<p class="product-added-note">
  {t(#This product has been added to your bag#,_ARRAY_(#href#^buildURL(#cart#))):h}
</p>
