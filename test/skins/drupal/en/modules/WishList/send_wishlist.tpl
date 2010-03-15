<form action="{shopURL(#cart.php#)}" method="POST" name="wishlist{wishlist.wishlist_id}_form">
  <input type=hidden name=target value="wishlist">
  <input type=hidden name=action value="send">
  <input type=hidden name=wishlist_id value="{wishlist.wishlist_id}">

  <span>Send entire wish list by e-mail:</span> <input type=text name="wishlist_recipient" value="{wishlist_recipient}"> <widget class="XLite_Validator_EmailValidator" field="wishlist_recipient"> <widget class="XLite_View_Button_Submit" label="Send" />

</form>
