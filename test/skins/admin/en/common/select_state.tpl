{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * State selection template
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}

<select {if:!nonFixed}class="FixedSelect"{end:} name="{getParam(#field#)}" size="1" onChange="{onChange}" id="{getParam(#fieldId#)}">
   <option value="0">Select one..</option>
   <option value="-1" selected="{isSelected(getParam(#value#),#-1#)}">Other</option>
   <option FOREACH="states,k,v" value="{v.state_id:r}" selected="{isSelected(v.state_id,getParam(#value#))}">{v.state:h}</option>
</select>
