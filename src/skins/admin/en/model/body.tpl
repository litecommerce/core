{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

<script IF="getTopInlineJSCode()" type="text/javascript">{getTopInlineJSCode():r}</script>
<widget template="{getDir()}/header.tpl" />

  <widget class="{getFormClass()}" name="{getFormName()}" />

    <div class="{getContainerClass()}">
      <widget template="{getDir()}/form_content.tpl" />
      <widget IF="!useButtonPanel()" template="{getDir()}/{getFormTemplate(#buttons#)}" />
      <widget IF="useButtonPanel()" class="{getButtonPanelClass()}" />
    </div>

  <widget name="{getFormName()}" end />

<widget template="{getDir()}/footer.tpl" />

<script IF="getBottomInlineJSCode()" type="text/javascript">{getBottomInlineJSCode():r}</script>
