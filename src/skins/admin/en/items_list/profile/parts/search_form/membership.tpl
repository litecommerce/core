{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * User membership
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *
 * @ListChild (list="itemsList.profile.search.search_form", weight="300")
 *}

<tr>
  <td>{t(#Membership#)}</td>
  <td><widget class="\XLite\View\MembershipSelect" field="membership" value="{getParam(#membership#)}" allOption pendingOption /></td>
</tr>
