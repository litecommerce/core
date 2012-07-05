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
        $result = $this->getHardTranslation($code);

        if (!isset($result) && !$allowEmptyResult) {
            $class  = $this instanceof \Doctrine\ORM\Proxy\Proxy ? get_parent_class($this) : get_class($this);
            $class .= 'Translation';

            $result = new $class();
            $result->setOwner($this);
            $result->setCode($this->getTranslationCode($code));
        }   
        
        return $result;
    }

    /**
     * Search for translation
     *
     * @param string $code Language code OPTIONAL
     *
     * @return \XLite\Model\Base\Translation
     * @see    ____func_see____
     * @since  1.0.22
     */
    public function getHardTranslation($code = null)
    {
        return \Includes\Utils\ArrayManager::searchInObjectsArray(
            $this->getTranslations()->toArray(),
            'getCode',
            $this->getTranslationCode($code)
        );
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
        $result = null;

        // Select by languages query (current languge -> default language -> hardcoded default language)
        $query = \XLite\Core\Translation::getLanguageQuery($code);
        foreach ($query as $code) {
            $result = $this->getTranslation($code, true);
            if (isset($result)) {
                break;
            }
        }

        // Get first translation
        if (!isset($result)) {
            $result = $this->getTranslations()->first();
        }

        // Get empty dump translation with specified code
        if (!isset($result)) {
            $result = $this->getTranslation(array_shift($query));
        }

        return $result;
    }

    /**
     * Check for translation
     *
     * @param string $code Language code OPTIONAL
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function hasTranslation($code = null)
    {
        return (bool) $this->getHardTranslation($code);
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
        return \Includes\Utils\ArrayManager::getObjectsArrayFieldValues($this->getTranslations()->toArray(), 'getCode');
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

        foreach ($this->getTranslations() as $translation) {
            $translation->detach();
        }
    }

    /**
     * Return current translation code
     *
     * @param string $code Language code OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.22
     */
    protected function getTranslationCode($code = null)
    {
        if (!isset($code)) {
            $code = $this->editLanguage ?: $this->getSessionLanguageCode();
        }

        return $code;
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
