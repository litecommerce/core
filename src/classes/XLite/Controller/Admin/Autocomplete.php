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

namespace XLite\Controller\Admin;

/**
 * Autocomplete controller 
 * 
 */
class Autocomplete extends \XLite\Controller\Admin\AAdmin
{
    /**
     * Data 
     * 
     * @var array
     */
    protected $data = array();

    /**
     * Preprocessor for no-action run
     *
     * @return void
     */
    protected function doNoAction()
    {
        $dictionary = \XLite\Core\Request::getInstance()->dictionary;

        if ($dictionary) {
            $method = 'assembleDictionary' . \XLite\Core\Converter::convertToCamelCase($dictionary);
            if (method_exists($this, $method)) {
                $this->data = $this->processData(
                    $this->$method(strval(\XLite\Core\Request::getInstance()->term))
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
     */
    public function processRequest()
    {
        $content = json_encode($this->data);

        header('Content-Type: application/json; charset=UTF-8');
        header('Content-Length: ' . strlen($content));
        header('ETag: ' . md5($content));

        print ($content);
    }


    /**
     * Assemble dictionary - conversation recipient
     *
     * @param string $term Term
     *
     * @return array
     */
    protected function assembleDictionaryAttributeOption($term)
    {
        $cnd = new \XLite\Core\CommonCell;
        if ($term) {
            $cnd->{\XLite\Model\Repo\AttributeOption::SEARCH_NAME} = $term;
        }

        $id = intval(\XLite\Core\Request::getInstance()->id);
        if ($id) {
            $cnd->{\XLite\Model\Repo\AttributeOption::SEARCH_ATTRIBUTE} = $id;
        }

        $list = array();

        foreach (\XLite\Core\Database::getRepo('\XLite\Model\AttributeOption')->search($cnd) as $a) {
            $list[$a->getName()] = $a->getName();
        }

        return $list;
    }

}
