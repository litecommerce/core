{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Products search targets block (horizontal)
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="advsearch.horizontal.childs", weight="40")
 *}
<tr>
  <td class="substring-options" colspan="2">

    <input type="checkbox" id="search_meta_tags" name="search[meta_tags]" checked="{search.meta_tags}" />
    <label for="search_meta_tags">Meta tags</label>

    <input type="checkbox" id="search_extra_fields" name="search[extra_fields]" checked="{search.extra_fields}" />
    <label for="search_extra_fields">Extra fields</label>

    {if:xlite.ProductOptionsEnabled}
    <input type="checkbox" id="search_options" name="search[options]" checked="{search.options}" />
    <label for="search_options">Product options</label>
    {end:}
  </td>
</tr>
