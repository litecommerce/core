{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Products search substring options (horizontal)
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="advsearch.horizontal.childs", weight="20")
 *}
<tr>
  <td colspan="2" class="substring-options">

    <input type="radio" name="search[logic]" value="2" id="search_logic_2" checked="{isSelected(search.logic,#2#)}" />
    <label for="search_logic_2">All words</label>

    <input type="radio" name="search[logic]" value="1" id="search_logic_1" checked="{isSelected(search.logic,#1#)}" />
    <label for="search_logic_1">Any word</label>

    <input type="radio" name="search[logic]" value="3" id="search_logic_3" checked="{isSelected(search.logic,#3#)}" />
    <label for="search_logic_3">Exact phrase</label>

  </td>
</tr>

<tr>
  <td colspan="3"><hr /></td>
</tr>

