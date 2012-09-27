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

namespace XLite\Core\DataSource;

/**
 * Abstract data source
 * 
 */
abstract class ADataSource
{
    /**
     * Data source configuration
     * 
     * @var \XLite\Model\DataSource
     */
    protected $configuration;

    /**
     * Get standardized data source information array
     * 
     * @return array
     */
    abstract public function getInfo();

    /**
     * Checks whether the data source is valid
     * 
     * @return boolean
     */
    abstract public function isValid();

    /**
     * Request and return products collection
     * 
     * @return \XLite\Core\DataSource\Base\Products
     */
    abstract public function getProductsCollection();

    /**
     * Request and return categories collection
     * 
     * @return \XLite\Core\DataSource\Base\Categories
     */
    abstract public function getCategoriesCollection();

    /**
     * Get all data sources
     * 
     * @return array
     */
    public static function getDataSources()
    {
        return array(
            '\XLite\Core\DataSource\Ecwid',
        );
    }

    /**
     * Constructor
     * 
     * @param \XLite\Model\DataSource $configuration Data source configuration model
     *  
     * @return void
     */
    public function __construct(\XLite\Model\DataSource $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * Get current data source configuration object
     * 
     * @return \XLite\Model\DataSource
     */
    protected function getConfiguration()
    {
        return $this->configuration;
    }
}
