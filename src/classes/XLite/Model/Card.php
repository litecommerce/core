<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * LiteCommerce
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to licensing@litecommerce.com so we can send you a copy immediately.
 * 
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage Model
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Model_Card extends XLite_Model_Abstract
{

    /**
    * @var string $alias The credit cards database table alias.
    * @access public
    */	
    public $alias = "card_types";

    public $primaryKey = array('code');

    /**
    * default payment method orider field
    */	
    public $defaultOrder = "orderby";

    /**
    * @var array $fields The card properties.
    * @access private
    */	
    public $fields = array(
            'code'       => '',  
            'card_type'  => '',
            'cvv2'       => 1,
            'orderby'    => '0',
            'enabled'    => 1
        );

    function isVisa($num) {
        $first4 = 0+substr($num,0,4);
        return ($first4>=4000 && $first4<=4999);
    }

    function isMc($num) {
        $first4 = 0+substr($num,0,4);
        return ($first4>=5100 && $first4<=5999);
    }

    function isAmex($num) {
        $first4 = 0+substr($num,0,4);
        return (($first4>=3400 && $first4<=3499) || ($first4>=3700 && $first4<=3799));
    }

    function isDiners($num) {
        $first4 = 0+substr($num,0,4);
        return (($first4>=3000 && $first4<=3059) || ($first4>=3600 && $first4<=3699) || ($first4>=3800 && $first4<=3889));
    }

    function isDc($num) {
        $first4 = 0+substr($num,0,4);
        return ($first4==6011);
    }

    function isJcb($num) {
        $first4 = 0+substr($num,0,4);
        return ($first4>=3528 && $first4<=3589);
    }

    function isTest($num,$rules) {
        $result = false;
        $num = trim($num);
        for ($ndx=0; $ndx<count($rules); ++$ndx) {
            list($hiPrefix,$loPrefix,$valLength,$issueLength,$startDateLength) = split(",",$rules[$ndx]);
            $prefix = substr($num,0,strlen($hiPrefix));
    
            if ($prefix>=$hiPrefix && $prefix<=$loPrefix && strlen($num)==$valLength) {
                $result = true;
                break;
            }
        }
        return $result;
    }

    function isSwitch($num) {
        $rules = array("490302,490309,18,1","490335,490339,18,1","491101,491102,16,1","491174,491182,18,1","493600,493699,19,1","564182,564182,16,2","633300,633300,16,0","633301,633301,19,1","633302,633349,16,0","675900,675900,16,0","675901,675901,19,1","675902,675904,16,0","675905,675905,19,1","675906,675917,16,0","675918,675918,19,1","675919,675937,16,0","675938,675940,18,1","675941,675949,16,0","675950,675962,19,1","675963,675997,16,0","675998,675998,19,1","675999,675999,16,0");
    
        return $this->isTest($num,$rules);
    }

    function isSolo($num) {
        $rules = array("633450,633453,16,0","633454,633457,16,0","633458,633460,16,0","633461,633461,18,1","633462,633472,16,0","633473,633473,18,1","633474,633475,16,0","633476,633476,19,1","633477,633477,16,0","633478,633478,18,1","633479,633480,16,0","633481,633481,19,1","633482,633489,16,0","633490,633493,16,1","633494,633494,18,1","633495,633497,16,2","633498,633498,19,1","633499,633499,18,1","676700,676700,16,0","676701,676701,19,1","676702,676702,16,0","676703,676703,18,1","676704,676704,16,0","676705,676705,19,1","676706,676707,16,2","676708,676711,16,0","676712,676715,16,0","676716,676717,16,0","676718,676718,19,1","676719,676739,16,0","676740,676740,18,1","676741,676749,16,0","676750,676762,19,1","676763,676769,16,0","676770,676770,19,1","676771,676773,16,0","676774,676774,18,1","676775,676778,16,0","676779,676779,18,1","676780,676781,16,0","676782,676782,18,1","676783,676794,16,0","676795,676795,18,1","676796,676797,16,0","676798,676798,19,1","676799,676799,16,0");
    
        return $this->isTest($num,$rules);
    }

    function isDelta($num) {
        return false;
    }

    function isCVV2()
    {
        return (bool) $this->get('cvv2');
    }

    function filter()
    {
        if (!$this->xlite->is('adminZone')) {
            if (!$this->get('enabled')) {
                return false;
            }
        }
        return parent::filter();
    }
}
