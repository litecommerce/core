{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Item name
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 * @ListChild (list="itemsList.profile.search.columns", weight="20")
 *}

<td class="login table-label">
  <a href="{buildURL(#profile#,##,_ARRAY_(#profile_id#^profile.profile_id))}">{profile.login:h}</a>
  <span class="account-disabled" IF="!profile.status=#E#"> ({t(#disabled account#)})</span>
</td>
