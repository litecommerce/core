{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Address book page. Address entries list.
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

<ul class="address-book">

  <widget FOREACH="profile.getAddresses(),address" class="\XLite\View\Address" displayMode="text" displayWrapper="1" address="{address}" />

  <widget class="\XLite\View\Address" displayMode="text" displayWrapper="1" />

</ul>

<div class="clear"></div>
