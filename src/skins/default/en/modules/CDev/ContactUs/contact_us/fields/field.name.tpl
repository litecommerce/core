{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Name 
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *
 * @ListChild (list="contact-us.send.fields", weight="100")
 *}

<div class="form-item">
  <label for="name">
    {t(#Your name#)}
    <span class="form-required" title="{t(#This field is required.#)}">*</span>
  </label>
  <widget class="XLite\View\FormField\Input\Text" fieldName="name" value="{getValue(#name#)}" fieldOnly="true" required="true" />
</div>
