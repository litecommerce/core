{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * "Open/close" link
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *
 * @ListChild (list="top_links.storefront", weight="300")
 *}

<li IF="!getCustomerZoneWarning()"><a class="close-storefront-link" href="{buildURL(#storefront#,#close#,_ARRAY_(#returnURL#^getURL()))}">{t(#Close storefront#)}</a></li>
<li IF="getCustomerZoneWarning()"><a class="open-storefront-link" href="{buildURL(#storefront#,#open#,_ARRAY_(#returnURL#^getURL()))}">{t(#Open storefront#)}</a></li>
