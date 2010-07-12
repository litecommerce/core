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

namespace XLite\Model;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XliteForm extends AModel
{

    /**
    * @var string $alias The forms database table alias.
    * @access public
    */	
    public $alias = "forms";

    public $primaryKey = array('form_id', "session_id");

    /**
    * default payment method orider field
    */	
    public $defaultOrder = "date";

    /**
    * @var array $fields The form properties.
    * @access private
    */	
    public $fields = array(
        'form_id'    => '',
        'session_id'  => '',
        'date'       => 0
    );

    function collectGarbage($session_id = null)
    {
        if (!is_null($session_id)) {
            $session_id = addslashes($session_id);
            $where = "session_id='$session_id'";
            $count = $this->count($where);
        } else {
            $count = $this->count();
        }
        $max_count = $this->getMaxFormsPerSession();
        if ($count > $max_count) {
            // don't delete more than 100 at once
            $delete_count = min(100, $count - $max_count);
            $table = $this->getTable();

            $where = (!is_null($session_id))?"WHERE session_id='$session_id'":"";
            $query = "SELECT date FROM $table $where ORDER BY date LIMIT $delete_count, 1";
            $delete_date = $this->db->getOne($query);

            $where = (!is_null($session_id))?"WHERE session_id='$session_id' AND date < '$delete_date'":"WHERE date < '$delete_date'";
            $query = "DELETE FROM $table $where LIMIT $delete_count";
            $this->db->query($query);
        }
    }

    function getMaxFormsPerSession()
    {
        return (0 >= ($maxCount = \XLite::getInstance()->getOptions(array('HTML_Template_Flexy', 'max_forms_per_session')))) ? 100 : $maxCount;
    }
}
