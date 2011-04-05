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
 * PHP version 5.3.0
 *
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     3.0.0
 */

namespace XLite\Controller\Admin;

/**
 * States management page controller
 * 
 * @see   ____class_see____
 * @since 3.0.0
 */
class States extends \XLite\Controller\Admin\AAdmin
{
    /**
     * Return the current page title (for the content area)
     *
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getTitle()
    {
        return 'States';
    }

    /**
     * init 
     * 
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function init()
    {
        if (!in_array('country_code', $this->params)) {

            $this->params[] = 'country_code';
        }

        parent::init();

        $countryCode = isset(\XLite\Core\Request::getInstance()->country_code)
            ? \XLite\Core\Request::getInstance()->country_code
            : $this->config->General->default_country;

        $this->set(
            'country_code',
            $countryCode
        );
    }
   
    /**
     * getStates 
     * 
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getStates()
    {
        if (is_null($this->states)) {

            $this->states = \XLite\Core\Database::getRepo('XLite\Model\State')
                ->findByCountryCode($this->get('country_code'));
        }

        return $this->states;
    }


    /**
     * Common method to determine current location
     *
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getLocation()
    {
        return 'States';
    }

    /**
     * setObligatoryStatus 
     * 
     * @param mixed $status ____param_comment____
     *  
     * @return void
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
     * doActionAdd 
     * 
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionAdd()
    {

        $fields = array('country_code', 'code', 'state');

        $postData = \XLite\Core\Request::getInstance()->getData();

        foreach ($postData as $k => $v) {

            if (in_array($k, $fields)) {

                $postData[$k] = trim($v);
            }
        }

        $country = \XLite\Core\Database::getRepo('XLite\Model\Country')->find($postData['country_code']);

        if (!$country) {

            $this->set('valid', false);

            $this->setObligatoryStatus('country_code');
        
        } elseif (empty($postData['code'])) {

            $this->set('valid', false);

            $this->setObligatoryStatus('code');
        
        } elseif (empty($postData['state'])) {

            $this->set('valid', false);

            $this->setObligatoryStatus('state');
        
        } else {

            $found = false;

            foreach ($country->getStates() as $s) {

                if (
                    $s->getCode() == $postData['code'] 
                    || $s->getState() == $postData['state']
                ) {
                    $found = true;

                    break;
                }
            }

            if ($found) {

                $this->set('valid', false);

                $this->setObligatoryStatus('exists');

            } else {

                $state = new \XLite\Model\State();
                $state->map($postData);
                $state->country = $country;

                \XLite\Core\Database::getEM()->persist($state);
                \XLite\Core\Database::getEM()->flush();

                $this->setObligatoryStatus('added');
            }
        }
    }

    /**
     * doActionUpdate 
     * 
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionUpdate()
    {
        $stateData = array();

        if (isset(\XLite\Core\Request::getInstance()->state_data)) {

            $stateData = \XLite\Core\Request::getInstance()->state_data;
        }

        // use POST'ed data to modify state properties
        foreach ($stateData as $stateId => $stateData) {

            $state = \XLite\Core\Database::getRepo('XLite\Model\State')->find($stateId);

            $state->map($stateData);

            \XLite\Core\Database::getEM()->persist($state);
        }

        \XLite\Core\Database::getEM()->flush();

        $this->setObligatoryStatus('updated');
    }

    /**
     * doActionDelete 
     * 
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionDelete()
    {
        $states = array();

        if (isset(\XLite\Core\Request::getInstance()->delete_states)) {

            $states = \XLite\Core\Request::getInstance()->delete_states;
        }

        foreach ($states as $id => $stateId) {

            $state = \XLite\Core\Database::getEM()->find('XLite\Model\State', $stateId);

            if ($state) {

                \XLite\Core\Database::getEM()->remove($state);
            }
        }

        \XLite\Core\Database::getEM()->flush();

        $this->setObligatoryStatus('deleted');
    }
}
