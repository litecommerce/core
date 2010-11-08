{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Order Print link
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="order.links", weight="20")
 *}
<li class="print">
  <a href="{buildUrl(#invoice#,##,_ARRAY_(#order_id#^order.order_id,#printable#^#1#))}"><span>{t(#Print invoice#)}</span></a>
</li>
