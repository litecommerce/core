{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Modules actions list
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="itemsList.module.manage.columns.actions", weight="30")
 *}
<a IF="showPHARPack()" href="{buildUrl(#modules#,#pack#,_ARRAY_(#moduleId#^module.getModuleId()))}">{t(#Pack it#)}</a>
<div IF="showPackWarning()" class="pack-warning">
{t(#Packing is disabled#)} <a href="#warn-pack" class="popup-link" rel="#warn-pack">{t(#Why#)}?</a>
</div>
<div class="pack-warn-clear"></div>
