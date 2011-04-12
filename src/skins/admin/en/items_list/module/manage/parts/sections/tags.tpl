{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Modules list
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 * 
 * @ListChild (list="itemsList.module.manage.sections", weight="200")
 *}

<div class="tags">
  <div class="tags-title">{t(#Tags#)}</div>
  <ul class="tags-list">
    <li FOREACH="getTags(),tagId,label" class="{getTagClasses(tagId)}">
      <a href="{buildURL(#modules#,##,_ARRAY_(#tag#^tagId,#filter#^getFilter()))}">{t(label)}</a>
    </li>
  </ul>
</div>
