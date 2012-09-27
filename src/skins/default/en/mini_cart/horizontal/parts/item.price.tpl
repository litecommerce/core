{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Display horizontal minicart item price
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="minicart.horizontal.item", weight="20")
 *}
<div class="item-price"><widget class="XLite\View\Surcharge" surcharge="{item.getNetPrice()}" currency="{cart.getCurrency()}" /> &times; {item.getAmount()}</div>
