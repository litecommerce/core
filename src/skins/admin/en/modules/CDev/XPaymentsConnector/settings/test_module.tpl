{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Test module 
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

<h3>{t(#Test module#)}</h3>

<widget class="\XLite\Module\CDev\XPaymentsConnector\View\Form\TestModule" name="test" />

  <p>{t(#To test the module settings and connection with X-Payments click the button below. If the module is configured properly, a "Test transaction completed successfully" message is displayed at the top of the page.#)}</p>
  <widget class="\XLite\View\Button\Submit" label="{t(#Test module#)}" style="main" />

<widget name="test" end />
