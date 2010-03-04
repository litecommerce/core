{* SVN $Id$ *}

<form action="{getFormAction()}" method="{getAttributes(#form_method#)}" name="{getFormName()}" onsubmit="javascript: {getJSOnSubmitCode()}">
<fieldset>
  <input FOREACH="getFormParams(),paramName,paramValue" type="hidden" name="{paramName}" value="{paramValue}" />
</fieldset>

