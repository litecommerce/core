{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Currency selector
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.2
 *}
<select name="{field}" class="{className}" onchange="{onchange}" id="{fieldId}">
   <option FOREACH="getCurrencies(),v" value="{v.getCurrencyId():r}" selected="{v.getCurrencyId()=currency}">{v.getName():h}</option>
</select>
