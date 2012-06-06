{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Pinterest button
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}
<div class="pinterest">
  <a href="{getButtonURL():h}" class="pin-it-button" {foreach:getButtonAttributes(),name,value} {name}="{value}"{end:}><img border="0" src="//assets.pinterest.com/images/PinExt.png" alt="{t(#Pin It#)}" /></a>
</div>
<widget class="\XLite\Module\CDev\GoSocial\View\ExternalSDK\Pinterest" />
