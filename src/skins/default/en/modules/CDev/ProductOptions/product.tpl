{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Product options
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="product.details.page.info", weight="70")
 * @ListChild (list="product.details.quicklook.info", weight="70")
 *}
<widget class="\XLite\Module\CDev\ProductOptions\View\ProductOptions" product="{getProduct()}" selectedOptions="{getSelectedOptions()}" />
