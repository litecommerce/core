{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Tweet button
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}

<iframe allowtransparency="true" frameborder="0" scrolling="no" src="//platform.twitter.com/widgets/tweet_button.html#{foreach:getButtonAttributes(),k,v}{k}={v:u}&amp;{end:}" style="width:110px; height:20px;" class="twitter-share-button twitter-count-horizontal"></iframe>
