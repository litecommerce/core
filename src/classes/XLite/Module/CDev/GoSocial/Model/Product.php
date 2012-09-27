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

namespace XLite\Module\CDev\GoSocial\Model;

/**
 * Product 
 * 
 */
abstract class Product extends \XLite\Model\Product implements \XLite\Base\IDecorator
{
    /**
     * Custom Open grasph meta tags
     *
     * @var string
     *
     * @Column (type="text")
     */
    protected $ogMeta = '';

    /**
     * USer Open graph meta tags generator flag
     *
     * @var boolean
     *
     * @Column (type="boolean")
     */
    protected $useCustomOG = false;

    /**
     * Get Open Graph meta tags 
     *
     * @param boolean $preprocessed Preprocessed OPTIONAL
     * 
     * @return string
     */
    public function getOpenGraphMetaTags($preprocessed = true)
    {
        $tags = $this->getUseCustomOG()
            ? $this->getOgMeta()
            : $this->generateOpenGraphMetaTags();

        return $preprocessed ? $this->preprocessOpenGraphMetaTags($tags) : $tags;
    }

    /**
     * Define Open Graph meta tags
     *
     * @return array
     */
    protected function defineOpenGraphMetaTags()
    {
        $language = \XLite\Core\Session::getInstance()->getLanguage();

        $list = array(
            'og:title'       => $this->getName(),
            'og:type'        => 'article',
            'og:url'         => '[PAGE_URL]',
            'og:site_name'   => \XLite\Core\Config::getInstance()->Company->company_name,
            'og:description' => $this->getBriefDescription(),
            'og:locale'      => 'en_US',
        );

        if ($this->getImage()) {
            $list['og:image'] = '[IMAGE_URL]';
        }

        if (\XLite\Core\Config::getInstance()->CDev->GoSocial->fb_app_id) {
            $list['fb:app_id'] = \XLite\Core\Config::getInstance()->CDev->GoSocial->fb_app_id;

        } elseif (\XLite\Core\Config::getInstance()->CDev->GoSocial->fb_admins) {
            $list['fb:admins'] = \XLite\Core\Config::getInstance()->CDev->GoSocial->fb_admins;
        }

        return $list;
    }

    /**
     * Get generated Open Graph meta tags 
     * 
     * @return void
     */
    protected function generateOpenGraphMetaTags()
    {
        $list = $this->defineOpenGraphMetaTags();

        $html = array();
        foreach ($list as $k => $v) {
            $html[] = '<meta property="' . $k . '" content="' . htmlentities($v, ENT_COMPAT, 'UTF-8') . '" />';
        }

        return implode("\n", $html);
    }

    /**
     * Preprocess Open Graph meta tags 
     * 
     * @param string $tags Tags content
     *  
     * @return string
     */
    protected function preprocessOpenGraphMetaTags($tags)
    {
        return str_replace(
            array(
                '[PAGE_URL]',
                '[IMAGE_URL]',
            ),
            array(
                $this->getFrontURL(),
                $this->getImage() ? $this->getImage()->getFrontURL() : '',
            ),
            $tags
        );
    }
}

