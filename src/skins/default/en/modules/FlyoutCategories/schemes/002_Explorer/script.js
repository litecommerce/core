var index = 0;
var nodes_list = Array();
var imgs = Array();
var last_list = Array();

var rootMenuOffsetY = 0; 

var imgBeg1 = new Image;
var imgBeg2 = new Image;
var imgBeg3 = new Image;
var imgBeg4 = new Image;
var imgBeg5 = new Image;
var imgBegEnd = new Image;
var imgBegEnd2 = new Image;
var imgMid = new Image;
var imgMid2 = new Image;
var imgEnd = new Image;
var imgNeopen = new Image;
var imgNeopen2 = new Image;
var imgNclose = new Image;
var imgNopen = new Image;
var imgSp = new Image;

var imgIcon1 = new Image;
var imgIcon2 = new Image;
var imgIcon3 = new Image;
var imgIcon4 = new Image;


imgBeg1.src = img_path + '/beg1.gif';
imgBeg2.src = img_path + '/beg2.gif';
imgBeg3.src = img_path + '/beg3.gif';
imgBeg4.src = img_path + '/beg4.gif';
imgBeg5.src = img_path + '/beg5.gif';
imgBegEnd.src = img_path + '/beg_end.gif';
imgBegEnd2.src = img_path + '/beg_end2.gif';
imgMid.src = img_path + '/mid.gif';
imgMid2.src = img_path + '/mid2.gif';
imgEnd.src = img_path + '/end.gif';
imgNeopen.src = img_path + '/neopen.gif';
imgNeopen2.src = img_path + '/neopen2.gif';
imgNclose.src = img_path + '/nclose.gif';
imgNopen.src = img_path + '/nopen.gif';
imgSp.src = img_path + '/spacer.gif';

imgIcon1.src = img_path + '/../1icon.gif';
imgIcon2.src = img_path + '/../1icon_active.gif';
imgIcon3.src = img_path + '/../2icon_plus.gif';
imgIcon4.src = img_path + '/../2icon_active_plus.gif';

function CategoryBodyA(id, href, caption)
{
	document.write('<a href="'+static_catalog_name+href+'" class="FlyoutItems" id="link_'+id+'">'+caption+'</a>');
}

function displayObject(id, flag, num, last, bg)
{
    obj = document.getElementById('parent_'+id);
    if ( obj ) {
        obj.style.display = (flag) ? "" : 'none';

        imgobj = document.getElementById('node_'+id);
        if ( imgobj ) {
            imgobj.src = (flag) ? imgNopen.src : imgNclose.src;
        }

	elm = document.getElementById('img_'+id);
        if ( elm ) {
		if(last == "last")	{
			elm.src = (flag) ? imgBegEnd2.src : imgBegEnd.src;
		} else if (num == 1)	{
			elm.src = (flag) ? imgBeg4.src : imgBeg5.src;
		} else {
			elm.src = (flag) ? imgBeg1.src : imgBeg2.src;
		}
        }
	
	
    }
//	if (document.getElementById(bg) && last != "last")	{
	if (document.getElementById(bg) )	{
		setBGImage(document.getElementById(bg), (flag) ? imgMid2.src : imgSp.src);
	}
}

function displayObjectSelected(id, flag)
{
    obj = document.getElementById('parent_'+id);
	bg = imgs[id];
    var last = last_list[id];
    if ( obj ) {
        obj.style.display = (flag) ? "" : 'none';

        imgobj = document.getElementById('node_'+id);
        if ( imgobj ) {
            imgobj.src = (flag) ? imgNopen.src : imgNclose.src;
        }


	elm = document.getElementById('img_'+id);
        if ( elm ) {
		if(last == "last")
			elm.src = (flag) ? imgBegEnd2.src : imgBegEnd.src;
		else
			elm.src = (flag) ? imgBeg1.src : imgBeg2.src;
        }
    }
    elm2 = document.getElementById('img_sel_'+id);
    if ( elm2 ) {
         elm2.src = imgNeopen2.src;
    }


    elm3 = document.getElementById('link_'+id);
    if ( elm3 ) {
	elm3.className = 'FlyoutItemsSelected';
    }

//	if (document.getElementById(bg) && last != "last")	{
	if (document.getElementById(bg))	{
		setBGImage(document.getElementById(bg), (flag) ? imgMid2.src : imgSp.src);
	}

}

function switchCategory(id)
{
//	var last = last_list[id];
	var num = 0;
	var usenum = 'none';
	for (i in nodes_list) {
		num = num + 1;
		if(nodes_list[i] == id)
			usenum = num;
	}

	obj = document.getElementById('parent_'+id);
	if ( obj ) {
		displayObject(id, (obj.style.display == '') ?  false : true, usenum, last_list[id], imgs[id]);
	}
}

function displayAll(flag)
{
	var num = 1;
	for (id in nodes_list) {
		cur_id = nodes_list[id];
		displayObject(nodes_list[id], flag, num, last_list[cur_id], imgs[cur_id]);
		num = num + 1;
	}
}

function setFirstImg()
{
	if(nodes_list[0] != '')	{
		obj = document.getElementById(nodes_list[0]);
		if ( obj ) {
			elm = document.getElementById('img_'+nodes_list[0]);
			if ( elm )	{
				if(elm.src == imgBeg4.src)
					elm.src = imgBeg4.src;
				else
					elm.src = imgBeg5.src;
			}
	        }
	}
}

function setImageSrc(elmName, srcStr)
{
	var elm = document.getElementById(elmName);
	if (elm) {
		elm.src = srcStr;
	}
}

