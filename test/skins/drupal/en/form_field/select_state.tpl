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

<select id="{getFieldId()}" name="{getName()}"{getAttributesCode():h} onchange="{onChange}" id="{fieldId}" class="field-state">
  <option value="0">Select one..</option>
  <option value="-1" selected="{getValue()=-1}">Other</option>
  <option FOREACH="getOptions(),state" value="{state.state_id:r}" selected="{state.state_id=getValue()}">{state.state:h}</option>
</select>
