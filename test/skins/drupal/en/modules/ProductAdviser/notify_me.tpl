{* SVN $Id$ *}
<form action="{buildURL(#notify_me#,action,_ARRAY_(#mode#^mode,#url#^prevUrl:h))}" method="POST" name="notify_me_form">
  <input FOREACH="buildURLArguments(#notify_me#,action,_ARRAY_(#mode#^mode,#url#^prevUrl:h)),paramName,paramValue" type="hidden" name="{paramName}" value="{paramValue}" />

  <span IF="action=#notify_product#">
    <input type="hidden" name="product_id" value="{product_id}">
    <span IF="productOptions">
      <span FOREACH="productOptions,option">
        <input type="hidden" name="product_options[{option.class:h}][option_id]" value="{option.option_id}">
        <input type="hidden" name="product_options[{option.class:h}][option]" value="{option.option}">
      </span>
    </span>
    <input type="hidden" name="amount" IF="amount" value="{amount}"/>
  </span>

  <span IF="action=#notify_price#">
    <input type="hidden" name="product_id" value="{product_id}">
    <input type="hidden" name="product_price" value="{product_price}">
  </span>

  <h1 IF="action=#notify_product#&isEmpty(amount)">Notify me when the product is in stock</h1>
  <h1 IF="action=#notify_product#&!isEmpty(amount)">Notify me when the stock quantity of a product increases</h1>
  <h1 IF="action=#notify_price#">Notify me when the price drops</h1>

  <table cellpadding="5" cellspacing="0">

    <tr>
      <td>Your e-mail:</td>
      <td>
        <input type="text" size="30" name="email" value="{email}">
        <widget class="XLite_Validator_EmailValidator" field="email">
      </td>
    </tr>

    <tr>
      <td>Your name:</td>
      <td>
        <input type="text" size="50" name="person_info" value="{xlite.auth.profile.billing_title} {xlite.auth.profile.billing_firstname} {xlite.auth.profile.billing_lastname}" />
      </td>
    </tr>

    <tr>
      <td>&nbsp;</td>
      <td><widget class="XLite_View_Button" label="Notify me" type="button"></td>
    </tr>
  </table>

</form>
