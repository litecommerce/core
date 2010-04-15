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
Use this page to configure your store to communicate with your Payment Processing Gateway. Complete the required fields below and press the "Update" button.

<p>
<span class="SuccessMessage" IF="updated">Authorize.Net parameters were successfully changed. Please make sure that the Authorize.Net payment method is enabled on the <a href="admin.php?target=payment_methods">Payment methods</a> page before you can start using it.</span>
<form action="admin.php" method="POST">
<input type="hidden" name="target" value="payment_method">
<input type="hidden" name="action" value="update">
<input type="hidden" name="payment_method" value="{pm.payment_method}">
<center>
<table border=0 cellspacing=10>
<tr>
<td>Merchant account username:</td>
<td><input type=text name="params[login]" size=24 value="{pm.params.login:r}"></td>
</tr>

<tr>
<td>Transaction key:</td>
<td><input type=text name="params[key]" size=24 value="{pm.params.key:r}"></td>
</tr>

<tr>
<td colspan="2">
To obtain the transaction key from the Merchant Interface, do the following:<br>
1. Log into the Merchant Interface<br>
2. Select <i>Settings</i> from the <i>Main Menu</i><br>
3. Click on <i>Obtain Transaction Key</i> in the Security section<br>
4. Type in the answer to the secret question configured on setup<br>
5. Click <i>Submit</i>
</td>
</tr>

<tr>
<td>Processing mode:</td>
<td>
<select name="params[test]">
<option value="TRUE" selected="{isSelected(pm.params.test,#TRUE#)}">Test mode</option>
<option value="FALSE" selected="{isSelected(pm.params.test,#FALSE#)}">Real transaction</option>
</select>
</td>
</tr>

<tr>
<td>Transaction type:</td>
<td>
<select name="params[type]">
<option value="AUTH_CAPTURE" selected="{isSelected(pm.params.type,#AUTH_CAPTURE#)}">Authentication and capture</option>
<option value="AUTH_ONLY" selected="{isSelected(pm.params.type,#AUTH_ONLY#)}">Authentication only</option>
</select>
</td>
</tr>

<tr>
<td>Enable CVV2 code:</td>
<td>
<select name="params[cvv2]">
<option value="0" selected="{isSelected(pm.params.cvv2,#0#)}">No</option>
<option value="1" selected="{isSelected(pm.params.cvv2,#1#)}">Yes</option>
</select>
</td>
</tr>

<tr>
  <td valign="top">
    <b>Security options:</b><br />
    These options protect your orders: they prohibit changing order total during payment process and paying in another currency.<br />
    <b>Note:</b> It is strongly recommended not to disable these options.
  </td>
  <td valign="top">
    <input type="checkbox" id="check_total_id" name="params[check_total]" value="1" checked="{isSelected(pm.params.check_total,#1#)}"><label for="check_total_id">Perform order total check after transaction</label><br>
  </td>
</tr>

<tr>
<td colspan="2">
To configure the filter to reject certain Card Code responses, do the following:<br>
Log into the Merchant Interface<br>
1. Select <i>Settings</i> from the Main Menu<br>
2. Click on the <i>Card Code Verification</i> link from the Security section<br>
3. Check the box(es) next to the Card Codes that the system should reject<br>
4. Click <i>Submit</i> to save changes
</td>
</tr>

<tr>
<td>MD5 secure hash value:</td>
<td>
<input type="text" value="{pm.params.md5HashValue:r}" name="params[md5HashValue]" size="20">
</td>
</tr>

<tr>
<td colspan="2">Set the MD5 Hash Value to use the MD5 security checks. In this case, you will have to set the same  MD5 Hash Value in your Merchant Interface:<br>
1. Log into the Merchant Interface<br>
2. Select <i>Settings</i> from the Main Menu<br>
3. Click on <i>MD5 Hash</i> in the Security section<br>
4. Enter the MD5 Hash Value<br>
5. Confirm the MD5 Hash Value entered<br>
6. Click <i>Submit</i> to save changes
</td>
</tr>

<tr>
<td>Invoice number prefix:</td>
<td>
<input type="text" value="{pm.params.prefix:r}" name="params[prefix]" size="10">
</td>
</tr>

<tr>
<td>The difference between your server time<br /> and the Authorize.Net server time (in seconds) <span class="Star">*</span></td>
<td>
<input type="text" value="{pm.params.prefix:r}" name="params[offset]" size="10" />
</td>
</tr>

<tr>
<td>Currency:</td>
<td>
<select name="params[currency]">
<option value="AFA" selected="{isSelected(pm.params.currency,#AFA#)}">Afghani (Afghanistan)
<option value="DZD" selected="{isSelected(pm.params.currency,#DZD#)}">Algerian Dinar (Algeria)
<option value="ADP" selected="{isSelected(pm.params.currency,#ADP#)}">Andorran Peseta (Andorra)
<option value="ARS" selected="{isSelected(pm.params.currency,#ARS#)}">Argentine Peso (Argentina)
<option value="AMD" selected="{isSelected(pm.params.currency,#AMD#)}">Armenian Dram (Armenia)
<option value="AWG" selected="{isSelected(pm.params.currency,#AWG#)}">Aruban Guilder (Aruba)
<option value="AUD" selected="{isSelected(pm.params.currency,#AUD#)}">Australian Dollar (Australia)
<option value="AZM" selected="{isSelected(pm.params.currency,#AZM#)}">Azerbaijanian Manat (Azerbaijan)
<option value="BSD" selected="{isSelected(pm.params.currency,#BSD#)}">Bahamian Dollar (Bahamas)
<option value="BHD" selected="{isSelected(pm.params.currency,#BHD#)}">Bahraini Dinar (Bahrain)
<option value="THB" selected="{isSelected(pm.params.currency,#THB#)}">Baht (Thailand)
<option value="PAB" selected="{isSelected(pm.params.currency,#PAB#)}">Balboa (Panama)
<option value="BBD" selected="{isSelected(pm.params.currency,#BBD#)}">Barbados Dollar (Barbados)
<option value="BYB" selected="{isSelected(pm.params.currency,#BYB#)}">Belarussian Ruble (Belarus)
<option value="BEF" selected="{isSelected(pm.params.currency,#BEF#)}">Belgian Franc (Belgium)
<option value="BZD" selected="{isSelected(pm.params.currency,#BZD#)}">Belize Dollar (Belize)
<option value="BMD" selected="{isSelected(pm.params.currency,#BMD#)}">Bermudian Dollar (Bermuda)
<option value="VEB" selected="{isSelected(pm.params.currency,#VEB#)}">Bolivar (Venezuela)
<option value="BOB" selected="{isSelected(pm.params.currency,#BOB#)}">Boliviano (Bolivia)
<option value="BRL" selected="{isSelected(pm.params.currency,#BRL#)}">Brazilian Real (Brazil)
<option value="BND" selected="{isSelected(pm.params.currency,#BND#)}">Brunei Dollar (Brunei Darussalam)
<option value="BGN" selected="{isSelected(pm.params.currency,#BGN#)}">Bulgarian Lev (Bulgaria)
<option value="BIF" selected="{isSelected(pm.params.currency,#BIF#)}">Burundi Franc (Burundi)
<option value="CAD" selected="{isSelected(pm.params.currency,#CAD#)}">Canadian Dollar (Canada)
<option value="CVE" selected="{isSelected(pm.params.currency,#CVE#)}">Cape Verde Escudo (Cape Verde)
<option value="KYD" selected="{isSelected(pm.params.currency,#KYD#)}">Cayman Islands Dollar (Cayman Islands)
<option value="GHC" selected="{isSelected(pm.params.currency,#GHC#)}">Cedi (Ghana)
<option value="XOF" selected="{isSelected(pm.params.currency,#XOF#)}">CFA Franc BCEAO (Guinea-Bissau)
<option value="XAF" selected="{isSelected(pm.params.currency,#XAF#)}">CFA Franc BEAC (Central African Republic)
<option value="XPF" selected="{isSelected(pm.params.currency,#XPF#)}">CFP Franc (New Caledonia)
<option value="CLP" selected="{isSelected(pm.params.currency,#CLP#)}">Chilean Peso (Chile)
<option value="COP" selected="{isSelected(pm.params.currency,#COP#)}">Colombian Peso (Colombia)
<option value="KMF" selected="{isSelected(pm.params.currency,#KMF#)}">Comoro Franc (Comoros)
<option value="BAM" selected="{isSelected(pm.params.currency,#BAM#)}">Convertible Marks (Bosnia And Herzegovina)
<option value="NIO" selected="{isSelected(pm.params.currency,#NIO#)}">Cordoba Oro (Nicaragua)
<option value="CRC" selected="{isSelected(pm.params.currency,#CRC#)}">Costa Rican Colon (Costa Rica)
<option value="CUP" selected="{isSelected(pm.params.currency,#CUP#)}">Cuban Peso (Cuba)
<option value="CYP" selected="{isSelected(pm.params.currency,#CYP#)}">Cyprus Pound (Cyprus)
<option value="CZK" selected="{isSelected(pm.params.currency,#CZK#)}">Czech Koruna (Czech Republic)
<option value="GMD" selected="{isSelected(pm.params.currency,#GMD#)}">Dalasi (Gambia)
<option value="DKK" selected="{isSelected(pm.params.currency,#DKK#)}">Danish Krone (Denmark)
<option value="MKD" selected="{isSelected(pm.params.currency,#MKD#)}">Denar (The Former Yugoslav Republic Of Macedonia)
<option value="DEM" selected="{isSelected(pm.params.currency,#DEM#)}">Deutsche Mark (Germany)
<option value="AED" selected="{isSelected(pm.params.currency,#AED#)}">Dirham (United Arab Emirates)
<option value="DJF" selected="{isSelected(pm.params.currency,#DJF#)}">Djibouti Franc (Djibouti)
<option value="STD" selected="{isSelected(pm.params.currency,#STD#)}">Dobra (Sao Tome And Principe)
<option value="DOP" selected="{isSelected(pm.params.currency,#DOP#)}">Dominican Peso (Dominican Republic)
<option value="VND" selected="{isSelected(pm.params.currency,#VND#)}">Dong (Vietnam)
<option value="GRD" selected="{isSelected(pm.params.currency,#GRD#)}">Drachma (Greece)
<option value="XCD" selected="{isSelected(pm.params.currency,#XCD#)}">East Caribbean Dollar (Grenada)
<option value="EGP" selected="{isSelected(pm.params.currency,#EGP#)}">Egyptian Pound (Egypt)
<option value="SVC" selected="{isSelected(pm.params.currency,#SVC#)}">El Salvador Colon (El Salvador)
<option value="ETB" selected="{isSelected(pm.params.currency,#ETB#)}">Ethiopian Birr (Ethiopia)
<option value="EUR" selected="{isSelected(pm.params.currency,#EUR#)}">Euro (Europe)
<option value="FKP" selected="{isSelected(pm.params.currency,#FKP#)}">Falkland Islands Pound (Falkland Islands)
<option value="FJD" selected="{isSelected(pm.params.currency,#FJD#)}">Fiji Dollar (Fiji)
<option value="HUF" selected="{isSelected(pm.params.currency,#HUF#)}">Forint (Hungary)
<option value="CDF" selected="{isSelected(pm.params.currency,#CDF#)}">Franc Congolais (The Democratic Republic Of Congo)
<option value="FRF" selected="{isSelected(pm.params.currency,#FRF#)}">French Franc (France)
<option value="GIP" selected="{isSelected(pm.params.currency,#GIP#)}">Gibraltar Pound (Gibraltar)
<option value="XAU" selected="{isSelected(pm.params.currency,#XAU#)}">Gold
<option value="HTG" selected="{isSelected(pm.params.currency,#HTG#)}">Gourde (Haiti)
<option value="PYG" selected="{isSelected(pm.params.currency,#PYG#)}">Guarani (Paraguay)
<option value="GNF" selected="{isSelected(pm.params.currency,#GNF#)}">Guinea Franc (Guinea)
<option value="GWP" selected="{isSelected(pm.params.currency,#GWP#)}">Guinea-Bissau Peso (Guinea-Bissau)
<option value="GYD" selected="{isSelected(pm.params.currency,#GYD#)}">Guyana Dollar (Guyana)
<option value="HKD" selected="{isSelected(pm.params.currency,#HKD#)}">Hong Kong Dollar (Hong Kong)
<option value="UAH" selected="{isSelected(pm.params.currency,#UAH#)}">Hryvnia (Ukraine)
<option value="ISK" selected="{isSelected(pm.params.currency,#ISK#)}">Iceland Krona (Iceland)
<option value="INR" selected="{isSelected(pm.params.currency,#INR#)}">Indian Rupee (India)
<option value="IRR" selected="{isSelected(pm.params.currency,#IRR#)}">Iranian Rial (Islamic Republic Of Iran)
<option value="IQD" selected="{isSelected(pm.params.currency,#IQD#)}">Iraqi Dinar (Iraq)
<option value="IEP" selected="{isSelected(pm.params.currency,#IEP#)}">Irish Pound (Ireland)
<option value="ITL" selected="{isSelected(pm.params.currency,#ITL#)}">Italian Lira (Italy)
<option value="JMD" selected="{isSelected(pm.params.currency,#JMD#)}">Jamaican Dollar (Jamaica)
<option value="JOD" selected="{isSelected(pm.params.currency,#JOD#)}">Jordanian Dinar (Jordan)
<option value="KES" selected="{isSelected(pm.params.currency,#KES#)}">Kenyan Shilling (Kenya)
<option value="PGK" selected="{isSelected(pm.params.currency,#PGK#)}">Kina (Papua New Guinea)
<option value="LAK" selected="{isSelected(pm.params.currency,#LAK#)}">Kip (Lao People's Democratic Republic)
<option value="EEK" selected="{isSelected(pm.params.currency,#EEK#)}">Kroon (Estonia)
<option value="HRK" selected="{isSelected(pm.params.currency,#HRK#)}">Kuna (Croatia)
<option value="KWD" selected="{isSelected(pm.params.currency,#KWD#)}">Kuwaiti Dinar (Kuwait)
<option value="MWK" selected="{isSelected(pm.params.currency,#MWK#)}">Kwacha (Malawi)
<option value="ZMK" selected="{isSelected(pm.params.currency,#ZMK#)}">Kwacha (Zambia)
<option value="AOR" selected="{isSelected(pm.params.currency,#AOR#)}">Kwanza Reajustado (Angola)
<option value="MMK" selected="{isSelected(pm.params.currency,#MMK#)}">Kyat (Myanmar)
<option value="GEL" selected="{isSelected(pm.params.currency,#GEL#)}">Lari (Georgia)
<option value="LVL" selected="{isSelected(pm.params.currency,#LVL#)}">Latvian Lats (Latvia)
<option value="LBP" selected="{isSelected(pm.params.currency,#LBP#)}">Lebanese Pound (Lebanon)
<option value="ALL" selected="{isSelected(pm.params.currency,#ALL#)}">Lek (Albania)
<option value="HNL" selected="{isSelected(pm.params.currency,#HNL#)}">Lempira (Honduras)
<option value="SLL" selected="{isSelected(pm.params.currency,#SLL#)}">Leone (Sierra Leone)
<option value="ROL" selected="{isSelected(pm.params.currency,#ROL#)}">Leu (Romania)
<option value="BGL" selected="{isSelected(pm.params.currency,#BGL#)}">Lev (Bulgaria)
<option value="LRD" selected="{isSelected(pm.params.currency,#LRD#)}">Liberian Dollar (Liberia)
<option value="LYD" selected="{isSelected(pm.params.currency,#LYD#)}">Libyan Dinar (Libyan Arab Jamahiriya)
<option value="SZL" selected="{isSelected(pm.params.currency,#SZL#)}">Lilangeni (Swaziland)
<option value="LTL" selected="{isSelected(pm.params.currency,#LTL#)}">Lithuanian Litas (Lithuania)
<option value="LSL" selected="{isSelected(pm.params.currency,#LSL#)}">Loti (Lesotho)
<option value="LUF" selected="{isSelected(pm.params.currency,#LUF#)}">Luxembourg Franc (Luxembourg)
<option value="MGF" selected="{isSelected(pm.params.currency,#MGF#)}">Malagasy Franc (Madagascar)
<option value="MYR" selected="{isSelected(pm.params.currency,#MYR#)}">Malaysian Ringgit (Malaysia)
<option value="MTL" selected="{isSelected(pm.params.currency,#MTL#)}">Maltese Lira (Malta)
<option value="TMM" selected="{isSelected(pm.params.currency,#TMM#)}">Manat (Turkmenistan)
<option value="FIM" selected="{isSelected(pm.params.currency,#FIM#)}">Markka (Finland)
<option value="MUR" selected="{isSelected(pm.params.currency,#MUR#)}">Mauritius Rupee (Mauritius)
<option value="MZM" selected="{isSelected(pm.params.currency,#MZM#)}">Metical (Mozambique)
<option value="MXN" selected="{isSelected(pm.params.currency,#MXN#)}">Mexican Peso (Mexico)
<option value="MXV" selected="{isSelected(pm.params.currency,#MXV#)}">Mexican Unidad de Inversion (Mexico)
<option value="MDL" selected="{isSelected(pm.params.currency,#MDL#)}">Moldovan Leu (Republic Of Moldova)
<option value="MAD" selected="{isSelected(pm.params.currency,#MAD#)}">Moroccan Dirham (Morocco)
<option value="BOV" selected="{isSelected(pm.params.currency,#BOV#)}">Mvdol (Bolivia)
<option value="NGN" selected="{isSelected(pm.params.currency,#NGN#)}">Naira (Nigeria)
<option value="ERN" selected="{isSelected(pm.params.currency,#ERN#)}">Nakfa (Eritrea)
<option value="NAD" selected="{isSelected(pm.params.currency,#NAD#)}">Namibia Dollar (Namibia)
<option value="NPR" selected="{isSelected(pm.params.currency,#NPR#)}">Nepalese Rupee (Nepal)
<option value="ANG" selected="{isSelected(pm.params.currency,#ANG#)}">Netherlands (Netherlands)
<option value="NLG" selected="{isSelected(pm.params.currency,#NLG#)}">Netherlands Guilder (Netherlands)
<option value="YUM" selected="{isSelected(pm.params.currency,#YUM#)}">New Dinar (Yugoslavia)
<option value="ILS" selected="{isSelected(pm.params.currency,#ILS#)}">New Israeli Sheqel (Israel)
<option value="AON" selected="{isSelected(pm.params.currency,#AON#)}">New Kwanza (Angola)
<option value="TWD" selected="{isSelected(pm.params.currency,#TWD#)}">New Taiwan Dollar (Province Of China Taiwan)
<option value="ZRN" selected="{isSelected(pm.params.currency,#ZRN#)}">New Zaire (Zaire)
<option value="NZD" selected="{isSelected(pm.params.currency,#NZD#)}">New Zealand Dollar (New Zealand)
<option value="BTN" selected="{isSelected(pm.params.currency,#BTN#)}">Ngultrum (Bhutan)
<option value="KPW" selected="{isSelected(pm.params.currency,#KPW#)}">North Korean Won (Democratic People's Republic Of Korea)
<option value="NOK" selected="{isSelected(pm.params.currency,#NOK#)}">Norwegian Krone (Norway)
<option value="PEN" selected="{isSelected(pm.params.currency,#PEN#)}">Nuevo Sol (Peru)
<option value="MRO" selected="{isSelected(pm.params.currency,#MRO#)}">Ouguiya (Mauritania)
<option value="TOP" selected="{isSelected(pm.params.currency,#TOP#)}">Pa'anga (Tonga)
<option value="PKR" selected="{isSelected(pm.params.currency,#PKR#)}">Pakistan Rupee (Pakistan)
<option value="XPD" selected="{isSelected(pm.params.currency,#XPD#)}">Palladium
<option value="MOP" selected="{isSelected(pm.params.currency,#MOP#)}">Pataca (Macau)
<option value="UYU" selected="{isSelected(pm.params.currency,#UYU#)}">Peso Uruguayo (Uruguay)
<option value="PHP" selected="{isSelected(pm.params.currency,#PHP#)}">Philippine Peso (Philippines)
<option value="XPT" selected="{isSelected(pm.params.currency,#XPT#)}">Platinum
<option value="PTE" selected="{isSelected(pm.params.currency,#PTE#)}">Portuguese Escudo (Portugal)
<option value="GBP" selected="{isSelected(pm.params.currency,#GBP#)}">Pound Sterling (United Kingdom)
<option value="BWP" selected="{isSelected(pm.params.currency,#BWP#)}">Pula (Botswana)
<option value="QAR" selected="{isSelected(pm.params.currency,#QAR#)}">Qatari Rial (Qatar)
<option value="GTQ" selected="{isSelected(pm.params.currency,#GTQ#)}">Quetzal (Guatemala)
<option value="ZAL" selected="{isSelected(pm.params.currency,#ZAL#)}">Rand (Financial) (Lesotho)
<option value="ZAR" selected="{isSelected(pm.params.currency,#ZAR#)}">Rand (South Africa)
<option value="OMR" selected="{isSelected(pm.params.currency,#OMR#)}">Rial Omani (Oman)
<option value="KHR" selected="{isSelected(pm.params.currency,#KHR#)}">Riel (Cambodia)
<option value="MVR" selected="{isSelected(pm.params.currency,#MVR#)}">Rufiyaa (Maldives)
<option value="IDR" selected="{isSelected(pm.params.currency,#IDR#)}">Rupiah (Indonesia)
<option value="RUB" selected="{isSelected(pm.params.currency,#RUB#)}">Russian Ruble (Russian Federation)
<option value="RUR" selected="{isSelected(pm.params.currency,#RUR#)}">Russian Ruble (Russian Federation)
<option value="RWF" selected="{isSelected(pm.params.currency,#RWF#)}">Rwanda Franc (Rwanda)
<option value="SAR" selected="{isSelected(pm.params.currency,#SAR#)}">Saudi Riyal (Saudi Arabia)
<option value="ATS" selected="{isSelected(pm.params.currency,#ATS#)}">Schilling (Austria)
<option value="SCR" selected="{isSelected(pm.params.currency,#SCR#)}">Seychelles Rupee (Seychelles)
<option value="XAG" selected="{isSelected(pm.params.currency,#XAG#)}">Silver
<option value="SGD" selected="{isSelected(pm.params.currency,#SGD#)}">Singapore Dollar (Singapore)
<option value="SKK" selected="{isSelected(pm.params.currency,#SKK#)}">Slovak Koruna (Slovakia)
<option value="SBD" selected="{isSelected(pm.params.currency,#SBD#)}">Solomon Islands Dollar (Solomon Islands)
<option value="KGS" selected="{isSelected(pm.params.currency,#KGS#)}">Som (Kyrgyzstan)
<option value="SOS" selected="{isSelected(pm.params.currency,#SOS#)}">Somali Shilling (Somalia)
<option value="ESP" selected="{isSelected(pm.params.currency,#ESP#)}">Spanish Peseta (Spain)
<option value="LKR" selected="{isSelected(pm.params.currency,#LKR#)}">Sri Lanka Rupee (Sri Lanka)
<option value="SHP" selected="{isSelected(pm.params.currency,#SHP#)}">St Helena Pound (St Helena)
<option value="ECS" selected="{isSelected(pm.params.currency,#ECS#)}">Sucre (Ecuador)
<option value="SDD" selected="{isSelected(pm.params.currency,#SDD#)}">Sudanese Dinar (Sudan)
<option value="SRG" selected="{isSelected(pm.params.currency,#SRG#)}">Surinam Guilder (Suriname)
<option value="SEK" selected="{isSelected(pm.params.currency,#SEK#)}">Swedish Krona (Sweden)
<option value="CHF" selected="{isSelected(pm.params.currency,#CHF#)}">Swiss Franc (Switzerland)
<option value="SYP" selected="{isSelected(pm.params.currency,#SYP#)}">Syrian Pound (Syrian Arab Republic)
<option value="TJR" selected="{isSelected(pm.params.currency,#TJR#)}">Tajik Ruble (Tajikistan)
<option value="BDT" selected="{isSelected(pm.params.currency,#BDT#)}">Taka (Bangladesh)
<option value="WST" selected="{isSelected(pm.params.currency,#WST#)}">Tala (Samoa)
<option value="TZS" selected="{isSelected(pm.params.currency,#TZS#)}">Tanzanian Shilling (United Republic Of Tanzania)
<option value="KZT" selected="{isSelected(pm.params.currency,#KZT#)}">Tenge (Kazakhstan)
<option value="TPE" selected="{isSelected(pm.params.currency,#TPE#)}">Timor Escudo (East Timor)
<option value="SIT" selected="{isSelected(pm.params.currency,#SIT#)}">Tolar (Slovenia)
<option value="TTD" selected="{isSelected(pm.params.currency,#TTD#)}">Trinidad and Tobago Dollar (Trinidad And Tobago)
<option value="MNT" selected="{isSelected(pm.params.currency,#MNT#)}">Tugrik (Mongolia)
<option value="TND" selected="{isSelected(pm.params.currency,#TND#)}">Tunisian Dinar (Tunisia)
<option value="TRL" selected="{isSelected(pm.params.currency,#TRL#)}">Turkish Lira (Turkey)
<option value="UGX" selected="{isSelected(pm.params.currency,#UGX#)}">Uganda Shilling (Uganda)
<option value="ECV" selected="{isSelected(pm.params.currency,#ECV#)}">Unidad de Valor Constante (Ecuador)
<option value="CLF" selected="{isSelected(pm.params.currency,#CLF#)}">Unidades de fomento (Chile)
<option value="USN" selected="{isSelected(pm.params.currency,#USN#)}">US Dollar (Next day) (United States)
<option value="USS" selected="{isSelected(pm.params.currency,#USS#)}">US Dollar (Same day) (United States)
<option value="USD" selected="{isSelected(pm.params.currency,#USD#)}">US Dollar (United States)
<option value="UZS" selected="{isSelected(pm.params.currency,#UZS#)}">Uzbekistan Sum (Uzbekistan)
<option value="VUV" selected="{isSelected(pm.params.currency,#VUV#)}">Vatu (Vanuatu)
<option value="KRW" selected="{isSelected(pm.params.currency,#KRW#)}">Won (Republic Of Korea)
<option value="YER" selected="{isSelected(pm.params.currency,#YER#)}">Yemeni Rial (Yemen)
<option value="JPY" selected="{isSelected(pm.params.currency,#JPY#)}">Yen (Japan)
<option value="CNY" selected="{isSelected(pm.params.currency,#CNY#)}">Yuan Renminbi (China)
<option value="ZWD" selected="{isSelected(pm.params.currency,#ZWD#)}">Zimbabwe Dollar (Zimbabwe)
<option value="PLN" selected="{isSelected(pm.params.currency,#PLN#)}">Zloty (Poland)
</select>
</td>
</tr>
</table>
<p>
<input type=submit value=" Update ">
</form>
</center>
