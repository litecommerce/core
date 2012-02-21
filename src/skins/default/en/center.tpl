{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Center column
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}

<h1 class="title" id="page-title" IF="isTitleVisible()&getTitle()">{t(getTitle())}</h1>

<widget template="center_top.tpl" />

{displayViewListContent(#center.bottom#)}
