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
<select class="FixedSelect" name="{fieldName}" multiple size="10" style="width:200pt">
   <option value="" IF="getParam(#allOption#)">All</option>
   <option FOREACH="getCategories(),k,v" value="{v.category_id:r}" selected="{isCategorySelected(v)}">{v.getStringPath():h}</option>
</select>
