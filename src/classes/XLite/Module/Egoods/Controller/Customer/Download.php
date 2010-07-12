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

namespace XLite\Module\Egoods\Controller\Customer;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Download extends \XLite\Controller\AController
{
    public $params = array('mode');

    function action_download()
    {
        if (isset($_REQUEST['acc']) && !empty($_REQUEST['acc'])) {
            $this->downloadByAccessKey();
        } else if (isset($_REQUEST['file_id']) && !empty($_REQUEST['file_id'])) {
            $this->downloadByFileId();
        }
    }

    function downloadByAccessKey() 
    {
        $access_key = $_REQUEST['acc'];
        $dl = new \XLite\Module\Egoods\Model\DownloadableLink();
        $time = time();
        
        // check if the link with given access key exists
        if ($dl->find("access_key='" . $access_key . "'")) {
            
            // check for product download availability
            if (!$dl->is('active')) {
                $reason = $dl->get('deniedReason');
                $this->set('returnUrl', 'cart.php?target=download&mode=file_access_denied&reason=' . $reason);
                return;
            }
            
            $df = new \XLite\Module\Egoods\Model\DownloadableFile($dl->get('file_id'));
            // check for file
            if (!is_file($df->get('data'))) {
                $this->set('returnUrl', 'cart.php?target=download&mode=file_not_found&filename=' . 
                    basename($df->get('data')) . 
                    "&requested_url=" . $this->retriveRequestedUrl()
                );
                return;
            }
            
            // download the file
            $this->set('silent', true);
            $this->startDownload(basename($df->get('data')));
            $this->readFile($df->get('data'));

            // decrase downloads limit
            $dl->set('available_downloads', $dl->get('available_downloads') - 1);
            $dl->update();
            
            // save download statistics
            $ds = new \XLite\Module\Egoods\Model\DownloadsStatistics();
            $ds->set('file_id', $df->get('file_id'));
            $ds->set('date', $time);
            $ds->set('headers', "HTTP_REFERER=" . $_SERVER['HTTP_REFERER'] . ", REMOTE_ADDR=" . $_SERVER['REMOTE_ADDR']);
            $ds->create();
            exit();
        } else {
            $this->set('returnUrl', 'cart.php?target=download&mode=file_access_denied');
        }
    }

    function downloadByFileId() 
    {
        $file_id = $_REQUEST['file_id'];
        $time = time();
        $df = new \XLite\Module\Egoods\Model\DownloadableFile($file_id);
        $product_id = $df->get('product_id');
        
        $product = new \XLite\Model\Product($product_id);
        if (!$product->isFreeForMembership($this->getComplex('cart.profile.membership'))) {
            $this->set('returnUrl', 'cart.php?target=download&mode=file_access_denied&reason=M');
            return;
        }
        

            // check for file
        if (!is_file($df->get('data'))) {
            $this->set('returnUrl', 'cart.php?target=download&mode=file_not_found&filename=' . 
                basename($df->get('data')) . 
                "&requested_url=" . $this->retriveRequestedUrl()
            );
            return;
        }
        
        // download the file
        $this->set('silent', true);
        $this->startDownload(basename($df->get('data')));
        $this->readFile($df->get('data'));

        // save download statistics
        $ds = new \XLite\Module\Egoods\Model\DownloadsStatistics();
        $ds->set('file_id', $df->get('file_id'));
        $ds->set('date', $time);
        $ds->set('headers', "HTTP_REFERER=" . $_SERVER['HTTP_REFERER'] . ", REMOTE_ADDR=" . $_SERVER['REMOTE_ADDR']);
        $ds->create();
        exit();
    }

    function readFile($name)
    {
        $handle = @fopen($name, "rb");
        if ($handle) {
            while (!feof($handle)) {
              $contents = @fread($handle, 8192);
              echo $contents;
            }
            fclose($handle);
        }
    }

    function retriveRequestedUrl()
    {
        return urlencode($_SERVER['QUERY_STRING']);
    }
}
