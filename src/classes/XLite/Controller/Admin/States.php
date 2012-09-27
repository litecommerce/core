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
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\Controller\Admin;

/**
 * States management page controller
 *
 */
class States extends \XLite\Controller\Admin\AAdmin
{
    /**
     * Return the current page title (for the content area)
     *
     * @return string
     */
    public function getTitle()
    {
        return 'States';
    }

    /**
     * init
     *
     * @return void
     */
    public function init()
    {
        if (!in_array('country_code', $this->params)) {
            $this->params[] = 'country_code';
        }

        parent::init();

        $countryCode = isset(\XLite\Core\Request::getInstance()->country_code)
            ? \XLite\Core\Request::getInstance()->country_code
            : \XLite\Core\Config::getInstance()->General->default_country;

        $this->set('country_code', $countryCode);
    }

    /**
     * getStates
     *
     * @return void
     */
    public function getStates()
    {
        if (!isset($this->states)) {
            $this->states = \XLite\Core\Database::getRepo('XLite\Model\State')
                ->findByCountryCode($this->get('country_code'));
        }

        return $this->states;
    }

    /**
     * doActionAdd
     *
     * @return void
     */
    protected function doActionAdd()
    {
        $fields   = array('country_code', 'code', 'state');
        $postData = \XLite\Core\Request::getInstance()->getData();

        foreach ($postData as $k => $v) {
            if (in_array($k, $fields)) {
                $postData[$k] = trim($v);
            }
        }

        $country = \XLite\Core\Database::getRepo('XLite\Model\Country')->find($postData['country_code']);

        if (!$country) {
            $this->set('valid', false);

        } elseif (empty($postData['code'])) {
            $this->set('valid', false);

        } elseif (empty($postData['state'])) {
            $this->set('valid', false);

        } else {
            $found = false;

            foreach ($country->getStates() as $s) {
                if ($s->getCode() == $postData['code'] || $s->getState() == $postData['state']) {
                    $found = true;

                    break;
                }
            }

            if ($found) {
                $this->set('valid', false);

            } else {
                $state = new \XLite\Model\State();
                $state->map($postData);
                $state->country = $country;

                \XLite\Core\Database::getEM()->persist($state);
                \XLite\Core\Database::getEM()->flush();

                \XLite\Core\Database::getRepo('XLite\Model\Country')->cleanCache();
            }
        }
    }

    /**
     * doActionUpdate
     *
     * @return void
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

        \XLite\Core\Database::getRepo('XLite\Model\Country')->cleanCache();
    }

    /**
     * doActionDelete
     *
     * @return void
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

        \XLite\Core\Database::getRepo('XLite\Model\Country')->cleanCache();
    }
}
