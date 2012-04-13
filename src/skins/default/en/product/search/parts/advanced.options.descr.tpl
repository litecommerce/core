{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Search in description
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.19
 *
 * @listChild (list="products.search.conditions.advanced.options", weight="200")
 *}

<li><label for="by-descr">
  <input type="checkbox" name="by_descr" id="by-descr" value="Y" checked="{getChecked(#by_descr#)}" />
  {t(#Description#)}
</label></li>
