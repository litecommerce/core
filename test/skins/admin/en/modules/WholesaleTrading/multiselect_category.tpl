<select class="FixedSelect" name="{fieldName}" multiple size="10" style="width:200pt">
   <option value="" IF="getParam(#allOption#)">All</option>
   <option FOREACH="getCategories(),k,v" value="{v.category_id:r}" selected="{isCategorySelected(v)}">{v.getStringPath():h}</option>
</select>
