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
 * Languages and language labels controller
 *
 */
class Languages extends \XLite\Controller\Admin\AAdmin
{
    /**
     * Controller parameters
     * FIXME: to remove
     *
     * @var string
     */
    protected $params = array('target', 'language', 'page');

    /**
     * Return the current page title (for the content area)
     *
     * @return string
     */
    public function getTitle()
    {
        return 'Language labels';
    }

    /**
     * Get return URL
     *
     * @return string
     */
    public function getReturnURL()
    {
        if (\XLite\Core\Request::getInstance()->action) {
            $url = $this->buildURL(
                'languages',
                '',
                array(
                    'language' => \XLite\Core\Request::getInstance()->language,
                    'page'     => max(1, intval(\XLite\Core\Request::getInstance()->page)),
                )
            );

        } else {
            $url = parent::getReturnURL();
        }

        return $url;
    }

    /**
     * getDefaultLanguage
     *
     * @return \XLite\Model\Language
     */
    public function getDefaultLanguageObject()
    {
        return \xLite\Core\Database::getRepo('\XLite\Model\Language')->findOneByCode(static::getDefaultLanguage());
    }

    /**
     * Search labels
     *
     * @return void
     */
    protected function doActionSearch()
    {
        $data = array('page' => 1);
        if (
            isset(\XLite\Core\Request::getInstance()->name)
            && strlen(\XLite\Core\Request::getInstance()->name)
        ) {
            $data['name'] = \XLite\Core\Request::getInstance()->name;
        }

        \XLite\Core\Session::getInstance()->set(
            'labelsSearch',
            $data
        );
    }

    /**
     * Active (add) language
     *
     * @return void
     */
    protected function doActionActive()
    {
        $id = intval(\XLite\Core\Request::getInstance()->lng_id);
        $language = \XLite\Core\Database::getRepo('\XLite\Model\Language')->find($id);

        if (!$language) {

            \XLite\Core\TopMessage::addError(
                'The language you want to add has not been found'
            );

        } elseif ($language->added) {

            \XLite\Core\TopMessage::addError(
                'The language you want to add has already been added'
            );

        } else {

            $language->added = true;
            \XLite\Core\Database::getEM()->persist($language);
            \XLite\Core\Database::getEM()->flush();

            \XLite\Core\TopMessage::addInfo(
                'The X language has been added successfully',
                array('language' => $language->name)
            );

            \XLite\Core\Translation::getInstance()->reset();
        }
    }

    /**
     * Inactive (delete) language
     *
     * @return void
     */
    protected function doActionDelete()
    {
        $id = intval(\XLite\Core\Request::getInstance()->lng_id);
        $language = \XLite\Core\Database::getRepo('\XLite\Model\Language')->find($id);

        if (!$language) {
            \XLite\Core\TopMessage::addError(
                'The language you want to delete has not been found'
            );

        } elseif ($language->code == static::getDefaultLanguage()) {
            \XLite\Core\TopMessage::addError(
                'The language you want to delete is the default interface language and cannot be deleted'
            );

        } else {
            $language->added = false;
            \XLite\Core\Database::getEM()->persist($language);
            \XLite\Core\Database::getEM()->flush();

            \XLite\Core\TopMessage::addInfo(
                'The X language has been deleted successfully',
                array('language' => $language->name)
            );

            \XLite\Core\Translation::getInstance()->reset();
        }
    }

    /**
     * Switch (enable / disabled) language
     *
     * @return void
     */
    protected function doActionSwitch()
    {
        $id = intval(\XLite\Core\Request::getInstance()->lng_id);
        $language = \XLite\Core\Database::getRepo('\XLite\Model\Language')->find($id);


        if (!$language) {

            \XLite\Core\TopMessage::addError(
                'The language has not been found'
            );

        } elseif ($language->code == static::getDefaultLanguage() && $language->enabled) {

            \XLite\Core\TopMessage::addError(
                'The default interface language cannot be disabled'
            );

        } else {

            $language->enabled = !$language->enabled;
            \XLite\Core\Database::getEM()->persist($language);
            \XLite\Core\Database::getEM()->flush();

            if ($language->enabled) {

                \XLite\Core\TopMessage::addInfo(
                    'The X language has been enabled successfully',
                    array(
                        'language' => $language->name,
                    )
                );

            } else {

                \XLite\Core\TopMessage::addInfo(
                    'The X language has been disabled successfully',
                    array(
                        'language' => $language->name,
                    )
                );

            }

            \XLite\Core\Translation::getInstance()->reset();
        }
    }

    /**
     * Update labels
     *
     * @return void
     */
    protected function doActionUpdate()
    {
        $current = \XLite\Core\Request::getInstance()->current;

        // Edit labels for current language
        if (is_array($current) && $current) {
            $this->saveLabels(
                $current,
                static::getDefaultLanguage()
            );
        }
        unset($current);

        $translated = \XLite\Core\Request::getInstance()->translated;
        $translateFail = false;
        if (is_array($translated) && $translated) {

            $language = \XLite\Core\Request::getInstance()->language;

            if (!$language) {

                \XLite\Core\TopMessage::addWarning(
                    'Text labels have not been updated successfully: the translation language has not been specified'
                );
                $translateFail = true;

            } elseif (
                static::getDefaultLanguage() == $language
            ) {

                \XLite\Core\TopMessage::addWarning(
                    'Text labels have not been updated successfully: the default application language has been set as the translation language'
                );
                $translateFail = true;

            } elseif (!\XLite\Core\Database::getRepo('\XLite\Model\Language')->findOneByCode($language)) {

                \XLite\Core\TopMessage::addWarning(
                    'Text labels have not been updated successfully: the translation language has not been found'
                );
                $translateFail = true;

            } else {
                $this->saveLabels(
                    $translated,
                    \XLite\Core\Request::getInstance()->language
                );
            }
        }
        unset($translated);

        if (!$translateFail) {
            \XLite\Core\TopMessage::addInfo('Text labels have been updated successfully');
        }

        \XLite\Core\Translation::getInstance()->reset();
    }

    /**
     * Delete labels
     *
     * @return void
     */
    protected function doActionDeleteLabels()
    {
        $ids = \XLite\Core\Request::getInstance()->mark;

        if ($ids && is_array($ids)) {

            $labels = \XLite\Core\Database::getRepo('\XLite\Model\LanguageLabel')->findByIds($ids);

            foreach ($labels as $label) {
                \XLite\Core\Database::getEM()->remove($label);
            }

            if ($labels) {
                \XLite\Core\Database::getEM()->flush();
            }
        }

        if (isset($labels) && $labels) {

            \XLite\Core\Translation::getInstance()->reset();
            \XLite\Core\TopMessage::addInfo('Text labels have been deleted');

        } else {

            \XLite\Core\TopMessage::addError(
                'Text labels have not been deleted: no text labels have been found or specified'
            );

        }
    }

    /**
     * Delete label
     *
     * @return void
     */
    protected function doActionDeleteLabel()
    {
        $id = \XLite\Core\Request::getInstance()->label_id;

        if ($id) {
            $label = \XLite\Core\Database::getRepo('\XLite\Model\LanguageLabel')->find($id);

            if ($label) {
                \XLite\Core\Database::getEM()->remove($label);
                \XLite\Core\Database::getEM()->flush();

                \XLite\Core\Translation::getInstance()->reset();

                \XLite\Core\TopMessage::addInfo('The text label has been deleted');
            }
        }

        if (!isset($label)) {
            \XLite\Core\TopMessage::addError(
                'The text label has not been deleted: it has been either not found or not specified'
            );
        }
    }

    /**
     * Add label
     *
     * @return void
     */
    protected function doActionAdd()
    {
        $name = substr(\XLite\Core\Request::getInstance()->name, 0, 255);
        $label = \XLite\Core\Request::getInstance()->label;
        $codeDefault = $codeInterface = static::getDefaultLanguage();

        if (!$name) {
            \XLite\Core\TopMessage::addError(
                'The text label has not been added, because its name has not been specified'
            );

        } elseif (\XLite\Core\Database::getRepo('\XLite\Model\LanguageLabel')->findOneByName($name)) {
            \XLite\Core\TopMessage::addError(
                'The text label has not been added, because such a text label already exists'
            );

        } elseif (!isset($label[$codeDefault]) || !$label[$codeDefault]) {
            \XLite\Core\TopMessage::addError(
                'The text label has not been added, because its translation to the default application language has not been specified'
            );

        } elseif (!isset($label[$codeInterface]) || !$label[$codeInterface]) {
            \XLite\Core\TopMessage::addError(
                'The text label has not been added, because its translation to the default interface language has not been specified'
            );

        } else {

            $lbl = new \XLite\Model\LanguageLabel();
            $lbl->setName($name);

            foreach ($label as $code => $text) {
                if (!empty($text)) {
                    $lbl->setEditLanguage($code)->setLabel($text);
                }
            }

            \XLite\Core\Database::getRepo('\XLite\Model\LanguageLabel')->insert($lbl);
            \XLite\Core\Translation::getInstance()->reset();

            \XLite\Core\TopMessage::addInfo('The text label has been added successfully');
        }
    }

    /**
     * Edit label
     *
     * @return void
     */
    protected function doActionEdit()
    {
        $label = \XLite\Core\Request::getInstance()->label;
        $labelId = intval(\XLite\Core\Request::getInstance()->label_id);
        $codeDefault = $codeInterface = static::getDefaultLanguage();
        $lbl = \XLite\Core\Database::getRepo('\XLite\Model\LanguageLabel')->find($labelId);

        if (!$lbl) {
            \XLite\Core\TopMessage::addError('The edited language has not been found');

        } elseif (!isset($label[$codeDefault]) || !$label[$codeDefault]) {
            \XLite\Core\TopMessage::addError(
                'The text label has not been modified, because its translation to the default application language has not been specified'
            );

        } elseif (!isset($label[$codeInterface]) || !$label[$codeInterface]) {
            \XLite\Core\TopMessage::addError(
                'The text label has not been modified, because its translation to the default interface language has not been specified'
            );

        } else {
            $objects = array('insert' => array(), 'delete' => array());
            
            foreach ($label as $code => $text) {
                if (!empty($text)) {
                    $translation = $lbl->getTranslation($code);
                    $translation->setLabel($text);

                    $objects['insert'][] = $translation;
                    
                } elseif ($lbl->hasTranslation($code)) {
                    $objects['delete'][] = $lbl->getTranslation($code);
                }   
            }

            \XLite\Core\Translation::getInstance()->reset();
            \XLite\Core\TopMessage::addInfo('The text label has been modified successfully');

            \XLite\Core\Database::getRepo('\XLite\Model\LanguageLabel')->insertInBatch($objects['insert']);
            \XLite\Core\Database::getRepo('\XLite\Model\LanguageLabel')->deleteInBatch($objects['delete']);
        }
    }

    /**
     * Update language data
     *
     * @return void
     */
    protected function doActionUpdateLanguage()
    {
        $lngId = intval(\XLite\Core\Request::getInstance()->lng_id);
        $lng = \XLite\Core\Database::getRepo('\XLite\Model\Language')->find($lngId);
        $names = \XLite\Core\Request::getInstance()->name;

        $codeDefault = static::getDefaultLanguage();

        if (!$lng) {

            \XLite\Core\TopMessage::addError('The edited language has not been found');

        } elseif (!is_array($names) || !isset($names[$codeDefault]) || !$names[$codeDefault]) {

            \XLite\Core\TopMessage::addError(
                'The name of the edited language in the default application language has not been specified'
            );

        } else {

            foreach ($names as $code => $name) {
                if ($name) {
                    $lng->getTranslation($code)->name = $name;

                } elseif ($lng->hasTranslation($code)) {
                    \XLite\Core\Database::getEM()->remove($lng->getTranslation($code));
                }
            }

            \XLite\Core\Database::getEM()->persist($lng);
            \XLite\Core\Database::getEM()->flush();

            if ($lng->enabled && \XLite\Core\Request::getInstance()->default) {
                \XLite\Core\Database::getRepo('\XLite\Model\Config')->createOption(
                    array(
                        'name'     => 'default_language',
                        'category' => 'General',
                        'value'    => $lng->code,
                    )
                );

                \XLite\Core\Session::getInstance()->updateSessionLanguage();
            }

            \XLite\Core\TopMessage::addInfo('The language data has been saved');
        }
    }

    /**
     * Save labels from array
     *
     * @param array  $values Array
     * @param string $code   Language code
     *
     * @return void
     */
    protected function saveLabels(array $values, $code)
    {
        $labels = \XLite\Core\Database::getRepo('\XLite\Model\LanguageLabel')->findByIds(
            array_keys($values)
        );

        $list = array();

        foreach ($labels as $label) {
            if (!empty($values[$label->getLabelId()])) {
                $label->setEditLanguage($code)->setLabel($values[$label->getLabelId()]);
                $list[] = $label;

            } elseif ($label->hasTranslation($code)) {
                $translation = $label->getTranslation($code);
                $label->getTranslations()->removeElement($translation);
                \XLite\Core\Database::getRepo('\XLite\Model\LanguageLabelTranslation')->delete($translation, false);
                $list[] = $label;
            }   
        }   

        \XLite\Core\Translation::getInstance()->reset();
        
        \XLite\Core\Database::getRepo('\XLite\Model\LanguageLabel')->updateInBatch($list);
    }
}
