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
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage View
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    GIT: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/*
 * Output the common HTML blocks
 */

if (!defined('XLITE_INSTALL_MODE')) {
    die('Incorrect call of the script. Stopping.');
}


function show_install_html_header() {

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>LiteCommerce v.<?php echo LC_VERSION; ?> <?php echo xtr('Installation Wizard'); ?></title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta http-equiv="Content-Script-Type" content="type/javascript" />
  <meta name="ROBOTS" content="NOINDEX" />
  <meta name="ROBOTS" content="NOFOLLOW" />
  <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />

<?php

}

function show_install_css() {

    global $skinsDir;

?>

  <style type="text/css">

/**
 * Clear styles
 */
 
html, body, div, span, applet, object, iframe, h1, h2, h3, h4, h5, h6, p, blockquote, pre, a, abbr, acronym, address, big, cite, code, del, dfn, em, font, img, ins, kbd, q, s, samp, small, strike, strong, sub, sup, tt, var, b, u, i, center, dl, dt, dd, ol, ul, li, fieldset, label, legend, caption, input, textarea
{
  margin: 0;
  padding: 0;
  border: 0;
  outline: 0;
}

ol, ul
{
  list-style: none;
}

blockquote, q
{
  quotes:none;
}

blockquote:before,
blockquote:after,
q:before,
q:after 
{
  content: '';
  content: none;
}

:focus
{
  outline:0;
}

a
{
  text-decoration: underline;
}


/**
 * Common styles
 */

body,
p,
div,
th,
td,
p,
input,
span,
textarea,
button
{
  color: #333333;
  font-size: 12px;
  font-family: Helvetica, Arial, Sans-serif; 
}

body
{
	background-color: #ffffff;
}

a
{
  color: #154e9c;
}

h1,
h2,
h3
{
  font-family: "Trebuchet MS", Helvetica, Sans-serif;  
  color: #69a4c9;
  font-weight: normal;
}

h1
{
  font-size: 30px;
  line-height: 36px;
  margin-bottom: 20px;
  margin: 10px 0 20px;
}

h2
{
  font-size: 24px;
  margin: 18px 0;
}

h3
{
  font-size: 18px;
  margin: 12px 0;
}

code {
    font-family: Arial, Helvetica, Sans-serif;  
    font-size: 14px;
    color: #106cb1;
}

/**
 * Form elements styles
 */

input[type=text],
input[type=password],
select,
textarea
{
  border-top: solid 1px #808080;
  border-left: solid 1px #808080;
  border-right: solid 1px #dfdfdf;
  border-bottom: solid 1px #dfdfdf;
  padding: 3px;
  background: #fff;
}

input[type=text],
input[type=password]
{
  height: 18px;
  line-height: 16px;
  font-size: 14px;
}

input[type=text]:focus,
input[type=password]:focus,
select:focus,
textarea:focus
{
  border: solid 1px #999;
  font-size: 14px;
}

select
{
  /* height: 24px;*/
  line-height: 24px;
}

input[type="submit"],
input[type="button"],
input[type="reset"],
button {
    -moz-border-radius: 19px;
    border-radius: 19px;
    -webkit-border-radius: 19px;
    background: url("<?php echo $skinsDir; ?>images/button_bg_light.png") repeat-x scroll center top #EFF8FE;
    border: 1px solid #CADCE8;
    color: #2C5FA6;
    cursor: pointer;
    font-family: "Trebuchet MS",Helvetica,Arial;
    font-size: 16px;
    font-weight: bold;
    line-height: 16px;
    margin: 7px 7px 7px 0;
    padding: 6px 15px;
}

input[type="submit"].small-button,
input[type="button"].small-button,
input[type="reset"].small-button,
button.small-button {
    cursor: pointer;
    font-family: "Trebuchet MS",Helvetica,Arial;
    font-size: 12px;
    font-weight: normal;
    line-height: 16px;
    margin: 10px 10px 10px 0;
    padding: 3px 18px;
}

input[type="submit"].next-button,
input[type="button"].next-button,
input[type="reset"].next-button,
button.next-button {
    margin-left: -20px;
}

input[type="submit"].disabled-button,
input[type="button"].disabled-button,
input[type="reset"].disabled-button,
button.disabled-button {
    background: #dfdfdf;
    border: 1px solid #dfdfdf;
    color: white;
    cursor: default;
}

button span
{
    color: #0e55a6;
    font-family: "Trebuchet MS",Helvetica,sans-serif;
    font-size: 15px;
    vertical-align: middle;
}

button:hover
{
  border-color: #b1c9e0;
}

button.main
{
  padding-left: 10px;
  padding-right: 10px;
}

button.main span
{
  font-size: 18px;
  line-height: 18px;
}

button.invert
{
  background: url(<?php echo $skinsDir; ?>images/button_bg_blue.png) repeat 0 0;
  border-color: transparent;
}

button.invert span
{
  color: #fff;
}

button.invert:hover
{
  background-color: transparent;
  background-image: url(<?php echo $skinsDir; ?>images/button_bg_blue_hover.png);
}

td.next-button-layer {
  background: url(<?php echo $skinsDir; ?>images/next_arrow.png) no-repeat 0 0;
  width: 151px;
  height: 114px;
}


/**
 * Layout
 */

html,
body
{
  min-width: 800px;
  height: 100%;
}

#content,
#sub-section,
#footer
{
  overflow: hidden;
}

#page-container {
  min-height: 100%;
  position: relative;
}

#page-container
{
  vertical-align: top;
  width: 100%;
}

#header {
  width: 100%;
  position: absolute;
  top: 0;
  left: 0;
}

#header, #menu
{
/*
  background: #0C263D url(<?php echo $skinsDir; ?>images/admin_header_bg.png) repeat-x left top;
  height: 76px;
*/
}

#header .logo
{
  background: url(<?php echo $skinsDir; ?>images/logo_admin.png) no-repeat 0 0;
  height: 70px;
  width: 82px;
  float: left;
}

.sw-version
{
  position: absolute;
  left: 90px;
  top: 12px;
  width: 870px;
}

.current
{
  color: #7f90A0;
  margin-right: 10px;
  font-size: 12px;
  display: inline;
}

.upgrade-note
{
  margin-right: 10px;
  text-align: right;
  float: right;
  white-space: nowrap;
}

.upgrade-note a {
    text-decoration: none;
    padding-left: 5px;
}

/**
 * Page content styles
 */
div.install-page
{
  width: 960px !important;
  margin: 0 auto;
}

div.install-page #header .logo {
  height: 70px;
  width: 82px;
}

div.install-page #header, #menu {
  background: transparent none;
}

div.install-page #header .sw-version {
  left: 100px;
}

div.install-page h1 {
  margin: 30px 0 0 100px;
  font-family: Arial,Verdana,sans-serif;
  font-size: 36px;
}

div.install-page #content {
  background: transparent none;
  border-top: 0 none;
}

div.content
{
  position: absolute;
  top: 160px;
  width: 100%;
  text-align: center;
}

#copyright_notice {
    border: 1px solid #999999;
    font-family: "Courier New", monospace;
    font-size: 14px;
    height: 400px;
    margin-bottom: 10px;
    margin-top: 10px;
    padding: 10px;
    overflow: auto;
    text-align: left;
    width: 938px;
}

.field-label {
  font-size: 14px;
  text-align: left;
  margin-right: 10px;
  color: #53769d;
  vertical-align: baseline;
}

.checkbox-field {
    font-size: 16px;
    text-align: left;
    color: #53769d;
    line-height: 1px;
    vertical-align: baseline;
}

.checkbox-field label
{
    display: inline;
    padding-left: 4px;
    white-space: nowrap;
}

.checkbox-field input
{
    vertical-align: middle;
}

.checkbox-field label span
{
    vertical-align: middle;
}

.field-notice {
  font-size: 12px;
  font-style: italic;
  text-align: left;
  color: #8f8f8f;
}

td.field-notice {
    padding-left: 10px;
    text-align: left;
}

/**
 * Common styles
 */

.status-ok {
  color: green;
}

.status-failed {
  color: #c11600;
}

.status-failed-link {
  color: #c11600;
  text-decoration: underline;
}

.status-failed-link-active {
  color: #c11600;
  text-decoration: none;
  cursor: default;
}

.status-skipped {
  color: #145d8f;
}

.status-already-exists {
  color: #145d8f;
}


/**
 * Requirements checking page styles
 */

.clear {
  clear: both;
}

div.requirements-report {
}

div.requirements-list {
  float: left;
  width: 60%;
}

div.requirements-notes {
  float: right;
  width: 40%;
}

div.section-title {
  color: #68a3c8;
  padding-top: 15px;
  padding-bottom: 5px;
  font-size: 16px;
  text-align: left;
}

div.list-row {
  text-align: left;
  font-size: 14px;
  padding-top: 4px;
  padding-left: 5px;
  padding-right: 15px;
  height: 20px;
}

.color-1 {
  background: #eeeeee;
}

.color-2 {
  background: white;
}

div.field-left {
  float: left;
  text-align: left;
  width: 70%;
}

div.field-right {
  float: right;
  text-align: right;
  width: 30%;
  white-space: nowrap;
}

.error-title {
  text-align: left;
  font-size: 16px;
  color: #c11600;
  padding-top: 15px;
  margin-left: 24px;
}

.error-text {
  text-align: left;
  padding-top: 10px;
  margin-left: 24px;
}

p {
  padding-bottom: 5px;
  padding-top: 5px;
}

div.requirements-warning-text {
  padding-top: 25px;
  padding-bottom: 25px;
  font-size: 12px;
  color: #333333;
}

div.status-report-box {
  border-style: solid;
  border-width: 10px;
  border-color: #eeead8;
  padding: 10px;
  margin-top: 15px;
  margin-left: 24px;
  text-align: left;
}

div.status-report-box-text {
  text-align: left;
  padding-bottom: 10px;
}

.link-expanded {
  margin-top: 4px;
  margin-right: -9px;
}

.requirements-success {
  padding-top: 45px;
  padding-left: 30px;
  text-align: center;
  font-family: Arial,Helvetica,sans-serif;
  font-size: 36px;
  color: #51924a;
}

.requirements-success-image {
  padding-left: 35px;
}

/**
 * Step bar styles definition
 */

div.steps-bar {
  position: absolute;
  top: 98px;
}

.steps {
    border-style: none;
    margin: 0;
    padding: 0;
}

.step-row {
	background: #66b4ef;
	float: left;
	list-style: none outside none;
	height: 40px;
	font-family: Arial,Verdana,sans-serif;
	font-size: 18px;
	color: white;
	line-height:37px;
	padding-left: 10px;
	padding-right: 10px;
}

.first {
	border-radius: 6px 0 0 6px;
	-moz-border-radius: 6px 0 0 6px;
	-webkit-border-radius: 6px 0 0 6px;
	padding-left: 20px;
}

.last {
	border-radius: 0 6px 6px 0;
	-moz-border-radius: 0 6px 6px 0;
	-webkit-border-radius: 0 6px 6px 0;
	padding-right: 20px;
}

.next {
	background: #dfdfdf;
}

.prev-prev {
	background: url(<?php echo $skinsDir; ?>images/arrow_blue.png) no-repeat scroll center center transparent;
}

.prev-next {
	background: url(<?php echo $skinsDir; ?>images/arrow_blue_grey.png) no-repeat scroll center center transparent;
}

.next-next {
	background: url(<?php echo $skinsDir; ?>images/arrow_grey.png) no-repeat scroll center center transparent;
}

/**
 * /end of step bar styles definition
 */

.full-width {
    width: 97%;
}

#process_iframe {
    padding-left: 15px;
    border: 1px solid black;
}

.keyhole-icon {
    margin-right: 50px;
}

a.final-link {
    font-size: 22px;
    text-decoration: underline;
    color: #144b9d;
}

.report-layer {
    background: url("<?php echo $skinsDir; ?>../../common/ui/images/popup_overlay.png") repeat scroll 50% 50% transparent;
    left: 0;
    position: absolute;
    top: 0;
    width: 900px;
    z-index: 1003;
    height: 100%;
    width: 100%;
}

.report-window {
    -moz-border-radius: 11px;
    border-radius: 11px;
    -webkit-border-radius: 11px;
    border: 10px solid #7a7a7a;
    background: white;
    width: 750px;
    margin: 60px auto;
    padding: 30px;
    z-index: 1004;
}

.report-title {
    font-size: 28px;
    color: #68a3c8;
}

textarea.report-details {
    font-family: "Courier New", monospace;
    font-size: 14px;
    height: 200px;
    width: 100%;
}

textarea.report-notes {
    height: 60px;
    width: 90%;
}

a.report-close {
    -moz-border-radius: 0 11px 11px 0;
    background: url("skins_original/admin/en/../../common/ui/images/icon_window_close.png") no-repeat scroll 10px 10px #7A7A7A;
    background: url("<?php echo $skinsDir; ?>../../common/ui/images/icon_window_close.png") no-repeat scroll 10px 10px #7A7A7A;
    display: block;
    height: 41px;
    margin-left: 780px;
    margin-top: -40px;
    outline-style: none;
    right: 0;
    top: 0;
    width: 40px;
    z-index: 1005;
}

.hidden {
    display: none;
}

.fatal-error,
.warning-text {
    -moz-border-radius: 9px;
    border-radius: 9px;
    -webkit-border-radius: 9px;
    border: 10px solid #7a7a7a;
    background: white;
    margin: 10px auto;
    padding: 20px;
    width: 500px;
    font-size: 16px;
    text-align: left;
}

.warning-text {
    color: #0e55a6;
}

.fatal-error {
    color: #c11600;
}

td.table-left-column {
    text-align: left;
    width: 70%;
}

td.table-right-column {
    text-align: left;
    width: 30%;
}

  </style>

<?php

}


