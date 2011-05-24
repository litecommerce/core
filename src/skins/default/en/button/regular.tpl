{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Regular button
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}

<button type="button" onclick="javascript: {getJSCode():h}" class="{getClass()}"{if:getId()} id="{getId()}"{end:} {if:isDisabled()} disabled="disabled" {end:}>
  <span>{t(getButtonLabel())}</span>
</button>
