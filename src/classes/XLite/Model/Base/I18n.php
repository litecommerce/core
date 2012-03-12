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
     * Languages query
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected static $languagesQuery = null;

    /**
     * Edit language code
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $editLanguage;

    /**
     * Get languages query
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function getLanguagesQuery()
    {
        if (!isset(static::$languagesQuery)) {
            static::$languagesQuery = array_fill_keys(
                \XLite\Core\Database::getRepo('\XLite\Model\Language')->getLanguagesQuery(),
                false
            );
        }

        return static::$languagesQuery;
    }

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
     * @param string $code Language code OPTIONAL
     *
     * @return \XLite\Model\Base\Translation
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getTranslation($code = null)
    {
        if (!isset($code)) {
            $code = $this->getDefaultLanguageCode();
        }

        $result = null;

        foreach ($this->getTranslations() as $t) {
            if ($t->getCode() == $code) {
                $result = $t;
                break;
            }
        }

        if (!$result) {
            $className = $this instanceof \Doctrine\ORM\Proxy\Proxy
                ? get_parent_class($this) . 'Translation'
                : get_called_class() . 'Translation';
            $result = new $className();
            $result->setOwner($this);
            $result->setCode($code);
            $this->addTranslations($result);
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
        $result = null;

        if (!isset($code)) {
            $code = $this->editLanguage ?: $this->getDefaultLanguageCode();
        }

        $translations = $this->getTranslations();
        $result = \Includes\Utils\ArrayManager::searchInObjectsArray($translations->toArray(), 'getCode', $code);

        if (!isset($result)) {
            $availLangs = static::getLanguagesQuery();

            foreach ($translations as $object) {
                if (isset($availLangs[$object->getCode()])) {
                    $result = $object;
                    break;
                }
            }

            if (!isset($result)) {
                $result = $translations->first();

                // DO NOT use isset() here
                if (!$result) {
                    $class  = $this instanceof \Doctrine\ORM\Proxy\Proxy ? get_parent_class($this) : get_class($this);
                    $class .= 'Translation';

                    $result = new $class();
                    $result->setOwner($this);
                    $result->setCode($code);
                    $this->addTranslations($result);
                }
            }
        }

        return $result;
    }

    /**
     * Set edit language code
     *
     * @param string $code Language code OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function setEditLanguageCode($code = null)
    {
        $this->editLanguage = $code;
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
            $code = $this->getDefaultLanguageCode();
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
    protected function getDefaultLanguageCode()
    {
        return \XLite\Core\Session::getInstance()->getLanguage()->getCode();
    }
}
