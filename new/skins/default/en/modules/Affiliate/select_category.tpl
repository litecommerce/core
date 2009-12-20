<select class="FixedSelect" name="{formField}" size="1" style="width:200pt">
   <option value="" IF="allOption">All</option>
   <option value="" IF="noneOption">None</option>
   <option FOREACH="categories,k,v" value="{v.category_id:r}" selected="{v.category_id=selectedCategory}">{v.stringPath:h}</option>
</select>
