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
 * @since     1.0.15
 */

namespace XLite\Module\CDev\GoSocial\Model;

/**
 * Product 
 * 
 * @see   ____class_see____
 * @since 1.0.15
 */
abstract class Product extends \XLite\Model\Product implements \XLite\Base\IDecorator
{
    /**
     * Custom Open grasph meta tags
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="text")
     */
    protected $ogMeta = '';

    /**
     * USer Open graph meta tags generator flag
     *
     * @var   boolean
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="boolean")
     */
    protected $useCustomOG = false;

    /**
     * Get Open Graph meta tags 
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.15
     */
    public function getOpenGraphMetaTags()
    {
        return $this->getUseCustomOG()
            ? $this->getOgMeta()
            : $this->generateOpenGraphMetaTags();
    }

    /**
     * Define Open Graph meta tags
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.15
     */
    public function defineOpenGraphMetaTags()
    {
        $language = \XLite\Core\Session::getInstance()->getLanguage();

        $list = array(
            'og:title'       => $this->getName(),
            'og:type'        => 'website',
            'og:url'         => $this->getFrontURL(),
            'og:site_name'   => \XLite\Core\Config::getInstance()->Company->company_name,
            'og:description' => $this->getBriefDescription(),
            'og:locale'      => 'en_US',
        );

        if ($this->getImage()) {
            $list['og:image'] = $this->getImage()->getFrontURL();
        }

        if (\XLite\Core\Config::getInstance()->CDev->GoSocial->fb_app_id) {
            $list['fb:app_id'] = \XLite\Core\Config::getInstance()->CDev->GoSocial->fb_app_id;
        }

        return $list;
    }

    /**
     * Get generated Open Graph meta tags 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.15
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

}

