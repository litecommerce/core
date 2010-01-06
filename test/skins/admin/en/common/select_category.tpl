<select name="{formField}" size="1" {if:nonFixed} style="width:200pt" {else:}  class="FixedSelect" {end:}  >
   <option value="" IF="allOption">All</option>
   <option value="" IF="noneOption">None</option>
   <option value="" IF="rootOption" class="CenterBorder">[Root Level]</option>
	{foreach:categories,k,v}
	{if:!v.category_id=currentCategory}
		<option value="{v.category_id:r}" selected="{v.category_id=selectedCategory}">{v.stringPath:h}</option>{end:}
	{end:}
   <span IF="!allOption"><option value="" IF="isEmpty(categories)">-- No categories --</option></span>
</select>
