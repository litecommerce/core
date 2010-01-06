<select {if:!nonFixed}class="FixedSelect"{end:} name="{field}" size="1" onChange="{onChange}" id="{fieldId}">
   <option value="">Select one..</option>
   <option FOREACH="countries,k,v" value="{v.code:r}" selected="{v.code=value}">{v.country:h}</option>
</select>
