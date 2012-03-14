{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Search phrase : any
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.19
 *
 * @listChild (list="products.search.conditions.phrase", weight="200")
 *}

<li>
  <input type="radio" name="including" id="including-any" value="any" checked="{getChecked(#including#,#any#)}" />
  <label for="including-any">{t(#Any word#)}</label>
</li>
