{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Custom JS template
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

<widget class="\XLite\Module\XC\ThemeTweaker\View\Form\CustomJs" name="form" />

  <input type="checkbox" value="1"{if:config.XC.ThemeTweaker.use_custom_js} checked="checked"{end:} id="use_js" name="use" /> <label for="use_js">{t(#Use custom js#)}</label>
  <br /><br />

  <widget class="\XLite\Module\XC\ThemeTweaker\View\FormField\Textarea\CodeMirror" fieldName="code" cols="140" rows="20" fieldId="code" codeMode="javascript" value="{getFileContent()}" fieldOnly="true" />

  <div class="buttons">
    <widget class="\XLite\View\Button\Submit" style="action" label="Save" />
  </div>

<widget name="form" end />
