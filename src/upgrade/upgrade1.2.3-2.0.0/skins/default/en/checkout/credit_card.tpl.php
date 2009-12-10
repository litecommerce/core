<?php

$source = strReplace('<table width="100%" border="1" border="0" cellspacing="0" cellpadding="2">', '<table width="100%" border="0" border="0" cellspacing="0" cellpadding="2">', $source, __FILE__, __LINE__);
$source = strReplace('<option FOREACH="card_types,card" value="{card.code:r}" selected="{isSelected(card,#code#,cart.details.cc_type)}">{card.card_type:h}</option>', '<option FOREACH="cart.paymentMethod.cardTypes,card" value="{card.code:r}" selected="{isSelected(card,#code#,cart.details.cc_type)}">{card.card_type:h}</option>', $source, __FILE__, __LINE__);
$source = strReplace('action=conditions', 'mode=terms_conditions', $source, __FILE__, __LINE__);
$source = strReplace('action=privacy_statement', 'mode=privacy_statement', $source, __FILE__, __LINE__);

?>
