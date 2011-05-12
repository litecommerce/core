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
  
  <div class="modified-files-group" FOREACH="getCustomFiles(),entry,files">
    <div class="modified-file" FOREACH="files,file,status">
      <label>
      <input type="checkbox" value="{#1#}" checked="{status}" name="toOverwrite[{file}]" />
      {file}
      </label>
    </div>
  </div>
  
</div>
</div>