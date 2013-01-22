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

namespace XLite\Core\Validator\String;

/**
 * CleanURL 
 *
 */
class CleanURL extends \XLite\Core\Validator\String\RegExp
{
    /**
     * Class name
     *
     * @var string
     */
    protected $class;

    /**
     * Entity id
     *
     * @var mixed
     */
    protected $id;

    /**
     * Constructor
     *
     * @param boolean $nonEmpty Non-empty flag OPTIONAL
     * @param string  $regExp   Regular expression OPTIONAL
     *
     * @return void
     */
    public function __construct($nonEmpty = false, $regExp = null, $class = '', $id = null)
    {
        parent::__construct($nonEmpty, $this->getCleanURLPattern());

        if (empty($class)) {
            \Includes\ErrorHandler::fireError(
                static::t('Empty "class" parameter is passed to the {{method}}', array('method' => __METHOD__))
            );

        } else {
            $this->class = $class;
            $this->id    = $id;
        }
    }

    /**
     * Validate
     *
     * @param mixed $data Data
     *
     * @return void
     * @throws \XLite\Core\Validator\Exception
     */
    public function validate($data)
    {
        $data = $this->sanitize($data);

        if (!\XLite\Core\Converter::isEmptyString($data)) {
            parent::validate($data);

            $entity = \XLite\Core\Database::getRepo($this->class)->findOneByCleanURL($data);

            // DO NOT use "!==" here
            if ($entity && (empty($this->id) || $entity->getUniqueIdentifier() != $this->id)) {
                $this->throwCleanURLError();
            }
        }
    }

    /**
     * Sanitize
     *
     * @param mixed $data Data
     *
     * @return string
     */
    public function sanitize($data)
    {
        return substr(
            $data = preg_replace('/\.htm(l?)$/', '', $data),
            0,
            \XLite\Core\Database::getRepo($this->class)->getFieldInfo('cleanURL', 'length'));
    }

    /**
     * Clean URL pattern
     *
     * @return string
     */
    protected function getCleanURLPattern()
    {
        return '/^' . \XLite\Core\Converter::getCleanURLAllowedCharsPattern() . '$/S';
    }

    /**
     * Wrapper
     *
     * @return void
     * @throws \XLite\Core\Validator\Exception
     */
    protected function throwCleanURLError()
    {
        throw $this->throwError('Clean URL must be unique');
    }
}
