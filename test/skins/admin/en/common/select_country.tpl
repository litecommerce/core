{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Country selection template
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<select {if:!nonFixed}class="FixedSelect"{end:} name="{getParam(#field#)}" size="1" onchange="{onChange}" id="{fieldId}">
  <option value="">Select one..</option>
  <option FOREACH="countries,k,v" value="{v.code:r}" selected="{isSelected(v.code,getParam(#value#))}}">{v.country:h}</option>
</select>
