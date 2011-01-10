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

namespace XLite\Module\CDev\DrupalConnector\Drupal;

/**
 * Helper 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Helper extends \XLite\Module\CDev\DrupalConnector\Drupal\ADrupal
{
    /**
     * Add JS/CSS files to Drupal
     * FIXME
     * 
     * @param array $resources List of resources to register
     *  
     * @return null
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function registerResources(array $resources)
    {
        static $weights = null;
        static $uniqueCounters = null;

        if (!isset($weights)) {
            $weights = array(
                'js'  => 0,
                'css' => 0,
            );
        }

        if (!isset($uniqueCounters)) {
            $uniqueCounters = array(
                'js'  => array(),
                'css' => array(),
            );
        }

        foreach ($resources as $key => $list) {
            foreach ($list as $path) {
                $weight = $weights[$key]++;

                $scope = 'header';
                if ($key == 'js' && !preg_match('/.skins.common.js./Ss', $path)) {
                    $scope = 'footer';
                }

                $bn = basename($path);
                if (!isset($uniqueCounters[$key][$bn])) {
                    $uniqueCounters[$key][$bn] = 0;
                }
                $uniqueCounters[$key][$bn]++;

                call_user_func_array(
                    'drupal_add_' . $key,
                    array(
                        $path,
                        array(
                            'type'     => 'file',
                            'weight'   => $weight,
                            'scope'    => $scope,
                            'basename' => preg_replace('/\.(css|js)$/Ss', '.' . $uniqueCounters[$key][$bn] . '.$1', $bn),
                            'group'    => CSS_DEFAULT,
                        ),
                    )
                );
            }
        }
    }
}
