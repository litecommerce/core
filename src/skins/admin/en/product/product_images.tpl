{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Product images management template
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}

<p class="error-message" IF="!product.getImages()">There are no images loaded for this product</p>

<br />

<form IF="product.getImages()" action="admin.php" name="images_form" method="post">

  <input FOREACH="allparams,_name,_val" type="hidden" name="{_name}" value="{_val}" />
  <input type="hidden" name="action" value="update_images" />
  <input type="hidden" name="image_id" value="" />

  <div FOREACH="product.getImages(),id,image">
    <p>
      <span class="admin-head">Image #{inc(id)}</span><br />
      <strong>Note:</strong> Image border will not be displayed in customer's frontend
    </p>
    <img src="{image.getURL()}" style="border: 1px solid #b2b2b3;" alt="" />
    <br />
    <br />

      <table>

        <tr>
        	<td align="right">Alternative text:</td>
	        <td><input type="text" name="alt[{image.getImageId()}]" value="{image.getAlt():r}" size="55" /></td>
        </tr>

        <tr>
	        <td align="right">Position:</td>
	        <td><input type="text" name="orderby[{image.getImageId()}]" value="{image.getOrderby():r}" class="orderby field-integer" /></td>
        </tr>

        <tr>
	        <td>&nbsp;</td>
        	<td>
            <widget class="\XLite\View\Button\Submit" label="Update" />
            &nbsp;
		        <widget class="\XLite\View\Button\Regular" label="Delete the image" jsCode="images_form.image_id.value='{image.getImageId()}'; images_form.action.value='delete_image'; images_form.submit()" />
	        </td>
        </tr>

      </table>

    <br />
  </div>

</form>

<br />
<br />
<br />

<form action="admin.php" method="post" name="imageForm" enctype="multipart/form-data">

  <input FOREACH="allparams,_name,_val" type="hidden" name="{_name}" value="{_val}" />
  <input type="hidden" name="action" value="add_image" />

  <table cellspacing="3" cellpadding="0">

    <tr>
      <td colspan="2" valign="top" class="admin-title">Add Image</td>
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
    	<td><input type="text" name="orderby" class="orderby field-integer" /></td>
    </tr>

    <tr>	
    	<td valign="top">Image file:</td>
    	<td valign="middle">
        <widget class="\XLite\View\ImageUpload" field="image" actionName="add_image" formName="imageForm" object="{product}" />
	    </td>
    </tr>

    <tr>
    	<td colspan="2"><widget class="\XLite\View\Button\Submit" label="Add" /></td>
    </tr>	

  </table>

</form>
