
//**************** Init  ***********************
function init_GiftsShop()
{
    rootmenuOffsetX = 0;
    rootmenuOffsetY = -1;
    submenuOffsetX = -1;
    submenuOffsetY = 0;
}
//********************** end (Init)  ******************


//*********************  GiftsShop ********************
function change_color_GiftsShop(id, type, prefix, selected)	{
	if(id == sel_root_cat && type == "out")
		selected = "selected";
	if(id == sel_root_cat)
		type="over";

	id = prefix + id;	
	set_bgimage(id + "_bg", type, 0, 1);
	change_class(id + "_link", "CategoriesListOut", "CategoriesListOver", type);
	change_class(id + "_bg", "CatTDOut", "CatTDOver", type);
	change_class(id + "_line1", "Line1", "Line1_2", type);
	change_class(id + "_line2", "Line2", "Line2_2", type);
	change_class(id + "_line3", "Line3", "Line3_2", type);
	change_class(id + "_line4", "Line4", "Line4_2", type);
	change_class(id + "_table", "TableOut", "TableOver", type);
}
function change_parents_color_GiftsShop(cat_id, type)	{
	var content = " ";
	for (var x = 0; x < parents[cat_id].length; x++)	{
		current_id = parents[cat_id][x];
		if(current_id != cat_id)	{
			id = current_id;
			if(document.getElementById('cat_' + id))
				prefix = 'cat_';
			else if(document.getElementById('table1_menu_' + id))
				prefix = 'table1_menu_';
			else if(document.getElementById('table2_menu_' + id))
				prefix = 'table2_menu_';

			change_color_GiftsShop(current_id, type, prefix);
		}
	}
}
//**********************  End (GiftsShop)  *******************


//********************************* Images init  *********************
function InitMenuImages_GiftsShop() {
    menuImages[0] = new Image; 
	menuImages[0].src = img_path + "/bg2.gif";
    menuImages[1] = new Image; 
	menuImages[1].src = img_path + "/bg1.gif";
}

function CategoryBody_GiftsShop(id, href, caption)
{
	obj = document.getElementById(id);
	if ( !obj ) return;
	line1_id = id + "_line1";
	line2_id = id + "_line2";
	line3_id = id + "_line3";
	line4_id = id + "_line4";
	link_id = id + "_link";
	bg_id = id + "_bg";

	content = '<table border=0 cellpadding=0 cellspacing=0 width="100%">';
	content += '<tr><td id="' + line1_id + '" class="Line1"><img src="'+ img_path +'/spacer.gif" width=1 height=1 alt="" /></td>';
	content += '<tr><td onClick="this.blur();" background="' + img_path + '/bg1.gif" class="CatTDOut" id="' + bg_id + '"><a href="' + static_catalog_name + href + '" class="CategoriesListOut" id="' + link_id + '">'+ caption+'</a></td></tr>';
	content += '<tr><td id="' + line2_id + '" class="Line2"><img src="'+ img_path +'/spacer.gif" width=1 height=1 alt="" /></td>';
	content += '<tr><td id="' + line3_id + '" class="Line3"><img src="'+ img_path +'/spacer.gif" width=1 height=1 alt="" /></td>';
	content += '<tr><td id="' + line4_id + '" class="Line4"><img src="'+ img_path +'/spacer.gif" width=1 height=1 alt="" /></td>';
	content += '</table>';
    obj.innerHTML = content;
}


function SubCategoryBody_GiftsShop(id, href, caption)
{
	obj = document.getElementById(id);
	if ( !obj ) return;
	line1_id = id + "_line1";
	line2_id = id + "_line2";
	line3_id = id + "_line3";
	line4_id = id + "_line4";
	table_id = id + "_table";
	link_id = id + "_link";
	bg_id = id + "_bg";

	content = '<table border=0 cellpadding=0 cellspacing=0 width="100%" class="TableOut" id="' + table_id + '">';
	content += '<tr><td id="' + line1_id + '" class="Line1"><img src="'+ img_path +'/spacer.gif" width=1 height=1 alt="" /></td>';
	content += '<tr><td onClick="this.blur();" background="' + img_path + '/bg1.gif" class="CatTDOut" id="' + bg_id + '"><a href="' + static_catalog_name + href + '" class="CategoriesListOut" id="' + link_id + '">'+ caption+'</a></td></tr>';
	content += '<tr><td id="' + line2_id + '" class="Line2"><img src="'+ img_path +'/spacer.gif" width=1 height=1 alt="" /></td>';
	content += '<tr><td id="' + line3_id + '" class="Line3"><img src="'+ img_path +'/spacer.gif" width=1 height=1 alt="" /></td>';
	content += '<tr><td id="' + line4_id + '" class="Line4"><img src="'+ img_path +'/spacer.gif" width=1 height=1 alt="" /></td>';
	content += '</table>';
    obj.innerHTML = content;
}

//***************************************************************
