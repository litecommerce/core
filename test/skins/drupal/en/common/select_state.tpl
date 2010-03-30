{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Select state
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<select name="{field}" onchange="{onChange}" id="{fieldId}" class="field-state">
   <option value="0">Select one..</option>
   <option value="-1" selected="{value=-1}">Other</option>
   <option FOREACH="states,k,v" value="{v.state_id:r}" selected="{v.state_id=value}">{v.state:h}</option>
</select>
