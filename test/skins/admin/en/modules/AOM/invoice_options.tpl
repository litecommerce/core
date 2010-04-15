{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<tr>
    <td>
    	<table border=0>
        <tr>
            <td><b>Options:</b>&nbsp;&nbsp;</td>
            <td>{foreach:item.productOptions,option}{option.class:h}: {option.option:h}<br>{end:}</td>
        </tr>
    	</table>
    </td>
</tr>
