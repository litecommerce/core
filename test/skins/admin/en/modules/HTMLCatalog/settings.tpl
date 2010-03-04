    {if:option.isCheckbox()}
    <input id="{option.name}" type="checkbox" name="{option.name}" checked="{option.isChecked()}" onClick="this.blur()">
    {end:}
    {if:option.isText()}
    <input id="{option.name}" type="text" name="{option.name}" value="{option.value}" size=45>
    {end:}
    {if:option.isTextArea()}
    <textarea id="{option.name}" name="{option.name}" rows=5>{option.value}</textarea>
    {end:}

    <select IF="option.name=#catalog_category#" class="FixedSelect" name="{option.name}" size="1" style="width:200pt">
       <option value="" selected="option.value=##">All</option>
    	{foreach:dialog.categories,k,v}
		<option value="{v.category_id:r}" selected="{v.category_id=option.value}">{v.stringPath:h}</option>
    	{end:}
    </select>

    <select IF="option.name=#catalog_pages_count#" name="{option.name}">
        <option value="5" selected="option.value=#5#">5</option>
        <option value="10" selected="option.value=#10#">10</option>
        <option value="20" selected="option.value=#20#">20</option>
        <option value="50" selected="option.value=#50#">50</option>
        <option value="100" selected="option.value=#100#">100</option>
    </select>

    <select IF="option.name=#catalog_pages#" name="{option.name}">
        <option value="both" selected="option.value=#both#">Subcategories and products</option>
        <option value="categories" selected="option.value=#categories#">Subcategories only</option>
        <option value="products" selected="option.value=#products#">Products only</option>
    </select>
    
