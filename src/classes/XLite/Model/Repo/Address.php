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
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     3.0.0
 */

namespace XLite\Model\Repo;

/**
 * The Address model repository
 * 
 * @see   ____class_see____
 * @since 3.0.0
 */
class Address extends \XLite\Model\Repo\ARepo
{
    /**
     * Repository type 
     * 
     * @var   string
     * @see   ____var_see____
     * @since 3.0.0
     */
    protected $type = self::TYPE_SERVICE;


    /**
     * Find the list of all cities registered in existing addresses
     * 
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findAllCities()
    {
        $result = $this->defineFindAllCities()->getResult();
    
        $cities = array();

        foreach ($result as $res) {
            $cities[] = $res->getCity();
        }

        return $cities;
    }


    /**
     * defineFindAllCities 
     * 
     * @return \Doctrine\ORM\QueryBuilder
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineFindAllCities()
    {
        return $this->createQueryBuilder()
            ->select('a.city')
            ->addGroupBy('a.city')
            ->addOrderBy('a.city');
    }

    /**
     * Get detailed foreign keys
     *
     * @return array
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDetailedForeignKeys()
    {
        $list = parent::getDetailedForeignKeys();

        $list[] = array(
            'fields'        => array('profile_id'),
            'referenceRepo' => 'XLite\Model\Profile',
        );

        return $list;
    }
}
