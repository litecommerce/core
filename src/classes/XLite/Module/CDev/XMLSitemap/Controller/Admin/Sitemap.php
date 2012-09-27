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

namespace XLite\Module\CDev\XMLSitemap\Controller\Admin;

/**
 * Sitemap 
 * 
 */
class Sitemap extends \XLite\Controller\Admin\AAdmin
{
    /**
     * Get engines 
     * 
     * @return array
     */
    public function getEngines()
    {
        return array(
            'Google'  => array(
                'title' => 'Google',
                'url'   => 'http://google.com/webmasters/tools/ping?sitemap=%url%',
            ),
            'Yandex'  => array(
                'title' => 'Yandex',
                'url'   => 'http://webmaster.yandex.ru/wmconsole/sitemap_list.xml?host=%url%',
            ),
            'Yahoo!'  => array(
                'title' => 'Yahoo!',
                'url'   => 'http://search.yahooapis.com/SiteExplorerService/V1/updateNotification?appid=SitemapWriter&url=%url%',
            ),
            'Ask.com' => array(
                'title' => 'Ask.com',
                'url'   => 'http://submissions.ask.com/ping?sitemap=%url%',
            ),
            'Bing'    => array(
                'title' => 'Bing',
                'url'   => 'http://www.bing.com/webmaster/ping.aspx?siteMap=%url%',
            ),
        );
    }

    /**
     * Place URL into engine's endpoints
     * 
     * @return void
     */
    protected function doActionLocate()
    {
        $engines = \XLite\Core\Request::getInstance()->engines;

        if ($engines) {
            foreach ($this->getEngines() as $key => $engine) {
                if (in_array($key, $engines)) {
                    $url = urlencode(
                        \XLite::getInstance()->getShopURL(
                            \XLite\Core\Converter::buildURL('sitemap', '', array(), \XLite::CART_SELF)
                        )
                    );
                    $url = str_replace('%url%', $url, $engine['url']);
                    $request = new \XLite\Core\HTTP\Request($url);
                    $response = $request->sendRequest();
                    if (200 == $response->code) {
                        \XLite\Core\TopMessage::addInfo(
                            'Site map successfully registred on X',
                            array('engine' => $key)
                        );

                    } else {
                        \XLite\Core\TopMessage::addWarning(
                            'Site map has not been registred in X',
                            array('engine' => $key)
                        );
                    }
                }
            }
        }
    }

}

