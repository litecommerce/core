/*
 * Layers.js
 * $Revision: 1.1 $ $Date: 2003/06/22 15:02:02 $
 */

/* ***** BEGIN LICENSE BLOCK *****
 * Version: MPL 1.1/GPL 2.0/LGPL 2.1
 *
 * The contents of this file are subject to the Mozilla Public License Version
 * 1.1 (the "License"); you may not use this file except in compliance with
 * the License. You may obtain a copy of the License at
 * http://www.mozilla.org/MPL/
 *
 * Software distributed under the License is distributed on an "AS IS" basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License
 * for the specific language governing rights and limitations under the
 * License.
 *
 * The Original Code is Netscape code.
 *
 * The Initial Developer of the Original Code is
 * Netscape Corporation.
 * Portions created by the Initial Developer are Copyright (C) 2002
 * the Initial Developer. All Rights Reserved.
 *
 * Contributor(s): Bob Clary <bclary@netscape.com>
 *
 * ***** END LICENSE BLOCK ***** */

/*
This script allow the limited emulation of the Netscape Navigator 4
Layer API in Mozilla.

Constructor createLayer(width, parentLayer)
Initializer initializeLayers()

Objects / Properties emulated

Layer.above				(not implemented)
Layer.below				(not implemented)
Layer.background		(read/write)
Layer.bgColor			(read/write)

Layer.clip				(read)
Layer.clip.top			(read/write)
Layer.clip.right		(read/write)
Layer.clip.bottom		(read/write)
Layer.clip.left			(read/write)
Layer.clip.width		(read/write)
Layer.clip.height		(read/write)

Layer.document			(read)
Layer.document.open
Layer.document.close
Layer.document.write
Layer.document.writeln
Layer.document.bgColor	(read/write)
Layer.document.layers	(read)
Layer.document.images	(read)

Layer.hidden			(read/write)
Layer.left				(read/write)
Layer.name				(read)
Layer.pageX				(read/write)
Layer.pageY				(read/write)
Layer.parentLayer		(read)
Layer.siblingAbove		(not implemented)
Layer.src				(not implemented)
Layer.top				(read/write)
Layer.visibility		(read/write)
Layer.zIndex			(read/write)


Methods
-------
Layer.captureEvents	(not implemented)
Layer.handleEvents	(not implemented)
Layer.load			(not implemented)
Layer.moveAbove		
Layer.moveBelow	
Layer.moveBy
Layer.MoveTo		(not implemented)
Layer.moveAbsolute
Layer.releaseEvents	(not implemented)
Layer.resizeBy
Layer.resizeTo
Layer.routeEvent	(not implemented)

*/

/* 
_Document Object used to emulated returned 
layer.document references

_Document._div reference to corresponding DIV
_Document.open
_Document.close
_Document.write
_Document.writeln
_Document.bgColor (read/write)
_Document.layers  (read)
_Document.images  (read)

*/

// return the effective CSS position property
function _getEffectiveStylePosition(elm)
{
  return  document.defaultView.getComputedStyle(elm, '').getPropertyValue('position');
}

function _Document(div)
{
	this._div = div;
}

_Document.prototype._div	= null;
_Document.prototype.open	= function () {};
_Document.prototype.close	= function () {};
_Document.prototype.write	= function (s) {this._div.innerHTML = s; };
_Document.prototype.writeln	= function (s) {this._div.innerHTML = s; };

_Document.prototype.__defineGetter__('bgColor', 
function()
{
	return this._div.style.backgroundColor;
}
);

_Document.prototype.__defineSetter__('bgColor', 
function(v)
{
	return this._div.style.backgroundColor = v;
}
);

_Document.prototype.__defineGetter__('layers', 
function()
{
	var i;
	var node;
	
	var layers = new Array();
  var pos;
	
	for (i = 0; i < this._div.childNodes.length; i++)
	{
		node = this._div.childNodes[i];
    pos = _getEffectiveStylePosition(node);
		
		if (node.nodeType == 1 && node.nodeName == 'DIV' && (pos == 'absolute' || pos == 'relative'))
			layers[layers.length] = new _Layer(node);
	}
	return layers;
}
);

function _GetContainedImages(node, list, count)
{
	var i;
	var child;

	for (i = 0; i < node.childNodes.length; i++)
	{
		child = node.childNodes[i];

		if (child.nodeName == 'IMG')
    {
			list[(count).toString()] = child;
      if (child.name)
      {
        list[child.name] = child;
      }
      ++count;
    }
		else // if (child.nodeName.nodeType == 1 && child.style.position != 'absolute')
			_GetContainedImages(child, list, count);
	}
	
}

_Document.prototype.__defineGetter__('images',
function()
{
  var count = 0;
	var hash = new Object();
	_GetContainedImages(this._div, hash, count);
	return hash;
}
);

function _ParseClipString(s)
{
	var ca		= new Array();
	var o		= new Object();
	
	o.top		= undefined;
	o.right		= undefined;
	o.bottom	= undefined;
	o.left		= undefined;
	
	if (s.indexOf('rect(') == 0)
	{
		ca = s.substring(5, s.length-1).split(' ');
		for (i = 0; i < 4; ++i)
		{
			val = parseInt(ca[i]);
			if (val != 0 && ca[i].indexOf('px') == -1)
				if (!confirm('A clipping region ' + a1 + ' was detected that did not use pixels as units.  Click Ok to continue, Cancel to Abort'))
					return;
			ca[i] = val;
		}
		o.top		= ca[0];
		o.right		= ca[1];
		o.bottom	= ca[2];
		o.left		= ca[3];
	}
	
	return o;
}

function _SetClipUndefines(o, div)
{
	if (typeof(o.top) == 'undefined')
		o.top = 0;
	
	if (typeof(o.right) == 'undefined')
		o.right =div.offsetWidth;
	
	if (typeof(o.bottom) == 'undefined')
		o.bottom = div.offsetHeight;
	
	if (typeof(o.left) == 'undefined')
		o.left = 0;
	
}


function _CreateClipString(o)
{
	var s = 'rect(' + o.top + 'px ' + o.right + 'px ' + o.bottom + 'px ' + o.left + 'px' + ')';
	return s;
}


function _Clip(div)
{
	this._div	= div;
}

_Clip.prototype._div			= null;

_Clip.prototype.__defineGetter__('top',		
function ()
{
	return _ParseClipString(this._div.style.clip).top;
});

_Clip.prototype.__defineSetter__('top',		
function (v)
{
	var o = _ParseClipString(this._div.style.clip);
	o.top = v;
	_SetClipUndefines(o, this._div);
	this._div.style.clip = _CreateClipString(o);
	return v;
}
);

_Clip.prototype.__defineGetter__('right',	
function ()
{
	return _ParseClipString(this._div.style.clip).right;
}
);

_Clip.prototype.__defineSetter__('right',	
function (v)
{
	var o = _ParseClipString(this._div.style.clip);
	o.right = v;
	_SetClipUndefines(o, this._div);
	this._div.style.clip = _CreateClipString(o);
	return v;
}
);

_Clip.prototype.__defineGetter__('bottom',	
function ()
{
	return _ParseClipString(this._div.style.clip).bottom;
}
);

_Clip.prototype.__defineSetter__('bottom',	
function (v)
{
	var o = _ParseClipString(this._div.style.clip);
	o.bottom = v;
	_SetClipUndefines(o, this._div);
	this._div.style.clip = _CreateClipString(o);
	return v;
});

_Clip.prototype.__defineGetter__('left',	
function ()
{
	return _ParseClipString(this._div.style.clip).left;
});

_Clip.prototype.__defineSetter__('left',	
function (v)
{
	var o = _ParseClipString(this._div.style.clip);
	o.left = v;
	_SetClipUndefines(o, this._div);
	this._div.style.clip = _CreateClipString(o);
	return v;
});

_Clip.prototype.__defineGetter__('width',	
function ()
{
	return this._div.offsetWidth;
});

_Clip.prototype.__defineSetter__('width',	
function (v)
{
	var o = _ParseClipString(this._div.style.clip);
	
	if (!o.left)
		o.left = 0;
		
	o.left = o.right + v;
	_SetClipUndefines(o, this._div);
	this._div.style.clip = _CreateClipString(o);
	return v;
});

_Clip.prototype.__defineGetter__('height',	
function ()
{
	return this._div.offsetHeight;
});

_Clip.prototype.__defineSetter__('height',	
function (v)
{
	var o = _ParseClipString(this._div.style.clip);
	
	if (!o.top)
		o.top = 0;
		
	o.bottom = o.top + v;
	_SetClipUndefines(o, this._div);
	this._div.style.clip = _CreateClipString(o);
	return v;
});

function __LayerGetElementTotalOffset(elm, propName)
{
	var v = elm[propName];
	var p = elm.parentNode;
	
	while (p)
	{
		v += p[propName];
		p = p.parentNode;
	}
	return v;
}

function _LayerCaptureEvents(eventTypeList)
{
	//throw 'not implemented';
}

function _LayerHandleEvent(event)
{
	throw 'not implemented';
}

function _LayerLoad(url, width)
{
	throw 'not implemented';
}

function _LayerMoveAbove(layer)
{
	this.zIndex = layer.zIndex + 1;
}

function _LayerMoveBelow(layer)
{
	this.zIndex = layer.zIndex - 1;
}

function _LayerMoveBy(x,y)
{
	this.left += x;
	this.top  += y;
}

function _LayerMoveTo(x,y)
{
	throw 'not implemented';
}

function _LayerMoveAbsolute(x,y)
{
	this.left = x;
	this.top = y;
}

function _LayerReleaseEvents(eventTypeList)
{
	throw 'not implemented';
}

function _LayerResizeBy(x,y)
{
	var o = _ParseClipString(this._div.style.clip);
	o.right += x;
	o.bottom += y;
	this._div.style.clip = _CreateClipString(o);
}

function _LayerResizeTo(x,y)
{
	var o = _ParseClipString(this._div.style.clip);
	o.right = x;
	o.bottom = y;
	this._div.style.clip = _CreateClipString(o);

}

function _LayerRouteEvent(event)
{
	throw 'not implemented';
}

function _Layer(div)
{
	this._div = div;
}

_Layer.prototype._div			= null;

_Layer.prototype.__defineGetter__('document',			
function ()
{
	return new _Document(this._div);
}
);


_Layer.prototype.__defineGetter__('above',			
function ()
{
	throw 'not implemented';
}
);

_Layer.prototype.__defineGetter__('below',			
function ()
{
	throw 'not implemented';
}
);

_Layer.prototype.__defineGetter__('background',		
function ()
{
	return this._div.style.backgroundImage;
}
);

_Layer.prototype.__defineSetter__('background',		
function (v)
{
	return this._div.style.backgroundImage = v;
}
);

_Layer.prototype.__defineGetter__('bgColor',		
function ()
{
	return this._div.style.backgroundColor;
}
);

_Layer.prototype.__defineSetter__('bgColor',		
function (v)
{
	return this._div.style.backgroundColor = v;
}
);

_Layer.prototype.__defineGetter__('clip',			
function ()
{
	return new _Clip(this._div);
}
);

_Layer.prototype.__defineGetter__('hidden',			
function ()
{
	return this._div.style.visibility != 'visible';
}
);

_Layer.prototype.__defineSetter__('hidden',			
function (v)
{
	switch(v)
	{
	case 'hide':
		this._div.style.visibility = 'hidden';
		break;
	case 'show':
		this._div.style.visibility = 'visible';
		break;
	}
	
	return v;
});

_Layer.prototype.__defineGetter__('left',			
function ()
{
	return this._div.offsetLeft;
});

_Layer.prototype.__defineSetter__('left',			
function (v)
{
	this._div.style.left = v + 'px';
}
);

_Layer.prototype.__defineGetter__('name',			
function ()
{
	return this._div.name;
}
);

_Layer.prototype.__defineGetter__('pageX',			
function ()
{
	return __LayerGetElementTotalOffset(this._div, propName)
}
);

_Layer.prototype.__defineSetter__('pageX',			
function (v)
{
	var parentPageX = 0;
	
	if (this._div.parentNode)
		parentPageX = __LayerGetElementTotalOffset(this._div.parentNode, 'offsetLeft');

	this._div.style.left = (v - parentPageX) + 'px';
}
);

_Layer.prototype.__defineGetter__('pageY',			
function ()
{
	var v = this._div.offsetTop;
	var p = this._div.parentNode;
	
	while (p)
	{
		v += p.offsetTop;
		p = p.parentNode;
	}
	return v;
}
);

_Layer.prototype.__defineSetter__('pageY',			
function (v)
{
	var parentPageY = 0;
	
	if (this._div.parentNode)
		parentPageY = __LayerGetElementTotalOffset(this._div.parentNode, 'offsetTop');

	this._div.style.top = (v - parentPageY) + 'px';
}
);

_Layer.prototype.__defineGetter__('parentLayer',	
function ()
{
	var p = this._div.parentNode;
  var pos;
	
	if (p)
	{
    pos = _getEffectiveStylePosition(p);
		while (p && (pos == 'absolute' || pos == 'relative'))
			p = p.parentNode;
	}

	if (!p)
		p = window;

	return p;
}
);

_Layer.prototype.__defineGetter__('siblingAbove',	
function ()
{
	throw 'not implemented';
}
);

_Layer.prototype.__defineGetter__('siblingBelow',	
function ()
{
	throw 'not implemented';
}
);

_Layer.prototype.__defineGetter__('src',			
function ()
{
	throw 'not implemented';
}
);

_Layer.prototype.__defineSetter__('src',			
function (v)
{
	throw 'not implemented';
}
);

_Layer.prototype.__defineGetter__('top',			
function ()
{
	return this._div.offsetTop;
}
);

_Layer.prototype.__defineSetter__('top',			
function (v)
{
	return this._div.style.top = v + 'px';
}
);

_Layer.prototype.__defineGetter__('visibility',		
function ()
{
	switch(this._div.style.visibility)
	{
	case 'hidden':
		return 'hide';
	case 'visible':
		return 'show';
	default:
		return '';
	}
}
);

_Layer.prototype.__defineSetter__('visibility',		
function (v)
{
	switch(v)
	{
	case 'hide':
	case 'hidden':
		this._div.style.visibility = 'hidden';
		break;
	case 'show':
	case 'visible':
		this._div.style.visibility = 'visible';
		break;
	default:
		this._div.style.visibility = '';
		break;
	}
		
	return v;
}
);

_Layer.prototype.__defineGetter__('zIndex',			
function ()
{
	return this._div.style.zIndex;
});

_Layer.prototype.__defineSetter__('zIndex',			
function (v)
{
	return this._div.style.zIndex = v;
}
);

_Layer.prototype.captureEvents	= _LayerCaptureEvents;
_Layer.prototype.handleEvent	= _LayerHandleEvent;
_Layer.prototype.load			= _LayerLoad;
_Layer.prototype.moveAbove		= _LayerMoveAbove;
_Layer.prototype.moveBelow		= _LayerMoveBelow;
_Layer.prototype.moveBy			= _LayerMoveBy;
_Layer.prototype.moveTo			= _LayerMoveTo;
_Layer.prototype.moveAbsolute	= _LayerMoveAbsolute;
_Layer.prototype.releaseEvents	= _LayerReleaseEvents;
_Layer.prototype.resizeBy		= _LayerResizeBy;
_Layer.prototype.resizeTo		= _LayerResizeTo;
_Layer.prototype.routeEvent		= _LayerRouteEvent;

HTMLDocument.prototype.layers = new Array();
var __Layers = new Array();

function createLayer(width, parentLayer)
{
	var layer;
	var parentElement;
	var div = document.createElement('div');
	
	div.style.position = 'absolute';
	if (width)
		div.style.width = width + 'px';
	
	if (parentLayer)
		parentElement = parentLayer._div;
	else
		parentElement = document.body;

	parentElement.appendChild(div);
	
	layer = new _Layer(div);
	
	if (parentLayer)
		parentLayer.document.layers[parentLayer.document.layers.length] = layer;
	else
		document.layers[document.layers.length] = layer;
	
	return layer;
}

function initializeLayers()
{
	var i;
	var layer;
	var list = document.getElementsByTagName('div');
  var pos;

	for (i = 0; i < list.length; i++)
	{
    pos = _getEffectiveStylePosition(list[i])
		if (pos == 'absolute')
		{
			layer = new _Layer(list[i]);
			document.layers[document.layers.length] = layer;
			//
			document.layers[layer._div.id] = layer;
			__Layers[layer._div.id] = layer;
			//
			document[list[i].id] = layer;
		}
	}
}


function getLayerByDiv(divId)
{
	for (i in document.layers)
	{
		text += i + "\n";
		if (i == divId) {
			return __Layers[i];
		}
	}
	return null;
}
