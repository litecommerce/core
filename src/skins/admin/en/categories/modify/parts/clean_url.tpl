{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Category clean URL
 *  
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.21
 *
 * @ListChild (list="category.modify.list", weight="1000")
 *}

<tr IF="!isRoot()">
  <td>{t(#Clean URL#)}</td>
  <td class="star"></td>
  <td>
    <input type="text" name="{getNamePostedData(#cleanURL#)}" value="{category.getCleanURL()}" size="50" id="cleanURLBox" />
    <p />
    <input type="checkbox" name="{getNamePostedData(#autogenerateCleanURL#)}" value="1" checked="{category.getCleanURL()}" id="autogenerateFlag" />
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
