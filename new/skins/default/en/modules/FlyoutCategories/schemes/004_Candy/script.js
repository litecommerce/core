var layout = 'side';

var menuImages = new Array();
var parents = Array();

var sel_root_cat = 0;

// defenitions for 'menumanagement.js'
var rootmenuOffsetX = 0;
var rootmenuOffsetY = 0;
var submenuOffsetX = 0;
var submenuOffsetY = 0;


function gotoCategory(id)
{
	var catHref = id;
	self.location = catHref;
}


//**************** Init  ***********************
function init_curve_blue()
{
	menuOffsetX = 1;
}

function init_curve_yellow()
{
	menuOffsetX = 0;
}

function init_grey_blue()
{
	menuOffsetX = 1;
}

function init_yellow()
{
	var menuOffsetX = 1;
}
//********************** end (Init)  ******************




//*******************  General functions ************************
function change_color(id, type, prefix, selected)	{
	switch (color)
	{
		case 'curve_blue':
			change_color_curve_blue(id, type, prefix, selected);
		break;

		case 'curve_yellow':
			change_color_curve_yellow(id, type, prefix, selected);
		break;

		case 'grey_blue':
			change_color_grey_blue(id, type, prefix, selected);
		break;

        case 'FashionBoutique':
            change_color_FashionBoutique(id, type, prefix, selected);
        break;

        case 'GiftsShop':
            change_color_GiftsShop(id, type, prefix, selected);
        break;

        case 'SummerSports':
            change_color_SummerSports(id, type, prefix, selected);
        break;

        case 'WinterSports':
            change_color_WinterSports(id, type, prefix, selected);
        break;

		case 'yellow':
		default:
			change_color_yellow(id, type, prefix, selected);
		break;
	}
}
//****************************   End (General functions)  ************************




//**************************** Curve blue *************************
function change_color_curve_blue(id, type, prefix, selected)	{
	if(id == sel_root_cat && type == "out")
		selected = "selected";
	if(id == sel_root_cat)
		type="over";

	id = prefix + id;	

	setTimeout("change_class('"+id+"_bg', 'BgRepeatXOut', 'BgRepeatXOver', '" + type + "')", 1);
	setTimeout("change_class('"+id+"_link', 'CategoriesListOut', 'CategoriesListOver', '" + type + "')", 1);

	setTimeout("set_bgimage('"+id+"_bg', '"+type+"', '0','1')", 1);
	setTimeout("set_bgimage('"+id+"_bg2', '"+type+"', '2','3')", 2);
	setTimeout("set_bgimage('"+id+"_img2', '"+type+"', '2','3')", 3);
	setTimeout("set_bgimage('"+id+"_img5', '"+type+"', '2','3')", 4);

	setTimeout("change_image('"+id+"_img1', 'fm1.gif', 'fm1_2.gif', '" + type + "')", 1);
	setTimeout("change_image('"+id+"_img3', 'fm3.gif', 'fm3_2.gif', '" + type + "')", 1);
	setTimeout("change_image('"+id+"_img4', 'fm4.gif', 'fm4_2.gif', '" + type + "')", 1);
	setTimeout("change_image('"+id+"_img6', 'fm6.gif', 'fm6_2.gif', '" + type + "')", 1);
}

function change_parents_color_curve_blue(cat_id, type)	{
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

			change_color_curve_blue(current_id, type, prefix);
		}
	}
}
//***************************************



//*********************  Curve yellow  ****************
function change_color_curve_yellow(id, type, prefix, selected)	{
	if(id == sel_root_cat && type == "out")
		selected = "selected";
	if(id == sel_root_cat)
		type="over";
	id = prefix + id;	
	change_class(id + "_bg", "BgOut", "BgOver", type);
	change_class(id + "_bg2", "BorderOut", "BorderOver", type);
	change_class(id + "_bg3", "BorderOut", "BorderOver", type);
	change_class(id + "_bg4", "BorderOut", "BorderOver", type);
	change_class(id + "_bg5", "BorderOut", "BorderOver", type);
	change_class(id + "_link", "CategoriesListOut", "CategoriesListOver", type);
}
function change_parents_color_curve_yellow(cat_id, type)	{
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

			change_color_curve_yellow(current_id, type, prefix);
		}
	}
}
//********************  End (Curve yellow)


//*********************  Grey blue ********************
function change_color_grey_blue(id, type, prefix, selected)	{
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
function change_parents_color_grey_blue(cat_id, type)	{
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

			change_color_grey_blue(current_id, type, prefix);
		}
	}
}
//**********************  End (Grey blue)  *******************


//************************ Yellow ****************************
function change_color_yellow(id, type, prefix, selected)	{
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
	change_class(id + "_table", "TableOut", "TableOver", type);
}
function change_parents_color_yellow(cat_id, type)	{
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

			change_color_yellow(current_id, type, prefix);
		}
	}
}
//*************************  End (Yellow)  ***********************

//********************************* Images init  *********************
function InitMenuImages_curve_blue() {
    menuImages[0] = new Image; 
	menuImages[0].src = img_path + "/bg1_2.gif";
    menuImages[1] = new Image; 
	menuImages[1].src = img_path + "/bg1.gif";
    menuImages[2] = new Image; 
	menuImages[2].src = img_path + "/bg2_2.gif";
    menuImages[3] = new Image; 
	menuImages[3].src = img_path + "/bg2.gif";
    menuImages[4] = new Image; 
	menuImages[4].src = img_path + "/fm2_2.gif";
    menuImages[5] = new Image; 
	menuImages[5].src = img_path + "/fm2.gif";
    menuImages[6] = new Image; 
	menuImages[6].src = img_path + "/fm5_2.gif";
    menuImages[7] = new Image; 
	menuImages[7].src = img_path + "/fm5.gif";
}
function InitMenuImages_curve_yellow() {
	// Empty.
}
function InitMenuImages_grey_blue() {
    menuImages[0] = new Image; 
	menuImages[0].src = img_path + "/bg2.gif";
    menuImages[1] = new Image; 
	menuImages[1].src = img_path + "/bg1.gif";
}
function InitMenuImages_yellow() {
    menuImages[0] = new Image; 
	menuImages[0].src = img_path + "/bg2.gif";
    menuImages[1] = new Image; 
	menuImages[1].src = img_path + "/bg1.gif";
}

function CategoryBody_yellow(id, href, caption)
{
	obj = document.getElementById(id);
	if ( !obj ) return;
	line1_id = id + "_line1";
	line2_id = id + "_line2";
	line3_id = id + "_line3";
	link_id = id + "_link";
	bg_id = id + "_bg";

	content = '<table border=0 cellpadding=0 cellspacing=0 width="100%">';
	content += '<tr><td id="' + line1_id + '" class="Line1"><img src="'+ img_path +'/spacer.gif" width=1 height=1 alt="" /></td>';
	content += '<tr><td onClick="this.blur();" background="' + img_path + '/bg1.gif" class="CatTDOut" id="' + bg_id + '"><a href="' + static_catalog_name + href + '" class="CategoriesListOut" id="' + link_id + '">'+ caption+'</a></td></tr>';
	content += '<tr><td id="' + line2_id + '" class="Line2"><img src="'+ img_path +'/spacer.gif" width=1 height=1 alt="" /></td>';
	content += '<tr><td id="' + line3_id + '" class="Line3"><img src="'+ img_path +'/spacer.gif" width=1 height=1 alt="" /></td>';
	content += '</table>';
    obj.innerHTML = content;
}


function SubCategoryBody_yellow(id, href, caption)
{
	obj = document.getElementById(id);
	if ( !obj ) return;
	line1_id = id + "_line1";
	line2_id = id + "_line2";
	line3_id = id + "_line3";
	table_id = id + "_table";
	link_id = id + "_link";
	bg_id = id + "_bg";

	content = '<table border=0 cellpadding=0 cellspacing=0 width="100%" class="TableOut" id="' + table_id + '">';
	content += '<tr><td id="' + line1_id + '" class="Line1"><img src="'+ img_path +'/spacer.gif" width=1 height=1 alt="" /></td>';
	content += '<tr><td onClick="this.blur();" background="' + img_path + '/bg1.gif" class="CatTDOut" id="' + bg_id + '"><a href="' + static_catalog_name + href + '" class="CategoriesListOut" id="' + link_id + '">'+ caption+'</a></td></tr>';
	content += '<tr><td id="' + line2_id + '" class="Line2"><img src="'+ img_path +'/spacer.gif" width=1 height=1 alt="" /></td>';
	content += '<tr><td id="' + line3_id + '" class="Line3"><img src="'+ img_path +'/spacer.gif" width=1 height=1 alt="" /></td>';
	content += '</table>';
    obj.innerHTML = content;
}



function CategoryBody_curve_blue(id, href, caption)
{
	obj = document.getElementById(id);
	if ( !obj ) return;
	img1_id = id + "_img1";
	img2_id = id + "_img2";
	img3_id = id + "_img3";
	img4_id = id + "_img4";
	img5_id = id + "_img5";
	img6_id = id + "_img6";
	link_id = id + "_link";
	bg_id = id + "_bg";
	bg2_id = id + "_bg2";

	content = '<table border=0 cellpadding=0 cellspacing=0 width="100%"><tr>';
	content += '<td width=14 valign=top background="' + img_path + '/fm2.gif" id="' + img2_id + '"><table width="100%" cellspacing=0 cellpadding=0><tr><td><img src="' + img_path + '/fm1.gif" width=14 height=19 alt="" id="' + img1_id + '" /></td></tr></table></td>';
	content += '<td onClick="this.blur();" background="' + img_path + '/bg1.gif" class="BgRepeatXOut" id="' + bg_id + '"><a href="' + static_catalog_name + href + '" class="CategoriesListOut" id="' + link_id + '">'+ caption+'</a></td>';
	content += '<td width=14 valign=top background="' + img_path + '/fm5.gif" id="' + img5_id + '"><table width="100%" cellspacing=0 cellpadding=0><tr><td><img src="' + img_path + '/fm4.gif" width=14 height=19 alt="" id="' + img4_id + '" /></td></tr></table></td>';
	content += '</tr><tr>';
	content += '<td width=14><img src="' + img_path + '/fm3.gif" width=14 height=2 alt="" id="' + img3_id + '" /></td>';
	content += '<td background="' + img_path + '/bg2.gif" id="' + bg2_id + '"><img src="'+ img_path +'/spacer.gif" width=1 height=2></td>';
	content += '<td width=14><img src="' + img_path + '/fm6.gif" width=14 height=2 alt="" id="' + img6_id + '" /></td>';
	content += '</tr></table>';
    obj.innerHTML = content;
}



function CategoryBody_grey_blue(id, href, caption)
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


function SubCategoryBody_grey_blue(id, href, caption)
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



function CategoryBody_curve_yellow(id, href, caption)
{
	obj = document.getElementById(id);
	if ( !obj ) return;
	link_id = id + "_link";
	bg_id = id + "_bg";
	bg2_id = id + "_bg2";
	bg3_id = id + "_bg3";
	bg4_id = id + "_bg4";
	bg5_id = id + "_bg5";

	content = '<table border=0 cellpadding=0 cellspacing=0 width="100%">';
	content += '<tr>';
	content += '<td width=1><img src="'+ img_path +'/spacer.gif" width=1 height=1 alt=""  /></td>';
	content += '<td id="' + bg2_id + '" class="BorderOut"><img src="'+ img_path +'/spacer.gif" width=1 height=1></td>';
	content += '<td width=1><img src="'+ img_path +'/spacer.gif" width=1 height=1 alt=""  /></td>';
	content += '</tr><tr>';
	content += '<td width=1 class="BorderOut"  id="' + bg3_id + '"><img src="'+ img_path +'/spacer.gif" width=1 height=1 alt=""/></td>';
	content += '<td onClick="this.blur();" class="BgOut" id="' + bg_id + '"><a href="' + static_catalog_name + href + '" class="CategoriesListOut" id="' + link_id + '">'+ caption+'</a></td>';
	content += '<td width=1 class="BorderOut" id="' + bg4_id + '"><img src="'+ img_path +'/spacer.gif" width=1 height=1 alt=""/></td>';
	content += '</tr><tr>';
	content += '<td width=1><img src="'+ img_path +'/spacer.gif" width=1 height=1 alt=""/></td>';
	content += '<td id="' + bg5_id + '" class="BorderOut"><img src="'+ img_path +'/spacer.gif" width=1 height=1></td>';
	content += '<td width=1><img src="'+ img_path +'/spacer.gif" width=1 height=1 alt=""/></td>';
	content += '</tr></table>';
    obj.innerHTML = content;
}

//***************************************************************
