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
 * ModuleKey
 *
 */
class ModuleKey extends \XLite\Controller\Admin\AAdmin
{
    // {{{ Public methods for viewers

    /**
     * Return page title
     *
     * @return string
     */
    public function getTitle()
    {
        return 'Enter license key';
    }

    // }}}

    // {{{ "Register key" action handler

    /**
     * Action of license key registration
     *
     * @return void
     */
    protected function doActionRegisterKey()
    {
        $key  = $this->processKey(\XLite\Core\Request::getInstance()->key);
        $key = trim($key);

        if ($key) {
            $addonsInfo = \XLite\Core\Marketplace::getInstance()->checkAddonKey($key);

        } else {
            $addonsInfo = null;
            $emptyKey = true;
        }

        if ($addonsInfo && $addonsInfo[$key]) {

            $addonsInfo = $addonsInfo[$key];

            $repo = \XLite\Core\Database::getRepo('\XLite\Model\ModuleKey');

            foreach ($addonsInfo as $info) {

                $module = \XLite\Core\Database::getRepo('\XLite\Model\Module')->findOneBy(
                    array(
                        'author' => $info['author'],
                        'name'   => $info['name'],
                    )
                );

                if ($module) {

                    $entity = $repo->findKey($info['author'], $info['name']);

                    if ($entity) {

                        $entity->setKeyValue($key);

                        $repo->update($entity);

                    } else {

                        $entity = $repo->insert($info + array('keyValue' => $key));
                    }

                    \XLite\Core\Database::getEM()->flush();

                    // Clear cache for proper installation
                    \XLite\Core\Marketplace::getInstance()->clearActionCache(\XLite\Core\Marketplace::ACTION_GET_ADDONS_LIST);

                    $this->showInfo(
                        __FUNCTION__, 'License key has been successfully verified for "{{name}}" module by "{{author}}" author', array(
                            'name'   => $module->getModuleName(),
                            'author' => $module->getAuthorName(),
                        )
                    );

                } else {

                    $this->showError(
                            __FUNCTION__, 'Key is validated, but the module [' . implode(',', $info) . '] was not found'
                    );
                }
            }

        } elseif (!isset($emptyKey)) {

            $error = \XLite\Core\Marketplace::getInstance()->getError();

            if ($error) {

                $this->showError(__FUNCTION__, 'Response from marketplace: ' . $error);

            } else {

                $this->showError(__FUNCTION__, 'Response from marketplace is not received');
            }

        } else {

            $this->showError(__FUNCTION__, 'Please specify non-empty key');
        }

        $this->setReturnURL($this->buildURL('addons_list_marketplace'));
    }

    // }}}

    /**
     * Preprocess key value
     *
     * @param string $key Key value
     *
     * @return string
     */
    protected function processKey($key)
    {
        $hostDetails = \XLite::getInstance()->getOptions('host_details');
        $host = $this->isHTTPS() ? $hostDetails['https_host'] : $hostDetails['http_host'];

        return $this->decryptKey($key, $host) ?: $key;
    }

    /**
     * Decrypt key value
     *
     * @param string $crypted Encrypted key string
     * @param string $sk      Service key
     *
     * @return string
     */
    protected function decryptKey($crypted, $sk)
    {
        $result = '';
        $s1 = $s2 = array();

        for ($i = 0; $i < strlen($crypted); $i+=2) {
            $s1[] = $crypted[$i];
            $s2[] = $crypted[$i+1];
        }

        $s1 = implode('', array_reverse($s1));
        $s2 = substr(implode($s2), 0, 32);

        if (substr(md5($sk), 0, min(32, strlen($s1))) == $s2) {
            $result = base64_decode($s1);
        }

        return $result;
    }
}
