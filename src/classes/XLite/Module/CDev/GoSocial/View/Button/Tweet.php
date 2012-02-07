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

namespace XLite\Module\CDev\GoSocial\View\Button;

/**
 * Tweet button
 *
 * @see   ____class_see____
 * @since 1.0.0
 *
 * @ListChild (list="product.details.page.info.share.bar", weight="100")
 */
class Tweet extends \XLite\View\AView
{
    /**
     * Alloed languages 
     * 
     * @var   array
     * @see   ____var_see____
     * @since 1.0.15
     */
    protected $languages = array('nl', 'en', 'fr', 'de', 'id', 'it', 'ja', 'ko', 'pt', 'ru', 'es', 'tr');

    /**
     * Return widget default template
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'modules/CDev/GoSocial/button/tweet.tpl';
    }

    /**
     * Get button attributes 
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getButtonAttributes()
    {
        $list = array(
            'url' => $this->getURL(),
        );

        if (!\XLite\Core\Config::getInstance()->CDev->GoSocial->tweet_show_count) {
            $list['count'] = 'none';
        }

        if (\XLite\Core\Config::getInstance()->CDev->GoSocial->tweet_via) {
            $list['via'] = \XLite\Core\Config::getInstance()->CDev->GoSocial->tweet_via;
        }

        if (\XLite\Core\Config::getInstance()->CDev->GoSocial->tweet_recommend) {
            $list['related'] = \XLite\Core\Config::getInstance()->CDev->GoSocial->tweet_recommend;
        }

        if (\XLite\Core\Config::getInstance()->CDev->GoSocial->tweet_hashtag) {
            $list['hashtags'] = \XLite\Core\Config::getInstance()->CDev->GoSocial->tweet_hashtag;
        }

        $language = \XLite\Core\Session::getInstance()->getLanguage()->getCode();

        $list['lang'] = in_array($language, $this->languages) ? $language : 'en';

        return $list;
    }

    /**
     * Check if widget is visible
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isVisible()
    {
        return parent::isVisible()
            && \XLite\Core\Config::getInstance()->CDev->GoSocial->tweet_use;
    }
}
