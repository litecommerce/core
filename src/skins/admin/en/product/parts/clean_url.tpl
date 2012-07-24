{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Product element
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="product.modify.list", weight="1040")
 *}

<tr>
  <td class="name-attribute">{t(#Clean URL#)}</td>
  <td class="star"></td>
  <td class="value-attribute">
    <input type="text" name="{getNamePostedData(#cleanURL#)}" value="{product.getCleanURL()}" size="50" maxlength="{getCleanURLMaxLength()}" id="cleanURLBox" />
    <p />
    <input type="checkbox" name="{getNamePostedData(#autogenerateCleanURL#)}" value="{#1#}" checked="{product.getCleanURL()}" id="autogenerateFlag" />
    <label for="autogenerateFlag" class="note">{t(#Autogenerate Clean URL#)}</label>
  </td>
</tr>

<script type="text/javascript">
  jQuery().ready(
    function() {
      jQuery('#cleanURLBox').keyup(
        function () {
          var flag = '' === jQuery(this).val();

          jQuery('#autogenerateFlag').attr('checked', flag);
          // jQuery('#autogenerateFlag').attr('disabled', !flag);
        }
      );

      jQuery('#cleanURLBox').keyup();
    }
  );
</script>
