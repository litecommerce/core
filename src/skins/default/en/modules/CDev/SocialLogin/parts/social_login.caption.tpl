{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * SocialLogin widget caption
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="social.login.buttons", weight="1")
 *}

<li class="social-login-caption" IF="getCaption()">{getCaption()}</li>
