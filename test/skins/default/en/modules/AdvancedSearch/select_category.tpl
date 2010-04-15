{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<select style="width : 335px;" id="search_category" name="{formField}" size="1">
   <option value="" IF="allOption">All</option>
   <option value="" IF="noneOption">None</option>
   <option FOREACH="categories,k,v" value="{v.category_id:r}" selected="{v.category_id=search.category}">{v.stringPath:h}</option>
   <span IF="!allOption"><option value="" IF="isEmpty(categories)">-- No categories --</option></span>
</select>
