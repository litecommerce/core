{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Currency selector
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}

<widget class="\XLite\View\Form\Order\CurrencySelector" name="selector" />

  <select name="currency" onchange="javascript: jQuery(this.form).submit();">
    <option FOREACH="getCurrencies(),c" value="{c.getCurrencyId():h}" selected="{isCurrencySelected(c)}">{c.getCode()}{if:c.getSymbol()} ({c.getSymbol():h}){end:}</option>
  </select>

<widget name="selector" end />
