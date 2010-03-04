<select class="FixedSelect" name="{field}" size="1" onChange="{onChange}" id="{fieldId}">
   <option value="0">Select one..</option>
   <option value="-1" selected="{value=-1}">Other</option>
   <option FOREACH="states,k,v" value="{v.state_id:r}" selected="{v.state_id=value}">{v.state:h}</option>
</select>
