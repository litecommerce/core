{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Logo
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

<img src="{getLogo()}" alt="" />
<p />
<input{getAttributesCode():h}/>
<p />
<label><input type="checkbox" name="useDefaultLogo" checked="{!getValue()}" /> {t(#Use default logo#)}</label>
