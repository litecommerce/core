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
 * @subpackage Controller
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Controller\Customer;

/**
 * REST services end-point
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Rest extends \XLite\Controller\Customer\ACustomer
{
    /**
     *  Response status codes
     */
    const STATUS_ERROR   = 'error';
    const STATUS_INAPPLY = 'inapply';
    const STATUS_SUCCESS = 'success';
    const STATUS_FAILED  = 'failed';


    /**
     * REST actions 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $restActions = array('get', 'post', 'put', 'delete');

    /**
     * REST repository classes 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $restClasses;

    /**
     * Current REST repository
     * 
     * @var    object
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $currentRepo;

    /**
     * Response data 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $data = array(
        'status' => self::STATUS_ERROR,
        'data'   => null,
    );

    /**
     * Handles the request.
     * Parses the request variables if necessary. Attempts to call the specified action function 
     * 
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function handleRequest()
    {
        if (in_array(\XLite\Core\Request::getInstance()->action, $this->restActions)) {
            $this->currentRepo = $this->getRepo(
                \XLite\Core\Request::getInstance()->name,
                \XLite\Core\Request::getInstance()->action
            );

            if (!$this->currentRepo) {
                $this->data['status'] = self::STATUS_INAPPLY;

            } else {
                $this->data['status'] = self::STATUS_SUCCESS;
            }
        }

        parent::handleRequest();
    }

    /**
     * Perform some actions before redirect
     *
     * @param string|null $action performed action
     *
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function actionPostprocess($action)
    {
        parent::actionPostprocess($action);

        header('Content-type: application/json');
        $data = json_encode($this->data);
        header('Content-Length: ' . strlen($data));

        echo ($data);

        exit (0);
    }

    /**
     * Get REST repository classes 
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getRESTClasses()
    {
        if (!isset($this->restClasses)) {
            $this->restClasses = array();

            foreach ($this->defineRESTClasses() as $class) {
                $repo = null;
                if (is_object($class)) {
                    $repo = $class;

                } elseif (is_string($class)) {
                    $repo = \XLite\Core\Database::getRepo($class);
                }

                if ($repo && $repo instanceof \XLite\Base\IREST) {
                    foreach ($repo->getRESTNames() as $name) {
                        $mname = \XLite\Core\Converter::convertToCamelCase($name);
                        $this->restClasses[$name] = array(
                            'repo'   => $repo,
                            'get'    => method_exists($repo, 'get' . $mname . 'REST') ? 'get' . $mname . 'REST' : null,
                            'post'   => method_exists($repo, 'post' . $mname . 'REST') ? 'post' . $mname . 'REST' : null,
                            'put'    => method_exists($repo, 'put' . $mname . 'REST') ? 'put' . $mname . 'REST' : null,
                            'delete' => method_exists($repo, 'delete' . $mname . 'REST') ? 'delete' . $mname . 'REST' : null,
                        );
                    }
                }
            }
        }

        return $this->restClasses;
    }

    /**
     * Define REST repository classes 
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineRESTClasses()
    {
        return array(
            \XLite\Core\Translation::getInstance(),
            'XLite\Model\Product',
        );
    }

    /**
     * Get repository by name and type
     * 
     * @param string $name Repository name
     * @param string $type Operation type name
     *  
     * @return object or null
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getRepo($name, $type = null)
    {
        $list = $this->getRESTClasses();

        $repo = isset($list[$name]) ? $list[$name] : null;

        if ($type && $repo && !isset($repo[$type])) {
            $repo = null;
        }

        return $repo;
    }

    /**
     * Get 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionGet()
    {
        if ($this->currentRepo) {
            $this->data['data'] = $this->currentRepo['repo']->{$this->currentRepo['get']}(
                \XLite\Core\Request::getInstance()->id,
                \XLite\Core\Request::getInstance()->data
            );
        }
    }

    /**
     * Post 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionPost()
    {
        if ($this->currentRepo) {
            $status = $this->currentRepo['repo']->{$this->currentRepo['post']}(
                \XLite\Core\Request::getInstance()->id,
                \XLite\Core\Request::getInstance()->data
            );

            if (!$status) {
                $this->data['status'] = self::STATUS_FAILED;
            }
        }
    }

    /**
     * Put 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionPut()
    {
        if ($this->currentRepo) {
            $status = $this->currentRepo['repo']->{$this->currentRepo['put']}(
                \XLite\Core\Request::getInstance()->data
            );

            if (!$status) {
                $this->data['status'] = self::STATUS_FAILED;
            }
        }
    }

    /**
     * Delete 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionDelete()
    {
        if ($this->currentRepo) {
            $status = $this->currentRepo['repo']->{$this->currentRepo['delete']}(
                \XLite\Core\Request::getInstance()->id,
                \XLite\Core\Request::getInstance()->data
            );

            if (!$status) {
                $this->data['status'] = self::STATUS_FAILED;
            }
        }
    }

}
