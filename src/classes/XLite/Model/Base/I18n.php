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

namespace XLite\Model\Base;

/**
 * Translation-owner abstract class
 *
 * @see   ____class_see____
 * @since 1.0.0
 *
 * @MappedSuperclass
 */
abstract class I18n extends \XLite\Model\AEntity
{
    /**
     * Current entity language
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.20
     */
    protected $editLanguage;

    /**
     * Constructor
     *
     * @param array $data Entity properties OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function __construct(array $data = array())
    {
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();

        parent::__construct($data);
    }

    /**
     * Set current entity language
     *
     * @param string $code Code to set
     *
     * @return self
     * @see    ____func_see____
     * @since  1.0.20
     */
    public function setEditLanguage($code)
    {
        $this->editLanguage = $code;

        return $this;
    }

    /**
     * Return all translations
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * Add translation to the list
     *
     * @param \XLite\Model\Base\Translation $translation Translation to add
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function addTranslations(\XLite\Model\Base\Translation $translation)
    {
        $this->translations[] = $translation;
    }

    /**
     * Get translation
     *
     * @param string  $code             Language code OPTIONAL
     * @param boolean $allowEmptyResult Flag OPTIONAL
     *
     * @return \XLite\Model\Base\Translation
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getTranslation($code = null, $allowEmptyResult = false)
    {
        if (!isset($code)) {
            $code = $this->editLanguage ?: $this->getSessionLanguageCode();
        }

        $result = \Includes\Utils\ArrayManager::searchInObjectsArray(
            $this->getTranslations()->toArray(),
            'getCode',
            $code
        );

        if (!isset($result) && !$allowEmptyResult) {
            $class  = $this instanceof \Doctrine\ORM\Proxy\Proxy ? get_parent_class($this) : get_class($this);
            $class .= 'Translation';

            $result = new $class();
            $result->setOwner($this);
            $result->setCode($code);
        }   
        
        return $result;
    }

    /**
     * Get translation in safe mode
     *
     * @param string $code Language code OPTIONAL
     *
     * @return \XLite\Model\Base\Translation
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getSoftTranslation($code = null)
    {
        $result = $this->getTranslation($code, true);

        if (!isset($result)) {
            $result = $this->getTranslation(static::getDefaultLanguage());

            if (!$result->isPersistent() && ($tmp = $this->getTranslations()->first())) {
                $result = $tmp;
            }
        }

        return $result;
    }

    /**
     * Check - has object translation or not
     *
     * @param string $code Language code OPTIONAL
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function hasTranslation($code = null)
    {
        if (!isset($code)) {
            $code = $this->getSessionLanguageCode();
        }

        $result = false;

        foreach ($this->getTranslations() as $t) {
            if ($t->getCode() == $code) {
                $result = true;
                break;
            }
        }

        return $result;
    }

    /**
     * Get translation codes
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getTranslationCodes()
    {
        $codes = array();

        foreach ($this->getTranslations() as $t) {
            $codes[] = $t->getCode();
        }

        return $codes;
    }

    /**
     * Detach self
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function detach()
    {
        parent::detach();

        foreach ($this->getTranslations() as $t) {
            $t->detach();
        }
    }

    /**
     * Get default language code
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getSessionLanguageCode()
    {
        return \XLite\Core\Session::getInstance()->getLanguage()->getCode();
    }
}
