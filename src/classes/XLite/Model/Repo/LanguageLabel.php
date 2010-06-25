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
 * @subpackage Model
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * Langauge labels repository
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Model_Repo_LanguageLabel extends XLite_Model_Repo_AbstractRepo
{
    /**
     * Define cache cells 
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineCacheCells()
    {
        $list = parent::defineCacheCells();

        $list['code'] = array(
            self::TTL_CACHE_CELL   => self::INFINITY_TTL,
            self::ATTRS_CACHE_CELL => array('code'),
        );

        return $list;
    }

    /**
     * Find labels by language code
     *
     * @param string $code Language code
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findLabelsByCode($code = null)
    {
        if (is_null($code)) {
            $code = XLite_Model_Session::getInstance()->getLanguage()->code;
        }

        $data = $this->getFromCache('code', array('code' => $code));
        if (is_null($data)) {
            $data = $this->postprocessLabelsByCode(
                $this->defineLabelsByCodeQuery($code)->getQuery()->getResult()
            );
            $this->saveToCache($data, 'code', array('code' => $code));
        }

        return $data;
    }

    /**
     * Define query builder for findLabelsByCode()
     *
     * @param string $code Language code
     * 
     * @return Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineLabelsByCodeQuery($code)
    {
        return $this->createQueryBuilder()
            ->where('l.code = :code')
            ->setParameter('code', $code);
    }

    /**
     * Postprocess for findLabelsByCode()
     * 
     * @param array $data Language labels
     *  
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function postprocessLabelsByCode(array $data)
    {
        $result = array();

        foreach ($data as $row) {
            $result[$row->name] = $row->translation;
        }
        ksort($result);

        return $result;
    }

}

