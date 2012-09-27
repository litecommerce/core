{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Link to the module
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="itemsList.module.manage.columns.module-main-section.cannot_enable.dependencies.details", weight="100")
 * @ListChild (list="itemsList.module.install.columns.module-main-section.cannot_enable.dependencies.details", weight="100")
 *}

<a{if:getDependencyHRef(depend)} href="{getDependencyHRef(depend)}"{end:}>{depend.getModuleName()} ({t(#by#)} {depend.getAuthorName()})</a>
