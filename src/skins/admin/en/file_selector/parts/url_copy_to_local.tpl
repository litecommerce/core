{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="file_select_dialog.file_selections", weight="700")
 *}

 <li class="url-copy-to-local input-field">
   <label for="url-copy-to-local">
     <widget
       class="\XLite\View\FormField\Input\Checkbox"
       value="Y"
       isChecked="false"
       attributes={_ARRAY_(#disabled#^#disabled#)}
       fieldName="url_copy_to_local"
       fieldOnly="true" />
     <span class="label">{t(#Copy file to local server#)}</span>
   </label>
 </li>
