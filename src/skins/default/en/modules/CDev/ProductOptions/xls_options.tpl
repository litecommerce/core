{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}
<tbody IF="product.hasOptions()">
  <tr>
    <td valign="top" nowrap="nowrap">{t(#Selected options#)}</td>
    <td>
	    <table cellpadding="0" cellspacing="0" border="0">
	      <tr FOREACH="getOptions(),option">
    		  <td nowrap>{option.class:h}: {option.option:h}<br></td>
	      </tr>
      </table>
    </td>
  </tr>
</tbody>
