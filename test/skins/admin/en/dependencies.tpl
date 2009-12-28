<p class="ErrorMessage">
Unable to install module &quot;{xlite.mm.moduleName}&quot; because some modules which it depends on, have not been installed or activated yet
</p>
<table border="0">
<tr>
	<td>
Please, make sure that the following modules are installed and enabled:
	</td>
	<td>
	<table border="0">
		<tr FOREACH="xlite.mm.dependencies,dependency">
		<td>{dependency}</td>
		</tr>
	</table>
	</td>
</tr>
</table>
