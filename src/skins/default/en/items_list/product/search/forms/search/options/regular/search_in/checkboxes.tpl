{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * "Search in" option variants
 *  
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.15
 *
 * @ListChild (list="itemsList.product.search.form.options.parts.regular.searchIn", weight="200")
 *}

<td>
  <ul>
    <li FOREACH="getSearchInOptions(),key,label">
      <input type="checkbox" name="{key}" id="search-{key}" value="{#1#}" checked="{getParam(key)}" />
      <label for="search-{key}">{t(label)}</label>
    </li>
  </ul>
</td>
