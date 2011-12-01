<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * LiteCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the GNU General Pubic License (GPL 2.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-2.0.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to licensing@litecommerce.com so we can send you a copy immediately.
 *
 * PHP version 5.3.0
 *
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU General Pubic License (GPL 2.0)
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.1
 */

namespace XLite\Module\CDev\DrupalConnector\Drupal;

/**
 * User accounts synchronization
 *
 * @see   ____class_see____
 * @since 1.0.1
 */
class UserSync extends \XLite\Module\CDev\DrupalConnector\Drupal\ADrupal
{
    /**
     * User synchronization form button label
     */
    const LC_OP_NAME_USERSYNC = 'Synchronize user accounts';

    
    /**
     * LiteCommerce accounts list
     * 
     * @var   array
     * @see   ____var_see____
     * @since 1.0.1
     */
    protected $lcAccounts = null;
    
    /**
     * Drupal accounts list
     * 
     * @var   array
     * @see   ____var_see____
     * @since 1.0.1
     */
    protected $drupalAccounts = null;
    
    /**
     * List of accounts missed in Drupal 
     * 
     * @var   array
     * @see   ____var_see____
     * @since 1.0.1
     */
    protected $accountsMissedInDrupal = null;
    
    /**
     * List of non-linked accounts (LiteCommerce accounts)
     * 
     * @var   array
     * @see   ____var_see____
     * @since 1.0.1
     */
    protected $nonLinkedAccounts = null;
    
    /**
     * Cache of Drupal account names (to validate names of new account before creation)
     * 
     * @var   array
     * @see   ____var_see____
     * @since 1.0.1
     */
    protected $drupalAccountNames = null;
    
    /**
     * Maximal number of user accounts processed per step 
     * 
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.1
     */
    protected $userAccountsPerStepCounter = 50;
    
    /**
     * Total number of non-synchronized accounts 
     * 
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.1
     */
    protected $totalNonSynchronizedAccounts = 0;


    /**
     * Returns users synchronization form
     * 
     * @param array &$form Form description
     *  
     * @return array
     * @see    ____func_see____
     * @since  1.0.1
     */
    public function addUserSyncForm(array &$form)
    {
        if ($this->isUserSynchronizationRequired()) {

            $formDescription = <<<OUT
<p>Non-synchronized user accounts found in the Drupal and LiteCommerce databases. This means that when user is logged in to the Drupal site but doesn't have a LiteCommerce account, the user cannot use the catalog of products as a registered user. Clicking on the button below will link the Drupal and LiteCommerce accounts, observing the following rules:</p>
<ul>
<li>If non-linked accounts with same email are present in both Drupal and LiteCommerce databases, these accounts will be linked.</li>
<li>If an account is present in Drupal but is missing in the LiteCommerce database, the linked account will be created in the LiteCommerce database with a randomly generated password and the same email as in the Drupal account.</li>
<li>If an account is present in LiteCommerce but is missing in the Drupal database, the linked account will be created in the Drupal database with a randomly generated password and the same email as in the LiteCommerce account.</li>
</ul>
<p>Tick the check box below to send notifications and links to reset password to users who get the new Drupal accounts.</p>
OUT;

            $form['lcc']['usersync'] = array(
                '#type' => 'fieldset',
                '#title' => t('User accounts synchronization'),

                '#description' => t('user_sync_form_description') == 'user_sync_form_description' 
                    ? $formDescription 
                    : t('user_sync_form_description'),
                'notify_users' => array(
                    '#type' => 'checkbox',
                    '#return_value' => 1,
                    '#title' => t('Require password reset for new Drupal accounts')
                ),
                'submit' => array(
                    '#type' => 'submit',
                    '#value' => t(self::LC_OP_NAME_USERSYNC),
                ),

            );
        }
    }

    /**
     * Runs batch process of user accounts synchronization on submit form above
     * 
     * @param array &$form      Form descriptions
     * @param array &$formState Form state
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.1
     */
    public function processUserSyncFormSubmit(array &$form, array &$formState)
    {
        if (t(self::LC_OP_NAME_USERSYNC) == $formState['values']['op']) {

            if ($this->isUserSynchronizationRequired()) {

                variable_set('lc_user_sync_notify', $formState['values']['notify_users']);

                // Calculate number of steps and list ot operations
                $maxSteps = $this->totalNonSynchronizedAccounts;  // $this->userAccountsPerStepCounter) + 1;
                $operations = array();
                for ($i = 0; $i < $maxSteps; $i++) {
                    $operations[] = array('lcConnectorUserSync', array());
                }

                // Initialize batch (to set title).
                $batch = array(
                    'title' => t('Synchronize user accounts'),
                    'init_message' => t('Starting user accounts synchronization...'),
                    'operations' => $operations,
                    'finished' => 'lcConnectorUserSyncFinishedCallback',
                );
                batch_set($batch);

                // Run batch process
                batch_process();

            } else {
                drupal_set_message(t('User accounts synchronization is not required.'));
            }
        }
    }

    /**
     * Perform a single step of user synchronization batch process
     * 
     * @param array $context Batch process context data
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function doUserSynchronization(array &$context)
    {
        if ($this->isUserSynchronizationRequired()) {

            if (empty($context['sandbox'])) {
                // Batch process just started
                $context['sandbox']['total'] = $this->totalNonSynchronizedAccounts;
                $context['sandbox']['processed'] = 0;
            }

            $startCounter = $this->userAccountsPerStepCounter;

            // Do accounts synchronization
            $this->linkUserAccounts();
            $this->createMissedLCAccounts();
            $this->createMissedDrupalAccounts();

            // Modify batch process parameters
            $context['sandbox']['processed'] += ($startCounter - $this->userAccountsPerStepCounter);

            if ($context['sandbox']['processed'] < $context['sandbox']['total']) {
                $context['finished'] = $context['sandbox']['processed'] / $context['sandbox']['total'];
            }
        }
    }

    /**
     * Finalize user accounts synchronization process
     * 
     * @param boolean $success    Batch process status
     * @param array   $results    Array of batch process results (not used here)
     * @param array   $operations Array of batch process operations (not used here)
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.1
     */
    public function doUserSyncFinished($success, $results, $operations)
    {
        if ($success) {
            drupal_set_message(t('User accounts have been synchronized.'));

        } else {
            drupal_set_message(t('Finished with an error.'), 'error');
        }
    }


    /**
     * Check if user accounts synchronization is required
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.1
     */
    protected function isUserSynchronizationRequired()
    {
        return $this->prepareNonSynchronizedAccounts();
    }

    /**
     * Gather all accounts from Drupal and LC databases and store them into internal properties
     * Returns true if accounts were found
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.1
     */
    protected function findAllAccounts()
    {
        if (is_null($this->drupalAccounts)) {
            // Get an array of all Drupal accounts
            $this->drupalAccounts = db_query('SELECT uid, mail, name, status FROM {users} WHERE uid > 0')->fetchAll();

            foreach ($this->drupalAccounts as $account) {
                $this->drupalAccountNames[] = $account->name;
            } 
        }

        if (is_null($this->lcAccounts)) {
            // Get array of LC accounts with order_id = NULL
            // Array contains the following fields for each account: {profile_id, login, cms_profile_id, cms_name}
            $this->lcAccounts = \XLite\Core\Database::getRepo('XLite\Model\Profile')->findAllProfilesArray();
        }

        return !empty($this->drupalAccounts) || !empty($this->lcAccounts);
    }

    /**
     * Prepare arrays of accounts that should be synchronized
     * Returns true if such accounts exists
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.1
     */
    protected function prepareNonSynchronizedAccounts()
    {
        if (
            is_null($this->accountsMissedInDrupal)
            || is_null($this->nonLinkedAccounts)
            || is_null($this->drupalAccounts)
        ) {

            if ($this->findAllAccounts()) {

                // Accounts presented in LiteCommerce but missed in Drupal
                $this->accountsMissedInDrupal = array();

                // Accounts presented in LC and Drupal but not linked via cms_profile_id field
                $this->nonLinkedAccounts = array();

                // Find accounts presented in LC but missed in Drupal
                foreach ($this->lcAccounts as $lk => $lcAccount) {
            
                    $found = false;
            
                    foreach ($this->drupalAccounts as $dk => $drupalAccount) {
                
                        if ($lcAccount['login'] == $drupalAccount->mail) {

                            if ($lcAccount['cms_profile_id'] != $drupalAccount->uid) {
                                $lcAccount['cms_profile_id'] = $drupalAccount->uid;
                                $this->nonLinkedAccounts[] = $lcAccount;
                            }

                            // Unset $drupalAccounts element as it is not needed anymore
                            unset($this->drupalAccounts[$dk]);

                            $found = true;
                            break;
                        }
                    }

                    if (!$found) {
                        $this->accountsMissedInDrupal[] = $lcAccount;
                    }
                }
            }
        }

        // Calculate sum of accounts that need to be processed
        $this->totalNonSynchronizedAccounts = count($this->drupalAccounts)
            + count($this->accountsMissedInDrupal)
            + count($this->nonLinkedAccounts);

        return $this->totalNonSynchronizedAccounts > 0;
    }

    /**
     * Link LiteCommerce accounts with Drupal accounts
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.1
     */
    protected function linkUserAccounts()
    {
        foreach ($this->nonLinkedAccounts as $k => $account) {

            if ($this->checkUserAccountsPerStepCounter()) {
                \XLite\Core\Database::getRepo('XLite\Model\Profile')->linkProfiles(
                    \XLite\Core\Database::getRepo('XLite\Model\Profile')->find($account['profile_id']),
                    $account['cms_profile_id']
                );

                unset($this->nonLinkedAccounts[$k]);
            
            } else {
                break;
            }
        }
    }

    /**
     * Create missed LiteCommerce accounts 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.1
     */
    protected function createMissedLCAccounts()
    {
        foreach ($this->drupalAccounts as $k => $account) {

            if ($this->checkUserAccountsPerStepCounter()) {

                $profile = new \XLite\Model\Profile();
                $profile->setLogin($account->mail);
                $profile->setCmsProfileId($account->uid);
                $profile->setCmsName(\XLite\Module\CDev\DrupalConnector\Handler::getInstance()->getCMSName());
                $profile->setStatus((1 === intval($account->status) ? 'E' : 'D'));

                $pass = \XLite\Core\Database::getRepo('XLite\Model\Profile')->generatePassword();
                $profile->setPassword(md5($pass));

                $user = user_load($account->uid);
                if (user_access(\XLite\Module\CDev\DrupalConnector\Drupal\Profile::LC_DRUPAL_ADMIN_ROLE_NAME, $user)) {
                    $profile->setAccessLevel(\XLite\Core\Auth::getInstance()->getAdminAccessLevel());
                }

                $profile->create();

                unset($this->drupalAccounts[$k]);

            } else {
                break;
            }
        }
    }

    /**
     * Create missed Drupal accounts 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.1
     */
    protected function createMissedDrupalAccounts()
    {
        if (!defined('LC_SUPPRESS_EMAIL_ALREADY_EXISTS_MESSAGE')) {
            define('LC_SUPPRESS_EMAIL_ALREADY_EXISTS_MESSAGE', true);
        }

        foreach ($this->accountsMissedInDrupal as $k => $account) {

            if ($this->checkUserAccountsPerStepCounter()) {

                $newAccount = new \stdclass();
                $newAccountData = array(
                    'is_new' => true,
                    'name'   => $this->getNameFromEmail($account['login']),
                    'mail'   => $account['login'],
                    'pass'   => user_password(),
                    'status' => true,
                );

                if (user_save($newAccount, $newAccountData)) {

                    $this->drupalAccountNames[] = $newAccount->name;

                    \XLite\Core\Database::getRepo('XLite\Model\Profile')->linkProfiles(
                        \XLite\Core\Database::getRepo('XLite\Model\Profile')->find($account['profile_id']),
                        $newAccount->uid
                    );
                
                    if (variable_get('lc_user_sync_notify', false)) {
                        // Send notification with one time login URL and instructions
                        _user_mail_notify('password_reset', $newAccount);
                    }

                    unset($this->accountsMissedInDrupal[$k]);
                }

            } else {
                break;
            }
        }
    }

    /**
     * Get valid Drupal user name from login (email) of LiteCommerce account 
     * 
     * @param string $email LiteCommerce login field (email)
     *  
     * @return string
     * @see    ____func_see____
     * @since  1.0.1
     */
    protected function getNameFromEmail($email)
    {
        $name = $nameId = preg_replace('/^([^@]+)@(.+)$/', '\\1', $email);

        for ($i = 1; 10 >= $i; $i++) {

            if (in_array($nameId, $this->drupalAccountNames)) {
                $nameId = sprintf('%s_%02d', $name, $i);
            
            } else {
                break;
            }
        }

        return $nameId;
    }

    /**
     * Check per-step-counter 
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.1
     */
    protected function checkUserAccountsPerStepCounter()
    {
        $result = $this->userAccountsPerStepCounter > 0;

        if ($result) {
            $this->userAccountsPerStepCounter--;
        }
        
        return $result;
    }
}
