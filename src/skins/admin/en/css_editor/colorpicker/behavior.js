///////////////////////////////////////////////////////////////////////
// JavaScript DHTML Utilies
// (c) 1998 Netscape Communications Corporation
// Written by Michael Bostock (mikebos@netscape.com)
///////////////////////////////////////////////////////////////////////

var isNav4, isIE4;
if (parseInt(navigator.appVersion.charAt(0)) >= 4) {
  isNav4 = (navigator.appName == "Netscape") ? true : false;
  isIE4 = (navigator.appName.indexOf("Microsoft") != -1) ? true : false;
}

///////////////////////////////////////////////////////////////////////
// Helper functions used by this library

function _contains(outerlayer, innerlayer) {
  if(isNav4) {
    if(innerlayer.left < outerlayer.left) return false;
    if(innerlayer.top < outerlayer.top) return false;
    if(innerlayer.left + innerlayer.clip.width >
	outerlayer.left + outerlayer.clip.width) return false;
    if(innerlayer.top + innerlayer.clip.height >
	outerlayer.top + outerlayer.clip.height) return false;
    return true;
  } else if(isIE4) {
    if(innerlayer.style.pixelLeft < outerlayer.style.pixelLeft)
      return false;
    if(innerlayer.style.pixelTop < outerlayer.style.pixelTop)
      return false;
    if(innerlayer.style.pixelLeft + innerlayer.style.pixelWidth >
       outerlayer.style.pixelLeft + outerlayer.style.pixelWidth)
      return false;
    if(innerlayer.style.pixelTop + innerlayer.style.pixelHeight >
       outerlayer.style.pixelTop + outerlayer.style.pixelHeight)
      return false;
    return true;
  }
}

///////////////////////////////////////////////////////////////////////
// The Behavior Object

function Behavior(drag) {
  this.mouseDownAction = null;
  this.mouseUpAction = null;
  this.mouseMoveAction = null;
  this.mouseOverAction = null;
  this.mouseOutAction = null;
  this.draggable = drag;
  this.setAction = setAction;
  this.applyBehavior = applyBehavior;
  this.hLock = false;
  this.vLock = false;
  this.useBounds = false;
  this.setBounds = setBounds;
  this.removeBounds = removeBounds;
  this.bounds = false;
  this.containers = false;
  this.addContainer = addContainer;
  this.update = _updateAll;
}

///////////////////////////////////////////////////////////////////////
// Behaviors Object: update

function _updateAll(doc) {
  if(!doc) doc = document;
  if(isNav4) {
    for(var i = 0; i < doc.layers.length; i++) {
      if(doc.layers[i].document.layers)
	this.update(doc.layers[i].document);
      if(doc.layers[i].behavior &&
	 doc.layers[i].behavior == this)
	this.applyBehavior(doc.layers[i]);
    }
  } else if(isIE4) {
    for(var i = 0; i < doc.all.length; i++) {
      if(doc.all[i].behavior &&
	 doc.all[i].behavior == this)
	this.applyBehavior(doc.all[i]);
    }
  }
}

///////////////////////////////////////////////////////////////////////
// Behaviors Object: containers

function addContainer(layer) {
  if(!this.containers) this.containers = new Array();
  this.containers[this.containers.length] = layer;
}

function removeContainer(layer) {
  for(var i = 0; i < this.containers.length; i++)
    if(this.containers[i] == layer)
      this.containers[i] = null;
}

///////////////////////////////////////////////////////////////////////
// Behaviors Object: setting bounds for dragging

function setBounds(l, r, t, b) {
  this.useBounds = true;
  if(this.bounds == false) this.bounds = new Array(4);
  this.bounds[0] = l;
  this.bounds[1] = r;
  this.bounds[2] = t;
  this.bounds[3] = b;
}

function removeBounds() {
  this.useBounds = false;
}

///////////////////////////////////////////////////////////////////////
// Behaviors Object: setting action-event pairs

function setAction(action, func) {
  eval('switch(action) {'+
    'case "MOUSEDOWN": this.mouseDownAction = func; break;'+
    'case "MOUSEMOVE": this.mouseMoveAction = func; break;'+
    'case "MOUSEUP":   this.mouseUpAction = func;   break;'+
    'case "MOUSEOVER": this.mouseOverAction = func; break;'+
    'case "MOUSEOUT":  this.mouseOutAction = func;  break;'+
    'case "CONTAINERPUSH": this.containerPushAction = func; break;'+
    'case "CONTAINERPULL": this.containerPullAction = func; break;'+
  '}');
}

///////////////////////////////////////////////////////////////////////
// Behaviors Object: cross-browser helpers

function isShift(e) {
  if(window.event) return window.event.shiftKey;
  else return (e.modifiers & Event.SHIFT_MASK);
}

function isAlt(e) {
  if(window.event) return window.event.altKey;
  else return (e.modifiers & Event.ALT_MASK);
}

function isControl(e) {
  if(window.event) return window.event.ctrlKey;
  else return (e.modifiers & Event.CONTROL_MASK);
}

///////////////////////////////////////////////////////////////////////
// Behaviors Object: applyBehavior

function applyBehavior(layer) {
  layer.layerObject = true;
  layer.behavior = this;
  layer.draggable = this.draggable;
  if(layer.captureEvents) {
    layer.captureEvents(Event.MOUSEDOWN|Event.MOUSEUP|Event.MOUSEOVER|Event.MOUSEOUT);
    document.captureEvents(Event.MOUSEMOVE);
  }
  document.onmouseup = _clearDBJ;
  layer.onmousedown = _handleMouseDown;
  layer.onmouseup = _handleMouseUp;
  document.onmousemove = _handleMouseMove;
  layer.onmouseover = _handleMouseOver;
  layer.onmouseout = _handleMouseOut;
  layer.containers = this.containers;
  layer.containerPushAction = this.containerPushAction;
  layer.containerPullAction = this.containerPullAction;
  layer.vLock = this.vLock;
  layer.hLock = this.hLock;
  layer.bounds = new Array(4);
  layer.bounds[0] = this.bounds[0];
  layer.bounds[1] = this.bounds[1];
  layer.bounds[2] = this.bounds[2];
  layer.bounds[3] = this.bounds[3];
  layer.useBounds = this.useBounds;
  layer.mouseDownAction = this.mouseDownAction;
  
  if (layer._div)
  	layer._div.onmousedown=_handleMouseDown;
  layer.mouseUpAction = this.mouseUpAction;
  if (layer._div)
  	layer._div.onmouseup=_handleMouseUp;
  layer.mouseMoveAction = this.mouseMoveAction;
  if (layer._div)
  	layer._div.onmousemove=_handleMouseMove;
  layer.mouseOverAction = this.mouseOverAction;
  if (layer._div)
  	layer._div.onmouseover=_handleMouseOver;
  layer.mouseOutAction = this.mouseOutAction;
  if (layer._div)
  	layer._div.onmouseout=_handleMouseOut;
}

///////////////////////////////////////////////////////////////////////
// Behaviors Object: event handlers with routing/canceling

function _handleMouseOver(e) {
  if(isNav4) {
    if(e.target != this) {
      routeEvent(e);
      return;
    }
  } else {
    if(window.event.srcElement == this &&
       window.event.srcElement.tagName == "DIV")
      window.event.cancelBubble = true;
    else if(window.event.srcElement == this) return;
  }
  if(this.mouseOverAction) this.mouseOverAction(e, "mouseover");
}

function _handleMouseOut(e) {
  if(isNav4) {
    if(e.target != this) {
      routeEvent(e);
      return;
    }
  } else {
    if(window.event.srcElement == this &&
       window.event.srcElement.tagName == "DIV")
      window.event.cancelBubble = true;
    else if(window.event.srcElement == this) return;
  }
  if(this.mouseOutAction) this.mouseOutAction(e, "mouseout");
}

///////////////////////////////////////////////////////////////////////
// Behaviors Object: built-in dragging handlers

var _dbj = new Array(); // drag Object

function _handleMouseDown(e) {
  if(isNav4) {
    if(e.handled) return true;
  } else window.event.cancelBubble = true;
  
	var layer;
	if (isNav4) {
		if (!this.layerObject) {
			layer = document.layers[this.id];
		} else {
			layer = this;
		}	
	} else {
		layer = window.event.srcElement;
	}
  
  if(layer.mouseDownAction) layer.mouseDownAction(e, "mousedown");
  if(!layer.draggable) return true;
  if(layer.containers) {
    layer.wasContained = false;
    for(var i = 0; i < layer.containers.length; i++) {
      if(_contains(layer.containers[i], layer)) {
	layer.wasContained = layer.containers[i];
	break;
      }
    }
  }
  if(isNav4) {
    layer.offsetX = e.pageX - layer.left;
    layer.offsetY = e.pageY - layer.top;
  } else {
    layer.offsetX = window.event.clientX - layer.style.pixelLeft;
    layer.offsetY = window.event.clientY - layer.style.pixelTop;
  }
  _dbj.layer = layer;
  _dbj.indrag = true;
  if(isNav4) e.handled = true;
  return false;
}

function _handleMouseMove(e) {
  var ret = false;
  if(isNav4) {
    if(!_dbj.layer) {
		if (!this.layerObject) {
			_dbj.layer = document.layers[this.id];
		} else {
			_dbj.layer = this;
		}	
	}	
    if(e.handled) return false;
  } else window.event.cancelBubble = true;
  if(!_dbj.layer) {
    if(isIE4) if(window.event.srcElement.mouseMoveAction)
      window.event.srcElement.mouseMoveAction(e, "mousemove");
    return true;
  }
  if(_dbj.layer.mouseMoveAction){
    ret = _dbj.layer.mouseMoveAction(e, "mousemove");
  }	
  if(!_dbj.layer.draggable) return ret;
  if(!_dbj.indrag) return true;
  if(!_dbj.layer.vLock) {
    var dstY;
    if(isNav4) {
      dstY = (e.pageY - _dbj.layer.offsetY);
      if((_dbj.layer.useBounds &&
	  (dstY >= _dbj.layer.bounds[2]) &&
	  (dstY + _dbj.layer.clip.height <= _dbj.layer.bounds[3])) ||
	 !_dbj.layer.useBounds)
	_dbj.layer.top = dstY;
      else if(_dbj.layer.useBounds) {
	if(dstY < _dbj.layer.bounds[2])
	  _dbj.layer.top = _dbj.layer.bounds[2];
	else _dbj.layer.top = _dbj.layer.bounds[3] -
	       _dbj.layer.clip.height;
      }
    } else {
      dstY = (window.event.clientY - _dbj.layer.offsetY);
      if((_dbj.layer.useBounds &&
	  (dstY >= _dbj.layer.bounds[2]) &&
	  (dstY + _dbj.layer.style.pixelHeight <=
	   _dbj.layer.bounds[3])) ||
	 !_dbj.layer.useBounds)
	_dbj.layer.style.pixelTop = dstY;
      else if(_dbj.layer.useBounds) {
	if(dstY < _dbj.layer.bounds[2])
	  _dbj.layer.style.pixelTop = _dbj.layer.bounds[2];
	else _dbj.layer.style.pixelTop = _dbj.layer.bounds[3] -
	       _dbj.layer.style.pixelHeight;
      }
    }
  }
  if(!_dbj.layer.hLock) {
    var dstX;
    if(isNav4) {
      dstX = (e.pageX - _dbj.layer.offsetX);
      if((_dbj.layer.useBounds &&
	  (dstX + _dbj.layer.clip.width <= _dbj.layer.bounds[1]) &&
	  (dstX >= _dbj.layer.bounds[0])) ||
	 !_dbj.layer.useBounds)
	_dbj.layer.left = dstX;
      else if(_dbj.layer.useBounds) {
	if(dstX < _dbj.layer.bounds[0])
	  _dbj.layer.left = _dbj.layer.bounds[0];
	else _dbj.layer.left = _dbj.layer.bounds[1] -
	       _dbj.layer.clip.width;
      }
    } else {
      dstX = (window.event.clientX - _dbj.layer.offsetX);
      if((_dbj.layer.useBounds &&
	  (dstX + _dbj.layer.style.pixelWidth <= _dbj.layer.bounds[1]) &&
	  (dstX >= _dbj.layer.bounds[0])) ||
	 !_dbj.layer.useBounds)
	_dbj.layer.style.pixelLeft = dstX;
      else if(_dbj.layer.useBounds) {
	if(dstX < _dbj.layer.bounds[0])
	  _dbj.layer.style.pixelLeft = _dbj.layer.bounds[0];
	else _dbj.layer.style.pixelLeft = _dbj.layer.bounds[1] -
	       _dbj.layer.style.pixelWidth;
      }
    }
  }
  if(isNav4) e.handled = true;
  return false;
}

function _clearDBJ() {
  _dbj.indrag = false;
  _dbj.layer = null;
}

function _handleMouseUp(e) {
  if(isNav4) {
    if(e.handled) return;
  } else window.event.cancelBubble = true;
  if(!_dbj.layer) { // weren't just dragging
    if(this.mouseUpAction) this.mouseUpAction(e, "mouseup");
    return;
  }
  if(_dbj.layer.mouseUpAction) _dbj.layer.mouseUpAction(e, "mouseup");
  if(_dbj.layer.containers) {
    _dbj.layer.isContained = false;
    for(var i = 0; i < _dbj.layer.containers.length; i++) {
      if(_contains(_dbj.layer.containers[i], _dbj.layer)) {
	_dbj.layer.isContained = _dbj.layer.containers[i];
      }
    }
    if(_dbj.layer.wasContained != _dbj.layer.isContained) {
      if(_dbj.layer.containerPullAction && _dbj.layer.wasContained)
	_dbj.layer.containerPullAction(_dbj.layer.wasContained, "containerpull");
      if(_dbj.layer.containerPushAction && _dbj.layer.isContained)
	_dbj.layer.containerPushAction(_dbj.layer.isContained, "containerpush");
    }
  }
  _clearDBJ();
  if(isNav4) e.handled = true;
  return;
}

///////////////////////////////////////////////////////////////////////
// Behaviors Object: the end

