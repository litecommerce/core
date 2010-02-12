var layout = 'flat';

var menuImages = new Array();
var parents = Array();
var cat_ids = Array();
var img_ids = Array();
var previous = Array();
var first = Array();
var next = Array();

var num = 0;
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


//*********  Init functions *********************
function init_blue_grey()
{
}

function init_blue()
{
}

function init_yellow_grey()
{
	rootmenuOffsetX = 1;
}
//**************  end (Init functions) ****************


//**************  General  *************************
function change_color_root(id, type, selected)	{
	if(color == 'blue')
		change_color_root_blue(id, type, selected);
	else if(color == 'blue_grey')
		change_color_root_blue_grey(id, type, selected);
	else if(color == 'yellow_grey')
		change_color_root_yellow_grey(id, type, selected);
	else if(color == 'FashionBoutique')
		change_color_root_FashionBoutique(id, type, selected);
	else if(color == 'GiftsShop')
		change_color_root_GiftsShop(id, type, selected);
	else if(color == 'SummerSports')
		change_color_root_SummerSports(id, type, selected);
	else if(color == 'WinterSports')
		change_color_root_WinterSports(id, type, selected);
}
//***************  end (General) *******************

//**************   begin BLUE SCHEME  *************
function change_color_blue(id, type)	{
	change_class("td2_" + id, "SubMenuOut", "SubMenuOver", type);
	change_class("link_" + id, "FlyoutSubItemsOut", "FlyoutSubItemsOver", type);
	change_image("arrow_" + id, "arrow1.gif", "arrow2.gif", type);
}
function change_color_root_blue(id, type, selected)	{
	if(id == sel_root_cat)
		type="over";
	change_class("td_" + id, "RootMenuOut", "RootMenuOver", type);
	change_class("link_" + id, "FlyoutRootItemsOut", "FlyoutRootItemsOver", type);
}
//**************  end BLUE SCHEME  *************************



//**************  begin BLUE GREY SCHEME  *************************
function change_color_blue_grey(id, type, last, first, arrow)	{
	var elm, elm2, elm3, elm4, elm5;

	obj = document.getElementById("td2_" + id);
	if(obj)	{
		if(type == "over")
			obj.className = "SubMenuOver";
		else	{
			if(first == 1)	{
				if(last == 1)
					obj.className = "SubMenuOutFirstLast";
				else
					obj.className = "SubMenuOutFirst";
			} else if(last == 1)
				obj.className = "SubMenuOutLast";
			else
				obj.className = "SubMenuOut";
		}
	}
	change_class("link_" + id, "FlyoutSubItemsOut", "FlyoutSubItemsOver", type);

	img1_id = "img1_" + id;
	if (document.getElementById(img1_id))	{
		elm3 = document.getElementById(img1_id);
		if(type == "over")
			elm3.src = menuImages[0].src;
		else	{
			if(last == 1)	{
				if(first == 1)
					elm3.src = menuImages[1].src;
				else
					elm3.src = menuImages[2].src;
			} else if(first == 1)
				elm3.src = menuImages[3].src;
			else
				elm3.src = menuImages[4].src;
		}
	}
	img2_id = "img2_" + id;
	if (document.getElementById(img2_id))	{
		elm4 = document.getElementById(img2_id);
		if(arrow == 1 && arrow_visible)	{
			if(type == "over")
				elm4.src = menuImages[5].src;
			else	{
				if(first == 1)	{
					if(last == 1)
						elm4.src = menuImages[6].src;	
					else
						elm4.src = menuImages[7].src;	

				} else if(last == 1)
					elm4.src = menuImages[8].src;	
				else
					elm4.src = menuImages[9].src;	
			}
		} else { 
			if(type == "over")
				elm4.src = menuImages[10].src;
			else	{
				if(first == 1)
					if(last == 1)
						elm4.src = menuImages[11].src;	
					else
						elm4.src = menuImages[12].src;
				else if(last == 1)
					elm4.src = menuImages[13].src;	
				else
					elm4.src = menuImages[14].src;	
			}
		}
	}

	arrow_id = "arrow_" + id;
	if (document.getElementById(arrow_id))	{

		elm2 = document.getElementById(arrow_id);
		if(type == "over")
			elm2.src = img_path + "/tree_go2.gif";
		else
			elm2.src = img_path + "/tree_go.gif";
	}

	change_class("space_" + id, "Bottom1", "Bottom2", type);
}
function change_color_root_blue_grey(id, type, selected)	{
	var prev = previous[id];
	var nxt = next[id];

	if(id == sel_root_cat)
		type="over";


	change_class('td_' + id, 'RootMenuOut', 'RootMenuOver', type)
	change_class("link_" + id, "FlyoutRootItemsOut", "FlyoutRootItemsOver", type);

	right_id = "spacer_" + img_ids[id];
	if(img_ids[id] == 1)
	{
		left_id = 0;
	}
	else
	{
		var num = img_ids[id] - 1;
		left_id = "spacer_" + num;
	}

	if(left_id == 0 && document.getElementById("spacer_first_" + id))	{
		left_id = "spacer_first_" + id;
	}
		

	if(type == "out")	{
		if(nxt == sel_root_cat)
			usetype = "over";
		else
			usetype = "out";
	} else {
		usetype = "over";
	}


	if(document.getElementById(right_id))	{
		elm2 = document.getElementById(right_id);
		if(usetype == "over")
			elm2.src = img_path + "/but3.gif";
		else
			elm2.src = img_path + "/but.gif";
	}

	if(type == "out")	{
		if(prev == sel_root_cat)
			usetype = "over";
		else
			usetype = "out";
	} else {
		usetype = "over";
	}

	if(document.getElementById(left_id))	{
		elm3 = document.getElementById(left_id);
		if(usetype == "over")
			elm3.src = img_path + "/but2.gif";
		else
			elm3.src = img_path + "/but.gif";
	}
		
}
function change_root_blue_grey(id, type)	{
	tdid = "td_" + id; td2id = "td2_" + id;
	if(document.getElementById(tdid))
		change_color_root_blue_grey(id, type);
	else	{
		last = cat_ids[id][0];
		first = cat_ids[id][1];
		arrow = cat_ids[id][2];
		change_color_blue_grey(id, type, last, first, arrow);
	}
}
function change_parents_color_blue_grey(cat_id, type)	{
	if(cat_id != 0)	{
		var content = " ";
		for (var x = 0; x < parents[cat_id].length; x++)	{
			current_id = parents[cat_id][x];
			if(current_id != cat_id)	{
				change_root_blue_grey(current_id, type);
			}
		}
	}
}

//**************  end BLUE GREY SCHEME  *************************


//**************  begin YELLOW GREY SCHEME  *************************
function change_color_yellow_grey(id, type, last, first, arrow)	{
	obj = document.getElementById("td2_" + id);
	if(obj)	{
		if(type == "over")
			obj.className = "SubMenuOver";
		else	{
			if(first == 1)	{
				if(last == 1)
					obj.className = "SubMenuOutFirstLast";
				else
					obj.className = "SubMenuOutFirst";
			} else if(last == 1)
				obj.className = "SubMenuOutLast";
			else
				obj.className = "SubMenuOut";
		}
	}
	change_class("link_" + id, "FlyoutSubItemsOut", "FlyoutSubItemsOver", type);

	img1_id = "img1_" + id;
	if (document.getElementById(img1_id))	{
		elm3 = document.getElementById(img1_id);
		if(type == "over")
			elm3.src = img_path + "/img2.gif";
		else	{
			if(last == 1)	{
				if(first == 1)
					elm3.src = img_path + "/img7.gif";
				else
					elm3.src = img_path + "/img1_last.gif";
			} else if(first == 1)
				elm3.src = img_path + "/img1_first.gif";
			else
				elm3.src = img_path + "/img1.gif";
		}
	}
	img2_id = "img2_" + id;
	if (document.getElementById(img2_id))	{
		elm4 = document.getElementById(img2_id);
		if(arrow == 1 && arrow_visible)	{
			if(type == "over")
				elm4.src = img_path + "/img4_arrow.gif";
			else	{
				if(first == 1)	{
					if(last == 1)
						elm4.src = img_path + "/img8_arrow.gif";	
					else
						elm4.src = img_path + "/img3_farr.gif";	

				} else if(last == 1)
					elm4.src = img_path + "/img3_larr.gif";	
				else
					elm4.src = img_path + "/img3_arrow.gif";	
			}
		} else { 
			if(type == "over")
				elm4.src = img_path + "/img4.gif";
			else	{
				if(first == 1)
					if(last == 1)
						elm4.src = img_path + "/img8.gif";	
					else
						elm4.src = img_path + "/img3_first.gif";
				else if(last == 1)
					elm4.src = img_path + "/img3_last.gif";	
				else
					elm4.src = img_path + "/img3.gif";	
			}
		}
	}

	arrow_id = "arrow_" + id;
	if (document.getElementById(arrow_id))	{

		elm2 = document.getElementById(arrow_id);
		if(type == "over")
			elm2.src = img_path + "/tree_go2.gif";
		else
			elm2.src = img_path + "/tree_go.gif";
	}

	change_class("space_" + id, "Bottom1", "Bottom2", type);
}
function change_color_root_yellow_grey(id, type, selected)	{
	var prev = previous[id];
	var nxt = next[id];

	if(id == sel_root_cat)
		type="over";

	change_class('td_' + id, 'RootMenuOut', 'RootMenuOver', type)
	change_class("link_" + id, "FlyoutRootItemsOut", "FlyoutRootItemsOver", type);

	right_id = "spacer_" + img_ids[id];
	if(img_ids[id] == 1)
	{
		left_id = 0;
	}
	else
	{
		var num = img_ids[id] - 1;
		left_id = "spacer_" + num;
	}
	if(document.getElementById(right_id))	{
		elm2 = document.getElementById(right_id);
		if(type == "over")
			elm2.src = img_path + "/but3.gif";
		else
			elm2.src = img_path + "/but.gif";
	}

	if(document.getElementById(left_id))	{
		
		elm3 = document.getElementById(left_id);
		if(type == "over")
			elm3.src = img_path + "/but2.gif";
		else
			elm3.src = img_path + "/but.gif";
	}
		
}

function change_root_yellow_grey(id, type)	{
	tdid = "td_" + id; td2id = "td2_" + id;
	if(document.getElementById(tdid))
		change_color_root_yellow_grey(id, type);
	else	{
		last = cat_ids[id][0];
		first = cat_ids[id][1];
		arrow = cat_ids[id][2];
		change_color_yellow_grey(id, type, last, first, arrow);
	}
}



function change_parents_color_yellow_grey(cat_id, type)	{
	if(cat_id != 0)	{
		var content = " ";
		for (var x = 0; x < parents[cat_id].length; x++)	{
			current_id = parents[cat_id][x];
			if(current_id != cat_id)	{
				change_root_yellow_grey(current_id, type);
			}
		}
	}
}
//**************  end YELLOW GREY SCHEME  *************************

//********************************* Images init  *********************
function InitMenuImages_blue_grey() {
    menuImages[0] = new Image; 
	menuImages[0].src = img_path + "/img2.gif";
    menuImages[1] = new Image; 
	menuImages[1].src = img_path + "/img7.gif";
    menuImages[2] = new Image; 
	menuImages[2].src = img_path + "/img1_last.gif";
    menuImages[3] = new Image; 
	menuImages[3].src = img_path + "/img1_first.gif";
    menuImages[4] = new Image; 
	menuImages[4].src = img_path + "/img1.gif";
    menuImages[5] = new Image; 
	menuImages[5].src = img_path + "/img4_arrow.gif";
    menuImages[6] = new Image; 
	menuImages[6].src = img_path + "/img8_arrow.gif";
    menuImages[7] = new Image; 
	menuImages[7].src = img_path + "/img3_farr.gif";
    menuImages[8] = new Image; 
	menuImages[8].src = img_path + "/img3_larr.gif";
    menuImages[9] = new Image; 
	menuImages[9].src = img_path + "/img3_arrow.gif";
    menuImages[10] = new Image; 
	menuImages[10].src = img_path + "/img4.gif";
    menuImages[11] = new Image; 
	menuImages[11].src = img_path + "/img8.gif";
    menuImages[12] = new Image; 
	menuImages[12].src = img_path + "/img3_first.gif";
    menuImages[13] = new Image; 
	menuImages[13].src = img_path + "/img3_last.gif";
    menuImages[14] = new Image; 
	menuImages[14].src = img_path + "/img3.gif";
}
function InitMenuImages_blue() {
	// Empty.
}
function InitMenuImages_yellow_grey() {
	// Empty.
}

function CategoryBody_blue(id, href, caption, cat_id)
{
	obj = document.getElementById(id);
    if ( !obj ) return;

    content = '<table border=0 cellpadding=0 cellspacing=0><tr><td width=21><img src="' + img_path + '/spacer.gif" width=2 height=26 ></td>';
	link_id = "link_" + cat_id;
    content += '<td nowrap><a href="'+static_catalog_name+href+'" class="FlyoutRootItemsOut" onClick="this.blur();" id="' + link_id + '">'+caption+'</a></td>';
    content += '<td width=21 align="right"><img src="' + img_path + '/spacer.gif" width=2 height=26 border=0></td>';
    content += '<td width=2 align="right"><img src="' + img_path + '/but.gif" width=2 height=26 alt="" /></td></tr></table>';
    obj.innerHTML = content;
}

function SubCategoryBody_blue(id, href, caption, show_arrow, iconUrl, showIcon, cat_id, arrow)
{
	obj = document.getElementById(id);
    if ( !obj ) return;

	content = '<table border=0 cellpadding=0 cellspacing=0 width="100%"><tr><td colspan=5 bgcolor="#ffffff"><img src="' + img_path + '/spacer.gif" width=1 height=1></td></tr><tr>';
	content += '<td width=10><img src="' + img_path + '/spacer.gif" width=10 height=1></td>';
	link_id = "link_" + cat_id;
	arrow_id = "arrow_" + cat_id;
	content += '<td width=10>&nbsp;</td><td nowrap height=26 width="100%"><a href="'+static_catalog_name+href+'" class="FlyoutSubItemsOut" onClick="this.blur();" id="' + link_id + '">'+caption+'</a></td>';
	if(show_arrow && arrow)	{ 
		content += '<td><img src="' + img_path + '/arrow1.gif"  id="' + arrow_id + '" width=27 height=18></td>';
	} else {
		content += '<td><img src="' + img_path + '/spacer.gif" width=27 height=18></td>';
	}
	content += '</tr></table>';
    obj.innerHTML = content;
}

function CategoryBody_blue_grey(id, href, caption, cat_id, first)
{
	obj = document.getElementById(id);
    if ( !obj ) return;

    content = '<table border=0 cellpadding=0 cellspacing=0><tr>';
if(first)	{
	first_id = "spacer_first_" + cat_id;
	content += '<td width=2 align=right><img src="' + img_path + '/but.gif" width=2 height=26 border=0 id="' + first_id + '"></td>';
}
    content += '<td width=21><img src="' + img_path + '/spacer.gif" width=2 height=26></td>';
	link_id = "link_" + cat_id;
    content += '<td nowrap><a href="'+static_catalog_name+href+'" class="FlyoutRootItemsOut" onClick="this.blur();" id="' + link_id + '">'+caption+'</a></td>';
    content += '<td width=21 align=right><img src="' + img_path + '/spacer.gif" width=2 height=26 border=0></TD>';
	img_id = "spacer_" + img_ids[cat_id];
    content += '<td width=2 align=right><img src="' + img_path + '/but.gif" width=2 height=26 border=0 id="' + img_id + '"></td></tr></table>';
    obj.innerHTML = content;
}

function SubCategoryBody_blue_grey(id, href, caption, show_arrow, iconUrl, showIcon, cat_id, is_first, is_last, arrow)
{
	obj = document.getElementById(id);
    if ( !obj ) return;
	img1_id = "img1_" + cat_id;
	link_id = "link_" + cat_id;
	img2_id = "img2_" + cat_id;
	space_id = "space_" + cat_id;
	bottom1_id = "bottom1_" + cat_id;
	bottom2_id = "bottom2_" + cat_id;

	content = '<table border=0 cellpadding=0 cellspacing=0 width="100%">';
	if(is_first)	{
		if(is_last)
			content += '<tr><td width=26><img src="' + img_path + '/img7.gif" width=26 height=23 id="' + img1_id + '"></td>';
		else
			content += '<tr><td width=26><img src="' + img_path + '/img1_first.gif" width=26 height=23 id="' + img1_id + '"></td>';
	} else if(is_last)
		content += '<tr><td width=26><img src="' + img_path + '/img1_last.gif" width=26 height=23 id="' + img1_id + '"></td>';
	else
		content += '<tr><td width=26><img src="' + img_path + '/img1.gif" width=26 height=23 id="' + img1_id + '"></td>';

	content += '<td nowrap width="100%"><a href="'+static_catalog_name+href+'" class="FlyoutSubItemsOut" onClick="this.blur();" id="' + link_id + '">'+caption+'</a></td>';


	if(show_arrow && arrow)	{ 
		if(is_first)	{
			if(is_last)
				content += '<td width=26><img src="' + img_path + '/img8_arrow.gif" width=26 height=23 id="' + img2_id + '"></td></tr>';
			else
				content += '<td width=26><img src="' + img_path + '/img3_farr.gif" width=26 height=23 id="' + img2_id + '"></td></tr>';
		} else if(is_last)
			content += '<td width=26><img src="' + img_path + '/img3_larr.gif" width=26 height=23 id="' + img2_id + '"></td></tr>';
		else
			content += '<td width=26><img src="' + img_path + '/img3_arrow.gif" width=26 height=23 id="' + img2_id + '"></td></tr>';
	} else {
		if(is_first)	{
			if(is_last)
				content += '<td width=26><img src="' + img_path + '/img8.gif" width=26 height=23 id="' + img2_id + '"></td></tr>';
			else 
				content += '<td width=26><img src="' + img_path + '/img3_first.gif" width=26 height=23 id="' + img2_id + '"></td></tr>';
		} else if(is_last)
			content += '<td width=26><img src="' + img_path + '/img3_last.gif" width=26 height=23 id="' + img2_id + '"></td></tr>';
		else
			content += '<td width=26><img src="' + img_path + '/img3.gif" width=26 height=23 id="' + img2_id + '"></td></tr>';
	}

	if(!is_last)	{
		content += '<tr><td colspan=3><table width="100%" cellspacing=0 cellpadding=0 border=0><tr>';
		content += '<td width=2><img src="' + img_path + '/img5.gif" width=2 height=1></td>';
		content += '<td class="Bottom1" id="' + space_id + '"><img src="' + img_path + '/spacer.gif" width=1 height=1></td>';
		content += '<td width=2><img src="' + img_path + '/img6.gif" width=2 height=1></td></tr></table></td></tr>';
	}
	content += '</table>'; 
    obj.innerHTML = content;
}

function CategoryBody_yellow_grey(id, href, caption, cat_id, first)
{
	obj = document.getElementById(id);
    if ( !obj ) return;
    content = '<TABLE border=0 cellpadding=0 cellspacing=0 style="cursor: pointer;"><TR>';
if(first)	{
	first_id = "spacer_first_" + cat_id;
	content += '<td width=3 align=right><img src="' + img_path + '/but.gif" width=3 height=26 border=0 id="' + first_id + '"></TD>';
}
    content += '<TD width=21><img src="'+img_path+'/spacer.gif" width=2 height=26 BORDER=0></TD>';
	link_id = "link_" + cat_id;
    content += '<TD nowrap><a href="'+static_catalog_name+href+'" class="FlyoutRootItemsOut" onClick="this.blur();" id="' + link_id + '">'+caption+'</a></TD>';
    content += '<TD width=21 align=right><img src="'+img_path+'/spacer.gif" width=2 height=26 border=0></TD>';
	img_id = "spacer_" + img_ids[cat_id];
    content += '<TD width=3 align=right><img src="' + img_path + '/but.gif" width=3 height=26 border=0 id="' + img_id + '"></TD></TR></TABLE>';
    obj.innerHTML = content;
}

function SubCategoryBody_yellow_grey(id, href, caption, show_arrow, iconUrl, showIcon, cat_id, is_first, is_last, arrow)
{
	obj = document.getElementById(id);
    if ( !obj ) return;
	img1_id = "img1_" + cat_id;
	link_id = "link_" + cat_id;
	img2_id = "img2_" + cat_id;
	space_id = "space_" + cat_id;
	bottom1_id = "bottom1_" + cat_id;
	bottom2_id = "bottom2_" + cat_id;

	content = '<table border=0 cellpadding=0 cellspacing=0 width="100%">';
	if(is_first)	{
		if(is_last)
			content += '<tr><td width=26><img src="' + img_path + '/img7.gif" width=26 height=23 id="' + img1_id + '"></td>';
		else
			content += '<tr><td width=26><img src="' + img_path + '/img1_first.gif" width=26 height=23 id="' + img1_id + '"></td>';
	} else if(is_last)
		content += '<tr><td width=26><img src="' + img_path + '/img1_last.gif" width=26 height=23 id="' + img1_id + '"></td>';
	else
		content += '<tr><td width=26><img src="' + img_path + '/img1.gif" width=26 height=23 id="' + img1_id + '"></td>';

	content += '<td nowrap width="100%"><a href="'+static_catalog_name+href+'" class="FlyoutSubItemsOut" onClick="this.blur();" id="' + link_id + '">'+caption+'</a></td>';


	if(show_arrow && arrow)	{ 
		if(is_first)	{
			if(is_last)
				content += '<td width=26><img src="' + img_path + '/img8_arrow.gif" width=26 height=23 id="' + img2_id + '"></td></tr>';
			else
				content += '<td width=26><img src="' + img_path + '/img3_farr.gif" width=26 height=23 id="' + img2_id + '"></td></tr>';
		} else if(is_last)
			content += '<td width=26><img src="' + img_path + '/img3_larr.gif" width=26 height=23 id="' + img2_id + '"></td></tr>';
		else
			content += '<td width=26><img src="' + img_path + '/img3_arrow.gif" width=26 height=23 id="' + img2_id + '"></td></tr>';
	} else {
		if(is_first)	{
			if(is_last)
				content += '<td width=26><img src="' + img_path + '/img8.gif" width=26 height=23 id="' + img2_id + '"></td></tr>';
			else 
				content += '<td width=26><img src="' + img_path + '/img3_first.gif" width=26 height=23 id="' + img2_id + '"></td></tr>';
		} else if(is_last)
			content += '<td width=26><img src="' + img_path + '/img3_last.gif" width=26 height=23 id="' + img2_id + '"></td></tr>';
		else
			content += '<td width=26><img src="' + img_path + '/img3.gif" width=26 height=23 id="' + img2_id + '"></td></tr>';
	}

	if(!is_last)	{
		content += '<tr><td colspan=3><table width="100%" cellspacing=0 cellpadding=0 border=0><tr>';
		content += '<td width=2><img src="' + img_path + '/img5.gif" width=2 height=1></td>';
		content += '<td class="Bottom1" id="' + space_id + '"><img src="'+img_path+'/spacer.gif" width=1 height=1></td>';
		content += '<td width=2><img src="' + img_path + '/img6.gif" width=2 height=1></td></tr></table></td></tr>';
	}
	content += '</table>'; 
    obj.innerHTML = content;
}

//*********************************************************************
