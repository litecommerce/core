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
 * @subpackage View
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\View\LanguagesModify;

/**
 * Languages and language labels modification
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Dialog extends \XLite\View\Dialog
{
    /**
     * Labels limit per page
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $limit = 20;

    /**
     * Founded labels with pagination (cache)
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $labels = null;

    /**
     * Labels count
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $labelsCount = null;

    /**
     * Pages count 
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $pagesCount = null;

    /**
     * Translate language 
     * 
     * @var    \XLite\Model\Language or false
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $translateLanguage = null;

    /**
     * Return default template
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'common/empty_dialog.tpl';
    }

    /**
     * Return title
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getHead()
    {
        return 'Language labels';
    }

    /**
     * Return templates directory name
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDir()
    {
        return 'languages';
    }

    /**
     * Return list of targets allowed for this widget
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getAllowedTargets()
    {
        $result = parent::getAllowedTargets();

        $result[] = 'languages';

        return $result;
    }

    /**
     * Get labels 
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getLabels()
    {
        $this->defineLabels();

        return $this->labels;
    }

    /**
     * Count labels 
     * 
     * @return integer
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function countLabels()
    {
        $this->defineLabels();

        return $this->labelsCount;
    }

    /**
     * Get pages count
     * 
     * @return integer
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getPages()
    {
        $this->defineLabels();

        return $this->pagesCount;
    }

    /**
     * Get page index
     * 
     * @return integer
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getPage()
    {
        $this->defineLabels();

        $data = \XLite\Model\Session::getInstance()->get('labelsSearch');

        return intval($data['page']);
    }

    /**
     * Get URL for simple pager
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getPagerURL()
    {
        return $this->buildUrl(
            'languages',
            '',
            array(
                'language' => \XLite\Core\Request::getInstance()->language,
            )
        );
    }

    /**
     * Get search substring 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getSearchSubstring()
    {
        $data = \XLite\Model\Session::getInstance()->get('labelsSearch');

        return is_array($data) && isset($data['name']) ? $data['name'] : '';
    }

    /**
     * Check - widget search all labels or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isSearchAll()
    {
        $data = \XLite\Model\Session::getInstance()->get('labelsSearch');

        return is_array($data) && !isset($data['name']);
    }

    /**
     * Check - search is enabled or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isSearch()
    {
        return is_array(\XLite\Model\Session::getInstance()->get('labelsSearch'));
    }

    /**
     * Check - application has added language withount default language or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isAnotherLanguagesAdded()
    {
        $languages = \XLite\Core\Database::getRepo('\XLite\Model\Language')->findAddedLanguages();

        foreach ($languages as $k => $l) {
            if ($l->code == $this->getDefaultLanguage()->code) {
                unset($languages[$k]);
                break;
            }
        }

        return 0 < count($languages);
    }

    /**
     * Get application default language 
     * 
     * @return \XLite\Model\Language
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getDefaultLanguage()
    {
        return \XLite\Core\Database::getRepo('\XLite\Model\Language')->getDefaultLanguage();
    }

    /**
     * Get iterface default language 
     * 
     * @return \XLite\Model\Language
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getInterfaceLanguage()
    {
        return \XLite\Core\Config::getInstance()->General->defaultLanguage;
    }

    /**
     * Get translate language 
     * 
     * @return \XLite\Model\Language or null
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getTranslatedLanguage()
    {
        if (!isset($this->translateLanguage)) {
            $this->translateLanguage = false;

            if (\XLite\Core\Request::getInstance()->language) {
                $language = \XLite\Core\Database::getRepo('\XLite\Model\Language')->findOneByCode(
                    \XLite\Core\Request::getInstance()->language
                );
                if ($language && $language->added) {
                    $this->translateLanguage = $language;
                }
            }
        }

        return $this->translateLanguage ? $this->translateLanguage : null;
    }

    /**
     * Check - is translate language selected or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isTranslatedLanguageSelected()
    {
        return \XLite\Core\Request::getInstance()->language
            && $this->getTranslatedLanguage();
    }

    /**
     * Get label translation with application default language
     * 
     * @param \XLite\Model\LanguageLabel $label Label
     *  
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getLabelDefaultValue(\XLite\Model\LanguageLabel $label)
    {
        return $label->getTranslation($this->getDefaultLanguage()->code)->label;
    }

    /**
     * Get label translation with translate language
     * 
     * @param \XLite\Model\LanguageLabel $label Label
     *  
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getTranslation(\XLite\Model\LanguageLabel $label)
    {
        return $this->getTranslatedLanguage()
            ? $label->getTranslation($this->getTranslatedLanguage()->code)->label
            : '';
    }

    /**
     * Define (search) labels 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineLabels()
    {
        if (!isset($this->labels)) {
            $this->labelsCount = 0;
            $this->labels = array();

            $data = \XLite\Model\Session::getInstance()->get('labelsSearch');

            if (is_array($data)) {

                // Get total count
                if (isset($data['name'])) {
                    $this->labelsCount = \XLite\Core\Database::getRepo('\XLite\Model\LanguageLabel')
                        ->countByName($data['name']);

                } else {
                    $this->labelsCount = \XLite\Core\Database::getRepo('\XLite\Model\LanguageLabel')->count();
                }

                $page = \XLite\Core\Request::getInstance()->page
                    ? \XLite\Core\Request::getInstance()->page
                    : $data['page'];

                list($this->pagesCount, $data['page']) = \XLite\Core\Operator::calculatePagination(
                    $this->labelsCount,
                    $page,
                    $this->limit
                );
                $start = ($data['page'] - 1) * $this->limit;

                // Get frame
                if (!$this->labelsCount) {
                    $this->labels = array();

                } elseif (isset($data['name'])) {
                    $this->labels = \XLite\Core\Database::getRepo('\XLite\Model\LanguageLabel')
                        ->findLikeName($data['name'], $start, $this->limit);

                } else {
                    $this->labels = \XLite\Core\Database::getRepo('\XLite\Model\LanguageLabel')
                        ->findFrame($start, $this->limit);
                }

                \XLite\Model\Session::getInstance()->set('labelsSearch', $data);
            }
        }
    }

    /**
     * Register CSS files
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();

        $list[] = 'languages/style.css';

        return $list;
    }

    /**
     * Register JS files
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();

        $list[] = 'languages/controller.js';

        return $list;
    }

}

