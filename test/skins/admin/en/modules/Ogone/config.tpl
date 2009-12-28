Use this page to configure your store to communicate with your Payment Processing Gateway. Complete the required fields below and press the "Update" button.<hr>

<p>
<span class="SuccessMessage" IF="dialog.updated">Ogone parameters were successfully changed. Please make sure that the Ogone payment method is enabled on the <a href="admin.php?target=payment_methods"><u>Payment methods</u></a> page before you can start using it.</span>
<form action="admin.php" method="POST">
<input type="hidden" name="target" value="payment_method">
<input type="hidden" name="action" value="update">
<input type="hidden" name="payment_method" value="{dialog.pm.get(#payment_method#)}">
<table border=0 cellspacing=10>

<tr>
<td>PSPID:</td>
<td><input type=text name=params[param01] size=32 value="{dialog.pm.params.param01}"></td>
</tr>

<tr>
<td>Password:</td>
<td><input type=password name=params[param02] size=32 value="{dialog.pm.params.param02}"></td>
</tr>

<tr>
<td>SHA-1 Signature:</td>
<td><input type=password name=params[param03] size=32 value="{dialog.pm.params.param03}"></td>
</tr>

<tr>
<td>Currency:</td>
<td>
<select name=params[param04]>
<option value=ATS selected="{IsSelected(dialog.pm.params.param04,#ATS#)}">Austrian Shilling
<option value=AUD selected="{IsSelected(dialog.pm.params.param04,#AUD#)}">Australian Dollar
<option value=BEF selected="{IsSelected(dialog.pm.params.param04,#BEF#)}">Belgian franc
<option value=CAD selected="{IsSelected(dialog.pm.params.param04,#CAD#)}">Canadian Dollar
<option value=CHF selected="{IsSelected(dialog.pm.params.param04,#CHF#)}">Swiss Franc
<option value=CZK selected="{IsSelected(dialog.pm.params.param04,#CZK#)}">Czech Koruna
<option value=DEM selected="{IsSelected(dialog.pm.params.param04,#DEM#)}">German mark
<option value=DKK selected="{IsSelected(dialog.pm.params.param04,#DKK#)}">Danish Kroner
<option value=ESP selected="{IsSelected(dialog.pm.params.param04,#ESP#)}">Spanish Peseta
<option value=EUR selected="{IsSelected(dialog.pm.params.param04,#EUR#)}">EURO
<option value=FIM selected="{IsSelected(dialog.pm.params.param04,#FIM#)}">Finnish Markka
<option value=FRF selected="{IsSelected(dialog.pm.params.param04,#FRF#)}">French franc
<option value=GBP selected="{IsSelected(dialog.pm.params.param04,#GBP#)}">British pound
<option value=HKD selected="{IsSelected(dialog.pm.params.param04,#HKD#)}">Hong Kong Dollar
<option value=HUF selected="{IsSelected(dialog.pm.params.param04,#HUF#)}">Hungarian Forint
<option value=IEP selected="{IsSelected(dialog.pm.params.param04,#IEP#)}">Irish Punt
<option value=ILS selected="{IsSelected(dialog.pm.params.param04,#ILS#)}">New Shekel
<option value=ITL selected="{IsSelected(dialog.pm.params.param04,#ITL#)}">Italian Lira
<option value=JPY selected="{IsSelected(dialog.pm.params.param04,#JPY#)}">Japanese Yen
<option value=LTL selected="{IsSelected(dialog.pm.params.param04,#LTL#)}">Litas
<option value=LUF selected="{IsSelected(dialog.pm.params.param04,#LUF#)}">Luxembourg franc
<option value=LVL selected="{IsSelected(dialog.pm.params.param04,#LVL#)}">Lats Letton
<option value=MXN selected="{IsSelected(dialog.pm.params.param04,#MXN#)}">Peso
<option value=NLG selected="{IsSelected(dialog.pm.params.param04,#NLG#)}">Dutch Guilders
<option value=NOK selected="{IsSelected(dialog.pm.params.param04,#NOK#)}">Norwegian Kroner
<option value=NZD selected="{IsSelected(dialog.pm.params.param04,#NZD#)}">New Zealand Dollar
<option value=PLN selected="{IsSelected(dialog.pm.params.param04,#PLN#)}">Polish Zloty
<option value=PTE selected="{IsSelected(dialog.pm.params.param04,#PTE#)}">Portuguese Escudo
<option value=RUR selected="{IsSelected(dialog.pm.params.param04,#RUR#)}">Rouble
<option value=SEK selected="{IsSelected(dialog.pm.params.param04,#SEK#)}">Swedish Krone
<option value=SGD selected="{IsSelected(dialog.pm.params.param04,#SGD#)}">Singapore Dollar
<option value=SKK selected="{IsSelected(dialog.pm.params.param04,#SKK#)}">Couronne Slovaque
<option value=THB selected="{IsSelected(dialog.pm.params.param04,#THB#)}">Thai Bath
<option value=TRL selected="{IsSelected(dialog.pm.params.param04,#TRL#)}">Lire Turque
<option value=USD selected="{IsSelected(dialog.pm.params.param04,#USD#)}">US Dollar
<option value=ZAR selected="{IsSelected(dialog.pm.params.param04,#ZAR#)}">South African Rand   
</select>
</td>
</tr>

<tr>
<td>Test/Live mode:</td>
<td>
<select name=params[testmode]>
<option value="Y" selected="{IsSelected(dialog.pm.params.testmode,#Y#)}">test
<option value="N" selected="{IsSelected(dialog.pm.params.testmode,#N#)}">live
</select>
</td>
</tr>

<tr>
<td>Order prefix:</td>
<td><input type=text name=params[param06] size=32 value="{dialog.pm.params.param06}"></td>
</tr>

<tr>
<td>Live gateway address:</td>
<td><input type=text name=params[param08] size=32 value="{dialog.pm.params.param08}"></td>
</tr>

<tr>
<td>Test gateway address:</td>
<td><input type=text name=params[param09] size=32 value="{dialog.pm.params.param09}"></td>
</tr>

<tr>
<td colspan="2">
<input type=submit value=" Update ">
</td>
</tr>

</table>
</form>
