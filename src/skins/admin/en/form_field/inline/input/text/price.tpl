{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Price inline view
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

<span class="symbol" IF="currency.getPrefix()">{currency.getPrefix():h}</span>
<span class="value">{getViewValue(singleField):h}</span>
<span class="symbol" IF="currency.getSuffix()">{currency.getSuffix():h}</span>

