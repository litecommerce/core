{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Deploy configuration 
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

<h3>{t(#Use X-Payments configuration bundle#)}</h3>

<widget class="\XLite\Module\CDev\XPaymentsConnector\View\Form\DeployConfiguration" name="deploy" />

  <p>{t(#Copy the value of the Configuration field from X-Payments Online Store Details page, paste the string here and click Deploy. All the connection settings will be automatically specified.#)}</p>
  <table class="settings-table">

    <tr>
      <td><widget class="\XLite\View\FormField\Input\Text" fieldName="deploy_configuration" fieldOnly="true" maxlength="false" /></td>
      <td>&nbsp;&nbsp;&nbsp;</td>
      <td><widget class="\XLite\View\Button\Submit" label="{t(#Deploy#)}" style="main" /></td>
    </tr>

  </table>

<widget name="deploy" end />
