{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Search options checkboxes
 *  
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.15
 *
 * @ListChild (list="itemsList.product.search.form.main.parts.including", weight="100")
 *}

<td>
  <ul>
    <li FOREACH="getIncludingOptions(),key,label">
      <input type="radio" name="including" id="including-{key}" value="{key}" checked="{key=getParam(#including#)}" />
      <label for="including-{key}">{t(label)}</label>
    </li>
  </ul>
</td>
