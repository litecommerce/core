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
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Module\AdvancedSecurity\Controller\Admin;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class AdvancedSecurity extends \XLite\Controller\Admin\AAdmin
{
    public $params = array('target', "mode");
    public $sample = "The quick brown fox jumps over the lazy dog.";
    
    function action_orders() 
    {
        $gpg = $this->get('gpg');
        $pubkey = $gpg->getPublicKey();
        $seckey = $gpg->getSecretKey();
        $this->set('valid', !empty($pubkey) && $gpg->isKeyValid($pubkey, "PUBLIC") && !empty($seckey) && $gpg->isKeyValid($seckey, "PRIVATE"));
        if (!$this->is('valid')) {
            $this->set('invalidKeyring', true);
            return;
        }
        if ($this->get('decrypt_orders') && !$gpg->isPasswordValid($this->get('passphrase'))) {
            $this->set('valid', false);
            $this->set('invalidOrderPassword', true);
            return;
        }
        $this->session->set('masterPassword', null); // to avoid update conflict
        $order = new \XLite\Model\Order();
        $orders = $order->findAll("payment_method='CreditCard'");
        $this->startDump();
        for ($i = 0; $i < count($orders); $i++) {
            if ($this->get('decrypt_orders')) {
                print "Decrypting order #" . $orders[$i]->get('order_id') . " ... ";
                $orders[$i]->decrypt($this->get('passphrase'));
                print "[OK]<br>\n";
            } elseif ($this->get('encrypt_orders')) {
                print "Encrypting order #" . $orders[$i]->get('order_id') . " ... ";
                $orders[$i]->encrypt();
                print "[OK]<br>\n";
            }
        }
?>
<br><br>Order(s) processed successfully. <a href="admin.php?target=advanced_security#order_management"><u>Click here to return to admin interface</u></a>
<?php
    }
    
    function testEncrypt() 
    {
        $gpg = $this->get('gpg');
        $this->encryptResult = $gpg->encrypt($this->sample);
    }

    function testDecrypt() 
    {
        $gpg = $this->get('gpg');
        $this->decryptResult = $gpg->decrypt($this->encryptResult, $this->get('passphrase'));
    }
    
    function action_test() 
    {
        // see template for testing details
        $this->set('valid', false); // no NOT redirect after test
    }

    function action_download_secret_key() 
    {
        $gpg = $this->get('gpg');
        $downloadPass = $this->get('download_password');
        if (!is_null($downloadPass) && $gpg->isPasswordValid($downloadPass)) {
            $this->set('silent', true);
            $this->startDownload('secring.asc');
            print $gpg->get('secretKey');
        } else {
            $this->set('invalidPassword', true);
            $this->set('valid', false);
        }
    }

    function getSecurityOptions() 
    {
        return \XLite\Core\Database::getRepo('XLite\Model\Config')->getByCategory('AdvancedSecurity', true, true);
    }
    
    function action_options() 
    {
        $options = $this->getSecurityOptions();

        for ($i = 0; $i < count($options); $i++) {

            $name = $options[$i]->name;
            $type = $options[$i]->type;

            if ($type == 'checkbox') {

                if (empty($_POST[$name])) {
                    $val = 'N';
                
                } else {
                    $val = 'Y';
                }

            } else {

                if (isset($_POST[$name])) {
                    $val = trim($_POST[$name]);
                
                } else {
                    continue;
                }
            }

            \XLite\Core\Database::getRepo('XLite\Model\Config')->createOption(
                array(
                    'category' => 'AdvancedSecurity',
                    'name'     => $name,
                    'value'    => $val
                )
            );
        }
    }

    function action_delete_keys()
    {
        $gpg = new \XLite\Module\AdvancedSecurity\Model\GPG();
        $gpg->deleteKeys();
    }
    
    function action_upload_keys()
    {
        $gpg = new \XLite\Module\AdvancedSecurity\Model\GPG();
        $this->set('valid', $gpg->uploadKeys());
    }

    function getGPG() 
    {
        if (is_null($this->gpg)) {
            $this->gpg = new \XLite\Module\AdvancedSecurity\Model\GPG();
        }
        return $this->gpg;
    }
}
