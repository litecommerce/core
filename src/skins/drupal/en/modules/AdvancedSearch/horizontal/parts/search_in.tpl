{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Products search 'Search in' block (horizontal)
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="advsearch.horizontal.childs", weight="30")
 *}
<tr>
  <td class="row-title" rowspan="2">Search in:</td>
  <td class="substring-options" colspan="2">

    <input type="checkbox" id="search_title" name="search[title]" checked="{search.title}" />
    <label for="search_title">Title</label>

    <input type="checkbox" id="search_brief_description" name="search[brief_description]" checked="{search.brief_description}" />
    <label for="search_brief_description">Description</label>

  </td>
</tr>
