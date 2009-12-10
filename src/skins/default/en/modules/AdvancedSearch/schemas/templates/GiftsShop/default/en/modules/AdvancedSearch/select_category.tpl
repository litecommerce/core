<select style="width : 335px; " id="search_category" name="{formField}" size="1">
   <option value="" IF="allOption">All</option>
   <option value="" IF="noneOption">None</option>
   <option FOREACH="categories,k,v" value="{v.category_id:r}" selected="{v.category_id=search.category}">{v.stringPath:h}</option>
   <span IF="!allOption"><option value="" IF="isEmpty(categories)">-- No categories --</option></span>
</select>

