{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

<ul class="tinymce-widget">
  <li class="textarea">
    {displayCommentedData(getTinyMCEConfiguration())}
    <textarea class="tinymce" {getAttributesCode():h}>{getValue()}</textarea>
  </li>
  <li class="button">
    <widget class="\XLite\View\Button\SwitchButton" first="makeTinyAdvanced" second="makeTinySimple" />
  </li>
</ul>
<div class="clear"></div>
