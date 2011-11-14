{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}

{if:!getParam(#fieldOnly#)}
  <div class="table-label {getFieldId()}-label">
    <label for="{getFieldId()}">{getParam(#label#)}:</label>
  </div>
  <div IF="getParam(#required#)" class="star">*</div>
  <div IF="!getParam(#required#)" class="star">&nbsp;</div>
{end:}

<div class="table-value {getFieldId()}-value">
  <widget template="{getDir()}/{getFieldTemplate()}" />
  <widget IF="getParam(#help#)" class="\XLite\View\Tooltip" text="{getParam(#help#)}" isImageTag=true className="help-icon" />
  <div IF="getParam(#comment#)" class="form-field-comment {getFieldId()}-comment">{getParam(#comment#):r}</div>
   <script IF="getInlineJSCode()" type="text/javascript">{getInlineJSCode():r}</script>
</div>

<div class="clear"></div>
