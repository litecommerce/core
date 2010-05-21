{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}

<div id="{getBlockId()}">

  <a href="#" class="close" onclick="javascript: document.getElementById('{getBlockId()}').style.display = 'none';">&nbsp;</a>

  <ul>
    <li FOREACH="getTopMessages(),data" class="{getType(data)}">
    {getText(data)}
    </li>
  </ul>

</div>
