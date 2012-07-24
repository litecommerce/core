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
 * @since     1.0.24
 */

namespace XLite\Controller\Admin;

/**
 * Autocomplete controller 
 * 
 * @see   ____class_see____
 * @since 1.0.24
 */
class Autocomplete extends \XLite\Controller\Admin\AAdmin
{
    /**
     * Data 
     * 
     * @var   array
     * @see   ____var_see____
     * @since 1.0.24
     */
    protected $data = array();

    /**
     * Preprocessor for no-action run
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doNoAction()
    {
        $dictionary = \XLite\Core\Request::getInstance()->dictionary;

        if ($dictionary) {
            $method = 'assembleDictionary' . \XLite\Core\Converter::convertToCamelCase($dictionary);
            if (method_exists($this, $method)) {
                $this->data = $this->processData(
                    $this->$method(strval(\XLite\Core\Request::getInstance()->erm))
                );
            }
        }

        $this->silent = true;
    }

    /**
     * Process data 
     * 
     * @param array $data Key-value data
     *  
     * @return array
     * @see    ____func_see____
     * @since  1.0.24
     */
    protected function processData(array $data)
    {
        $list = array();

        foreach ($data as $k => $v) {
            $list[] = array(
                'label' => $v,
                'value' => $k,
            );
        }

        return $list;
    }

    /**
     * Process request
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function processRequest()
    {
        $content = json_encode($this->data);

        header('Content-Type: application/json; charset=UTF-8');
        header('Content-Length: ' . strlen($content));
        header('ETag: ' . md5($content));

        print ($content);
    }
}
