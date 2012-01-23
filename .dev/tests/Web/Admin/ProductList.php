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
 *
 * @resource product
 */

require_once __DIR__ . '/AAdmin.php';

class XLite_Web_Admin_ProductList extends XLite_Web_Admin_AAdmin
{
    const PRODUCT_LIST_PAGE = 'admin.php?target=product_list';

    /**
     * Test of visual reactions - row switchers and hovers
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.16
     */
    public function testReactions()
    {
        $this->logIn();

        $this->open(static::PRODUCT_LIST_PAGE);

        $saveDisabledXpath = 'xpath=//form/div[@class="sticky-panel"]/div/div/div/button[contains(@class,"submit") and contains(@class,"disabled")]';
        $saveEnabledXpath = 'xpath=//form/div[@class="sticky-panel"]/div/div/div/button[contains(@class,"submit") and not(contains(@class,"disabled"))]';
        $cancelDisabledXpath = 'xpath=//form/div[@class="sticky-panel"]/div/div/div/a[contains(@class,"cancel") and contains(@class,"disabled")]';
        $cancelEnabledXpath = 'xpath=//form/div[@class="sticky-panel"]/div/div/div/a[contains(@class,"cancel") and not(contains(@class,"disabled"))]';


        // Row hightlight
        $rowXpath = 'xpath=//table[@class="list"]/tbody[@class="lines"]/tr[position()=1 and contains(@class,"edit-mark")]';
        $overXpath = 'xpath=//table[@class="list"]/tbody[@class="lines"]/tr[position()=1]';

        $this->assertElementNotPresent($rowXpath, 'No highlight row');

        $this->mouseOver($overXpath);

        $this->assertElementPresent($rowXpath, 'Has highlight row');

        $this->mouseOut($overXpath);
        $this->assertElementNotPresent($rowXpath, 'No highlight row (out)');

        // Remove
        $rowXpath = 'xpath=//table[@class="list"]/tbody[@class="lines"]/tr[position()=1 and contains(@class,"remove-mark")]';
        $inpXpath = 'xpath=//table[@class="list"]/tbody[@class="lines"]/tr[position()=1]/td[@class="cell actions right"]/div/button/input';

        $this->assertElementPresent($saveDisabledXpath, '\'Save changes\' button is disabled (pre) #2');
        $this->assertElementPresent($cancelDisabledXpath, '\'Cancel\' button is disabled (pre) #2');
        $this->assertElementNotPresent($rowXpath, 'No remove highlight row');
        $this->assertElementValueNotEquals($inpXpath, 'on');

        $this->click(
            'xpath=//table[@class="list"]/tbody[@class="lines"]/tr[position()=1]/td[@class="cell actions right"]/div/button[contains(@class,"remove")]'
        );

        $this->assertElementPresent($rowXpath, 'Has remove highlight row');
        $this->assertElementValueEquals($inpXpath, 'on');
        $this->assertElementPresent($saveEnabledXpath, '\'Save changes\' button is enabled (action) #2');
        $this->assertElementPresent($cancelEnabledXpath, '\'Cancel\' button is enabled (action) #2');

        $this->click(
            'xpath=//table[@class="list"]/tbody[@class="lines"]/tr[position()=1]/td[@class="cell actions right"]/div/button[contains(@class,"remove")]'
        );

        $this->assertElementNotPresent($rowXpath, 'No remove highlight row (switch)');
        $this->assertElementValueNotEquals($inpXpath, 'on');
        $this->assertElementPresent($saveDisabledXpath, '\'Save changes\' button is disabled (post) #2');
        $this->assertElementPresent($cancelDisabledXpath, '\'Cancel\' button is disabled (post) #2');

        // Switcher
        $clickXpath = 'xpath=//table[@class="list"]/tbody[@class="lines"]/tr[position()=1]/td[@class="cell actions left"]/div/div[@class="inline-field editable inline-switcher"]/div/div/span/div[@class="widget"]';
        $inpXpath = 'xpath=//table[@class="list"]/tbody[@class="lines"]/tr[position()=1]/td[@class="cell actions left"]/div/div[@class="inline-field editable inline-switcher"]/div/div/span/input[@type="checkbox" and @checked="checked"]';
        $enabledBox = 'xpath=//table[@class="list"]/tbody[@class="lines"]/tr[position()=1]/td[@class="cell actions left"]/div/div[@class="inline-field editable inline-switcher"]/div/div/span[contains(@class,"enabled")]';

        $this->assertElementPresent($saveDisabledXpath, '\'Save changes\' button is disabled (pre) #3');
        $this->assertElementPresent($cancelDisabledXpath, '\'Cancel\' button is disabled (pre) #3');
        $this->assertElementPresent($enabledBox, 'Enabled widget');
        $this->assertElementPresent($inpXpath, 'Enabled input');

        $this->click($clickXpath);

        $this->assertElementPresent(
            'xpath=//table[@class="list"]/tbody[@class="lines"]/tr[position()=1]/td[@class="cell actions left"]/div/div[@class="inline-field editable inline-switcher"]/div/div/span[contains(@class,"disabled")]',
            'Disabled widget'
        );
        $this->assertElementNotPresent($inpXpath, 'Disabled input');
        $this->assertElementPresent($saveEnabledXpath, '\'Save changes\' button is enabled (action) #3');
        $this->assertElementPresent($cancelEnabledXpath, '\'Cancel\' button is enabled (action) #3');

        $this->click($clickXpath);

        $this->assertElementPresent($enabledBox, 'Enabled widget (switch)');
        $this->assertElementPresent($inpXpath, 'Enabled input (switch)');
        $this->assertElementPresent($saveDisabledXpath, '\'Save changes\' button is disabled (post) #3');
        $this->assertElementPresent($cancelDisabledXpath, '\'Cancel\' button is disabled (post) #3');

        // Inline fields
        $overXpath = 'xpath=//table[@class="list"]/tbody[@class="lines"]/tr[position()=1]';
        $clickXpath = 'xpath=//table[@class="list"]/tbody[@class="lines"]/tr[position()=1]/td[contains(@class,"price")]/div/div[@class="view"]';
        $overXpath2 = 'xpath=//table[@class="list"]/tbody[@class="lines"]/tr[position()=2]';
        $clickXpath2 = 'xpath=//table[@class="list"]/tbody[@class="lines"]/tr[position()=2]/td[contains(@class,"price")]/div/div[@class="view"]';
        $outClickXpath = 'xpath=//?keytable[@class="list"]/tbody[@class="lines"]/tr[position()=2]';
        $inpXpath = 'xpath=//table[@class="list"]/tbody[@class="lines"]/tr[position()=1]/td[contains(@class,"price")]/div/div[@class="field"]';
        $EOMXpath = 'xpath=//table[@class="list"]/tbody[@class="lines"]/tr[position()=1 and contains(@class,"edit-open-mark")]';

        $this->assertElementNotPresent($EOMXpath, 'Row is closed');
        $this->assertNotVisible($inpXpath, 'Price input hidden');

        $this->mouseOver($overXpath);
        $this->click($clickXpath);
        $this->focus($clickXpath);

        $text = $this->getValue($inpXpath . '/div/span/input');

        $this->assertElementPresent($EOMXpath, 'Row is open');
        $this->assertVisible($inpXpath, 'Price input visible');

        $this->click($clickXpath2);
        $this->focus($clickXpath2);
        $this->fireEvent($inpXpath, 'blur');
        $this->getJSExpression('jQuery("td.price .field input").blur()');
        sleep(1);

        $this->assertElementNotPresent($EOMXpath, 'Row is closed (out)');
        $this->assertNotVisible($inpXpath, 'Price input hidden (out)');

        $this->assertEquals(
            $text,
            $this->getText($clickXpath . '/span[@class="value"]'),
            'Save field value'
        );
    }

    /**
     * Test list operations with field actiovation
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.16
     */
    public function testListOperations()
    {
        $this->logIn();

        $this->open(static::PRODUCT_LIST_PAGE);

        $nextXPath = 'xpath=//div[@class="table-pager"]/div/a[@class="next enabled"]';
        $pagerInpXpath = 'xpath=//div[@class="table-pager"]/div/div/input[not(@disabled="disabled")]';
        $pageLengthXpath = 'xpath=//div[@class="table-pager"]/div/select[contains(@class,"page-length") and not(@disabled="disabled")]';

        $nextBXPath = 'xpath=//div[@class="table-pager"]/div/a[@class="next disabled"]';
        $pagerInpBXpath = 'xpath=//div[@class="table-pager"]/div/div/input[@disabled="disabled"]';
        $pageLengthBXpath = 'xpath=//div[@class="table-pager"]/div/select[contains(@class,"page-length") and @disabled="disabled"]';

        $this->assertElementPresent($nextXPath, 'Active \'Next \' pager button');
        $this->assertElementPresent($pagerInpXpath, 'Active pager input');
        $this->assertElementPresent($pageLengthXpath, 'Active page length selector');

        $this->click($nextXPath);
        $this->waitForLocalCondition('jQuery(".table-pager .left input").val() == 2', 30000);

        $this->assertElementPresent($nextXPath, 'Active \'Next \' pager button (page 2)');
        $this->assertElementPresent($pagerInpXpath, 'Active pager input (page 2)');
        $this->assertElementPresent($pageLengthXpath, 'Active page length selector (page 2)');

        $clickXpath = 'xpath=//table[@class="list"]/tbody[@class="lines"]/tr[position()=1]/td[@class="cell actions left"]/div/div[@class="inline-field editable inline-switcher"]/div/div/span/div[@class="widget"]';

        $this->click($clickXpath);

        $this->assertElementPresent($nextBXPath, 'Inactive \'Next \' pager button');
        $this->assertElementPresent($pagerInpBXpath, 'Inactive pager input');
        $this->assertElementPresent($pageLengthBXpath, 'Inactive page length selector');

        $this->click($clickXpath);

        $this->assertElementPresent($nextXPath, 'Active \'Next \' pager button (page 2 - undo)');
        $this->assertElementPresent($pagerInpXpath, 'Active pager input (page 2 - undo)');
        $this->assertElementPresent($pageLengthXpath, 'Active page length selector (page 2 - undo)');
    }

    /**
     * Test remove operations
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.16
     */
    public function testRemove()
    {
        $this->logIn();

        $this->open(static::PRODUCT_LIST_PAGE);

        $name = $this->getAttribute('xpath=//table[@class="list"]/tbody[@class="lines"]/tr[position()=1]/td[@class="cell actions right"]/div/button/input[@type="checkbox"]@name');
        preg_match('/^delete.(\d+).$/Ss', $name, $match);
        $pid = intval($match[1]);

        $product = \XLite\Core\Database::getRepo('XLite\Model\Product')->find($pid);
        $this->assertTrue(isset($product), 'Product exists');
        \XLite\Core\Database::getEM()->detach($product);

        $this->click(
            'xpath=//table[@class="list"]/tbody[@class="lines"]/tr[position()=1]/td[@class="cell actions right"]/div/button[contains(@class,"remove")]'
        );

        $this->click(
            'xpath=//form/div[@class="sticky-panel"]/div/div/div/button[contains(@class,"submit") and not(contains(@class,"disabled"))]'
        );

        $this->waitForPageToLoad();

        $name2 = $this->getAttribute('xpath=//table[@class="list"]/tbody[@class="lines"]/tr[position()=1]/td[@class="cell actions right"]/div/button/input[@type="checkbox"]@name');

        $this->assertNotEquals($name, $name2, 'First name is not equal name before removing');
        $product = \XLite\Core\Database::getRepo('XLite\Model\Product')->find($pid);
        $this->assertFalse(isset($product), 'Product removed');
    }

    /**
     * Test modify entity
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.16
     */
    public function testModify()
    {
        $this->logIn();

        $this->open(static::PRODUCT_LIST_PAGE);

        $name = $this->getAttribute('xpath=//table[@class="list"]/tbody[@class="lines"]/tr[position()=1]/td[@class="cell actions right"]/div/button/input[@type="checkbox"]@name');
        preg_match('/^delete.(\d+).$/Ss', $name, $match);
        $pid = intval($match[1]);

        $product = \XLite\Core\Database::getRepo('XLite\Model\Product')->find($pid);
        $this->assertTrue(isset($product), 'Product exists');
        \XLite\Core\Database::getEM()->detach($product);

        $clickXpath = 'xpath=//table[@class="list"]/tbody[@class="lines"]/tr[position()=1]/td[contains(@class,"price")]/div/div[@class="view"]';
        $overXpath2 = 'xpath=//table[@class="list"]/tbody[@class="lines"]/tr[position()=2]';
        $clickXpath2 = 'xpath=//table[@class="list"]/tbody[@class="lines"]/tr[position()=2]/td[contains(@class,"price")]/div/div[@class="view"]';
        $inpXpath = 'xpath=//table[@class="list"]/tbody[@class="lines"]/tr[position()=1]/td[contains(@class,"price")]/div/div[@class="field"]';
        $clickSWXpath = 'xpath=//table[@class="list"]/tbody[@class="lines"]/tr[position()=1]/td[@class="cell actions left"]/div/div[@class="inline-field editable inline-switcher"]/div/div/span/div[@class="widget"]';

        $this->click($clickXpath);

        $text = $this->getValue($inpXpath . '/div/span/input');
        $this->assertEquals($text, $product->getPrice(), 'Old value (DB)');
        $next = $text + 5;
        $this->type($inpXpath . '/div/span/input', $next);

        $this->click($clickSWXpath);
        $enabled = $product->getEnabled();

        $this->click($clickXpath2);
        $this->focus($clickXpath2);
        $this->fireEvent($inpXpath, 'blur');
        $this->getJSExpression('jQuery("td.price .field input").blur()');
        sleep(1);

        $this->assertEquals(
            $next,
            $this->getText($clickXpath . '/span[@class="value"]'),
            'Save field value'
        );

        $this->click(
            'xpath=//form/div[@class="sticky-panel"]/div/div/div/button[contains(@class,"submit") and not(contains(@class,"disabled"))]'
        );
        $this->waitForPageToLoad();

        $saved = $this->getValue($inpXpath . '/div/span/input');

        $this->assertEquals($next, $saved, 'Saved field value');

        $product = \XLite\Core\Database::getRepo('XLite\Model\Product')->find($pid);
        $this->assertTrue(isset($product), 'Product exists');
        \XLite\Core\Database::getEM()->detach($product);

        $this->assertEquals($next, $product->getPrice(), 'Saved value (DB)');
        $this->assertNotEquals($enabled, $product->getEnabled(), 'Saved value (DB) - enabled');
    }

    /**
     * Test undo changes
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.16
     */
    public function testUndo()
    {
        $this->logIn();

        $this->open(static::PRODUCT_LIST_PAGE);

        $name = $this->getAttribute('xpath=//table[@class="list"]/tbody[@class="lines"]/tr[position()=1]/td[@class="cell actions right"]/div/button/input[@type="checkbox"]@name');
        preg_match('/^delete.(\d+).$/Ss', $name, $match);
        $pid = intval($match[1]);

        $product = \XLite\Core\Database::getRepo('XLite\Model\Product')->find($pid);
        $this->assertTrue(isset($product), 'Product exists');
        \XLite\Core\Database::getEM()->detach($product);

        $clickXpath = 'xpath=//table[@class="list"]/tbody[@class="lines"]/tr[position()=1]/td[contains(@class,"price")]/div/div[@class="view"]';
        $overXpath2 = 'xpath=//table[@class="list"]/tbody[@class="lines"]/tr[position()=2]';
        $clickXpath2 = 'xpath=//table[@class="list"]/tbody[@class="lines"]/tr[position()=2]/td[contains(@class,"price")]/div/div[@class="view"]';
        $inpXpath = 'xpath=//table[@class="list"]/tbody[@class="lines"]/tr[position()=1]/td[contains(@class,"price")]/div/div[@class="field"]';
        $clickSWXpath = 'xpath=//table[@class="list"]/tbody[@class="lines"]/tr[position()=1]/td[@class="cell actions left"]/div/div[@class="inline-field editable inline-switcher"]/div/div/span/div[@class="widget"]';
        $cancelXpath = 'xpath=//form/div[@class="sticky-panel"]/div/div/div/a[contains(@class,"cancel")]';
        $cancelDisabledXpath = 'xpath=//form/div[@class="sticky-panel"]/div/div/div/a[contains(@class,"cancel") and contains(@class,"disabled")]';
        $removeRowXpath = 'xpath=//table[@class="list"]/tbody[@class="lines"]/tr[position()=3 and contains(@class,"remove-mark")]';

        $this->click($clickXpath);

        $text = $this->getValue($inpXpath . '/div/span/input');
        $this->assertEquals($text, $product->getPrice(), 'Old value (DB)');
        $next = $text + 5;
        $this->type($inpXpath . '/div/span/input', $next);

        $this->click($clickSWXpath);
        $enabled = $product->getEnabled();

        $this->click(
            'xpath=//table[@class="list"]/tbody[@class="lines"]/tr[position()=3]/td[@class="cell actions right"]/div/button[contains(@class,"remove")]'
        );
        $this->assertElementPresent($removeRowXpath, 'Row #3 marked as remove');

        $this->click($clickXpath2);
        $this->focus($clickXpath2);
        $this->fireEvent($inpXpath, 'blur');
        $this->getJSExpression('jQuery("td.price .field input").blur()');
        sleep(1);

        $this->click($cancelXpath);

        $this->assertEquals(
            $text,
            $this->getValue($inpXpath . '/div/span/input'),
            'Undo price'
        );
        $this->assertEquals(
            $text,
            $this->getText($clickXpath . '/span[@class="value"]'),
            'Undo view value'
        );
        $this->assertElementNotPresent($removeRowXpath, 'Row #3 unmarked as remove (undo)');
        $this->assertElementNotPresent(
            'xpath=//table[@class="list"]/tbody[@class="lines"]/tr[position()=3]/td[@class="cell actions right"]/div/button[contains(@class,"remove")]/input[@type="checkbox" and not(@checked="checked")]',
            'Row #3 remove checkbox is unchecked'
        );

        $this->assertElementPresent($cancelDisabledXpath, '\'Cancel\' button is disabled');

        $switchBox = 'xpath=//table[@class="list"]/tbody[@class="lines"]/tr[position()=1]/td[@class="cell actions left"]/div/div[@class="inline-field editable inline-switcher"]/div/div/span[contains(@class,"' . ($enabled ? 'enabled' : 'disabled') . '")]';
        $this->assertElementPresent($switchBox, 'Undo switch widget');

    }

    /**
     * Test pager input and items-per-page selector
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.16
     */
    public function testPager()
    {
        $this->logIn();

        $this->open(static::PRODUCT_LIST_PAGE);

        $pagerInpXpath = 'xpath=//div[@class="table-pager"]/div/div/input';
        $pageLengthXpath = 'xpath=//div[@class="table-pager"]/div/select[contains(@class,"page-length")]';

        $name = $this->getAttribute('xpath=//table[@class="list"]/tbody[@class="lines"]/tr[position()=1]/td[@class="cell actions right"]/div/button/input[@type="checkbox"]@name');
        $this->type($pagerInpXpath, '2');
        $this->getJSExpression('jQuery(".table-pager .left input").blur()');
        $this->waitForLocalCondition('jQuery("tbody.lines .line .remove input").attr("name") != "' . $name . '"', 30000, 'Wait widget reload #1');
        $this->waitForLocalCondition(
            'jQuery(".table-pager .left input").val() == 2',
            1000,
            'Wait 2 page - input'
        );

        $name = $this->getAttribute('xpath=//table[@class="list"]/tbody[@class="lines"]/tr[position()=1]/td[@class="cell actions right"]/div/button/input[@type="checkbox"]@name');
        $this->type($pagerInpXpath, '1');
        $this->getJSExpression('jQuery(".table-pager .left input").blur()');
        $this->waitForLocalCondition('jQuery("tbody.lines .line .remove input").attr("name") != "' . $name . '"', 30000, 'Wait widget reload #2');
        $this->waitForLocalCondition(
            'jQuery(".table-pager .left input").val() == 1',
            1000,
            'Wait 1 page - input'
        );

        $this->select($pageLengthXpath, 'value=50');
        $this->getJSExpression('jQuery(".table-pager .right select").change()');
        $this->waitForLocalCondition(
            'jQuery("tbody.lines tr.line").length == 50',
            30000,
            'Wait 50 items per page'
        );

        $this->select($pageLengthXpath, 'value=25');
        $this->getJSExpression('jQuery(".table-pager .right select").change()');
        $this->waitForLocalCondition(
            'jQuery("tbody.lines tr.line").length == 25',
            30000,
            'Wait 25 items per page'
        );
    }
}
