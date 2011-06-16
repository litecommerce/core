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
 * @package    Tests
 * @subpackage Web
 * @author     Creative Development LLC <info@cdev.ru>
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      1.0.0
 */

require_once __DIR__ . '/AAdmin.php';

class XLite_Web_Admin_ModuleManage extends XLite_Web_Admin_AAdmin
{
    const UNINSTALL_BUTTON = '//a[@class="uninstall"]';

    const ENABLE_CHECKBOX = '//span[@class="disable"]/input[@type="checkbox"]';

    const DISABLE_CHECKBOX = '//span[@class="enable"]/input[@id="switch1"]';

    const SAVE_CHANGES = '//div[@class="buttons modules-buttons"]/button[@class="action" and @type="submit"]';

    public function testModulesManage()
    {
        $this->logIn();

        $this->open('admin.php?target=addons_list_installed');

        $this->click(self::ENABLE_CHECKBOX);

        $this->clickAndWait(self::SAVE_CHANGES);

        $this->chooseCancelOnNextConfirmation();

        // Try to uninstall
        $this->click(self::UNINSTALL_BUTTON);

        $this->assertEquals($this->getConfirmation(), 'Are you sure you want to uninstall this add-on?', 'Wrong confirmation dialog');

        $this->click(self::DISABLE_CHECKBOX);

        $this->clickAndWait(self::SAVE_CHANGES);

    }
}
