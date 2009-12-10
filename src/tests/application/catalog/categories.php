<?php

// test category ID# 3 (Books)
// NOTE: categories should exist in database and be enabled in order test succeed

//say("Test category ID# 3");
$get = array("category_id" => 3);
sendRequest("category", "view", $get);

// check the result, two subcategory (Internet, Software) expected
$w =& Widget::getByName("Categories");
assert(count($w->dialog->subcategories) == 2 &&
    $w->dialog->subcategories[0]->get("category_id") == 23 &&
    $w->dialog->subcategories[1]->get("category_id") == 24);
?>
