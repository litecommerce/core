{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Checkout details agreement block
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="chekout.details", weight="90")
 *}
<div class="agree">
  <input type="checkbox" name="agree" id="agree" value="Y" />&nbsp;<label for="agree">I accept "<a href="{buildUrl(#help#,##,_ARRAY_(#mode#^#terms_conditions#))}">Terms &amp; Conditions</a>" and "<a href="{buildUrl(#help#,##,_ARRAY_(#mode#^#privacy_statement#))}">Privacy statement</a>".</label>
</div>
