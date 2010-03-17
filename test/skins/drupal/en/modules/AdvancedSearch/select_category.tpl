{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Category selector
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<select name="{getParam(#fieldName#)}">
  <option value="" IF="getParam(#allOption#)">All</option>
  <option value="" IF="getParam(#noneOption#)">None</option>
  <option FOREACH="getCategories(),v" value="{v.category_id:r}" selected="{isCategorySelected(v)}">{v.stringPath}</option>
  {if:isDisplayNoCategories()}<option value="">-- No categories --</option>{end:}
</select>
