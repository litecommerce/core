{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Temporary warning about not tested controllers
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
{* FIXME - to remove *}
<div style="width: 100%; text-align: center;" IF="!isTested()">
  <img src="images/icon_warning.gif" alt="" />
  <strong>This controller is not working properly for current LC version</strong>
  <br /><br />
</div>
