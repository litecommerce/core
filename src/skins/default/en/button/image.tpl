{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Image-based button
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}
<input type="image" src="images/spacer.gif" class="{getClass()}" value="{t(getButtonLabel())}" title="{t(getButtonLabel())}" onclick="javascript: {getJSCode():h}" disabled="{getParam(#disabled#)}" alt="{t(getButtonLabel())}" />
