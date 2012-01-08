{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Form start
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}

<form action="{getFormAction()}" method="{getParam(#formMethod#)}" accept-charset="utf-8" onsubmit="javascript: {getJSOnSubmitCode()}"{if:getClassName()} class="{getClassName()}"{end:}{if:isMultipart()} enctype="multipart/form-data"{end:}>
<div class="form-params" style="display: none;">
  <input FOREACH="getFormParams(),paramName,paramValue" type="hidden" name="{paramName}" value="{paramValue}" />
</div>
