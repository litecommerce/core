{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Modules list
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="itemsList.module.manage.sections", weight="200")
 *}

{* <div class="tags">
  <div class="tags-title">{t(#Tags#)}</div>
  <ul class="tags-list">
    <li FOREACH="getTags(),tagId,label" class="{getTagClasses(tagId)}">
      <a href="{buildURL(#addons_list_installed#,##,_ARRAY_(#tag#^tagId,#filter#^getFilter()))}">{t(label)}</a>
    </li>
  </ul>
</div> *}
