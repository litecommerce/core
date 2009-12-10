var layout = 'side';

var parents = Array();
var last_data = Array();
var first_data = Array();
var previous_data = Array();
var next_data = Array();
var depth_data = Array();
var sel_root_cat = 0;

// defenitions for 'menumanagement.js'
var rootmenuOffsetX = 0;
var rootmenuOffsetY = -1;
var submenuOffsetX = -1;
var submenuOffsetY = 0;


// Browser compatibility
if (isMozilla || isOpera)
{
	submenuOffsetY -= 1;
}


function gotoCategory(id)
{
	var catHref = id;
	self.location = catHref;
}


function change_top_border(id, type, flag, prefix)	{
	var prefix = "";
	if(document.getElementById('cat_' + id))
		prefix = 'cat_';
	else if(document.getElementById('table1_menu_' + id))
		prefix = 'table1_menu_';
	else if(document.getElementById('table2_menu_' + id))
		prefix = 'table2_menu_';

	if(document.getElementById(prefix + id))	{
		var elm = document.getElementById(prefix + id);
		if(type == "over")	{
			if(prefix == 'cat_' || flag == 'root')
				elm.className = 'CatOverOnlyBorder';
			else
				elm.className = 'CatSubOverOnlyBorder';
		} else {
			if(prefix == 'cat_')
				elm.className = 'CatOut';
			else
				elm.className = 'CatSubOut';
		}
	}
}


function change_subcolor(id, prefix, type, last, first, previous, depth, next, selected)	{
	if(id == sel_root_cat && type == "out")
		selected = "selected";
	if(id == sel_root_cat)
		type="over";

	var id = prefix + id;	
	if(document.getElementById(id))
	{
		var elm = document.getElementById(id);
		if(type == "over")
		{
			if(last == 1 && depth != 1)
			{
				if(prefix == 'cat_')
					elm.className = 'CatOverWithoutBorder';
				else
					elm.className = 'CatSubOverWithoutBorder';
			} else	{
				if(prefix == 'cat_')
					elm.className = 'CatOver';
				else
					elm.className = 'CatSubOver';
			}
		} else {
			if(prefix == 'cat_')
				elm.className = 'CatOut';
			else {
                if (last == 1) 
                    elm.className = 'CatSubOutLast';
                else 
    				elm.className = 'CatSubOut';
            }
		}

		// Change the bottom border of previous element
		if(previous != -1  && previous != sel_root_cat)
				change_top_border(previous, type);
		
		if(first == 1 && depth == 1)
			change_class("first", "BorderOut", "BorderOver", type);

		if(next == sel_root_cat && type == "out")
			change_top_border(id, "over", "root");

	}

	change_class(id + "_icon", "CatImageBorderOut", "CatImageBorderOver", type)
	change_class(id + "_link", "CategoriesListOut", "CategoriesListOver", type);
	image_id = id + "_img";

	if(document.getElementById(image_id))
	{
		var elm = document.getElementById(image_id);
		if(selected == "selected")
			elm.src = img_path + '/tree_go3.gif';
		else if(type == "over")	{
			elm.src = img_path + '/tree_go.gif';
		} else {
			elm.src = img_path + '/tree_go2.gif';
		}
	}
}

function change_parents_color(cat_id, type)	{
	var content = " ";
	for (var x = 0; x < parents[cat_id].length; x++)
	{
		current_id = parents[cat_id][x];
		if(current_id != cat_id)
			change_color(current_id, type);
	}
}


function change_color(id, type, selected)
{
	var last = last_data[id];
	var first = first_data[id];
	var previous = previous_data[id];
	var next = next_data[id];
	var depth = depth_data[id];

	var prefix = '';
	if(document.getElementById('cat_' + id))	{
		// Root category
		prefix = 'cat_';
	} else if(document.getElementById('table1_menu_' + id))	{
		// Subcategory
		prefix = 'table1_menu_';
	} else if(document.getElementById('table2_menu_' + id))	{
		// Subcategory
		prefix = 'table2_menu_';
	}
	if(selected != "selected") {
		selected = "";
	}

	change_subcolor(id,prefix, type, last, first, previous, depth, next, selected);
}


function CategoryBodyA(id, href, caption, showArrow, iconUrl, showIcon, iwidth)
{
	var obj = document.getElementById(id);
	if ( !obj ) return;

	var iwidth = fc_correctWidth(iwidth);

	var content = '<TABLE border=0 cellpadding=0 cellspacing=0 width="100%"><TR>';
    if ( showIcon ) {
        content += '<td width=7>&nbsp;</td>';
        content += '<td width=20>';
	icon_id = id + "_icon";
        if ( iconUrl ) {
            content += '<img src="'+static_catalog_path+iconUrl+'" width="'+iwidth+'" align=absmiddle border=0 class=CatImageBorderOut id=' + icon_id + ' alt="">';
        } else {
            content += '<img src="'+img_path+'/spacer.gif" width="'+iwidth+'" height=1 align=absmiddle border=0 alt="">';
        }
        content += '</td>';
    }

	var link_id = id + "_link";
	content += '<td width=5>&nbsp;</td><TD onClick="this.blur();" height=25><a href="' + static_catalog_name + href + '" class="CategoriesListOut" id=' + link_id + '>'+ caption+'</a></TD>';
	var img_id = id + "_img";                               
	if ( showArrow ) content += '<td width=18 height=25 ><img src="' + img_path + '/tree_go2.gif" width=18 height=25 align=absmiddle border=0 id=' + img_id + '></td>';
	content += '</TR></TABLE>';
    obj.innerHTML = content;
}

function CategoryBodyB(id, href, caption, showArrow, iconUrl, showIcon, iwidth)
{
    var obj = document.getElementById(id);
	if ( !obj ) return;

	var iwidth = fc_correctWidth(iwidth);

	var content = '<TABLE border=0 cellpadding=0 cellspacing=0 width="100%"><TR>';
    if ( showIcon ) {
        content += '<td width=7>&nbsp;</td>';
        content += '<td width=20>';
	icon_id = id + "_icon";
        if ( iconUrl ) {
            content += '<img src="'+static_catalog_path+iconUrl+'" width="'+iwidth+'" align=absmiddle border=0 class=CatImageBorderOut alt="">';
        } else {
            content += '<img src="'+img_path+'/spacer.gif" width="'+iwidth+'" height=1 align=absmiddle border=0 alt="">';
        }
        content += '</td>';
    }
	var link_id = id + "_link";
	content += '<td width=5>&nbsp;</td><TD height=25 onClick="this.blur();" ><a href="' + static_catalog_name + href + '" class="CategoriesListOut" id=' + link_id + '>'+caption+'</a></TD>';
	if ( showArrow ) content += '<td width=13 height=25>&nbsp;</td>';
	content += '</TR></TABLE>';
    obj.innerHTML = content;
}

function SubCategoryBodyA(id, href, caption, show_arrow, iconUrl, showIcon, iwidth)
{
    var obj = document.getElementById(id);
    if ( !obj ) return;

	var iwidth = fc_correctWidth(iwidth);

    var content = '<table border=0 cellpadding=0 cellspacing=0 width="100%"><tr>';
    if ( showIcon ) {
        content += '<td width=7><img src="'+img_path+'/spacer.gif" width=7 height=1 alt="" /></td>';
        content += '<td width=15>';
        if ( iconUrl ) {
	icon_id = id + "_icon";
            content += '<img src="'+static_catalog_path+iconUrl+'" width="'+iwidth+'" border=0 class="CatImageBorderOut" id="' + icon_id + '" alt="">';
        } else {
            content += '<img src="'+img_path+'/spacer.gif" width="'+iwidth+'" height=1 border=0 alt="">';
        }
        content += '</td>';
        content += '<td width=5><img src="'+img_path+'/spacer.gif" width=5 height=1 alt="" /></td>';
    }
	var link_id = id + "_link";
    content += '<td width=5>&nbsp;</td><td height=25 onClick="this.blur();"><a href="' + static_catalog_name + href + '" class="CategoriesListOut" id=' + link_id + '>'+caption+'</a></td>';
    if ( show_arrow ) content += '<td width=13 height=18>&nbsp;</td>';
    content += '</tr></table>';
    obj.innerHTML = content;
}

function SubCategoryBodyB(id, href, caption, show_arrow, iconUrl, showIcon, iwidth)
{
    var obj = document.getElementById(id);
    if ( !obj ) return;

	var iwidth = fc_correctWidth(iwidth);

    var content = '<table border=0 cellpadding=0 cellspacing=0 width="100%"><tr>';
    if ( showIcon ) {
        content += '<td width=7><img src="'+img_path+'/spacer.gif" width=7 height=1 alt="" /></td>';
        content += '<td width=15>';
        if ( iconUrl ) {
	icon_id = id + "_icon";
            content += '<img src="'+static_catalog_path+iconUrl+'" width="'+iwidth+'" border=0 class="CatImageBorderOut" id="' + icon_id + '" alt="">';
        } else {
            content += '<img src="'+img_path+'/spacer.gif" width="'+iwidth+'" height=1 border=0 alt="">';
        }
        content += '</td>';
        content += '<td width=5><img src="'+img_path+'/spacer.gif" width=5 height=1 alt="" /></td>';
    }
	var link_id = id + "_link";
    content += '<td width=5>&nbsp;</td><td height=25 onClick="this.blur();"><a href="' + static_catalog_name + href + '" class="CategoriesListOut" id=' + link_id + '>' + caption+'</a></td>';
	var img_id = id + "_img";
    if ( show_arrow ) content += '<td width=18 height=25><img src="' + img_path + '/tree_go2.gif" width=18 height=25 align=absmiddle border=0 id=' + img_id + ' alt=""></td>';
    content += '</tr></table>';
    obj.innerHTML = content;
}

function fc_correctWidth(_width)
{
	var _width = Math.round(_width);
	return (!_width) ? 16 : _width;
}
