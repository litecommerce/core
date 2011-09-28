{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Import
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.10
 *}
<div class="import">
  <div class="warning"><div>{t(#Importing will overwrite the existing product information. This operation connot be undone.#)}</div></div>
  <div class="note">
    {t(#To get the format of the import data, you can export your products to a file.#,_ARRAY_(#url#^buildURL(target,##,_ARRAY_(#page#^#export#)))):h}<br />
    {t(#Depending on the size of your data file, importing may take some time.#)}
  </div>

  <widget class="\XLite\View\Form\Import" name="import" />
    <widget
      class="\XLite\View\Button\FileSelector"
      style="action"
      label="Import from file"
      object="import" />
  <widget name="import" end />

  <div class="bottom-note">{t(#The importing starts immediately upon selecting the data file. No additional confirmation prompts will appear.#)}</div>
</div>
