{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}

<div class="admin-title">Australia Post settings</div>

<br />

<form action="admin.php" method="post">

  <input type="hidden" name="target" value="aupost" />
  <input type="hidden" name="action" value="update" />

  <table cellpadding="2" cellspacing="1">

    <tr>
      <td><b>Package length (mm):</b></td>
      <td rowspan="4">&nbsp;</td>
      <td><input type="text" name="length" value="{config.CDev.AustraliaPost.length:r}" size="15" /></td>
    </tr>

    <tr>
      <td><b>Package width (mm):</b></td>
      <td><input type="text" name="width" value="{config.CDev.AustraliaPost.width:r}" size="15" /></td>
    </tr>

    <tr>
      <td><b>Package height (mm):</b></td>
      <td><input type="text" name="height" value="{config.CDev.AustraliaPost.height:r}" size="15" /></td>
    </tr>

    <tr>
      <td><b>Currency rate:</b></td>
      <td><input type="text" name="currency_rate" value="{config.CDev.AustraliaPost.currency_rate:r}" size="8" /></td>
    </tr>

    <tr>
      <td colspan="2">(specify rate X, where 1 AUD = X in shop currency)</td>
    </tr>

    <tr>
      <td colspan="2"><br /><widget class="\XLite\View\Button\Submit" label="Save" /></TD>
    </tr>

  </table>

</form>
