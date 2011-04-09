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
 * @ListChild (list="itemsList.module.manage.columns.module-main-section", weight="1000")
 *}

<script type="text/javascript">
  depends[{module.getModuleId()}] = [];
  {foreach:module.getDependentModules(),k,m}
    {if:m.getEnabled()}
      depends[{module.getModuleId()}][{k}] = '{m.getModuleName()} ({t(#by#)} {m.getAuthorName()})';
    {end:}
  {end:}
</script>
