{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Payment actions unit
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

{*<widget class="\XLite\View\Button\Regular" label="{getUnitName()}" jsCode="submitFormDefault(this.form, '{getUnitName()}');" style="{getCSSClass()}" />*}
<widget class="\XLite\View\Button\Regular" label="{getUnitName()}" jsCode="self.location = '{getActionURL()}'" style="{getCSSClass()}" />

