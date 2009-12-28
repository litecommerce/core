<select name="{field}" size="1" onChange="{onChange}">
   <span IF="widget.inherit">
   <option value="-1" selected="{widget.scheme_id=-1}">Inherit (*)</option>
   </span>
   <option FOREACH="widget.schemes,k,v" value="{v.scheme_id}" selected="{v.scheme_id=widget.scheme_id}">{v.name:h}</option>
</select>
