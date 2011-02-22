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
 * @subpackage Controller
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    GIT: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Controller\Admin;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Countries extends \XLite\Controller\Admin\AAdmin
{
    /**
     * Return the current page title (for the content area)
     *
     * @return string
     * @access public
     * @since  3.0.0
     */
    public function getTitle()
    {
        return $this->t('Countries');
    }

    /**
     * Get all countries 
     * TODO - move to widget
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCountries()
    {
        return \XLite\Core\Database::getRepo('XLite\Model\Country')->findAllCountries();
    }


    /**
     * Common method to determine current location
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getLocation()
    {
        return $this->t('Countries');
    }

    /**
     * setObligatoryStatus 
     * 
     * @param mixed $status ____param_comment____
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function setObligatoryStatus($status)
    {
        if (!in_array('status', $this->params)) {
            $this->params[] = 'status';
        }
        $this->set('status', $status);
    }

    /**
     * action 'update'
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionUpdate()
    {
        $update = false;

        foreach (\XLite\Core\Database::getRepo('XLite\Model\Country')->findAll() as $country) {
            if (isset(\XLite\Core\Request::getInstance()->countries[$country->getCode()])) {
                $data['eu_member'] = isset($data['eu_member']);
                $data['enabled'] = isset($data['enabled']);
                $country->map($data);
                $update = true;
            }
        }

        if ($update) {
            \XLite\Core\Database::getEM()->flush();
        }

        $this->setObligatoryStatus('updated');
    }

    /**
     * action 'add'
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionAdd()
    {
        if (empty(\XLite\Core\Request::getInstance()->code)) {
            $this->set('valid', false);
            $this->setObligatoryStatus('code');

        } else {

            $country = \XLite\Core\Database::getRepo('XLite\Model\Country')
                ->find(\XLite\Core\Request::getInstance()->code);

            if ($country) {
                $this->set('valid', false);
                $this->setObligatoryStatus('exists');

            } else {

                if (empty(\XLite\Core\Request::getInstance()->country)) {
                    $this->set('valid', false);
                    $this->setObligatoryStatus('country');

                } else {

                    $country = new \XLite\Model\Country();

                    $country->map(\XLite\Core\Request::getInstance()->getData());
                    $country->eu_member = isset(\XLite\Core\Request::getInstance()->eu_member);
                    $country->enabled = isset(\XLite\Core\Request::getInstance()->enabled);

                    \XLite\Core\Database::getEM()->persist($country);
                    \XLite\Core\Database::getEM()->flush();

                    $this->setObligatoryStatus('added');
                }
            }
        }
    }

    /**
     * action 'delete'
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionDelete()
    {
        $countries = \XLite\Core\Request::getInstance()->delete_countries;

        if ( is_array($countries) && count($countries) > 0 ) {
            foreach ($countries as $code) {
                $country = \XLite\Core\Database::getRepo('XLite\Model\Country')->find($code);
                if ($country) {
                    \XLite\Core\Database::getEM()->remove($country);
                }
            }
            \XLite\Core\Database::getEM()->flush();
        }

        $this->setObligatoryStatus('deleted');
    }
}
