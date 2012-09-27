{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

{if:!getParam(#fieldOnly#)}
  <div class="{getLabelContainerClass()}">
    <label for="{getFieldId()}">{t(getParam(#label#))}:</label>
  </div>
{end:}

<div class="{getValueContainerClass()}">
  <widget template="{getDir()}/{getFieldTemplate()}" />
  <widget IF="getParam(#help#)" class="\XLite\View\Tooltip" text="{getParam(#help#)}" isImageTag=true className="help-icon" />
  <div IF="getParam(#comment#)" class="form-field-comment {getFieldId()}-comment">{t(getParam(#comment#)):r}</div>
  <script IF="getInlineJSCode()" type="text/javascript">{getInlineJSCode():r}</script>
</div>

{if:!getParam(#fieldOnly#)}
  <div IF="getParam(#required#)" class="star">*</div>
  <div IF="!getParam(#required#)" class="star">&nbsp;</div>
  <div class="clear"></div>
{end:}
