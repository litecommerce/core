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

namespace XLite\Module\CDev\Demo\Controller\Admin;

/**
 * Login / logoff
 *
 */
class Login extends \XLite\Controller\Admin\Login implements \XLite\Base\IDecorator
{
    /**
     * Demo admin login
     *
     * @var string
     */
    protected $demoLogin = 'bit-bucket@litecommerce.com';

    /**
     * Demo admin password
     *
     * @var string
     */
    protected $demoPassword = 'master';


    /**
     * Handles the request.
     * Parses the request variables if necessary. Attempts to call the specified action function
     * FIXME - adapt after the mapping will be removed
     *
     * @return void
     */
    public function handleRequest()
    {
        parent::handleRequest();

        $this->set('login', $this->demoLogin);
        $this->set('password', $this->demoPassword);
        $this->set(
            'additional_note', 
            '<div style=\'width: 490px; text-align: center;\'>(login: ' . $this->demoLogin . ', password: ' . $this->demoPassword . ')</div>'
        );
        $this->set('additional_header', \XLite\Module\CDev\Demo\View\Demo::getAdditionalHeader());
    }
}
