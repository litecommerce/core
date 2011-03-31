{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Modules main description section list
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *
 * @ListChild (list="itemsList.module.manage.columns.module-description-section", weight="20")
 * @ListChild (list="itemsList.module.install.columns.module-description-section", weight="20")
 *}

<div class="author">
  {t(#Author#)}:
  {if:module.getAuthorPageURL()}
    <a href="{module.getAuthorPageURL()}" target="_blank">{module.getAuthorName()}</a>
  {else:}
    {module.getAuthorName()}
  {end:}
</div>
