<select name="{field}" size="1" onChange="{onChange}">
   <span IF="inherit">
   <option value="-1" selected="{scheme_id=-1}">Inherit (*)</option>
   </span>
   <option FOREACH="schemes,k,v" value="{v.scheme_id}" selected="{v.scheme_id=scheme_id}">{v.name:h}</option>
</select>
