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
 * @subpackage ____sub_package____
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * Languages and language labels modification
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_View_LanguagesModify extends XLite_View_Dialog
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
	 * @var    mixed
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

	protected function getLabels()
	{
		$this->defineLabels();

		return $this->labels;
	}

	public function countLabels()
	{
        $this->defineLabels();

        return $this->labelsCount;
	}

	public function getPages()
	{
		$this->defineLabels();

		return $this->pagesCount;
	}

    public function getPage()
    {
        $this->defineLabels();

        $data = XLite_Model_Session::getInstance()->get('labelsSearch');

        return intval($data['page']);
    }

    public function getPagerURL()
    {
        return $this->buildUrl(
            'languages',
            '',
            array(
                'language' => XLite_Core_Request::getInstance()->language,
            )
        );
    }

	public function getSearchSubstring()
	{
		$data = XLite_Model_Session::getInstance()->get('labelsSearch');

		return is_array($data) && isset($data['name']) ? $data['name'] : '';
	}

    public function isSearchAll()
	{
        $data = XLite_Model_Session::getInstance()->get('labelsSearch');

        return is_array($data) && !isset($data['name']);
	}

    public function isSearch()
    {
        return is_array(XLite_Model_Session::getInstance()->get('labelsSearch'));
    }

    public function getAnotherLanguagesAdded()
    {
        $languages = XLite_Core_Database::getRepo('XLite_Model_Language')->findAddedLanguages();

        foreach ($languages as $k => $l) {
            if ($l->code == $this->getDefaultLanguage()->code) {
                unset($languages[$k]);
                break;
            }
        }

        return $languages;
    }

    public function isAnotherLanguagesAdded()
    {
        return 0 < count($this->getAnotherLanguagesAdded());
    }

    public function getAddedLanguages()
    {
        return XLite_Core_Database::getRepo('XLite_Model_Language')->findAddedLanguages();
    }

    public function getDefaultLanguage()
    {
        return XLite_Core_Database::getRepo('XLite_Model_Language')->getDefaultLanguage();
    }

    public function getInterfaceLanguage()
    {
        return XLite_Core_Config::getInstance()->General->defaultLanguage;
    }

    public function getTranslatedLanguage()
    {
        if (XLite_Core_Request::getInstance()->language) {
            $language = XLite_Core_Database::getRepo('XLite_Model_Language')->findOneByCode(
                XLite_Core_Request::getInstance()->language
            );
            if ($language && !$language->added) {
                $language = null;
            }
        }

        return isset($language)
            ? $language
            : $this->getDefaultLanguage();
    }

    public function isTranslatedLanguageSelected()
    {
        return XLite_Core_Request::getInstance()->language
            && !$this->isDefaultLanguage();
    }

    public function isCurrentLanguage(XLite_Model_Language $language)
    {
        return $this->getInterfaceLanguage()->code == $language->code;
    }

    public function isTranslatedLanguage(XLite_Model_Language $language)
    {
        return $this->getTranslatedLanguage()->code == $language->code;
    }

    public function isInterfaceLanguage(XLite_Model_Language $language)
    {
        return $this->getInterfaceLanguage()->code == $language->code;
    }

    public function canDelete(XLite_Model_Language $language)
    {
        return !in_array(
            $language->code,
            array($this->getDefaultLanguage()->code, $this->getInterfaceLanguage()->code)
        );
    }

    public function canSelect(XLite_Model_Language $language)
    {
        return $language->code != $this->getDefaultLanguage()->code
            && (!$this->isTranslatedLanguageSelected() || $language->code != $this->getTranslatedLanguage()->code);
    }


	public function isDefaultLanguage()
	{
		return $this->getDefaultLanguage()->code == $this->getTranslatedLanguage()->code;
	}

    public function getTranslation(XLite_Model_LanguageLabel $label)
    {
        $label = XLite_Core_Database::getRepo('XLite_Model_LanguageLabel')->find($label->label_id);

        return $label
            ? $label->getTranslation($this->getTranslatedLanguage()->code)->label
            : '';
    }

    public function getInactiveLanguages()
    {
        return XLite_Core_Database::getRepo('XLite_Model_Language')
            ->findInactiveLanguages();
    }

	protected function defineLabels()
	{
		if (!isset($this->labels)) {
            $this->labelsCount = 0;
            $this->labels = array();

            $data = XLite_Model_Session::getInstance()->get('labelsSearch');

			if (is_array($data)) {

				// Get total count
				if (isset($data['name'])) {
					$this->labelsCount = XLite_Core_Database::getRepo('XLite_Model_LanguageLabel')
						->countByName($data['name']);

				} else {
					$this->labelsCount = XLite_Core_Database::getRepo('XLite_Model_LanguageLabel')->count();
				}

                $page = XLite_Core_Request::getInstance()->page
                    ? XLite_Core_Request::getInstance()->page
                    : $data['page'];

				list($this->pagesCount, $data['page']) = XLite_Core_Operator::calculatePagination(
                    $this->labelsCount,
                    $page,
                    $this->limit
                );
				$start = ($data['page'] - 1) * $this->limit;

				// Get frame
				if (!$this->labelsCount) {
					$this->labels = array();

				} elseif (isset($data['name'])) {
					$this->labels = XLite_Core_Database::getRepo('XLite_Model_LanguageLabel')
						->findLikeName($data['name'], $start, $this->limit);

				} else {
					$this->labels = XLite_Core_Database::getRepo('XLite_Model_LanguageLabel')
                        ->findFrame($start, $this->limit);
				}

				XLite_Model_Session::getInstance()->set('labelsSearch', $data);
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

