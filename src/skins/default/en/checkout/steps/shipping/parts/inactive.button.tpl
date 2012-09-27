{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Checkout : shipping step : inactive state : button
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="checkout.shipping.inactive", weight="30")
 *}
<div class="button-row">
  {if:isPrevious}
    <widget class="\XLite\View\Button\Link" label="Change shipping info" location="{buildURL(#checkout#,##,_ARRAY_(#step#^getStepName()))}" style="change" />
  {end:}
</div>
