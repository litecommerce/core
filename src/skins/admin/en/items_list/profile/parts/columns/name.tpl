{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Item name
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 * @ListChild (list="itemsList.profile.search.columns", weight="30")
 *}

<td class="name table-label">
  <a
    href="{buildURL(#address_book#,##,_ARRAY_(#profile_id#^profile.profile_id))}"
    IF="profile.billing_address.firstname&profile.billing_address.lastname">
    {profile.billing_address.firstname:h}&nbsp;{profile.billing_address.lastname:h}
  </a>

  {*TODO !!! this "if" construction is using global negation - MUST use the assigned method of class !!! *}

  <a
    href="{buildURL(#address_book#,##,_ARRAY_(#profile_id#^profile.profile_id))}"
    IF="!profile.billing_address.firstname&profile.billing_address.lastname">
    {t(#n/a#)}
  </a>

</td>
