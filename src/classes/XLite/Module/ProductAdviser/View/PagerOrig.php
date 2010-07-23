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
 * @subpackage View
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Module\ProductAdviser\View;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Pager extends \XLite\View\PagerOrig
{
    function initView()
    {
    	if (parent::get('pageIDX') && !in_array(parent::get('pageIDX'), $this->params)) {
    		$this->params[] = parent::get('pageIDX');
    	}
    	if (parent::get('extraParameter') && !in_array(parent::get('extraParameter'), $this->params)) {
    		$this->params[] = parent::get('extraParameter');
    	}
        parent::initView();
    }

    function get($name)
    {
    	$result = parent::get($name);

    	if ($name == "pageID" && parent::get('pageIDX')) {
    		$result = isset($_REQUEST[parent::get('pageIDX')]) ? $_REQUEST[parent::get('pageIDX')] : 0;
        	if (count($this->get('pages')) <= $result) {
            	$result = count($this->get('pages')) - 1;
            }
    	}

    	return $result;
    }

    function set($name, $value)
    {
    	if ($name == "pageID" && parent::get('pageIDX')) {
    		parent::set(parent::get('pageIDX'), $value);
    	} else {
    		parent::set($name, $value);
    	}
    }

    function getPageUrls()
    {
    	if (parent::get('pageIDX')) {
            $result = array();
            $params = $this->getComplex('dialog.allParams');
            $params['pageID'] = null;
            if (parent::get('extraParameter') && isset($_REQUEST[parent::get('extraParameter')])) {
                $params[parent::get('extraParameter')] = $_REQUEST[parent::get('extraParameter')];
            }
            $dialog = $this->get('dialog');
            for ($i = 0; $i < count($this->get('pages')); $i++) {
                if ($i == 0) {
                    $params[parent::get('pageIDX')] = null; // exclude pageID for the first page
                } else {
                    $params[parent::get('pageIDX')] = $i;
                }
                $result[$i+1] = $dialog->getUrl($params);
            }
    	} else {
            $result = array();
            $params = $this->getComplex('dialog.allParams');
            if (parent::get('extraParameter') && isset($_REQUEST[parent::get('extraParameter')])) {
                $params[parent::get('extraParameter')] = $_REQUEST[parent::get('extraParameter')];
            }
            $dialog = $this->get('dialog');
            for ($i = 0; $i < count($this->get('pages')); $i++) {
                if ($i == 0) {
                    $params['pageID'] = null; // exclude pageID for the first page
                } else {
                    $params['pageID'] = $i;
                }
                $result[$i+1] = $dialog->getUrl($params);
            }
        }
        return $result;
    }
}
