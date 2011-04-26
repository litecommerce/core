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

{*<div IF="isCoreUpdateNeeded()">
  Core, {getCoreVersionCurrent()} --------&gt; {getCoreVersionForUpdate()}
</div>

<div FOREACH="getModulesForUpdate(),module">
  {module.getModuleName()}, {t(#by#)} {module.getAuthorName()},
  <span IF="getObjectField(getModuleInstalled(module),#getEnabled#)">{t(#enabled#)}</span>
  <span IF="getObjectField(getModuleInstalled(module),#getEnabled#)">{t(#disabled#)}</span>
  , {getObjectField(getModuleInstalled(module),#getVersion#)} --------&gt; {module.getVersion()}
</div>*}
