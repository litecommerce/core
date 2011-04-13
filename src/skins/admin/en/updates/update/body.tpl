{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Updates
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}

{* :TODO: integrate design *}
{* :TODO: of course, devide it into parts (lists) *}

<widget class="\XLite\View\Form\Updates\Update\Main" name="install_updates_form" />

  <div IF="isCoreUpdateNeeded()">
    Core, {getCoreVersionForUpdate()}
  </div>

  <div FOREACH="getModulesForUpdate(),module">
    {module.getModuleName()}, {t(#by#)} {module.getAuthorName()}, 
    <span IF="getObjectField(getModuleInstalled(module),#getEnabled#)">{t(#enabled#)}</span>
    <span IF="!getObjectField(getModuleInstalled(module),#getEnabled#)">{t(#disabled#)}</span>
    , {module.getVersion()}
  </div>

  <widget class="\XLite\View\Button\Submit" label="Install updates" />

<widget name="install_updates_form" end />
