{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Modules main section list
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *
 * @ListChild (list="itemsList.module.manage.columns.module-main-section", weight="30")
 *}

<div IF="!canDisable(module)" class="note dependencies">

  {t(#Can't be disabled. The module is required by:#)}

  <ul>
    <li FOREACH="module.getDependentModules(),depend">
      <a href="#{depend.getName()}">{depend.getModuleName()} ({t(#by#)} {depend.getAuthorName()})</a>
    </li>
  </ul>

</div>
