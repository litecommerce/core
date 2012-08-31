{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Order : total
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="order", weight="300")
 *}

<p class="total">{t(#Order Total X#,_ARRAY_(#total#^getOrderTotal())):h}</p>
