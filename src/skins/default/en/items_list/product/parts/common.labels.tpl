{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Labels list
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="itemsList.product.grid.customer.info", weight="998")
 * @ListChild (list="itemsList.product.list.customer.photo", weight="998")
 * @ListChild (list="itemsList.product.table.customer.columns", weight="25")
 * @ListChild (list="itemsList.product.big_thumbnails.customer.info", weight="998")
 * @ListChild (list="itemsList.product.small_thumbnails.customer.details", weight="25")
 * @ListChild (list="productBlock.info.photo", weight="998")
 *}

<widget class="\XLite\View\Labels" labels="{getLabels(product)}" />
