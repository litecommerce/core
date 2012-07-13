{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Order : placed box
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *
 * @ListChild (list="order", weight="200")
 *}

<p class="title">
  {if:hasProfilePage()}
    {t(#Placed on X by Y link#,_ARRAY_(#date#^getOrderDate(),#url#^getProfileURL(),#name#^getProfileName())):h}
  {else:}
    {t(#Placed on X by Y#,_ARRAY_(#date#^getOrderDate(),#name#^getProfileName())):h}
  {end:}
  {if:getMembership()}
    <span class="membership">({membership.getName()})</span>
  {end:}
</p>
