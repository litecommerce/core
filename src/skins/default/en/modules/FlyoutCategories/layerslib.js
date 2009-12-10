/**
* @package FlyoutCategories
* @access public
* @version $Id: layerslib.js,v 1.4 2008/12/24 07:53:56 vgv Exp $
*/

InitEnvironment();

function InitEnvironment()
{
    isDOM = isDocW3C = (document.getElementById) ? true : false;
    isDocAll = (document.all) ? true : false;
    isOpera = isOpera5 = window.opera && isDOM;
    isOpera6 = isOpera && navigator.userAgent.indexOf("Opera 6") > 0 || navigator.userAgent.indexOf("Opera/6") >= 0;
    isOpera7 = isOpera && navigator.userAgent.indexOf("Opera 7") > 0 || navigator.userAgent.indexOf("Opera/7") >= 0;
	isOpera8 = isOpera && navigator.userAgent.indexOf("Opera 8") > 0 || navigator.userAgent.indexOf("Opera/8") >= 0;
	isOpera9 = isOpera && navigator.userAgent.indexOf("Opera 9") > 0 || navigator.userAgent.indexOf("Opera/9") >= 0;
    isMSIE = isIE = document.all && document.all.item && !isOpera;
    isNC = navigator.appName=="Netscape";
    isNC4 = isNC && !isDOM;
    isNC6 = isMozilla = isNC && isDOM;

    if ( !isDOM && !isNC && !isMSIE && !isOpera )
    {
        isLayers = false;
    }
    else
    {
        CONST_DOC_IMAGEPreloaderCount = 0;
        CONST_DOC_IMAGEPreloaderArray = new Array();

        CONST_DOC_IMAGERef = "document.images[\"";
        CONST_DOC_IMAGEPostfix = "\"]";
        DOC_styleSwitch = ".style";
        CONST_DOC_LAYERPostfix = "\"]";

        if ( isNC4 )
        {
            CONST_DOC_LAYERRef = "document.layers[\"";
            DOC_styleSwitch = "";
        }

        if ( isMSIE ) 
        {
            CONST_DOC_LAYERRef = "document.all[\"";
        }

        if ( isDOM )
        {
            CONST_DOC_LAYERRef = "document.getElementById(\"";
            CONST_DOC_LAYERPostfix = "\")";
        }

        isLayers = true;
    }

    isWindows = (navigator.appVersion.indexOf("Win") != -1);
    isWin95NT = (isWindows && (navigator.appVersion.indexOf("Win16") == -1 && navigator.appVersion.indexOf("Windows 3.1") == -1));
    isMac = (navigator.appVersion.indexOf("Mac") != -1);
    isMacPPC = (isMac && (navigator.appVersion.indexOf("PPC") != -1 || navigator.appVersion.indexOf("PowerPC") != -1));
    isUnix = (navigator.appVersion.indexOf("X11") != -1);

	isIE5forMac = (isMSIE && (isMacPPC || isMac) && navigator.userAgent.indexOf("MSIE 5."));
}

// document and window functions:

function getWindowLeft(w)
{
    if ( !w ) w = self;
    if ( isMSIE || isOpera7 || isOpera8 || isOpera9 ) return w.screenLeft;
    if ( isNC || isOpera ) return w.screenX;
}

function getWindowTop(w)
{
    if ( !w ) w = self;
    if ( isMSIE || isOpera7 || isOpera8 || isOpera9 ) return w.screenTop;
    if ( isNC || isOpera  ) return w.screenY;
}

function getWindowWidth(w)
{
    if ( !w ) w = self;
    if ( isMSIE  ) return w.document.body.clientWidth;
    if ( isNC || isOpera  ) return w.innerWidth;
}

function getWindowHeight(w)
{
    if ( !w ) w = self;
    if ( isMSIE  ) return w.document.body.clientHeight;
    if ( isNC || isOpera  ) return w.innerHeight;
}

function getDocumentWidth(w)
{
    if ( !w ) w = self;
    var d = w.document;
    if ( isMSIE || isOpera7 || isOpera8 || isOpera9 ) return d.body.scrollWidth;
    if ( isNC  ) return d.width;
    if ( isOpera5  ) return d.body.style.pixelWidth;
}

function getDocumentHeight(w)
{
    if ( !w ) w = self;
    var d = w.document;
    if ( isMSIE || isOpera7 || isOpera8 || isOpera9 ) return d.body.scrollHeight;
    if ( isNC  ) return d.height;
    if ( isOpera5  ) return d.body.style.pixelHeight;
}

function getScrollX(w)
{
    if ( !w ) w = self;
    if ( isMSIE || isOpera7 || isOpera8 || isOpera9 ) return w.document.body.scrollLeft;
    if ( isNC || isOpera5  ) return w.pageXOffset;
}

function getScrollY(w)
{
    if ( !w ) w = self;
    if ( isMSIE || isOpera7 || isOpera8 || isOpera9 ) return w.document.body.scrollTop;
    if ( isNC || isOpera5  ) return w.pageYOffset;
}

function preloadImage(imageFile)
{
    CONST_DOC_IMAGEPreloaderArray[CONST_DOC_IMAGEPreloaderCount] = new Image();
    CONST_DOC_IMAGEPreloaderArray[CONST_DOC_IMAGEPreloaderCount++].src = imageFile;
}

function DOC_getPageOffset(o)
{ 
    var DOC_left = 0;
    var DOC_top = 0;
    do
    {
        DOC_left += o.offsetLeft;
        DOC_top += o.offsetTop;
    }
    while ( o==o.offsetParent );
    return [DOC_left, DOC_top];
}

function DOC_findObject(what,where,type)
{
    var i,j,l,s;
    var len = eval(where+".length");

    for ( j=0; j<len; j++ )
    {
        s = where+"["+j+"].document.layers";
        if ( type==CONST_DOC_LAYER )
        {
            l = s+"[\""+what+"\"]";
        }
        if ( type==CONST_DOC_IMAGE )
        {
            i = where+"["+j+"].document.images";
            l = i+"[\""+what+"\"]";
        }
        if ( eval(l)  ) return l;

        l = DOC_findObject(what,s,type);
        if ( l != "null " ) return l;
    }

    return "null"
}

function DOC_getObjectPath(name,parent,type)
{
    var l= ((parent && isNC4)?(parent+"."):("")) + ((type==CONST_DOC_LAYER)?CONST_DOC_LAYERRef:CONST_DOC_IMAGERef) + name + ((type==CONST_DOC_LAYER)?CONST_DOC_LAYERPostfix:CONST_DOC_IMAGEPostfix);
    if ( eval(l) ) return l;
    
    if ( ! isNC4 )
    {
        return l;
    }
    else
    {
        return DOC_findObject(name,"document.layers",type);
    }
}

function DOC_Layer(name)
{
    return new cDOCLAYER(name,null);
}

function DOC_LayerFrom(name,parent)
{
    if ( parent.indexOf("document.")<0 ) parent = DOC_Layer(parent).path;
    return new cDOCLAYER(name,parent);
}

function DOC_Image(name)
{
    return new cDOCIMAGE(name,null);
}

function DOC_ImageFrom(name,parent)
{
    if ( parent.indexOf("document.")<0 ) parent = DOC_Layer(parent).path;
    return new cDOCIMAGE(name,parent);
}

// class "cDOCLAYER":

function cDOCLAYER(name,parent)
{
    this.path = DOC_getObjectPath(name,parent,CONST_DOC_LAYER);
    this.object = eval (this.path);
    if ( !this.object  ) return;
    this.style = this.css = eval(this.path+DOC_styleSwitch);
}

DOCLAYER=cDOCLAYER.prototype;

DOCLAYER.isExist = DOCLAYER.exists = function()
{
    return (this.object) ? true : false;
}

DOCLAYER.getLeft=function()
{
  var o = this.object;
  if ( isMSIE || isNC6 || isOpera ) return o.offsetLeft-pageLeft;
  if ( isNC4 ) return o.x-pageLeft;
}

DOCLAYER.getTop=function()
{
  var o = this.object;
  if ( isMSIE || isNC6 || isOpera ) return o.offsetTop-pageTop;
  if ( isNC4 ) return o.y-pageTop;
}

DOCLAYER.getAbsoluteLeft=function()
{
  var o = this.object;
  if ( isMSIE || isNC6 || isOpera ) return DOC_getPageOffset(o)[0]-pageLeft;
  if ( isNC4 ) return o.pageX-pageLeft;
}

DOCLAYER.getAbsoluteTop=function()
{
  var o = this.object;
  if ( isMSIE || isNC6 || isOpera ) return DOC_getPageOffset(o)[1]-pageTop;
  if ( isNC4 ) return o.pageY-pageTop;
}

DOCLAYER.getWidth=function()
{
  var o = this.object;
  if ( isMSIE || isNC6 || isOpera7 || isOpera8 || isOpera9 ) return o.offsetWidth;
  if ( isOpera5 ) return this.css.pixelWidth;
  if ( isNC4 ) return o.document.width;
}

DOCLAYER.getHeight=function()
{
  var o = this.object;
  if ( isMSIE || isNC6 || isOpera7 || isOpera8 || isOpera9 ) return o.offsetHeight;
  if ( isOpera5 ) return this.css.pixelHeight;
  if ( isNC4 ) return o.document.height;
}

DOCLAYER.getRight=function()
{
	return this.getLeft() + this.getWidth();
}

DOCLAYER.getBottom=function()
{
	return this.getTop() + this.getHeight();
}

DOCLAYER.getZIndex=function()
{
  return this.css.zIndex;
}

DOCLAYER.setLeft=DOCLAYER.moveX=function(x)
{
    x += pageLeft;
    if ( isOpera)
    {
        this.css.pixelLeft = x;
    }
    else if ( isNC4 )
    {
        this.object.x = x;
    }
    else
    {
        this.css.left = x + "px";
    }
}

DOCLAYER.setTop=DOCLAYER.moveY=function(y)
{
	y += pageTop;
    if ( isOpera )
    {
        this.css.pixelTop = y;
    }
    else if ( isNC4 )
    {
        this.object.y = y;
    }
    else
    {
        this.css.top = y + "px";
    }
}

DOCLAYER.moveTo=DOCLAYER.move=function(x,y)
{
    this.setLeft(x);
    this.setTop(y);
}

DOCLAYER.moveBy=function(x,y)
{
    this.moveTo(this.getLeft()+x,this.getTop()+y);
}

DOCLAYER.setZIndex=DOCLAYER.moveZ=function(z)
{
    this.css.zIndex = z;
}

DOCLAYER.setVisibility=function(v)
{
    this.css.visibility=(v)?(isNC4?"show":"visible"):(isNC4?"hide":"hidden");
}

DOCLAYER.show=function( )
{
    this.setVisibility(true);
}

DOCLAYER.hide=function( )
{
    this.setVisibility(false);
}

DOCLAYER.isVisible=DOCLAYER.getVisibility=function( )
{
    return (this.css.visibility.toLowerCase().charAt(0)=='h')?false:true;
}

DOCLAYER.setBgColor=function(c)
{
    if ( isMSIE || isNC6 || isOpera7 || isOpera8 || isOpera9 )
    {
        this.css.backgroundColor = c;
    }
    else if ( isOpera5 )
    {
        this.css.background = c;
    }
    else if ( isNC4 )
    {
        this.css.bgColor = c;
    }
}

DOCLAYER.setBgImage=function(url)
{
    if ( isMSIE || isNC6 || isOpera6 )
    {
        this.css.backgroundImage="url("+url+")";
    }
    else if ( isNC4 )
    {
        this.css.background.src = url;
    }
}

DOCLAYER.setClip=DOCLAYER.clip=function(top,right,bottom,left )
{
    if ( isMSIE || isNC6 || isOpera7 || isOpera8 || isOpera9 )
    {
        this.css.clip="rect("+top+"px "+right+"px "+bottom+"px "+left+"px)";
    }
    else if ( isNC4 )
    {
        var c    = this.css.clip;
        c.top    = top;
        c.right  = right;
        c.bottom = bottom;
        c.left   = left;
    }
}

DOCLAYER.scrollTo=DOCLAYER.scroll=function(windowLeft,windowTop,windowWidth,windowHeight,scrollX,scrollY )
{
    if ( scrollX>this.getWidth()-windowWidth ) scrollX = this.getWidth()-windowWidth;
    if ( scrollY>this.getHeight()-windowHeight ) scrollY = this.getHeight()-windowHeight;
    if ( scrollX<0 ) scrollX = 0;
    if ( scrollY<0 ) scrollY = 0;
    var top    = 0;
    var right  = windowWidth;
    var bottom = windowHeight;
    var left   = 0;
    left       = left + scrollX;
    right      = right + scrollX;
    top        = top + scrollY;
    bottom     = bottom + scrollY;
    this.moveTo(windowLeft-scrollX,windowTop-scrollY);
    this.setClip(top,right,bottom,left);
}

DOCLAYER.scrollBy=DOCLAYER.scrollByOffset=function(windowLeft,windowTop,windowWidth,windowHeight,scrollX,scrollY )
{
    /* KOI8-R comment:
    В некоторых случаях this.css.left может быть в виде "15px".
    Тесты показали, что функция parseInt корректно парсит такие 
    значения => никакие дополнительные телодвижения не нужны
    */    
    var X =- parseInt(this.css.left)+windowLeft+scrollX;
    var Y =- parseInt(this.css.top)+windowTop+scrollY;
    this.scroll(windowLeft,windowTop,windowWidth,windowHeight,X,Y);
}

DOCLAYER.scrollByPercentage=function(windowLeft,windowTop,windowWidth,windowHeight,scrollX,scrollY )
{
    var X = (this.getWidth()-windowWidth)*scrollX/100;
    var Y = (this.getHeight()-windowHeight)*scrollY/100;
    this.scroll(windowLeft,windowTop,windowWidth,windowHeight,X,Y);
}

DOCLAYER.write=function(str)
{
    var o = this.object;
    if ( isMSIE || isNC6 || isOpera7 || isOpera8 || isOpera9 )
    {
        o.innerHTML = str;
    }
    else if ( isNC4 || isOpera6 )
    {
        var d = o.document;
        d.open();
        d.write(str);
        d.close();
    }
}

DOCLAYER.add=function(str)
{
    var o = this.object;
    if ( isMSIE || isNC6 || isOpera7 || isOpera8 || isOpera9 )
    {
        o.innerHTML += str;
    }
    else if ( isNC4 || isOpera6 )
    {
        var d = o.document;
        d.write(str);
    }
}

// class "cDOCIMAGE":

DOCIMAGE=cDOCIMAGE.prototype

function cDOCIMAGE(name)
{
    this.path = DOC_getObjectPath(name,false,CONST_DOC_IMAGE);
    this.object = eval(this.path);
}

DOCIMAGE.isExist=DOCIMAGE.exists=function()
{
  return (this.object)?true:false;
}

DOCIMAGE.getSrc=DOCIMAGE.src=function()
{
    return this.object.src;
}

DOCIMAGE.setSrc=DOCIMAGE.load=function(url)
{
    this.object.src = url;
}
