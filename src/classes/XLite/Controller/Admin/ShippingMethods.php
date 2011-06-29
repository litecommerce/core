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
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite\Controller\Admin;

/**
 * Shipping methods management page controller
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class ShippingMethods extends \XLite\Controller\Admin\AAdmin
{
    /**
     * Return the current page title (for the content area)
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getTitle()
    {
        return 'Shipping methods';
    }

    /**
     * Do action 'Add'
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function doActionAdd()
    {
        $postedData = \XLite\Core\Request::getInstance()->getData();

        $newMethod = new \XLite\Model\Shipping\Method();

        $newMethod->setPosition(intval($postedData['position']));
        $newMethod->setProcessor('offline');

        $code = $this->getCurrentLanguage();
        $newMethod->getTranslation($code)->name = $postedData['name'];

        \XLite\Core\Database::getEM()->persist($newMethod);
        \XLite\Core\Database::getEM()->flush();

        \XLite\Core\TopMessage::addInfo('Shipping method has been added');
    }

    /**
     * Do action 'Update'
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function doActionUpdate()
    {
        $postedData = \XLite\Core\Request::getInstance()->getData();

        $methodIds = array_keys($postedData['methods']);

        $methods = \XLite\Core\Database::getRepo('XLite\Model\Shipping\Method')->findMethodsByIds($methodIds);

        $code = $this->getCurrentLanguage();

        foreach ($methods as $method) {

            if (isset($postedData['methods'][$method->getMethodId()])) {

                $data = $postedData['methods'][$method->getMethodId()];

                $method->setPosition(intval($data['position']));
                $method->setEnabled(isset($data['enabled']) ? 1 : 0);
                $method->getTranslation($code)->name = $data['name'];

                $method->getClasses()->clear();
                $method->setClasses($this->getClasses($method));

                \XLite\Core\Database::getEM()->persist($method);
            }
        }

        if (isset($data)) {

            \XLite\Core\Database::getEM()->flush();

            \XLite\Core\TopMessage::addInfo('Shipping methods have been updated');
        }
    }

    /**
     * Do action 'Delete'
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function doActionDelete()
    {
        $postedData = \XLite\Core\Request::getInstance()->getData();

        $method = \XLite\Core\Database::getRepo('XLite\Model\Shipping\Method')
            ->find(intval($postedData['method_id']));

        if (isset($method)) {

            \XLite\Core\Database::getEM()->remove($method);
            \XLite\Core\Database::getEM()->flush();
            \XLite\Core\Database::getEM()->clear();

            \XLite\Core\TopMessage::addInfo('The selected shipping method has been deleted successfully');
        }
    }

    /**
     * getClasses
     *
     * @param \XLite\Model\Shipping\Method $method ____param_comment____
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getClasses(\XLite\Model\Shipping\Method $method)
    {
        $classes = new \Doctrine\Common\Collections\ArrayCollection();

        $postedData = $this->getPostedData('class_ids');

        foreach ((array) $postedData[$method->getMethodId()] as $classId) {

            $class = \XLite\Core\Database::getRepo('\XLite\Model\ProductClass')->findOneById($classId);

            if ($class) {

                if (!$class->getShippingMethods()->contains($method)) {

                    $class->getShippingMethods()->add($method);
                }

                $classes->add($class);
            }
        }

        return $classes;
    }

    /**
     * Common method to determine current location
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getLocation()
    {
        return static::t('Shipping methods');
    }
}
