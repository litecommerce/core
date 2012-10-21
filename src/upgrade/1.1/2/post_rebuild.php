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
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

return function()
{

    $types = array(
        'PurchaseOrder'      => 'O',
        'PhoneOrdering'      => 'O',
        'FaxOrdering'        => 'O',
        'MoneyOrdering'      => 'O',
        'Echeck'             => 'O',
        'COD'                => 'O',
        'AuthorizeNet SIM'   => 'C',
        'Moneybookers.WLT'   => 'N',
        'Moneybookers.ACC'   => 'C',
        'Moneybookers.VSA'   => 'C',
        'Moneybookers.MSC'   => 'C',
        'Moneybookers.VSD'   => 'C',
        'Moneybookers.VSE'   => 'C',
        'Moneybookers.MAE'   => 'C',
        'Moneybookers.SLO'   => 'C',
        'Moneybookers.AMX'   => 'C',
        'Moneybookers.DIN'   => 'C',
        'Moneybookers.JCB'   => 'C',
        'Moneybookers.LSR'   => 'C',
        'Moneybookers.GCB'   => 'C',
        'Moneybookers.DNK'   => 'C',
        'Moneybookers.PSP'   => 'C',
        'Moneybookers.CSI'   => 'C',
        'Moneybookers.OBT'   => 'N',
        'Moneybookers.GIR'   => 'N',
        'Moneybookers.DID'   => 'N',
        'Moneybookers.SFT'   => 'N',
        'Moneybookers.ENT'   => 'N',
        'Moneybookers.EBT'   => 'N',
        'Moneybookers.SO'    => 'N',
        'Moneybookers.IDL'   => 'N',
        'Moneybookers.NPY'   => 'N',
        'Moneybookers.PLI'   => 'N',
        'Moneybookers.PWY'   => 'N',
        'Moneybookers.PWY5'  => 'N',
        'Moneybookers.PWY6'  => 'N',
        'Moneybookers.PWY7'  => 'N',
        'Moneybookers.PWY14' => 'N',
        'Moneybookers.PWY15' => 'N',
        'Moneybookers.PWY17' => 'N',
        'Moneybookers.PWY18' => 'N',
        'Moneybookers.PWY19' => 'N',
        'Moneybookers.PWY20' => 'N',
        'Moneybookers.PWY21' => 'N',
        'Moneybookers.PWY22' => 'N',
        'Moneybookers.PWY25' => 'N',
        'Moneybookers.PWY26' => 'C',
        'Moneybookers.PWY28' => 'N',
        'Moneybookers.PWY32' => 'N',
        'Moneybookers.PWY33' => 'N',
        'Moneybookers.PWY36' => 'N',
        'Moneybookers.EPY'   => 'N',
        'PayflowLink'        => 'C',
        'PaypalAdvanced'     => 'A',
        'PaypalWPSUS'        => 'A',
        'ExpressCheckout'    => 'N',
        'QuantumGateway'     => 'C',
        '2Checkout.com'      => 'C',
    );

    // Get disabled modules
    $classes = array();
    $cnd = new \XLite\Core\CommonCell;
    $cnd->{\XLite\Model\Repo\Module::P_INSTALLED} = true;
    $cnd->{\XLite\Model\Repo\Module::P_ISSYSTEM} = false;
    foreach (\XLite\Core\Database::getRepo('XLite\Model\Module')->search($cnd) as $module) {
        if (!$module->getEnabled()) {
            $classes[] = $module->getActualName();
        }
    }

    // Enable/disable  all payment methods by modules
    foreach (\XLite\Core\Database::getRepo('XLite\Model\Payment\Method')->findAll() as $method) {
        $parts = explode('\\', $method->getClass());

        if ('Module' == $parts[0]) {
            $method->setModuleName(implode('_', array_slice($parts, 1, 2)));
        }

        $class = implode('\\', array_slice($parts, 1, 2));
        $method->setModuleEnabled(!in_array($class, $classes));

        if (!$method->getType()) {
            $method->setType(isset($types[$method->getServiceName()]) ? $types[$method->getServiceName()] : 'C');
        }

        if (!$method->getAdded() && $method->getType() == \XLite\Model\Payment\Method::TYPE_OFFLINE) {
            $method->setAdded(true);

        } else {
            $method->setAdded($method->getEnabled());
        }
    }
    \XLite\Core\Database::getEM()->flush();

    // Loading data to the database from yaml file
    $yamlFile = __DIR__ . LC_DS . 'post_rebuild.yaml';

    if (\Includes\Utils\FileManager::isFileReadable($yamlFile)) {
        \XLite\Core\Database::getInstance()->loadFixturesFromYaml($yamlFile);
    }

};
