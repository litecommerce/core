<?php

$source = "{* E-mail sent to admin when customer fills in Help / Contact us form *}\n" . $source;
$source = strReplace('{b_address}', '{address}', $source, __FILE__, __LINE__);
$source = strReplace('{b_city}', '{city}', $source, __FILE__, __LINE__);
$source = strReplace('{state.state}', '{state}', $source, __FILE__, __LINE__);
$source = strReplace('{country.country}', '{country}', $source, __FILE__, __LINE__);
$source = strReplace('{b_zipcode}', '{zipcode}', $source, __FILE__, __LINE__);
$source = strReplace('{subject}', '{subj}', $source, __FILE__, __LINE__);

?>
