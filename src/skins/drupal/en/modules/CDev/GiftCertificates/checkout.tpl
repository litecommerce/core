{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Checkout form
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<div class="details">
  <table cellspacing="0" class="form-table">

    <tr>
      <td><label for="gc_code">Gift certificate code:</label></td>
      <td class="marker">*</td>
      <td>
        <input type="text" id="gc_code" name="gcid" />
        <widget class="\XLite\Module\CDev\GiftCertificates\Validator\GCValidator" field="gcid" />
      </td>
    </tr>

  </table>

</div>
