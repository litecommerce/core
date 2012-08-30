{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Payment methods list
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}

<div class="{getListCSSClasses()}">

  <ul class="list" IF="getPageData()">
    {foreach:getPageData(),method}
      <li class="{getLineClass(method)}">
        <list name="payment.methods.list.line" method="{method}" />
      </li>
    {end:}
  </ul>

</div>
