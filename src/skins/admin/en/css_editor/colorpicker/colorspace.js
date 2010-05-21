function HextoRGB(hex) {
  hex = hex.toUpperCase();
  if(hex.charAt(0) == "#") hex = hex.substring(1,hex.length);
  var rgb = new Array(3);
  rgb.r = hex.substring(0,2);
  rgb.g = hex.substring(2,4);
  rgb.b = hex.substring(4,6);
  rgb.r = parseInt(rgb.r,16);
  rgb.g = parseInt(rgb.g,16);
  rgb.b = parseInt(rgb.b,16);
  if(isNaN(rgb.r)) rgb.r = 0;
  if(isNaN(rgb.g)) rgb.g = 0;
  if(isNaN(rgb.b)) rgb.b = 0;
  return rgb;
}
function RGBtoHex(R, G, B) {
  var n = Math.round(B); 
  n += Math.round(G) << 8;
  n += Math.round(R) << 16;
  return DectoHex(n);
}
// turns decimal integer (e.g., bgColor) into hexadecimal string
function DectoHex(num) {
  var i = 0; var j = 20;
  var str = "#";
  while(j >= 0) {
    i = (num >> j)%16;
    if(i >= 10) {
      if(i == 10) str += "A";
      else if(i == 11) str += "B";
      else if(i == 12) str += "C";
      else if(i == 13) str += "D";
      else if(i == 14) str += "E";
      else str += "F";
    } else
      str += i;
    j -= 4;
  }
  return str;
}
function MIN() {
  var min = 255;
  for(var i = 0; i < arguments.length; i++)
    if(arguments[i] < min)
      min = arguments[i];
  return min;
}
function MAX() {
  var max = 0;
  for(var i = 0; i < arguments.length; i++)
    if(arguments[i] > max)
      max = arguments[i];
  return max;
}
function RGBtoCMYK(r,g,b) { // doesn't distort! not really usable...
  r /= 255;
  g /= 255;
  b /= 255;
  var cmyk = new Array(4);
  cmyk.c = Math.pow(1-r,.45);
  cmyk.m = Math.pow(1-g,.45);
  cmyk.y = Math.pow(1-b,.45);
  cmyk.k = MIN(cmyk.c,cmyk.y,cmyk.m);
  cmyk.c -= cmyk.k;
  cmyk.m -= cmyk.k;
  cmyk.y -= cmyk.k;
  return cmyk;
}
function RGBtoHSV(r,g,b) {
  r /= 255;
  g /= 255;
  b /= 255;
  var min, max, delta;
  var hsv = new Array(3);
  min = MIN(r,g,b);
  max = MAX(r,g,b);
  hsv.v = max;
  delta = max - min;
  if (max != 0) hsv.s = delta/max;
  else {
    hsv.s = .005;
    hsv.h = 0;
    return hsv;
  }
  if(delta == 0) {
    hsv.s = .005;
    hsv.h = 0;
    return hsv;
  }
  if (r == max) hsv.h = (g-b)/delta;
  else if(g == max) hsv.h = 2+(b-r)/delta;
  else hsv.h = 4+(r-g)/delta;
  hsv.h *= 60;
  if(hsv.h<0) hsv.h += 360;
  if(hsv.h>=360) hsv.h -= 360;
  return hsv;
}
function HSVtoRGB(h,s,v) {
  var rgb = new Array(3);
  var i;
  var f, p, q, t;
  if(s == 0) {
    rgb.r = rgb.g = rgb.b = v*255;
    return rgb;
  }
  h /= 60;
  i = Math.floor(h);
  f = h-i;
  p = v*(1-s);
  q = v*(1-s*f);
  t = v*(1-s*(1-f));
  switch(i) {
  case 0:
    rgb.r = v;
    rgb.g = t;
    rgb.b = p;
    break;
  case 1:
    rgb.r = q;
    rgb.g = v;
    rgb.b = p;
    break;
  case 2:
    rgb.r = p;
    rgb.g = v;
    rgb.b = t;
    break;
  case 3:
    rgb.r = p;
    rgb.g = q;
    rgb.b = v;
    break;
  case 4:
    rgb.r = t;
    rgb.g = p;
    rgb.b = v;
    break;
  default:
    rgb.r = v;
    rgb.g = p;
    rgb.b = q;
    break;
  }
  rgb.r *= 255;
  rgb.g *= 255;
  rgb.b *= 255;
  return rgb;
}
function RGBtoHLS(R,G,B) {
  R /= 255;
  G /= 255;
  B /= 255;
  var max, min,diff,r_dist,g_dist,b_dist;
  var hls = new Array(3);
  max = MAX(R,G,B);
  min = MIN(R,G,B);
  diff = max-min;
  hls.l = (max+min)/2;
  if (diff==0) {
    hls.h = 0;
    hls.s = 0;
  } else {
    if (hls.l<0.5) hls.s = diff/(max+min);
    else hls.s = diff/(2-max-min);      
    r_dist = (max-R)/diff;
    g_dist = (max-G)/diff;
    b_dist = (max-B)/diff;
    if (R == max) { hls.h = b_dist-g_dist; }
    else if (G == max) { hls.h = 2+r_dist-b_dist; }
    else if (B == max) { hls.h = 4+g_dist-r_dist; }
    hls.h *= 60;
    if (hls.h<0) hls.h += 360;
    if (hls.h>=360) hls.h -= 360;
  }
  return hls;
}
function RGB(q1,q2,hue) {
  if (hue>360) hue=hue-360;
  if (hue<0) hue=hue+360;
  if (hue<60) return (q1+(q2-q1)*hue/60);
  else if (hue<180) return(q2);
  else if (hue<240) return(q1+(q2-q1)*(240-hue)/60);
  else return(q1);
}
function HLStoRGB(H,L,S) {
  var p1,p2;
  var rgb = new Array(3);
  if (L<=0.5) p2=L*(1+S);
  else p2=L+S-(L*S);
  p1=2*L-p2;
  if (S==0) {
    rgb.r=L; 
    rgb.g=L;
    rgb.b=L;
  } else {
    rgb.r=RGB(p1,p2,H+120);
    rgb.g=RGB(p1,p2,H);
    rgb.b=RGB(p1,p2,H-120);
  }
  rgb.r *= 255;
  rgb.g *= 255;
  rgb.b *= 255;
  return rgb;
}
