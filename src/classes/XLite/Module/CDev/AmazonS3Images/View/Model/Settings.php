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

namespace XLite\Module\CDev\AmazonS3Images\View\Model;

/**
 * Settings dialog model widget
 *
 */
abstract class Settings extends \XLite\View\Model\Settings implements \XLite\Base\IDecorator
{
    /**
     * Amazon S3 required settings 
     * 
     * @var array
     */
    protected $amazonS3RequiredSettings = array(
        'access_key',
        'secret_key',
        'bucket',
        'server',
    );

    /**
     * Check if field is valid and (if needed) set an error message
     *
     * @param array  $data    Current section data
     * @param string $section Current section name
     *
     * @return void
     */
    protected function validateFields(array $data, $section)
    {
        parent::validateFields($data, $section);

        if (
            'default' == $section
            && \XLite::getController() instanceOf \XLite\Controller\Admin\Module
            && 'CDev\AmazonS3Images' == $this->getModule()->getActualName()
            && !$this->errorMessages
        ) {
            $vars = array();
            foreach ($data[self::SECTION_PARAM_FIELDS] as $field) {
                $vars[$field->getName()] = $field->getValue();
            }
            $client = \XLite\Module\CDev\AmazonS3Images\Core\S3::getInstance();
            if (
                !empty($vars['access_key'])
                && !empty($vars['secret_key'])
                && !empty($vars['bucket'])
                && !$client->checkSettings($vars['access_key'], $vars['secret_key'], $vars['bucket'])
            ) {
                $this->addErrorMessage(
                    'access_key',
                    'Connection to Amazon S3 failed.'
                    . ' Check whether the AWS Access key и AWS Secret key specified in the module settings are correct.',
                    $data
                );
            }
        }
    }

    /**
     * Check - option is required or not
     *
     * @param \XLite\Model\Config $option Option
     *
     * @return boolean
     */
    protected function isOptionRequired(\XLite\Model\Config $option)
    {
        return parent::isOptionRequired($option)
            || ('CDev\AmazonS3Images' == $option->getCategory() && in_array($option->getName(), $this->amazonS3RequiredSettings));
    }

}
