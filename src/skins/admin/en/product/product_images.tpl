{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Product images management template
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}

<p class="error-message" IF="!product.getImages()">{t(#There are no images loaded for this product#)}</p>

<br />

<form IF="product.getImages()" action="admin.php" name="images_form" method="post">

  <input FOREACH="allparams,_name,_val" type="hidden" name="{_name}" value="{_val}" />
  <input type="hidden" name="action" value="update_images" />
  <input type="hidden" name="image_id" value="" />

  <div FOREACH="product.getImages(),id,image">
    <p>
      <span class="admin-head">{t(#Image#)} #{inc(id)}</span><br />
      <strong>{t(#Note#)}:</strong> {t(#Image border will not be displayed in customer's frontend#)}
    </p>
    <img src="{image.getURL()}" style="border: 1px solid #b2b2b3;" alt="" />
    <br />
    <br />

      <table>

        <tr>
        	<td align="right">{t(#Alternative text#)}:</td>
	        <td><input type="text" name="alt[{image.getImageId()}]" value="{image.getAlt():r}" size="55" /></td>
        </tr>

        <tr>
	        <td align="right">{t(#Position#)}:</td>
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

<widget
  class="\XLite\View\Button\FileSelector"
  style="main-button"
  label="Add image"
  object="product"
  objectId="{product.getProductId()}"
  fileObject="images" />
