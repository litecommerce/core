{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Product details Weight main block
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *
 * @ListChild (list="product.details.common.product-attributes", weight="100")
 *}
<li IF="!product.weight=0">
  <strong>{t(#Weight#)}:</strong>
  <span>{product.weight} {config.General.weight_symbol}</span>
</li>
