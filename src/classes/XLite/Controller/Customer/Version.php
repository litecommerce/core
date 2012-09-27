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

namespace XLite\Controller\Customer;

/**
 * Version 
 *
 */
class Version extends \XLite\Controller\Customer\ACustomer
{
    /**
     * Preprocessor for no-action ren
     *
     * @return void
     */
    protected function doNoAction()
    {
        \Includes\Utils\Operator::flush($this->getInfoMessage());

        exit(0);
    }

    /**
     * Method to compose different messages
     *
     * @return string
     */
    protected function getInfoMessage()
    {
        $result = '';

        $result .= $this->getVersionMessage() . LC_EOL;
        $result .= $this->getModulesMessage() . LC_EOL;

        return $result;
    }

    /**
     * Return info about current LC version
     *
     * @return string
     */
    protected function getVersionMessage()
    {
        return static::t('Version') . ': ' . \XLite::getInstance()->getVersion() . LC_EOL;
    }

    /**
     * Return info about installed modules
     *
     * @return string
     */
    protected function getModulesMessage()
    {
        $result = array();

        foreach (\Includes\Utils\ModulesManager::getActiveModules() as $data) {
            $result[] = '(' . $data['authorName'] . '): ' . $data['moduleName'] 
                . ' (v.' . $data['majorVersion'] . '.' . $data['minorVersion'] . ')';
        }

        return 'Installed modules:' . LC_EOL . ($result ? implode(LC_EOL, $result) : static::t('None'));
    }
}
