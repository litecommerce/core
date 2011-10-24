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
 * @since     1.0.12
 */

namespace XLite\Module\CDev\XMLSitemap\Drupal;

/**
 * Controller 
 * 
 * @see   ____class_see____
 * @since 1.0.12
 */
abstract class Controller extends \XLite\Module\CDev\DrupalConnector\Drupal\Controller implements \XLite\Base\IDecorator
{
    /**
     * Get XML sitemap link info 
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.12
     */
    public function getXMLSitemapLinkInfo()
    {
        return array(
            'lc_connector' => array(
                'label' => 'LC connector',
                'xmlsitemap' => array(
                    'rebuild callback' => function () {
                        return LCConnector_Handler::callSafely('Controller', 'generateXMLSitemapLinks');
                    },
                ),
            ),
        );
    }

    /**
     * Generate XML sitemap links 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.12
     */
    public function generateXMLSitemapLinks()
    {
        $iterator = new \XLite\Module\CDev\XMLSitemap\Logic\SitemapIterator;

        $link = array(
            'type'    => 'lc_connector',
            'subtype' => '',
            'loc'     => 'store/catalog',
        );
        xmlsitemap_link_save($link);

        foreach ($iterator as $record) {
            $target = $record['loc']['target'];
            unset($record['loc']['target']);
            $record['loc'] = \XLite\Core\Converter::buildDrupalPath($target, '', $record['loc']);

            $record['lastmod'] = date('Y-m-dh:m:s', $record['lastmod']);
            $record['type'] = 'lc_connector';
            $record['subtype'] = '';

            xmlsitemap_link_save($record);
        }
    }
}

