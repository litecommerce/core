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

namespace XLite\Core\Validator\Pair;

/**
 * Hash array simple pair validator
 *
 */
class Simple extends \XLite\Core\Validator\Pair\APair
{
    /**
     * Validation mode
     *
     * @var string
     */
    protected $mode = self::STRICT;

    /**
     * Cell name
     *
     * @var string
     */
    protected $name;

    /**
     * Public name 
     * 
     * @var string
     */
    protected $publicName;

    /**
     * Cell value validator
     *
     * @var \XLite\Core\Validator\AValidator
     */
    protected $validator;

    /**
     * Default value
     *
     * @var mixed
     */
    protected $defaultValue;

    /**
     * Get name
     *
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set cell name
     *
     * @param string $name Name
     *
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Set cell public name
     *
     * @param string $name Name
     *
     * @return void
     */
    public function setPublicName($name)
    {
        $this->publicName = $name;
    }

    /**
     * Get validator
     *
     * @return \XLite\Core\Validator\AValidator
     */
    public function getValidator()
    {
        return $this->validator;
    }

    /**
     * Set cell validator
     *
     * @param \XLite\Core\Validator\AValidator $validator Validator
     *
     * @return void
     */
    public function setValidator(\XLite\Core\Validator\AValidator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Set default value
     *
     * @param mixed $value Default value
     *
     * @return void
     */
    public function setDefaultValue($value)
    {
        $this->defaultValue = $value;
    }

    /**
     * Find cell
     *
     * @param array $data Data
     *
     * @return boolean
     */
    public function find(array $data)
    {
        return isset($data[$this->name]);
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
        if (!is_array($data)) {
            throw $this->throwError('Not an array');
        }

        if (!$this->name) {
            throw $this->throwInternalError('Pair key is not defined');
        }

        if (!$this->validator) {
            throw $this->throwError('Pair validator is not defined');
        }

        if ($this->find($data)) {
            try {
                $this->validator->validate($data[$this->name]);

            } catch (\XLite\Core\Validator\Exception $exception) {
                $exception->addPathItem($this->name);
                if ($this->publicName) {
                    $exception->setPublicName($this->publicName);
                }
                throw $exception;
            }

        } elseif (self::STRICT == $this->mode) {
            throw $this->throwError('Value did not found', array(), $this->name, $this->publicName);
        }
    }

    /**
     * Sanitaize
     *
     * @param mixed $data Daa
     *
     * @return array
     */
    public function sanitize($data)
    {
        return $this->find($data)
            ? array($this->name => $this->validator->sanitize($data[$this->name]))
            : array($this->name => $this->defaultValue);
    }
}
