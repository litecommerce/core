<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * LiteCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to licensing@litecommerce.com so we can send you a copy immediately.
 *
 * PHP version 5.3.0
 *
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

/*
 * Output a common Javascript block
 */

if (!defined('XLITE_INSTALL_MODE')) {
    die('Incorrect call of the script. Stopping.');
}

?>

<script type="text/javascript">
function visibleBox(id, status)
{
    var Element = document.getElementById(id);
    if (Element) {
        Element.style.display = ((status) ? "" : "none");
    }
}

var visibleBoxId = false;
function setBoxVisible(id)
{
    var box = document.getElementById(id);
    if (box) {
        if (box.style.display == "none") {
            if (visibleBoxId) {
                setBoxVisible(visibleBoxId);
            }
            box.style.display = "";
            visibleBoxId      = id;
        } else {
            box.style.display = "none";
            visibleBoxId      = false;
        }
    }
}

var failedCodes = new Array();
var isDOM = false;
var isDocAll = false;
var isDocW3C = false;
var isOpera = false;
var isOpera5 = false;
var isOpera6 = false;
var isOpera7 = false;
var isMSIE = false;
var isIE = false;
var isNC = false;
var isNC4 = false;
var isNC6 = false;
var isMozilla = false;
var isLayers = false;
isDOM = isDocW3C = (document.getElementById) ? true : false;
isDocAll = (document.all) ? true : false;
isOpera = isOpera5 = window.opera && isDOM;
isOpera6 = isOpera && navigator.userAgent.indexOf("Opera 6") > 0 || navigator.userAgent.indexOf("Opera/6") >= 0;
isOpera7 = isOpera && navigator.userAgent.indexOf("Opera 7") > 0 || navigator.userAgent.indexOf("Opera/7") >= 0;
isMSIE = isIE = document.all && document.all.item && !isOpera;
isNC = navigator.appName=="Netscape";
isNC4 = isNC && !isDOM;
isNC6 = isMozilla = isNC && isDOM;

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

function setLeft(elm, x)
{
    if ( isOpera)
    {
        elm.style.pixelLeft = x;
    }
    else if ( isNC4 )
    {
        elm.object.x = x;
    }
    else
    {
        elm.style.left = x;
    }
}

function setTop(elm, y)
{
    if ( isOpera )
    {
        elm.style.pixelTop = y;
    }
    else if ( isNC4 )
    {
        elm.object.y = y;
    }
    else
    {
        elm.style.top = y;
    }
}

function showDetails(code)
{
    if (code == "" && document.getElementById('test_passed_icon')) {
        document.getElementById('test_passed_icon').style.display = '';
        return;
    }

    failedCodes.push(code);
    var failedElementsIds = new Array("");
    var detailsElement = document.getElementById('detailsElement');
    var hiddenElement = document.getElementById(code);
    var failedElement = document.getElementById('failed-' + code);
    var failedImageElement = document.getElementById('failed-image-' + code);

    if (hiddenElement) {
        detailsElement.innerHTML = hiddenElement.innerHTML;

    } else {
        detailsElement.innerHTML = '';
    }

    // failedElement.style.textDecoration = '';
    failedElement.className = 'status-failed-link-active';

    failedImageElement.style.display = 'inline';

    for (var i = 0; i < failedCodes.length; i++) {
        if (failedCodes[i] != code) {
            failedElement = document.getElementById('failed-' + failedCodes[i]);
            if (failedElement) {
                // failedElement.style.textDecoration = 'underline';
                failedElement.className = 'status-failed-link';
            }
            failedImageElement = document.getElementById('failed-image-' + failedCodes[i]);
            if (failedImageElement) {
                failedImageElement.style.display = 'none';
            }

        }
    }

}
</script>
