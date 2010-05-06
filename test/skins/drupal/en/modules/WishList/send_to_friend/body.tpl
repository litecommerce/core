{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Send to friend widget
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<widget class="XLite_Module_WishList_View_Form_Product_SendToFriend" name="send_to_friend" product="{product}" className="send-to-friend" />

  <table cellspacing="0" class="form-table">

  	<tr>
	  	<td>Your name:</td>
		  <td><input type="text" name="sender_name" size="32" value="{senderName}" /></td>
		  <td><widget class="XLite_Validator_RequiredValidator" field="sender_name" value="{sender_name}"></td>
	  </tr>

  	<tr>	
      <td>Your e-mail:</td> 
      <td><input type="text" name="sender_email" size="32" value="{senderEmail}" /></td>
      <td><widget class="XLite_Validator_EmailValidator" field="sender_email"></td>
  	</tr>	

  	<tr>
      <td>Friend's e-mail:</td>
      <td><input type="text" name="recipient_email" size="32" value="{recipient_email}" /></td>
      <td><widget class="XLite_Validator_EmailValidator" field="recipient_email"></td>
	  </tr>	

  </table>

  <div class="button-row">
    <widget class="XLite_View_Button_Submit" label="Submit" />
  </div>

<widget name="send_to_friend" end />
