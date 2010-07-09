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
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage Controlle
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * Languages and language labels controller
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Controller_Admin_Languages extends XLite_Controller_Admin_AAdmin
{
    /**
     * Controller parameters
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $params = array('target', 'language', 'page');

    /**
     * Search labels
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionSearch()
    {
        $data = array('page' => 1);
        if (
            isset(XLite_Core_Request::getInstance()->name)
            && strlen(XLite_Core_Request::getInstance()->name)
        ) {
            $data['name'] = XLite_Core_Request::getInstance()->name;
        }

        XLite_Model_Session::getInstance()->set(
            'labelsSearch',
            $data
        );
    }

    /**
     * Active (add) laneguage
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionActive()
    {
        $id = intval(XLite_Core_Request::getInstance()->lng_id);
        $language = XLite_Core_Database::getRepo('XLite_Model_Language')->find($id);

        if (!$language) {

            XLite_Core_TopMessage::addError(
                'The language you want to add has not been found'
            );

        } elseif ($language->added) {

            XLite_Core_TopMessage::addError(
                'The language you want to add has already been added'
            );

        } else {

            $language->added = true;
            XLite_Core_Database::getEM()->persist($language);
            XLite_Core_Database::getEM()->flush();

            XLite_Core_TopMessage::addInfo(
                'The X language has been added successfully',
                array('language' => $language->name)
            );

        }
    }

    /**
     * Inactive (delete) language
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionDelete()
    {
        $id = intval(XLite_Core_Request::getInstance()->lng_id);
        $language = XLite_Core_Database::getRepo('XLite_Model_Language')->find($id);

        if (!$language) {

            XLite_Core_TopMessage::addError(
                'The language you want to delete has not been found'
            );

        } elseif ($language->code == XLite_Core_Database::getRepo('XLite_Model_Language')->getDefaultLanguage()->code) {

            XLite_Core_TopMessage::addError(
                'The language you want to delete is the default application language and cannot be deleted'
            );

        } elseif ($language->code == XLite_Core_Config::getInstance()->General->defaultLanguage->code) {

            XLite_Core_TopMessage::addError(
                'The language you want to delete is the default interface language and cannot be deleted'
            );

        } else {

            $language->added = false;
            XLite_Core_Database::getEM()->persist($language);
            XLite_Core_Database::getEM()->flush();

            XLite_Core_TopMessage::addInfo(
                'The X language has been deleted successfully',
                array('language' => $language->name)
            );

        }
    }

    /**
     * Switch (enable / disabled) language
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionSwitch()
    {
        $id = intval(XLite_Core_Request::getInstance()->lng_id);
        $language = XLite_Core_Database::getRepo('XLite_Model_Language')->find($id);


        if (!$language) {

            XLite_Core_TopMessage::addError(
                'The language has not been found'
            );

        } elseif ($language->code == XLite_Core_Config::getInstance()->General->defaultLanguage->code) {

            XLite_Core_TopMessage::addError(
                'The default interface language cannot be disabled'
            );

        } else {

            $language->enabled = !$language->enabled;
            XLite_Core_Database::getEM()->persist($language);
            XLite_Core_Database::getEM()->flush();

            if ($language->enabled) {

                XLite_Core_TopMessage::addInfo(
                    'The X language has been enabled successfully',
                    array(
                        'language' => $language->name,
                    )
                );

            } else {

                XLite_Core_TopMessage::addInfo(
                    'The X language has been disabled successfully',
                    array(
                        'language' => $language->name,
                    )
                );

            }

        }
    }

    /**
     * Update labels
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionUpdate()
    {
        $current = XLite_Core_Request::getInstance()->current;

        // Edit labels for current language
        if (is_array($current) && $current) {
            $this->saveLabels(
                $current,
                XLite_Core_Database::getRepo('XLite_Model_Language')->getDefaultLanguage()->code
            );
        }
        unset($current);

        $translated = XLite_Core_Request::getInstance()->translated;
        $translateFail = false;
        if (is_array($translated) && $translated) {

            $language = XLite_Core_Request::getInstance()->language;

            if (!$language) {

                XLite_Core_TopMessage::addWarning(
                    'Text labels have not been updated successfully: the translation language has not been specified'
                );
                $translateFail = true;

            } elseif (
                XLite_Core_Database::getRepo('XLite_Model_Language')->getDefaultLanguage()->code == $language
            ) {

                XLite_Core_TopMessage::addWarning(
                    'Text labels have not been updated successfully: the default application language has been set as the translation language'
                );
                $translateFail = true;

            } elseif (!XLite_Core_Database::getRepo('XLite_Model_Language')->findOneByCode($language)) {

                XLite_Core_TopMessage::addWarning(
                    'Text labels have not been updated successfully: the translation language has not been found'
                );
                $translateFail = true;

            } else {
                $this->saveLabels(
                    $translated,
                    XLite_Core_Request::getInstance()->language
                );
            }
        }
        unset($translated);

        if (!$translateFail) {
            XLite_Core_TopMessage::addInfo('Text labels have been updated successfully');
            XLite_Core_Translation::getInstance()->reset();
        }
    }

    /**
     * Delete labels 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionDeleteLabels()
    {
        $ids = XLite_Core_Request::getInstance()->mark;
        if ($ids && is_array($ids)) {
            $labels = XLite_Core_Database::getRepo('XLite_Model_LanguageLabel')->findByIds($ids);
            foreach ($labels as $label) {
                XLite_Core_Database::getEM()->remove($label);
            }

            if ($labels) {
                XLite_Core_Database::getEM()->flush();
            }
        }

        if (isset($labels) && $labels) {

            XLite_Core_Translation::getInstance()->reset();
            XLite_Core_TopMessage::addInfo('Text labels have been deleted');

        } else {

            XLite_Core_TopMessage::addError(
                'Text labels have not been deleted: no text labels have been found or specified'
            );

        }
    }

    /**
     * Delete label 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionDeleteLabel()
    {
        $id = XLite_Core_Request::getInstance()->label_id;
        if ($id) {
            $label = XLite_Core_Database::getRepo('XLite_Model_LanguageLabel')->find($id);
            if ($label) {
                XLite_Core_Database::getEM()->remove($label);
                XLite_Core_Database::getEM()->flush();
            
                XLite_Core_Translation::getInstance()->reset();

                XLite_Core_TopMessage::addInfo('The text label has been deleted');
            }
        }

        if (!isset($label)) {
            XLite_Core_TopMessage::addError(
                'The text label has not been deleted: it has been either not found or not specified'
            );
        }
    }

    /**
     * Add label
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionAdd()
    {
        $name = XLite_Core_Request::getInstance()->name;
        $label = XLite_Core_Request::getInstance()->label;
        $codeDefault = XLite_Core_Database::getRepo('XLite_Model_Language')->getDefaultLanguage()->code;
        $codeInterface = XLite_Core_Config::getInstance()->General->defaultLanguage->code;

        if (!$name) {

            XLite_Core_TopMessage::addError(
                'The text label has not been added, because its name has not been specified'
            );

        } elseif (XLite_Core_Database::getRepo('XLite_Model_LanguageLabel')->findOneByName($name)) {

            XLite_Core_TopMessage::addError('The text label has not been added, because such a text label already exists');

        } elseif (!isset($label[$codeDefault]) || !$label[$codeDefault]) {

            XLite_Core_TopMessage::addError(
                'The text label has not been added, because its translation for the default application language has not been specified'
            );

        } elseif (!isset($label[$codeInterface]) || !$label[$codeInterface]) {

            XLite_Core_TopMessage::addError(
                'The text label has not been added, because its translation for the default interface language has not been specified'
            );

        } else {

            $lbl = new XLite_Model_LanguageLabel();
            $lbl->name = $name;
            XLite_Core_Database::getEM()->persist($lbl);
            XLite_Core_Database::getEM()->flush();
        
            foreach ($label as $code => $l) {
                if ($l) {
                    $lbl->getTranslation($code)->label = $l;
                }
            }

            XLite_Core_Database::getEM()->persist($lbl);
            XLite_Core_Database::getEM()->flush();

            XLite_Core_Translation::getInstance()->reset();

            XLite_Core_TopMessage::addInfo('The text label has been added successfully');
        }
    }

    /**
     * Edit label
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionEdit()
    {
        $label = XLite_Core_Request::getInstance()->label;
        $labelId = intval(XLite_Core_Request::getInstance()->label_id);
        $codeDefault = XLite_Core_Database::getRepo('XLite_Model_Language')->getDefaultLanguage()->code;
        $codeInterface = XLite_Core_Config::getInstance()->General->defaultLanguage->code;
        $lbl = XLite_Core_Database::getRepo('XLite_Model_LanguageLabel')->find($labelId);

        if (!$lbl) {

            XLite_Core_TopMessage::addError('The edited language has not been found');

        } elseif (!isset($label[$codeDefault]) || !$label[$codeDefault]) {

            XLite_Core_TopMessage::addError(
                'The text label has not been modified, because its translation for the default application language has not been specified'
            );

        } elseif (!isset($label[$codeInterface]) || !$label[$codeInterface]) {

            XLite_Core_TopMessage::addError(
                'The text label has not been modified, because its translation for the default interface language has not been specified'
            );

        } else {

            foreach ($label as $code => $l) {
                if ($l) {
                    $lbl->getTranslation($code)->label = $l;
                    XLite_Core_Database::getEM()->persist($lbl->getTranslation($code));

                } elseif ($lbl->hasTranslation($code)) {
                    XLite_Core_Database::getEM()->remove($lbl->getTranslation($code));
                }
            }

            XLite_Core_Database::getEM()->flush();

            XLite_Core_Translation::getInstance()->reset();

            XLite_Core_TopMessage::addInfo('The text label has been modified successfully');
        }
    }

    /**
     * Update language data
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionUpdateLanguage()
    {
        $lngId = intval(XLite_Core_Request::getInstance()->lng_id);
        $lng = XLite_Core_Database::getRepo('XLite_Model_Language')->find($lngId);
        $names = XLite_Core_Request::getInstance()->name;

        $codeDefault = XLite_Core_Database::getRepo('XLite_Model_Language')->getDefaultLanguage()->code;

        if (!$lng) {

            XLite_Core_TopMessage::addError('The edited language has not been found');

        } elseif (!is_array($names) || !isset($names[$codeDefault]) || !$names[$codeDefault]) {

            XLite_Core_TopMessage::addError(
                'The name of the edited language in the default application language has not been specified'
            );

        } else {

            foreach ($names as $code => $name) {
                if ($name) {
                    $lng->getTranslation($code)->name = $name;

                } elseif ($lng->hasTranslation($code)) {
                    XLite_Core_Database::getEM()->remove($lng->getTranslation($code));
                }
            }

            XLite_Core_Database::getEM()->persist($lng);
            XLite_Core_Database::getEM()->flush();

            if (
                $lng->enabled
                && XLite_Core_Request::getInstance()->default
                && XLite_Core_Config::getInstance()->General->defaultLanguage->code != $lng->code
            ) {
                XLite_Core_Database::getRepo('XLite_Model_Config')->createOption(
                    array(
                        'name'     => 'default_language',
                        'category' => 'General',
                        'value'    => $lng->code,
                    )
                );
            }

            XLite_Core_TopMessage::addInfo('The language data has been saved');
        }
    }

    /**
     * Save labels from array
     * 
     * @param array  $values Array
     * @param string $code   Language code
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function saveLabels(array $values, $code)
    {
        $labels = XLite_Core_Database::getRepo('XLite_Model_LanguageLabel')->findByIds(
            array_keys($values)
        );

        foreach ($labels as $label) {
            if (isset($values[$label->label_id])) {
                if (strlen($values[$label->label_id])) {
                    $label->getTranslation($code)->label = $values[$label->label_id];

                } elseif ($label->hasTranslation($code)) {
                    XLite_Core_Database::getEM()->remove($label->getTranslation($code));
                }
            }
        }

        XLite_Core_Database::getEM()->flush();
    }

    /**
     * Default URL to redirect
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultReturnURL()
    {
        if (XLite_Core_Request::getInstance()->action) {
            $url = $this->buildUrl(
                'languages',
                '',
                array(
                    'language' => XLite_Core_Request::getInstance()->language,
                    'page'     => max(1, intval(XLite_Core_Request::getInstance()->page)),
                )
            );

        } else {
            $url = parent::getDefaultReturnURL();
        }

        return $url;
    }
}

