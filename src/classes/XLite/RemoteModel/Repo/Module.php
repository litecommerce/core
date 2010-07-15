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
 * @subpackage RemoteModel
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\RemoteModel\Repo;

/**
 * Module repository
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Module extends ARepo implements \XLite\Base\ISingleton
{
    const ALL_MODULES  = 0;
    const FREE_MODULES = 1;
    const PAY_MODULES  = 2;

    const SORT_BY_NAME   = 'name';
    const SORT_BY_PRICE  = 'price';
    const SORT_BY_RATING = 'rating';

    const SORT_ASC  = 'asc';
    const SORT_DESC = 'desc';


    /**
     * Request URL for get module 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $requestURLGet = 'https://litecommerce.com/module/%1$s/info?authCode=%2$s';

    /**
     * Request URL for find modules 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $requestURLFind = 'https://litecommerce.com/modules/search';

    /**
     * Find modules 
     * 
     * @param string  $name       Part of module name or description
     * @param string  $category   Module category name
     * @param integer $moduleType Module type (pay / free / all)
     * @param string  $version    Module version
     * @param string  $sortType   Sort field name
     * @param string  $sortOrder  Sort direction
     *  
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findModules(
        $name = null,
        $category = null,
        $moduleType = self::ALL_MODULES,
        $version = null,
        $sortType = self::SORT_BY_NAME,
        $sortOrder = self::SORT_ASC
    ) {
        $request = new \XLite\Model\HTTPS();
        $request->url = $this->requestURLFind;
        $request->method = 'post';
        $request->data = array(
            'name'       => $name,
            'category'   => $category,
            'moduleType' => $moduleType,
            'version'    => $version,
            'sortType'   => $sortType,
            'sortOrder'  => $sortOrder,
        );

        $modules = array();

        if ($request::HTTPS_SUCCESS == $request->request() && $request->response) {
            $modules = $this->postprocessFind($request->response);
        }

        return $modules;
    }

    /**
     * Postprocess search operation
     * 
     * @param string $data Response
     *  
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function postprocessFind($data)
    {
    }

    /**
     * Get module 
     * 
     * @param string $name Module name
     *  
     * @return \XLite\RemoteModel\Module or null
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getModule($name)
    {
        $request = new \XLite\Model\HTTPS();
        $request->url = sprintf($this->requestURLGet, $name, $this->getAuthCode());
        $request->method = 'get';

        $module = null;

        if ($request::HTTPS_SUCCESS == $request->request() && $request->response) {
            $module = new \XLite\RemoteModel\Module;
            $this->hydrate($module, $request->response);
        }

        return $module;
    }

    /**
     * Get application authentication code 
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getAuthCode()
    {
        return $_SERVER['HTTP_HOST'];
    }

    /**
     * Hydrate module data to model
     * 
     * @param \XLite\RemoteModel\Module $module Model
     * @param string                    $data   Response block
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function hydrate(\XLite\RemoteModel\Module $module, $data)
    {
    }
}
