{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
{if:!getParam(%static::PARAM_FIELD_ONLY%)}
<td class="table-label {getFieldId()}-label">
  <label for="{getFieldId()}">{getParam(#label#)}:</label>
</td>
<td class="star">
  {if:getParam(#required#)}*{else:}&nbsp;{end:}
</td>
<td class="table-value {getFieldId()}-value">
{end:}
  <widget template="{getDir()}/{getFieldTemplate()}" />
{if:!getParam(%static::PARAM_FIELD_ONLY%)}
  <div class="form-field-comment {getFieldId()}-comment">{getParam(#comment#):r}</div>
  <script IF="getInlineJSCode()" type="text/javascript">{getInlineJSCode():r}</script>
</td>
{end:}

