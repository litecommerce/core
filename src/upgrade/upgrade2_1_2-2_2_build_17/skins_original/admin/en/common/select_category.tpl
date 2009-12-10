<select {if:!nonFixed}class="FixedSelect"{end:} name="{formField}" size="1" style="width:200pt">
   <option value="" IF="allOption">All</option>
   <option value="" IF="noneOption">None</option>
   <option value="" IF="rootOption" class="CenterBorder">[Root Level]</option>
	{foreach:categories,k,v}
	{if:!v.category_id=currentCategory}
		<option value="{v.category_id:r}" selected="{v.category_id=selectedCategory}">{v.stringPath:h}</option>{end:}
	{end:}
   <span IF="!allOption"><option value="" IF="isEmpty(categories)">-- No categories --</option></span>
</select>
