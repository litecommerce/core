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
 * @since     1.0.20
 */

namespace XLite\View\Model\Base;

/**
 * Simple CRUD 
 * 
 * @see   ____class_see____
 * @since 1.0.20
 */
abstract class Simple extends \XLite\View\Model\AModel
{
    /**
     * Update message 
     * 
     * @var   string
     * @see   ____var_see____
     * @since 1.0.20
     */
    protected $updateMessage = null;

    /**
     * Create message 
     * 
     * @var   string
     * @see   ____var_see____
     * @since 1.0.20
     */
    protected $createMessage = null;

    /**
     * Entity class 
     * 
     * @var   string
     * @see   ____var_see____
     * @since 1.0.20
     */
    protected $entityClass = null;

    /**
     * Return current model ID
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getModelId()
    {
        return \XLite\Core\Request::getInstance()->id;
    }

    /**
     * This object will be used if another one is not pased
     *
     * @return \XLite\Module\CDev\Suppliers\Model\Supplier
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultModelObject()
    {
        $model = $this->getModelId()
            ? \XLite\Core\Database::getRepo($this->entityClass)->find($this->getModelId())
            : null;

        return $model ?: new $this->entityClass;
    }

    /**
     * Return list of the "Button" widgets
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getFormButtons()
    {
        $result = parent::getFormButtons();

        $label = $this->getModelObject()->getId() ? 'Update' : 'Create';

        $result['submit'] = new \XLite\View\Button\Submit(
            array(
                \XLite\View\Button\AButton::PARAM_LABEL => $label,
                \XLite\View\Button\AButton::PARAM_STYLE => 'action',
            )
        );

        return $result;
    }

    /**
     * Add top message
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function addDataSavedTopMessage()
    {
        if ('create' != $this->currentAction) {
            if ($this->updateMessage) {
                \XLite\Core\TopMessage::addInfo($this->updateMessage);
            }

        } else {
            if ($this->createMessage) {
                \XLite\Core\TopMessage::addInfo($this->createMessage);
            }
        }
    }

}

