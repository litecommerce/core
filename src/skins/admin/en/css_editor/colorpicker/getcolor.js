//
// cross-browser schna
//
function setClip(layer, l, r, t, b) {
  if(isNav4) {
    layer.clip.left = l; layer.clip.right = r;
    layer.clip.top = t;  layer.clip.bottom = b;
  } else {
    layer.style.pixelWidth = r-l;
    layer.style.pixelHeight = b-t;
    layer.style.clip = "rect("+t+","+r+","+b+","+l+")";
  }
}
function setClipHeight(layer, h) {
  isNav4? layer.clip.height = h : layer.style.pixelHeight = h;
}
function setClipWidth(layer, w) {
  if(isNav4) layer.clip.width = w;
  else {
    layer.style.pixelWidth = w;
    setClip(layer, 0, layer.style.pixelWidth,
                   0, layer.style.pixelHeight);
  }
}
function setLeft(layer, l) {
  isNav4? layer.left = l : layer.style.pixelLeft = l;
}
function setTop(layer, t) {
  isNav4? layer.top = t : layer.style.pixelTop = t;
}
function setVisibility(layer, v) {
  isNav4? layer.visibility = v : layer.style.visibility = v;
}
function setZIndex(layer, z) {
  isNav4? layer.zIndex = z : layer.style.zIndex = z;
}
function getLeft(layer) {
  return isNav4? layer.left : layer.style.pixelLeft;
}
function getTop(layer) {
  return isNav4? layer.top : layer.style.pixelTop;
}
function setLayerBgcolor(layer, b) {
  isNav4? layer.bgColor = b : layer.style.backgroundColor = b;
}

function getHSV() {
  var ar = new Array(3);
  ar.h = 360-getLeft(document.all.thumbH)+14;
  ar.s = (getTop(document.all.thumbS)-65)/150;
  ar.v = (getTop(document.all.thumbV)-65)/150;
  if(getTop(document.all.thumbS) == 214) ar.s = 1;
  if(getTop(document.all.thumbV) == 214) ar.v = 1;
  return ar;
}
function getHLS() {
  var ar = new Array(3);
  ar.h = 360-getLeft(document.all.thumbH)+14;
  ar.l = (getTop(document.all.thumbL)-65)/150;
  ar.s = (getTop(document.all.thumbS2)-65)/150;
  if(getTop(document.all.thumbL) == 214) ar.l = 1;
  if(getTop(document.all.thumbS2) == 214) ar.s = 1;
  return ar;
}
function getRGB() {
  var ar = new Array(3);
  ar.r = Math.round((getTop(document.all.thumbR)-35)/180*255);
  if(getTop(document.all.thumbR) == 214) ar.r = 255;
  ar.g = Math.round((getTop(document.all.thumbG)-35)/180*255);
  if(getTop(document.all.thumbG) == 214) ar.g = 255;
  ar.b = Math.round((getTop(document.all.thumbB)-35)/180*255);
  if(getTop(document.all.thumbB) == 214) ar.b = 255;
  return ar;
}
function setHLS(h,l,s,format) {
  if(format != 0 && h != -1) setLeft(document.all.thumbH, (360-h)+14);
  if(l != -1) setTop(document.all.thumbL, Math.round(l*150)+65);
  if(s != -1) setTop(document.all.thumbS2, Math.round(s*150)+65);
}
function setHSV(h,s,v,format) {
  if(format != 1 && h != -1) setLeft(document.all.thumbH, (360-h)+14);
  if(s != -1) setTop(document.all.thumbS, Math.round(s*150)+65);
  if(v != -1) setTop(document.all.thumbV, Math.round(v*150)+65);
}
function setRGB(r,g,b) {
  if(r != -1) setTop(document.all.thumbR, Math.round(r/255*180)+35);
  if(g != -1) setTop(document.all.thumbG, Math.round(g/255*180)+35);
  if(b != -1) setTop(document.all.thumbB, Math.round(b/255*180)+35);
}
