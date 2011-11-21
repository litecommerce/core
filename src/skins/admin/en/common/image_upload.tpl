{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Image upload template
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}

<input type="file" name="{getNamePostedData(getParam(#field#))}" />&nbsp;&nbsp;&nbsp;
<widget class="\XLite\View\Button\Regular" IF="hasImage()" label="{t(#Delete#)}" jsCode="document.{formName}.{field}_delete.value='1';document.{formName}.action.value='{actionName}';document.{formName}.submit()" />
<input type="hidden" value="0" name="{getParam(#field#)}_delete" />

<br />

{*<input type="checkbox" name="{getParam(#field#)}_filesystem" value="1" checked="{isFS()}" /> Upload to file system*}

<br />
