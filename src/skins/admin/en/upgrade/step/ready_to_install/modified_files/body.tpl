{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Info about modified files
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}

<div class="modified-files-section">
<div class="modified-files-section-frame">

  <div class="header">{t(#Some files are modified#)}</div>

  <div class="description">
    {t(#The system has detected that some custom modifications were made by editing core and 
    module files, not by writing new modules. In order to proceed with the upgrade you should decide
    what to do with the modifications#)}:
  </div>

  <ul class="selections-list">
    <li>
      <label>
        <input type="radio" name="" value="" />
        {t(#Replace all modified files with the unmodified newer versions#)}
      </label>
      <div class="warning">({t(#the custom modifications will be lost#)})</div>
    </li>
    <li>
      <label>
        <input type="radio" name="" value="" />
         {t(#Replace only selected files and keep the files which are not selected#)}
      </label>
      <div class="warning">({t(#your web site may crash#)})</div>
    </li>
  </ul>

  <ul class="select-actions">
    <li><a href="javascript:void(0);" class="select-all">{t(#Select all#)}</a></li>
    <li class="separator">|</li>
    <li><a href="javascript:void(0);" class="unselect-all">{t(#Unselect all#)}</a></li>
  </ul>

  <div class="clear"></div>
  
  <ul class="modified-files-group-list">
    <li class="modified-files-block">
      
      <div class="modified-files-group" FOREACH="getCustomFiles(),entry,files">
        <div class="modified-file" FOREACH="files,file,status">
          <label>
            <input type="checkbox" value="{#1#}" checked="{status}" name="toOverwrite[{file}]" />
            {file}
          </label>
        </div>
      </div>
      
    </li>
    <li class="switch-button-block">
      <widget class="\XLite\View\Button\SwitchButton" first="makeSmallHeight" second="makeLargeHeight" />
    </li>
  </ul>
  
  <div class="clear"></div>
  
</div>
</div>