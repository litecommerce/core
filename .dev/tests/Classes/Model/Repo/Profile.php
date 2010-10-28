<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * XLite\Model\Repo\Profile class tests
 *
 * @category   LiteCommerce
 * @package    Tests
 * @subpackage Classes
 * @author     Creative Development LLC <info@cdev.ru>
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

class XLite_Tests_Model_Repo_Profile extends XLite_Tests_TestCase
{
    protected $testSearchData = array(
        // Test dataset #1
        0 => array(
            'cnd' => array(
                'profile_id' => 4,
            ),
            'result' => array(
                'ids'   => array(4),
            ),
        ),
        // Test dataset #2
        1 => array(
            'cnd' => array(
                'order_id' => 1,
            ),
            'result' => array(
                'ids'   => array(8),
            ),
        ),
        // Test dataset #3
        2 => array(
            'cnd' => array(
                'referer' => 'google',
            ),
            'result' => array(
                'ids'   => array(2, 3),
            ),
        ),
        // Test dataset #4
        3 => array(
            'cnd' => array(
                'membership' => 'pending_membership',
            ),
            'result' => array(
                'ids' => array(3, 5),
            ),
        ),
        // Test dataset #5
        4 => array(
            'cnd' => array(
                'membership' => 2,
            ),
            'result' => array(
                'ids' => array(4, 5),
            ),
        ),
        // Test dataset #6
        5 => array(
            'cnd' => array(
                'membership' => 1,
            ),
            'result' => array(
                'isEmpty' => true
            ),
        ),
        // Test dataset #7
        6 => array(
            'cnd' => array(
                'membership' => 2,
                'user_type'  => 'A',
            ),
            'result' => array(
                'ids'   => array(1, 7),
            ),
        ),
        // Test dataset #8
        7 => array(
            'cnd' => array(
                'language' => 'de',
            ),
            'result' => array(
                'ids' => array(4),
            ),
        ),
        // Test dataset #9
        8 => array(
            'cnd' => array(
                'pattern' => 'Patrick Smith',
            ),
            'result' => array(
                'ids' => array(3, 4),
            ),
        ),
        // Test dataset #10
        9 => array(
            'cnd' => array(
                'pattern' => 'John Smith',
            ),
            'result' => array(
                'ids' => array(2),
            ),
        ),
        // Test dataset #11
        10 => array(
            'cnd' => array(
                'pattern' => 'Smith John',
            ),
            'result' => array(
                'ids' => array(2, 3),
            ),
        ),
        // Test dataset #12
        11 => array(
            'cnd' => array(
                'phone' => '76543',
            ),
            'result' => array(
                'ids' => array(2, 3, 4, 5, 6, 7),
            ),
        ),
        // Test dataset #13
        12 => array(
            'cnd' => array(
                'country' => 'FR',
            ),
            'result' => array(
                'ids' => array(4),
            ),
        ),
        // Test dataset #14
        13 => array(
            'cnd' => array(
                'state' => 37,
            ),
            'result' => array(
                'ids' => array(5),
            ),
        ),
        // Test dataset #15
        14 => array(
            'cnd' => array(
                'address_pattern' => 'Paris',
            ),
            'result' => array(
                'ids' => array(4),
            ),
        ),
        // Test dataset #16
        15 => array(
            'cnd' => array(
                'address_pattern' => 'est str',
            ),
            'result' => array(
                'ids' => array(7),
            ),
        ),
        // Test dataset #17
        16 => array(
            'cnd' => array(
                'address_pattern' => 'ate te',
            ),
            'result' => array(
                'ids' => array(4),
            ),
        ),
        // Test dataset #18
        17 => array(
            'cnd' => array(
                'address_pattern' => '435',
            ),
            'result' => array(
                'ids' => array(4),
            ),
        ),
        // Test dataset #19
        18 => array(
            'cnd' => array(
                'user_type' => 'C',
            ),
            'result' => array(
                'ids' => array(2, 3, 4, 5, 6, 8),
            ),
        ),
        // Test dataset #20
        19 => array(
            'cnd' => array(
                'date_type'   => 'R',
                'date_period' => 'M',
            ),
            'result' => array(
                'ids'        => array(4, 6, 7, 8),
                'doNotCount' => true,
            ),
        ),
        // Test dataset #21
        20 => array(
            'cnd' => array(
                'date_type'   => 'R',
                'date_period' => 'W',
            ),
            'result' => array(
                'ids'        => array(4, 6, 7),
                'doNotCount' => true,
            ),
        ),
        // Test dataset #22
        21 => array(
            'cnd' => array(
                'date_type'   => 'R',
                'date_period' => 'D',
            ),
            'result' => array(
                'ids'        => array(4, 6, 7),
                'doNotCount' => true,
            ),
        ),
        // Test dataset #23
        22 => array(
            'cnd' => array(
                'date_type'   => 'R',
                'date_period' => 'C',
                'startDate'   => 0,
                'endDate'     => 0,
            ),
            'result' => array(
                'ids' => array(2),
            ),
        ),
        // Test dataset #24
        23 => array(
            'cnd' => array(
                'order_id'    => 0,
                'date_type'   => 'L',
                'date_period' => 'C',
                'startDate'   => 0,
                'endDate'     => 0,
            ),
            'result' => array(
                'ids' => array(1, 2, 3, 4, 5, 6, 7),
            ),
        ),
        // Test dataset #25
        24 => array(
            'cnd' => array(
                'order_by' => array('p.added', 'DESC'),
                'limit'    => array(1, 4), // 1 - start, 4 - limit
            ),
            'result' => array(
                'ids' => array(4, 6, 7),
            ),
        ),
    );
        
    /**
     * setUp
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function setUp()
    {
        parent::setUp();

        \XLite\Core\Database::getEM()->clear();

        $this->query(file_get_contents(__DIR__ . '/sql/profile/setup.sql'));
        \XLite\Core\Database::getEM()->flush();

        $this->testSearchData[22]['cnd']['startDate'] = date('M j, Y', time()-60*60*24*7);
        $this->testSearchData[22]['cnd']['endDate'] = date('M j, Y', time()-60*60*24*3);
    }

    /**
     * tearDown
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->query(file_get_contents(__DIR__ . '/sql/profile/restore.sql'));
        \XLite\Core\Database::getEM()->flush();
    }

    /**
     * testSearch 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testSearch()
    {
        foreach ($this->testSearchData as $testId => $data) {

            $testIdStr = sprintf(' [%d]', $testId);

            $cnd = new \XLite\Core\CommonCell();

            foreach ($data['cnd'] as $key => $value) {
                $cnd->$key = $value;
            }

            $searchResults = \XLite\Core\Database::getRepo('XLite\Model\Profile')->search($cnd);

            $this->assertTrue(is_array($searchResults), 'The result of search() must be an array' . $testIdStr);

            if (!empty($data['result']['isEmpty'])) {
                $this->assertEquals(0, count($searchResults), 'Count of search results is expected to be zero' . $testIdStr);
            
            } else {

                if (!isset($data['result']['doNotCount'])) {
                    $this->assertEquals(count($data['result']['ids']), count($searchResults), 'Checking the count of search result items' . $testIdStr);
                }

                foreach ($searchResults as $profile) {
                    
                    $this->assertTrue($profile instanceof \XLite\Model\Profile, 'Checking that search items are profile instances' . $testIdStr);
                    
                    if (!isset($data['result']['doNotCount'])) {
                        $this->assertTrue(in_array($profile->getProfileId(), $data['result']['ids']), 'Checking that correct items are found' . $testIdStr);
                    }
                }
            }

            $countSearchResults = \XLite\Core\Database::getRepo('XLite\Model\Profile')->search($cnd, true);

            $this->assertTrue(is_int($countSearchResults), 'Wrong type returned by search(cnd, true)' . $testIdStr);
            $this->assertEquals(count($searchResults), $countSearchResults, 'Checking the count of search results ('.count($searchResults).')' . $testIdStr);
        }
    }

    /**
     * testFindByLogin 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testFindByLogin()
    {
        // Test #1
        $profile = \XLite\Core\Database::getRepo('XLite\Model\Profile')->findByLogin('rnd_tester02@rrf.ru');

        $this->assertTrue($profile instanceof \XLite\Model\Profile, 'check that profile is an object');

        $this->assertEquals('rnd_tester02@rrf.ru', $profile->getLogin(), 'check the login');

        // Test #2
        $profile = \XLite\Core\Database::getRepo('XLite\Model\Profile')->findByLogin('wrong login');

        $this->assertNull($profile, 'check that profile is null');
    }

    /**
     * testFindByLoginPassword 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testFindByLoginPassword()
    {
        // Test #1
        $profile = \XLite\Core\Database::getRepo('XLite\Model\Profile')->findByLoginPassword('rnd_tester02@rrf.ru', md5('guest'));

        $this->assertTrue($profile instanceof \XLite\Model\Profile, 'check that profile is an object');

        $this->assertEquals('rnd_tester02@rrf.ru', $profile->getLogin(), 'check the login');
        $this->assertEquals(0, $profile->getOrderId(), 'check the order_id (0)');

        // Test #2
        $profile = \XLite\Core\Database::getRepo('XLite\Model\Profile')->findByLoginPassword('rnd_tester02@rrf.ru', md5('wrong password'));

        $this->assertNull($profile, 'check that profile is null');

        // Test #3
        $profile = \XLite\Core\Database::getRepo('XLite\Model\Profile')->findByLoginPassword('wrong login', md5('guest'));

        $this->assertNull($profile, 'check that profile is null');

        // Test #4: user is disabled
        $profile = \XLite\Core\Database::getRepo('XLite\Model\Profile')->findByLoginPassword('rnd_tester03@rrf.ru', md5('guest'));

        $this->assertNull($profile, 'check that profile is null');
    }

    /**
     * testFindRecentAdmins 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testFindRecentAdmins()
    {
        $profiles = \XLite\Core\Database::getRepo('XLite\Model\Profile')->findRecentAdmins();
    
        $this->assertTrue(is_array($profiles), 'Check that array is returned');
        $this->assertEquals(2, count($profiles), 'Check that count of array items');

        foreach ($profiles as $profile) {
            $this->assertEquals(100, $profile->getAccessLevel(), 'Check access_level');
        }
    }

    /**
     * testFindUserWithSameLogin 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testFindUserWithSameLogin()
    {
        $profile = \XLite\Core\Database::getRepo('XLite\Model\Profile')->findByLogin('rnd_tester02@rrf.ru');

        // Test #1
        $profile2 = \XLite\Core\Database::getRepo('XLite\Model\Profile')->findUserWithSameLogin($profile);

        $this->assertNull($profile2, 'Profile not found and must be null');

        // Test #2
        $profile->setLogin('rnd_tester04@rrf.ru');

        $profile2 = \XLite\Core\Database::getRepo('XLite\Model\Profile')->findUserWithSameLogin($profile);

        $this->assertTrue($profile2 instanceof \XLite\Model\Profile, 'Profile is found and must return an object');
        $this->assertEquals(6, $profile2->getProfileId(), 'check profile_id');
    }

    /**
     * testFindCountOfAdminAccounts 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testFindCountOfAdminAccounts()
    {
        $adminsCount = \XLite\Core\Database::getRepo('XLite\Model\Profile')->findCountOfAdminAccounts();
    
        $this->assertEquals(2, $adminsCount, 'Checking the count of administrator accounts');
    }

}
