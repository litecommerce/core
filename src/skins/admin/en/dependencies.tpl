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
<p class="ErrorMessage">
Unable to install module &quot;{mm.moduleName}&quot; because some modules which it depends on, have not been installed or activated yet
</p>
<table border="0">
<tr>
	<td>
Please, make sure that the following modules are installed and enabled:
	</td>
	<td>
	<table border="0">
		<tr FOREACH="mm.dependencies,dependency">
		<td>{dependency}</td>
		</tr>
	</table>
	</td>
</tr>
</table>
