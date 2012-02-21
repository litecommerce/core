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

<p class="error-message">
{t(#Unable to install module X because some modules, which it depends on, have not been installed or activated yet#,_ARRAY_(#X#^mm.moduleName))}
</p>
<table>
<tr>
  <td>
  {t(#Please, make sure that the following modules are installed and enabled:#)}
	</td>
	<td>
	<table>
		<tr FOREACH="mm.dependencies,dependency">
		<td>{dependency}</td>
		</tr>
	</table>
	</td>
</tr>
</table>
