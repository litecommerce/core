<select class="FixedSelect" name="{formField}" multiple size="10" style="width:200pt">
   <option value="" IF="allOption">All</option>
   <option FOREACH="categories,k,v" value="{v.category_id:r}" selected="{isCategorySelected(v.category_id)}">{v.getStringPath():h}</option>
</select>
