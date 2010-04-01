{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Detailed images management template
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}

<p class="ErrorMessage" IF="!product.detailedImages">There are no detailed images for this product</p>

<br />

<form IF="product.detailedImages" action="admin.php" name="images_form" method="POST">

  <input FOREACH="allparams,_name,_val" type="hidden" name="{_name}" value="{_val}" />
  <input type="hidden" name="action" value="update_detailed_images" />
  <input type="hidden" name="image_id" value="" />

  <div FOREACH="product.detailedImages,id,image" class="Text">
    <p>
      <font class="AdminHead">Detailed image #{inc(id)}</font><br />
      <strong>Note:</strong> Image border will not be displayed in customer's frontend
    </p>
    <img src="{image.imageURL}" style="border: 1px solid #b2b2b3;" alt="" />
    <br />
    <br />

      <table>

        <tr>
        	<td align="right">Alternative text:</td>
	        <td><input type="text" name="alt[{image.image_id}]" value="{image.alt:r}" size="55" /></td>
        </tr>

        <tr>
	        <td align="right">Position:</td>
	        <td><input type="text" name="order_by[{image.image_id}]" value="{image.order_by:r}" size="3" /></td>
        </tr>

        <tr>
          <td align="right">It's zoom:</td>
          <td><input type="checkbox" name="is_zoom[{image.image_id}]" value="Y" {if:image.is_zoom=#Y#}checked="checked"{end:} /></td>
        </tr>

        <tr>
	        <td>&nbsp;</td>
        	<td>
            <input type="submit" value=" Update ">
            &nbsp;
		        <input type="button" Value=" Delete the image " onclick="images_form.image_id.value='{image.image_id}'; images_form.action.value='delete_detailed_image'; images_form.submit()">
	        </td>
        </tr>

      </table>

    <br />
  </div>

</form>

<br />
<br />
<br />

<form action="admin.php" method="POST" name="imageForm" enctype="multipart/form-data">

  <input FOREACH="allparams,_name,_val" type="hidden" name="{_name}" value="{_val}" />
  <input type="hidden" name="action" value="add_detailed_image" />

  <table cellspacing="3" cellpadding="0">

    <tr>
      <td colspan="2" valign="top" class="AdminTitle">Add Image</td>
    </tr>

    <tr>
      <td colspan=2>&nbsp;</td>
    </tr>

    <tr>
    	<td>Alternative text:</td>
	    <td><input type="text" name="alt" size="55" /></td>
    </tr>

    <tr>
    	<td>Position:</td>
    	<td><input type="text" name="order_by" size="3" value="1" /></td>
    </tr>

    <tr>
      <td>It's zoom:</td>
      <td><input type="checkbox" name="is_zoom" value="Y" /></td>
    </tr>

    <tr>	
    	<td valign="top">Image file:</td>
    	<td valign="middle">
        <widget class="XLite_View_ImageUpload" field="image" actionName="add_detailed_image" formName="imageForm" object="{product}" />
	    </td>
    </tr>

    <tr>
    	<td colspan="2"><input type="submit" value=" Add " /></td>
    </tr>	

  </table>

</form>
