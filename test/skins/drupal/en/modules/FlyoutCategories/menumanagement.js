/**
* @package FlyoutCategories
* @access public
* @version $Id: menumanagement.js,v 1.9 2007/04/12 12:05:59 osipov Exp $
*/

var is_flat = false;
var is_candy = false;
var is_icons = false;

var CurrentMenuObj = null;

var closeId = null;
var openId = null;

var lastCategoryId = null;

/* // Should be defined in each scheme
var rootmenuOffsetX = 0;
var rootmenuOffsetY = -1;
var submenuOffsetX = 0;
var submenuOffsetY = 0;
//*/

var mouseX = 0;
var mouseY = 0;


function cPOPUPMENU(layerId, parentObj)
{
	this.layer = DOC_Layer(layerId);
	this.id = layerId;
	this.submenu = false;
	if (parentObj)
	{
		this.level = parentObj.level + 1;
		this.parent = parentObj;
	}
	else
	{
		this.level = 0;
		this.parent = false;
	}
}

POPUPMENU = cPOPUPMENU.prototype;

function POPUP_Menu(layerId,parentObj)
{
	return new cPOPUPMENU(layerId,parentObj);
}

function InitPopUp()
{
	if (!isLayers)
	{
		return;
	}

	if (isNC4)
	{
		document.captureEvents(Event.MOUSEMOVE);
	}

	if (isOpera)
	{
		document.onmousemove=function()
		{
			mouseX = event.clientX;
			mouseY = event.clientY;
			return true;
		}
	}

	menuPopupDelay = menuPopupDelay * 1000;
	menuPopupDelay = (!menuPopupDelay) ? 500 : menuPopupDelay; // If NaN, set default 0.5 sec

	menuCloseDelay = menuVisibleDelay * 1000;
	menuCloseDelay = (!menuCloseDelay) ? 500 : menuCloseDelay; // If NaN, set default 0.5 sec
}


function show_menu(newCategoryId, currentCategoryId)
{
	lastCategoryId = (newCategoryId) ? newCategoryId : currentCategoryId;

	cancelOpen();

	// Prevent catagory blinking and chain-brake
	if (menuCloseDelay >= menuPopupDelay && newCategoryId > 0)
	{
		cancelClose();
	}

	if (CurrentMenuObj && CurrentMenuObj.id == 'submenu_'+currentCategoryId)
	{
		cancelClose();
	}

	openId = setTimeout("show_menu_proc('"+newCategoryId+"', '"+currentCategoryId+"')", menuPopupDelay);
}

function show_menu_proc(newCategoryId, currentCategoryId)
{
	if (newCategoryId == 0)
	{
		return;
	}

	var ParentMenuObj = null;
	var MenuObj = POPUP_Menu('submenu_'+newCategoryId, CurrentMenuObj);

	// Close layer
	closeLayerRollBack(CurrentMenuObj, 'submenu_'+currentCategoryId);

	// prevent to using not loaded objects 
	if (MenuObj.layer.object == null)
		return;

	var offsetX = 0;
	var offsetY = 0;
	var parentMenuLayer = null;

	var bounced = false;

	if (currentCategoryId == 0)
	{
		// root/first category
		parentMenuLayer = DOC_Layer('cat_'+newCategoryId);

		if (layout == 'flat')
		{
			offsetX = ((dropDir == 'right') ? (parentMenuLayer.getLeft() + rootmenuOffsetX) : (parentMenuLayer.getRight() - rootmenuOffsetX - MenuObj.layer.getWidth()));
			offsetY = parentMenuLayer.getBottom() + rootmenuOffsetY;
		} else {

			if (dropLogic == 'smart')
			{
				if (dropDir == 'right' && (parentMenuLayer.getRight() + rootmenuOffsetX + MenuObj.layer.getWidth()) >= getWindowWidth())
				{
					dropDir = 'left';
					bounced = true;
				} else if (!bounced && dropDir == 'left' && (parentMenuLayer.getLeft() - rootmenuOffsetX - MenuObj.layer.getWidth()) <= 0)
					dropDir = 'right';
					bounced = true;
			}

			offsetX = ((dropDir == 'right') ? (parentMenuLayer.getRight() + rootmenuOffsetX) : (parentMenuLayer.getLeft() - rootmenuOffsetX - MenuObj.layer.getWidth()));
			offsetY = parentMenuLayer.getTop() + rootmenuOffsetY;
		}
	}
	else
	{
		// subcategory
		parentMenuLayer = DOC_Layer('submenu_'+currentCategoryId);
		var layerMenu = DOC_Layer('menu_'+newCategoryId);

		var localDropDir = dropDir;

		var bounceOffsetY = 0;
		if (dropLogic == 'smart')
		{
			if (dropDir == 'left' && (parentMenuLayer.getLeft() - MenuObj.layer.getWidth()) <= 0)
			{
				localDropDir = 'right';
				bounced = true;
			}
			if (!bounced && dropDir == 'right' && ((parentMenuLayer.getRight() + MenuObj.layer.getWidth()) >= getWindowWidth()))
			{
				localDropDir = 'left';
				bounced = true;
			}

			if (bounced)
				bounceOffsetY += (layerMenu.getHeight() * 0.75);
		}

		offsetX = ((localDropDir == 'right') ? (parentMenuLayer.getRight() + submenuOffsetX) : (parentMenuLayer.getLeft() - submenuOffsetX - MenuObj.layer.getWidth()));
		offsetY = parentMenuLayer.getTop() + layerMenu.getTop() + submenuOffsetY + bounceOffsetY;
	}


	if (!MenuObj.layer.isVisible())
	{
		MenuObj.layer.moveTo(offsetX, offsetY);
		MenuObj.layer.show();
	}

	CurrentMenuObj = MenuObj;
}

function close_menu()
{
	lastCategoryId = null;
	cancelOpen();
	closeId = setTimeout("closeLayerRollBack()", menuCloseDelay);
}

function closeLayerRollBack(menuObj, stopLabel)
{
	if (!menuObj)
	{
		menuObj = CurrentMenuObj;
	}

	if (!stopLabel)
	{
		stopLabel = 'submenu_'+lastCategoryId;
	}

	while (menuObj)
	{
		if (menuObj.id == stopLabel)
		{
			break;
		}

		if (menuObj.layer.isVisible())
		{
			menuObj.layer.hide();
		}

		menuObj = menuObj.parent;
		CurrentMenuObj = menuObj;
	}
}

function cancelClose()
{
	if (closeId)
	{
		clearTimeout(closeId);
		closeId = null;
	}
}

function cancelOpen()
{
	if (openId)
	{
		clearTimeout(openId);
		openId = null;
	}
}



function isRealOverAction(e, _this)
{
	var dest = (e.relatedTarget) ? e.relatedTarget : e.fromElement;

	if (!findObjectRecursive(dest, _this))
	{
		return true;
	}

	return false;
}

function isRealOutAction(e, _this)
{
	var dest = (e.relatedTarget) ? e.relatedTarget : e.toElement;

	if (!findObjectRecursive(dest, _this))
	{
		return true;
	}

	return false;
}

function findObjectRecursive(node, obj)
{
	if (!node || node == null || node == undefined)
	{
		return false;
	}

	if (node == obj)
	{
		return true;
	}

	return findObjectRecursive(node.parentNode, obj);
}



function _is_number(a_string)
{
	tc = a_string.charAt(0);
	return (tc == "0" || tc == "1" || tc == "2" || tc == "3" || tc == "4" || tc == "5" || tc == "6" || tc == "7" || tc == "8" || tc == "9") ? true : false;
}

function change_class(id, ClassOut, ClassOver, type)
{
	if (document.getElementById(id))
	{
		var elm = document.getElementById(id);
		elm.className = ((type == "over") ? ClassOver : ClassOut);
	}
}

function change_image(id, src_out, src_over, type)
{
	if (document.getElementById(id))
	{
		var elm = document.getElementById(id);
		elm.src = ((type == "over") ? (img_path + '/' + src_over) : (img_path + '/' + src_out));
	}
}

function setBGImage(obj, url)
{
	if (isMSIE || isMozilla || isOpera6 || isOpera9)
	{
		obj.style.backgroundImage = "url("+url+")";
	}
	else if (isNN4)
	{
		obj.css.background.src = url;
	}
}

function set_bgimage(id, type, over, out)
{
	if (document.getElementById(id))
	{
		obj = document.getElementById(id);
		if (type == "over")
			setBGImage(obj, menuImages[over].src);
		else
			setBGImage(obj, menuImages[out].src);
	}
}

