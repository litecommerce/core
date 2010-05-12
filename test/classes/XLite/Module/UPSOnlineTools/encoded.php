<?php

/**
* @version $Id$
*/

/*
* Hiden methods
*/

//* Subspace optimization defenitions

define('COMBINE_ITERATION_LIMIT', 10);
//*/

// Tune params
define("PACKING_SIMPLIFY_AFTER", 24);
define("PACKING_EXECUTION_TIME", 3600);

///////////////////////////////////////////////////////////////////////////////////////////
////////////// Bin-packing algorithm methods
///////////////////////////////////////////////////////////////////////////////////////////
function UPSOnlineTools_rotateItem($item)
{
    $temp = $item->get("width");
    $item->set("width", $item->get("length"));
    $item->set("length", $temp);

    return $item;
}

function UPSOnlineTools_dropItems(&$items)
{
    foreach ($items as $k=>$item) {
        if ($item->get("handle_care"))
            continue;

        $temp = $item->get("height");
        $items[$k]->set("height", $item->get("length"));
        $items[$k]->set("length", $temp);
    }
}

function UPSOnlineTools_sort_by_width($a, $b)
{
    if ($a->get("width") == $b->get("width")) {
        if ($a->get("length") < $b->get("length")) {
            return true;
        }
    }

    if ($a->get("width") < $b->get("width")) {
        return true;
    }

    return false;
}

function UPSOnlineTools_sort_by_height($a, $b)
{
    if ($a->get("height") < $b->get("height")) {
        return true;
    }

    return false;
}

function UPSOnlineTools_sort_by_square($a, $b)
{
    if ($a->getSquare() < $b->getSquare())
        return true;

    return false;
}


function UPSOnlineTools_orientItems($width, $length, $height, &$items)
{
    $refine_items = array();
    $skipped_items = array();

    // Check for problem is solveable
    foreach ($items as $k=>$item) {
        $skipped = false;
        if ($item->get("handle_care")) {
            if (($item->get("height") > $height) || 
                ($item->get("width") > $width || $item->get("length") > $length) &&
                ($item->get("width") > $length || $item->get("length") > $width)) {
                $skipped_items[] = $item;
                $skipped = true;
            }
        } else {
            if (($item->get("height") > $height || $item->get("width") > $width || $item->get("length") > $length) &&
                ($item->get("height") > $height || $item->get("width") > $length || $item->get("length") > $width) &&
                ($item->get("height") > $width || $item->get("width") > $height || $item->get("length") > $length) &&
                ($item->get("height") > $length || $item->get("width") > $width || $item->get("length") > $height) &&
                ($item->get("height") > $width || $item->get("width") > $length || $item->get("length") > $height) &&
                ($item->get("height") > $length || $item->get("width") > $height || $item->get("length") > $width)) {
                $skipped_items[] = $item;
                $skipped = true;
            }
        }

        if (!$skipped) {
            $refine_items[] = $item;
        }
    }

    $items = $refine_items;
    $refine_items = null;

    // "Drop" items on "best" side
    foreach ($items as $k=>$item) {
        if ($item->get("handle_care"))
            continue;

        $plane = 1;
        $s1 = $item->get("width") * $item->get("length");
        $s2 = $item->get("width") * $item->get("height");
        $s3 = $item->get("length") * $item->get("height");

        if (($s2 >= $s1 && $s2 >= $s3) && (($width >= $item->get("width") && $length >= $item->get("height")) || ($length >= $item->get("width") && $width >= $item->get("height")))) {
            $plane = 2;
        } elseif (($s3 > $s1 && $s3 > $s2) && (($width >= $item->get("length") && $length >= $item->get("height")) || ($width >= $item->get("height") && $length >= $item->get("length")))) {
            $plane = 3;
        }

        switch ($plane) {
            case 2:    // by width
                $temp = $item->get("length");
                $item->set("length", $item->get("height"));
                $item->set("height", $temp);
            break;

            case 3:    // by height
                $temp = $item->get("width");
                $item->set("width", $item->get("height"));
                $item->set("height", $temp);
            break;

            default:
                // item in right position
            break;
        }

        $items[$k] = $item;
    }

    // Rotate items belong Z (vertical) axis
    $max_width = 0;
    foreach ($items as $k=>$item) {
        if ($items[$k]->get("length") > $items[$k]->get("width")) {
            $temp = $items[$k]->get("width");
            $items[$k]->set("width", $items[$k]->get("length"));
            $items[$k]->set("length", $temp);
        }

        if ($items[$k]->get("width") > $max_width)
            $max_width = $items[$k]->get("width");
    }

    return $skipped_items;
}

function UPSOnlineTools_optimize_subspaces(&$subspaces, $optimize_method)
{
    $methods = array();

    if ($optimize_method & XLite_Module_UPSOnlineTools_Model_Container::OPTIMIZE_DIVIDE_HOR) {
        $methods[] = "divide_subspaces_h";
    }

    if ($optimize_method & XLite_Module_UPSOnlineTools_Model_Container::OPTIMIZE_DIVIDE_VER) {
        $methods[] = "divide_subspaces_v";
    }

    if ($optimize_method & XLite_Module_UPSOnlineTools_Model_Container::OPTIMIZE_REVERSE) {
        $methods = array_reverse($methods);
    }

    $is_divided = false;
    foreach ($methods as $method) {
        if ($is_divided && ($optimize_method & XLite_Module_UPSOnlineTools_Model_Container::OPTIMIZE_COMBINE_INTERMEDIATE)) {
            UPSOnlineTools_combine_subspaces($subspaces);
        }

        switch ($method) {
            case "divide_subspaces_h":
                UPSOnlineTools_divide_subspaces_h($subspaces);
            break;

            default:
                UPSOnlineTools_divide_subspaces_v($subspaces);
            break;
        }

        $is_divided = true;
    }

    if ($optimize_method & XLite_Module_UPSOnlineTools_Model_Container::OPTIMIZE_COMBINE) {
        UPSOnlineTools_combine_subspaces($subspaces);
    }

}


function UPSOnlineTools_combine_subspaces(&$subspaces)
{
    $h = $subspaces;
    $v = $subspaces;

    UPSOnlineTools_combine_similar_subspaces_h($h);
    UPSOnlineTools_combine_similar_subspaces_v($v);

    if (count($h) <= 0 && count($v) <= 0)
        return;

    if (count($h) <= 0) {
        $subspaces = $v;
        return;
    }

    if (count($v) <= 0) {
        $subspaces = $h;
        return;
    }

    // analyze horizontal combine results
    $count_h = 0;
    $epsilon_h = 0;
    foreach ($h as $obj) {
        $epsilon_h += $obj->getEpsilon();
        $count_h++;
    }
    $epsilon_h /= $count_h;

    // analyze vertical combine results
    $count_v = 0;
    $epsilon_v = 0;
    foreach ($v as $obj) {
        $epsilon_v += $obj->getEpsilon();
        $count_v++;
    }
    $epsilon_v /= $count_v;

    $subspaces = ($epsilon_h > $epsilon_v) ? $h : $v;
}


function UPSOnlineTools_divide_subspaces_h(&$subspaces)
{
    $is_divide = false;

    do {
        $ignore = array();
        $buffer = array();

        $divide = false;
        $count = count($subspaces);

        for ($i = 0; $i < $count - 1; $i++) {
            if (in_array($i, $ignore))
                continue;

            for ($j = $i + 1; $j < $count; $j++) {
                if (in_array($i, $ignore) || in_array($j, $ignore))
                    continue;

                $a = $subspaces[$i];
                $b = $subspaces[$j];

                if ((($a->left + $a->width) == $b->left) ||
                    (($b->left + $b->width) == $a->left)) {
                    $a_v0 = $a->top;
                    $a_v1 = $a->top + $a->length;
                    $b_v0 = $b->top;
                    $b_v1 = $b->top + $b->length;

                    // skip object if they intersect by point
                    if ($b->top >= $a->top + $a->length ||
                        $a->top >= $b->top + $b->length) {
                        continue;
                    }

                    if (($a_v0 < $b_v0 && $a_v1 < $b_v0) ||
                        ($a_v0 > $b_v1 && $a_v1 > $b_v1) ||
                        ($a_v0 == $b_v0 && $a_v1 == $b_v1)) {
                        continue;
                    }

                    $div_a = false;
                    $div_b = false;

                    // object "a"
                    if ($b_v0 - $a_v0 > 0) {
                        $sub_a0 = new XLite_Module_UPSOnlineTools_Model_Subspace();
                        $sub_a0->init($a->width, ($b_v0 - $a_v0), $a->left, min($a_v0, $b_v0));
                        $buffer[] = $sub_a0;
                        $div_a = true;
                    }

                    if (min($b_v1, $a_v1) - max($b_v0, $a_v0) > 0) {
                        $sub_a1 = new XLite_Module_UPSOnlineTools_Model_Subspace();
                        $sub_a1->init($a->width, (min($b_v1, $a_v1) - max($b_v0, $a_v0)), $a->left, max($b_v0, $a_v0));
                        $buffer[] = $sub_a1;
                        $div_a = true;
                    }

                    if ($a_v1 - $b_v1 > 0) {
                        $sub_a2 = new XLite_Module_UPSOnlineTools_Model_Subspace();
                        $sub_a2->init($a->width, ($a_v1 - $b_v1), $a->left, min($b_v1, $a_v1));
                        $buffer[] = $sub_a2;
                        $div_a = true;
                    }

                    if ($div_a) {
                        $ignore[] = $i;
                    }

                    // object "b"
                    if ($a_v0 - $b_v0 > 0) {
                        $sub_b0 = new XLite_Module_UPSOnlineTools_Model_Subspace();
                        $sub_b0->init($b->width, ($a_v0 - $b_v0), $b->left, $b_v0);
                        $buffer[] = $sub_b0;
                        $div_b = true;
                    }

                    if (min($a_v1, $b_v1) - max($a_v0, $b_v0) > 0) {
                        $sub_b1 = new XLite_Module_UPSOnlineTools_Model_Subspace();
                        $sub_b1->init($b->width, (min($a_v1, $b_v1) - max($a_v0, $b_v0)), $b->left, max($b_v0, $a_v0));
                        $buffer[] = $sub_b1;
                        $div_b = true;
                    }

                    if ($b_v1 - $a_v1 > 0) {
                        $sub_b2 = new XLite_Module_UPSOnlineTools_Model_Subspace();
                        $sub_b2->init($b->width, ($b_v1 - $a_v1), $b->left, min($b_v1, $a_v1));
                        $buffer[] = $sub_b2;
                        $div_b = true;
                    }

                    if ($div_b) {
                        $ignore[] = $j;
                    }

                    if ($div_a || $div_b) {
                        $is_divide = true;
                        $divide = true;
                    }
                }
            }
        }

        // remove ignore subspaces from the list
        foreach ($ignore as $k) {
            unset($subspaces[$k]);
        }

        $subspaces = array_merge($subspaces, $buffer);
        $subspaces = array_values($subspaces);
    } while ($divide);

    return $is_divide;
}


// divide all subspaces by vertical 
function UPSOnlineTools_divide_subspaces_v(&$subspaces)
{
    $is_divide = false;

    do {
        $ignore = array();
        $buffer = array();

        $divide = false;
        $count = count($subspaces);

        for ($i = 0; $i < $count - 1; $i++) {
            if (in_array($i, $ignore))
                continue;

            for ($j = $i + 1; $j < $count; $j++) {
                if (in_array($i, $ignore) || in_array($j, $ignore))
                    continue;

                $a = $subspaces[$i];
                $b = $subspaces[$j];

                if ((($a->top + $a->length) == $b->top) ||
                    (($b->top + $b->length) == $a->top)) {
                    $a_v0 = $a->left;
                    $a_v1 = $a->left + $a->width;
                    $b_v0 = $b->left;
                    $b_v1 = $b->left + $b->width;

                    if ($b->left >= $a->left + $a->width ||
                        $a->left >= $b->left + $b->width) {
                        continue;
                    }

                    if (($a_v0 < $b_v0 && $a_v1 < $b_v0) ||
                        ($a_v0 > $b_v1 && $a_v1 > $b_v1) ||
                        ($a_v0 == $b_v0 && $a_v1 == $b_v1)) {
                        continue;
                    }

                    $div_a = false;
                    $div_b = false;

                    // object "a"
                    if ($b_v0 - $a_v0 > 0) {
                        $sub_a0 = new XLite_Module_UPSOnlineTools_Model_Subspace();
                        $sub_a0->init(($b_v0 - $a_v0), $a->length, min($a_v0, $b_v0), $a->top);
                        $buffer[] = $sub_a0;
                        $div_a = true;
                    }

                    if (min($b_v1, $a_v1) - max($b_v0, $a_v0) > 0) {
                        $sub_a1 = new XLite_Module_UPSOnlineTools_Model_Subspace();
                        $sub_a1->init((min($b_v1, $a_v1) - max($b_v0, $a_v0)), $a->length, max($b_v0, $a_v0), $a->top);
                        $buffer[] = $sub_a1;
                        $div_a = true;
                    }

                    if ($a_v1 - $b_v1 > 0) {
                        $sub_a2 = new XLite_Module_UPSOnlineTools_Model_Subspace();
                        $sub_a2->init(($a_v1 - $b_v1), $a->length, min($b_v1, $a_v1), $a->top);
                        $buffer[] = $sub_a2;
                        $div_a = true;
                    }
                    if ($div_a) {
                        $ignore[] = $i;
                    }

                    // object "b"
                    if ($a_v0 - $b_v0 > 0) {
                        $sub_b0 = new XLite_Module_UPSOnlineTools_Model_Subspace();
                        $sub_b0->init(($a_v0 - $b_v0), $b->length, $b_v0, $b->top);
                        $buffer[] = $sub_b0;
                        $div_b = true;
                    }

                    if (min($a_v1, $b_v1) - max($a_v0, $b_v0) > 0) {
                        $sub_b1 = new XLite_Module_UPSOnlineTools_Model_Subspace();
                        $sub_b1->init((min($a_v1, $b_v1) - max($a_v0, $b_v0)), $b->length, max($b_v0, $a_v0), $b->top);
                        $buffer[] = $sub_b1;
                        $div_b = true;
                    }

                    if ($b_v1 - $a_v1 > 0) {
                        $sub_b2 = new XLite_Module_UPSOnlineTools_Model_Subspace();
                        $sub_b2->init(($b_v1 - $a_v1), $b->length, min($b_v1, $a_v1), $b->top);
                        $buffer[] = $sub_b2;
                        $div_b = true;
                    }

                    if ($div_b) {
                        $ignore[] = $j;
                    }

                    if ($div_a || $div_b) {
                        $is_divide = true;
                        $divide = true;
                    }
                }
            }
        }

        // remove ignore subspaces from the list
        foreach ($ignore as $k) {
            unset($subspaces[$k]);
        }

        $subspaces = array_merge($subspaces, $buffer);
        $subspaces = array_values($subspaces);
    } while ($divide);

    return $is_divide;
}


function UPSOnlineTools_combine_similar_subspaces_h(&$subspaces)
{
    usort($subspaces, "UPSOnlineTools_sort_by_square");
    $subspaces = array_values($subspaces);

    $limit = COMBINE_ITERATION_LIMIT;
    do {
        $result = UPSOnlineTools_combine_horizontal($subspaces);
        $result |= UPSOnlineTools_combine_vertical($subspaces);
        $limit--;
    } while ($result && ($limit > 0));

}

function UPSOnlineTools_combine_similar_subspaces_v(&$subspaces)
{
    usort($subspaces, "UPSOnlineTools_sort_by_square");
    $subspaces = array_values($subspaces);

    $limit = COMBINE_ITERATION_LIMIT;
    do {
        $result = UPSOnlineTools_combine_vertical($subspaces);
        $result |= UPSOnlineTools_combine_horizontal($subspaces);
        $limit--;
    } while ($result && ($limit > 0));

}

function UPSOnlineTools_combine_horizontal(&$subspaces)
{
    $is_combine = false;

    $ignore = array();
    $combined_spaces = array();

    // combine horizontal
    do {
        $combined = false;

        $count = count($subspaces);
        for ($i = 0; $i < $count - 1; $i++) {
            if (in_array($i, $ignore))
                continue;

            for ($j = $i + 1; $j < $count; $j++) {
                if ($i == $j || in_array($i, $ignore) || in_array($j, $ignore))
                    continue;

                $a = $subspaces[$i];
                $b = $subspaces[$j];

                if ($a->top != $b->top)
                    continue;

                if ((($a->left + $a->width) == $b->left ||
                    ($b->left + $b->width) == $a->left) && $a->length == $b->length ) {

                    if ($a->left > $b->left) {
                        $comb = $b;
                        $comb->width += $a->width;
                    } else {
                        $comb = $a;
                        $comb->width += $b->width;
                    }

                    $ignore[] = $i;
                    $ignore[] = $j;

                    $subspace = new XLite_Module_UPSOnlineTools_Model_Subspace();
                    $subspace->init($comb->width, $comb->length, $comb->left, $comb->top);
                    $combined_spaces[] = $subspace;

                    $combined = true;
                    $is_combine = true;
                    break;
                }
            }
        }

    } while ($combined);

    foreach ($subspaces as $k=>$v) {
        if (in_array($k, $ignore))
            continue;

        $combined_spaces[] = $v;
    }

    $subspaces = array_values($combined_spaces);

    return $is_combine;
}


function UPSOnlineTools_combine_vertical(&$subspaces)
{
    $ignore = array();
    $combined_spaces = array();

    $is_combine = false;

    // combine vertical
    do {
        $combined = false;

        $count = count($subspaces);
        for ($i = 0; $i < $count - 1; $i++) {
            if (in_array($i, $ignore))
                continue;

            for ($j = $i + 1; $j < $count; $j++) {
                if ($i == $j || in_array($i, $ignore) || in_array($j, $ignore))
                    continue;

                $a = $subspaces[$i];
                $b = $subspaces[$j];

                if ($a->left != $b->left)
                    continue;

                if ((($a->top + $a->length) == $b->top ||
                    ($b->top + $b->length) == $a->top) && $a->width == $b->width ) {

                    if ($a->top > $b->top) {
                        $comb = $b;
                        $comb->length += $a->length;
                    } else {
                        $comb = $a;
                        $comb->length += $b->length;
                    }

                    $ignore[] = $i;
                    $ignore[] = $j;

                    $subspace = new XLite_Module_UPSOnlineTools_Model_Subspace();
                    $subspace->init($comb->width, $comb->length, $comb->left, $comb->top);
                    $combined_spaces[] = $subspace;

                    $is_combine = true;
                    $combined = true;
                    break;
                }
            }
        }
    } while ($combined);

    foreach ($subspaces as $k=>$v) {
        if (in_array($k, $ignore))
            continue;

        $combined_spaces[] = $v;
    }

    $subspaces = array_values($combined_spaces);

    return $is_combine;
}

//
// Calculates weight units
// Supported units: lbs, oz, kg, g
//
//function func_weight_convert($weight, $from_unit="lbs", $to_unit="kg", $precision=null)
function UPSOnlineTools_convertWeight($weight, $from_unit="lbs", $to_unit="kg", $precision=null)
{
    $from_unit = strtolower($from_unit);
    $to_unit = strtolower($to_unit);

    if (strcmp($from_unit, $to_unit) != 0) {
        $units = array(
            "lbs-oz"    => 16,
            "lbs-g"        => 453.59237,
            "kg-lbs"    => 2.20462262,
            "kg-oz"        => 35.2739619,
            "kg-g"        => 1000,
            "oz-g"        => 28.3495231
        );

        $rate = 1.0;
        if (array_key_exists("$from_unit-$to_unit", $units)) {
            $rate = $units["$from_unit-$to_unit"];
        } else if (array_key_exists("$to_unit-$from_unit", $units)) {
            $rate = $units["$to_unit-$from_unit"];
            $rate = (($rate <= 0) ? 1.0 : (1.0 / $rate));
        }

        $weight = $weight * $rate;
    }

    return ((is_null($precision)) ? ceil($weight) : round($weight, intval($precision)));
}


function UPSOnlineTools_packItems($width, $length, $height, $weight, &$items, $optimize_method)
{
    $index = 1;
    $last_count = count($items);

    // orient items and check dimensions
    $skiped_items = UPSOnlineTools_orientItems($width, $length, $height, $items);
    if (count($items) <= 0) {
        $items = $skiped_items;
        return false;
    }

    // mark all overweight items as skipped
    if ($weight > 0) {
        $items_next = array();
        foreach ($items as $item) {
            if ($weight < $item->get("weight")) {
                $skiped_items[] = $item;
            } else {
                $items_next[] = $item;
            }
        }

        $items = $items_next;
        $items_next = null;
    }

    // solve
    $containers = array();
    while (count($items) > 0) {
        $container = new XLite_Module_UPSOnlineTools_Model_Container();
        $container->setDimensions($width, $length, $height);
        $container->setWeightLimit($weight);
        $container->setOptimizeMethod($optimize_method);

        $result = $container->progressive_solve($items);

        if (!$result) {
            $items = $skiped_items;
            return false;
        }

        $container->container_id = $index++;

        $containers[] = $container;
        $last_count = count($items);
    }

    $items = $skiped_items;

    return $containers;
}


function UPSOnlineTools_solve_binpack($width, $length, $height, $weight, &$items)
{
    $back_items = $items;

    $_max_execution_time = ini_get("max_execution_time");
    ini_set("max_execution_time", PACKING_EXECUTION_TIME);

    if (count($items) > PACKING_SIMPLIFY_AFTER) {
        $presets = array(XLite_Module_UPSOnlineTools_Model_Container::OPTIMIZE_PRESET_3);
    } else {
        $presets = array(XLite_Module_UPSOnlineTools_Model_Container::OPTIMIZE_PRESET_1, XLite_Module_UPSOnlineTools_Model_Container::OPTIMIZE_PRESET_2, XLite_Module_UPSOnlineTools_Model_Container::OPTIMIZE_PRESET_3, XLite_Module_UPSOnlineTools_Model_Container::OPTIMIZE_PRESET_4, XLite_Module_UPSOnlineTools_Model_Container::OPTIMIZE_PRESET_5, XLite_Module_UPSOnlineTools_Model_Container::OPTIMIZE_PRESET_6);
    }

    $found = false;
    $best_result = array();
    $best_result_count = count($items);
    $oversize_items = array();
    foreach ($presets as $preset) {
        $items = $back_items;
        $result = UPSOnlineTools_packItems($width, $length, $height, $weight, $items, $preset);
        if ($result === false) {
            continue;
        }

        $found = true;
        if (count($result) < $best_result_count || count($best_result) <= 0) {
            $best_result = $result;
            $best_result_count = count($best_result);
            $oversize_items = $items;
        }
    }

    $items = (($found) ? $oversize_items : $back_items);

    ini_set("max_execution_time", $_max_execution_time);

    return (($found) ? $best_result : false);
}


// Solver's classes hiden methods
function UPSOnlineTools_placeBox($_this, $_width, $_length)
{
    if (!$_this->isPlaceable($_width, $_length))
        return false;

    $sub = array(
        0 => new XLite_Module_UPSOnlineTools_Model_Subspace(),
        1 => new XLite_Module_UPSOnlineTools_Model_Subspace(),
    );

    $a = new XLite_Module_UPSOnlineTools_Model_Subspace();
    $a->init($_this->width, ($_this->length - $_length), $_this->left, ($_this->top + $_length));

    $b = new XLite_Module_UPSOnlineTools_Model_Subspace();
    $b->init(($_this->width - $_width), ($_this->length), ($_this->left + $_width), ($_this->top));

    $eps_a = $a->getEpsilon();
    $eps_b = $b->getEpsilon();

    // use more squareable first
    if ($eps_a > $eps_b) {
        $sub[0]->init($_this->width, ($_this->length - $_length), $_this->left, ($_this->top + $_length));
        $sub[1]->init(($_this->width - $_width), ($_length), ($_this->left + $_width), ($_this->top));
    } else {
        $sub[0]->init(($_this->width - $_width), ($_this->length), ($_this->left + $_width), ($_this->top));
        $sub[1]->init($_width, ($_this->length - $_length), $_this->left, ($_this->top + $_length));
    }

    return $sub;
}

function UPSOnlineTools_progressive_solve($_this, &$items)
{
    $use_overlaped = true;
    do {
        $level = $_this->getNextLevel($use_overlaped);

        do {
            $item_weight_limit = round(($_this->getWeightLimit() - $_this->getWeight() - $level->getWeight()), 2);
            $res = $_this->progressive_placeItem($level, $items, $item_weight_limit);
        } while ($res);

        if ($level->getItemsCount() <= 0) {
            // try again, but with non-overlaped new level
            if ($use_overlaped === true) {
                $use_overlaped = false;
                continue;
            }

            break;
        }

        $use_overlaped = true;

        // Drop all items on the front side and try to pack item into level
        UPSOnlineTools_dropItems($items);
        do {
            // optimize on each step
            $level->optimizeSubspaces(XLite_Module_UPSOnlineTools_Model_Container::OPTIMIZE_PRESET_6);

            $item_weight_limit = round(($_this->getWeightLimit() - $_this->getWeight() - $level->getWeight()), 2);
            $res = $_this->progressive_placeItem($level, $items, $item_weight_limit);
        } while ($res);

        UPSOnlineTools_dropItems($items);


        // Add level to container
        $_this->addLevel($level);
    } while(count($items) > 0);

    return true;
}

function UPSOnlineTools_progressive_placeItem($_this, &$level, &$items, $item_weight_limit)
{
    // sorting...
    $avg_height = ceil($_this->height * 0.5);

    usort($items, "UPSOnlineTools_sort_by_height");

    $a = $b = array();
    foreach ($items as $item) {
        if ($item->get("height") > $avg_height) {
            $a[] = $item;
        } else {
            $b[] = $item;
        }
    }

    usort($a, "UPSOnlineTools_sort_by_width");
    usort($b, "UPSOnlineTools_sort_by_width");

    $items = array_merge($a, $b);

    // start comparision...

    $place_method = "";
    $place_item = false;

    $spaces = $level->getSubspaces();

    foreach ($items as $key=>$item) {
        if ($item->get("weight") > $item_weight_limit && $_this->getWeightLimit() > 0) {
            continue;
        }

        if ($item->get("height") > $level->getHeight()) {
            continue;
        }

        // Try to place item in equal dimensions with "+/-" threshold
        foreach ($spaces as $k=>$space) {
            $dw = $space->width - $item->get("width");
            $dh = $space->length - $item->get("length");
            if ($dw >= 0 && $dw < $_this->threshold && $dh >= 0 && $dh < $_this->threshold) {
                $space_id = $k;
                $place_item = true;
                $place_method = "equal_dimensions_A";
            }

            // check 'rotated' item
            $dw = $space->width - $item->get("length");
            $dh = $space->length - $item->get("width");
            if ($dw >= 0 && $dw < $_this->threshold && $dh >= 0 && $dh < $_this->threshold) {
                $space_id = $k;
                $item = UPSOnlineTools_rotateItem($item);

                $place_item = true;
                $place_method = "equal_dimensions_B";
            }
        }

        // select space with one-size equal (with threshold)
        $_this->threshold2 = $_this->threshold; // try to switch this option to zero (0)
//        $_this->threshold2 = 0;
        if (!$place_item) {
            foreach ($spaces as $k=>$space) {
                $dw = $space->width - $item->get("width");
                $dh = $space->length - $item->get("length");
                if (($dw >= 0 && $dw < $_this->threshold2 && $space->length > $item->get("length")) ||
                    ($dh >= 0 && $dh < $_this->threshold && $space->width > $item->get("width"))) {
                    $space_id = $k;
                    $place_item = true;
                    $place_method = "one_size_A";
                }

                // check 'rotated' item
                $dw = $space->width - $item->get("length");
                $dh = $space->length - $item->get("width");
                if (($dw >= 0 && $dw < $_this->threshold2 && $space->length > $item->get("width")) ||
                    ($dh >= 0 && $dh < $_this->threshold && $space->width > $item->get("length"))) {
                    $space_id = $k;
                    $item = UPSOnlineTools_rotateItem($item);
                    $place_item = true;
                    $place_method = "one_size_B";

                }
            }
        }


        // search by capacity/dimensions
        // if item is bigger than space, try to rotate
        if (!$place_item) {
            foreach ($spaces as $k=>$space) {
                if (!$space->isPlaceable($item->get("width"), $item->get("length"))) {
                    // if item not placeable, try to rotate it
                    $item = UPSOnlineTools_rotateItem($item);

                    if (!$space->isPlaceable($item->get("width"), $item->get("length"))) {
                        $item = UPSOnlineTools_rotateItem($item);
                        continue;
                    }
                }

                $space_id = $k;
                $place_item = true;
                break;
            }

        }

        // Place item found by search functions described above
        if ($place_item) {
            $space = $spaces[$space_id];

            $sub = $space->placeBox($item->get("width"), $item->get("length"));

            // add subspace as used
            $used_space = new XLite_Module_UPSOnlineTools_Model_Subspace();
            $used_space->init($item->get("width"), $item->get("length"), $space->left, $space->top);
            $used_space->setUpperLimit($level->getBottomHeight() + $item->get("height"));
            $level->addUsedSpace($used_space);

            unset($items[$key]);
            unset($spaces[$space_id]);
            $spaces = array_merge($spaces, $sub);

            $level->setSubspaces($spaces);


            // create new container item
            $cont_item = new XLite_Module_UPSOnlineTools_Model_ContainerItem();

            $cont_item->item_id = $item->get("OrderItemId");
            $cont_item->global_id = $item->get("GlobalId");
            $cont_item->setPosition($space->left, $space->top);
            $cont_item->setDimensions($item->get("width"), $item->get("length"), $item->get("height"));
            $cont_item->setWeight($item->get("weight"));

            $level->addItem($cont_item);

            return true;
        }
    }

    return false;
}

function UPSOnlineTools_getNextLevel($_this, $overlaped=true)
{
    if ($_this->getLevelsCount() <= 0) {
        $level = new XLite_Module_UPSOnlineTools_Model_ContainerLevel();
        $level->init(0, $_this->width, $_this->length, $_this->height);
        return $level;
    }

    $levels = $_this->getLevels();
    $last_level = array_pop($levels);

    $med_height = (($overlaped) ? $last_level->getMediumHeight() : $last_level->getHeight());
    $start_height = $last_level->getBottomHeight() + $med_height;

    $level = new XLite_Module_UPSOnlineTools_Model_ContainerLevel();
    $level->init($start_height, $_this->width, $_this->length, ($_this->height - $start_height));

    // move valid used subspaces to subspaces array
    $used = $last_level->getUsedSpaces();
    $sub = $last_level->getSubspaces();

    foreach ($used as $k=>$v) {
        $limit = $v->getUpperLimit();
        if ($limit > $start_height) {
            // dirt region
            continue;
        }

        $v->setUpperLimit(0);
        $sub[] = $v;
        unset($used[$k]);
    }

    $level->setUsedSpaces($used);
    $level->setSubspaces($sub);
    $level->setDirtSpaces($used);

    // optimize: try to combine subspaces
    $level->optimizeSubspaces($_this->optimize_method);

    return $level;
}


///////////////////////////////////////////////////////////////////
//////////////////////////// Visual methods ///////////////////////
///////////////////////////////////////////////////////////////////

function UPSOnlineTools_getColorByIndex($index)
{
    static $colors = array("SeaGreen", "Yellow", "Orange", "DeepSkyBlue ", "MediumSlateBlue", "Silver", "Violet", "Chartreuse", "Magenta", "Coral", "DarkKhaki ", "Cyan");
    $count = count($colors);

    $id = $index - $count * floor($index / count($colors));

    return $colors[$id];
}

function UPSOnlineTools_getLayoutSkinPath()
{
    // get layout path
    $layout = XLite_Model_Layout::getInstance();
    return $layout->get("path");
}

function UPSOnlineTools_displayLevel_gdlib($width, $length, $items, $dirt_regions, $_width)
{
    $scale = ($_width < $width) ? $width / $_width : $_width / $width;
    $_length = $length * $scale;

    $image = imagecreate($_width, $_length);
    
    // define colors
    $background = ImageColorAllocate($image, 120, 120, 120);
    $dirt = ImageColorAllocate($image, 0, 0, 0);
    $low_red = ImageColorAllocate($image, 150, 120, 120);

    $colors = array(
        ImageColorAllocate($image,230,223,188),     // blur
        ImageColorAllocate($image, 255, 0, 0),      // red
        ImageColorAllocate($image, 0, 255, 0),      // green
        ImageColorAllocate($image, 0, 0, 255),      // blue 
        ImageColorAllocate($image, 255, 255, 0),    // yellow
        ImageColorAllocate($image, 255, 0, 255),    // purple
        ImageColorAllocate($image, 0, 255, 255),    // magenta
        ImageColorAllocate($image, 250, 250, 250),  // white
    );

    $font_color = ImageColorAllocate($image, 0, 0, 0);

    $font_size = 12;

    if (is_array($items) && count($items) > 0) {
        foreach ($items as $item) {
            $color = array_shift($colors);
            $colors[] = $color;

            $left = $item["left"] * $scale;
            $right = ($item["left"] + $item["width"]) * $scale;
            $top = $item["top"] * $scale;
            $bottom = ($item["top"] + $item["length"]) * $scale;
            imagefilledrectangle($image, $left, $top, $right, $bottom, $color);
            imagerectangle($image, $left, $top, $right, $bottom, $dirt);
            imagestring($image, 5, $left+2, $top, $item["global_id"], $font_color);
        }
    }

    if (is_array($dirt_regions) && count($dirt_regions) > 0) {
        foreach ($dirt_regions as $region) {
            $left = $region["left"] * $scale;
            $right = $region["top"] * $scale;
            $top = ($region["left"] + $region["width"]) * $scale;
            $bottom = ($region["top"] + $region["length"]) * $scale;

            imagefilledrectangle($image, $left, $right, $top, $bottom, $dirt);
        }
    }

    ob_start();
    imagejpeg($image, "", 75);
    $content = ob_get_contents();
    ob_end_clean();

    return $content;
}

function UPSOnlineTools_displayContainer_div($_this, $container, $_left, $_top, $_width, $_height)
{
    if (!isset($container["levels"]) || count($container["levels"]) <= 0 || $_this->xlite->config->getComplex('UPSOnlineTools.display_gdlib')) {
        return;
    }

    // get layout path
    $layout_path = UPSOnlineTools_getLayoutSkinPath();

    // prepare div
    $html = "";
    $html .= '<div style="POSITION: relative; PADDING: 0px; MARGIN: 0px; border: 0px groove black;">';
    $html .= '<img src="'.$layout_path.'/images/modules/UPSOnlineTools/ups_box.gif">';

    $deltaH = $_height / $container["height"];
    $deltaW = $_width / $container["width"];

    $med_height = $_height / count($container["levels"]);

    $level_index = 0;
    foreach ($container["levels"] as $level) {
        if ($_this->xlite->config->getComplex('UPSOnlineTools.level_display_method') == 1) {
            // proportional
            $height = ceil($level["height"] * $deltaH);
            $top = - floor($level["bottom"] * $deltaH) + $_top + $_height - $height;
        } else {
            // actual
            $height = ceil($med_height);
            $top = - floor($med_height * $level_index) + $_top + $_height - $height;
        }

        $color = UPSOnlineTools_getColorByIndex($level_index);
        $html .= '<img style="POSITION: absolute; BACKGROUND-COLOR: '.$color.'; LEFT: '.$_left.'; TOP: '.$top.'; WIDTH: '.$_width.'; HEIGHT: '.$height.'px; MARGIN: 0px;" src="'.$layout_path.'/images/spacer.gif" title="Layer: #'.($level["level_id"]+1).'" />';

        $level_index++;
    }

    $html .= '</div>';

    return $html;
}

function UPSOnlineTools_displayLevel_div($width, $length, $items, $dirt_regions, $_width, $level_id)
{
    // get layout path
    $layout_path = UPSOnlineTools_getLayoutSkinPath();

    $scale = ($_width < $width) ? $width / $_width : $_width / $width;
    $_length = $length * $scale;

    // get level color
    $color = UPSOnlineTools_getColorByIndex($level_id);

    $html = "";
    $html .= '<div style="POSITION: relative; PADDING: 0px; MARGIN: 0px; width: '.($_width+2).'px; height: '.($_length+2).'px; border: 1px groove black; BACKGROUND-COLOR: '.$color.';" title="Layer: #'.($level_id+1).'">'."\n";

    // display level's boxes
    if (is_array($items) && count($items) > 0) {
        foreach ($items as $item) {
            $left = ($item["left"] * $scale) + 1;
            $top = ($item["top"] * $scale) + 1;
            $width = ($item["width"] * $scale) - 1;
            $height = ($item["length"] * $scale) - 1;

            if ($height > 10 && $width > 10) {
                $html .= '<div style="POSITION: absolute; BACKGROUND-IMAGE: url('.$layout_path.'/images/modules/UPSOnlineTools/white_bg.gif); BACKGROUND-REPEAT: repeat-x; BACKGROUND-POSITION: bottom; BACKGROUND-COLOR: white; LEFT: '.$left.'; TOP: '.$top.'; WIDTH: '.$width.'; HEIGHT: '.$height.'; border: 1px groove black;"'.(($item["title"]) ? ' title="'.$item["title"].'"' : "").'>'.$item["global_id"].'</div>'."\n";
            } else {
                $html .= '<img src="'.$layout_path.'/images/spacer.gif" style="POSITION: absolute; BACKGROUND-IMAGE: url('.$layout_path.'/images/modules/UPSOnlineTools/white_bg.gif); BACKGROUND-REPEAT: repeat-x; BACKGROUND-POSITION: bottom; BACKGROUND-COLOR: white; LEFT: '.$left.'; TOP: '.$top.'; WIDTH: '.$width.'; HEIGHT: '.$height.'; border: 1px groove black;"'.(($item["title"]) ? ' title="'.$item["title"].'"' : "").'>'."\n";
            }
        }

        // display dirt/used regions
        if (is_array($dirt_regions) && count($dirt_regions) > 0) {
            foreach ($dirt_regions as $region) {
                $left = ($region["left"] * $scale) + 1;
                $top = ($region["top"] * $scale) + 1;
                $width = ($region["width"] * $scale) - 1;
                $height = ($region["length"] * $scale) - 1;

                $html .= '<img src="'.$layout_path.'/images/spacer.gif" style="POSITION: absolute; BACKGROUND-IMAGE: url('.$layout_path.'/images/modules/UPSOnlineTools/shading.gif); LEFT: '.$left.'; TOP: '.$top.'; WIDTH: '.$width.'; HEIGHT: '.$height.'; border: 1px groove black;" title="Filled area">'."\n";
            }

        }
    }

    $html .= '</div>';

    return $html;
}

?>
