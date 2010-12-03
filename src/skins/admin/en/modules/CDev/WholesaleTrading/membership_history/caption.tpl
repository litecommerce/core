{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Membeship history caption template
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}

{if:membership_history}
&nbsp;&nbsp;<A href="javascript: void(0);" onclick="if (document.getElementById('membership_history').style.display == 'none') document.getElementById('membership_history').style.display = ''; else document.getElementById('membership_history').style.display = 'none'"><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle"> <font class="FormButton">View membership history</font></a>
<br />
<widget module="CDev\WholesaleTrading" template="modules/CDev/WholesaleTrading/membership_history/body.tpl" />
{end:}
