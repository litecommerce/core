{* SVN $Id$ *}
<form action="{buildURL(#wishlist#,#update#,_ARRAY_(#item_id#^item.item_id,#wishlist_id#^item.wishlist_id,#product_id#^item.product_id))}" method="POST" name="update{key}_form">
  <input FOREACH="buildURLArguments(#wishlist#,#update#,_ARRAY_(#item_id#^item.item_id,#wishlist_id#^item.wishlist_id,#product_id#^item.product_id)),paramName,paramValue" type="hidden" name="{paramName}" value="{paramValue}" />

  <table cellpadding="0" cellspacing="0" width="100%">

    <tr>
	    <td valign="top" width="100px">
		    <span IF="item.hasImage()"><a href="{buildURL(item.url.target,item.url.action,item.url.arguments)}"><img src="{item.imageURL}" width="70" alt="" /></a></span>
	    </td>
	    <td>
		    <table id="productDetailsTable" cellpadding="0" cellspacing="0" width="100%">
          <tr id="descriptionTitle">
        	  <td colspan="3" class="ProductTitle">
              <a href="{buildURL(item.url.target,item.url.action,item.url.arguments)}"><font class="DialogTitle">{item.name:h}</font></a>
            </td>
          </tr>
		      <tr id="descriptionTitle">
			      <td colspan="3" class="ProductDetailsTitle">
              <br />
              Description
            </td>
		      </tr>
		      <tr>
      			<td colspan="2"><hr color="#E0E1E4" /></td>
			      <td></td>
		      </tr>
		      <tr>
			      <td colspan="3">&nbsp;</td>
		      </tr>
		      <tr id="description">
			      <td colspan="3">
              {truncate(item.brief_description,#300#):h}
			      </td>
		      </tr>
	        <tr id="description">
		        <td colspan=3>
  				    <widget module="ProductOptions" template="modules/ProductOptions/selected_options.tpl" visible="{item.hasOptions()}" item="{item}">
		        </td>
		      </tr>
		      <tr>
			      <td colspan="3">&nbsp;</td>
		      </tr>
		      <tr id="detailsTitle">
			      <td colspan="3" class="ProductDetailsTitle">Details</td>
		      </tr>
		      <tr>
			      <td colspan="2"><hr color="#E0E1E4" /></td>
			      <td></td>
		      </tr>
		      <tr IF="{item.sku}">
			      <td width="20%" class="ProductDetails">SKU:</td>
			      <td class="ProductDetails" colspan=2 nowrap>{item.sku}</td>
		      </tr>
		      <tr IF="{!item.weight=0}">
			      <td width="20%" class="ProductDetails">Weight:</td>
			      <td class="ProductDetails" colspan=2 nowrap>
				      {item.weight} {config.General.weight_symbol}
			      </td>
		      </tr>
          <tr>
            <td colspan="3">&nbsp;</td>
          </tr>
		      <tr>
			      <td colspan="3">
			        <table cellspacing="10" cellpadding="0">
			          <tr>
				          <td nowrap>
	        		      <font class="ProductPriceTitle">Price:</font>
                    <font class="ProductPriceConverting">{price_format(item,#price#):h}&nbsp;x&nbsp;</font>
	        		      <input type="text" name="wishlist_amount" value="{item.amount}" size="3" maxlength="6" />
	        		      <font class="ProductPriceConverting">&nbsp;=&nbsp;</font>
	        		      <font class="ProductPrice">{price_format(item,#total#):h}</font>
				          </td>
				          <td nowrap>
                		<widget class="XLite_View_Button" label="Update amount" type="button">
				          </td>
				          <td width="100%">
                		<widget class="XLite_View_Button" label="Remove" type="button" href="{buildURLPath(#wishlist#,#delete#,_ARRAY_(#item_id#^item.item_id,#wishlist_id#^item.wishlist_id,#product_id#^item.product_id))}">
				          </td>														
			          </tr>
			        </table>
			      </td>
		      </tr>
		      <tr>
		 	      <td colspan="3">
				      <table cellpadding="0" cellspacing="0">
					      <tr>
				          <td colspan="3">
							      <widget class="XLite_View_Button" label="Add to cart" type="button" href="{buildURLPath(#cart#,#add#,_ARRAY_(#item_id#^item.item_id,#wishlist_id#^item.wishlist_id,#product_id#^item.product_id))}" img="cart4button.gif" font="FormButton">
					        </td>
					      </tr>	
				      </table>	
			      </td>
		      </tr>	
        </table>
      </td>
    </tr>

  </table>
</form>
<br />
<br />
<br />
