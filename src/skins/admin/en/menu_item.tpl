{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Menu item
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}
<a href="{buildURL(item.target)}" class="icon"><img src="{layout.getResourceWebPath(item.icon)}" alt="{t(item.title)}" /></a>
<a href="{buildURL(item.target)}" class="title">{t(item.title)}</a>
<span IF="item.description">{t(item.description)}</span>
