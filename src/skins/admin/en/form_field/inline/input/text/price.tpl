{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Price inline view
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

<span class="symbol">{if:currency.getSymbol()}{currency.getSymbol():h}{else:}{currency.getCode()}{end:}</span>
<span class="value">{getViewValue(singleField):h}</span>
