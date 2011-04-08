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
 * @since     1.0.0
 */

namespace XLite\Model\Shipping;

/**
 * Shipping method model
 * 
 * @see   ____class_see____
 * @since 1.0.0
 *
 * @Entity (repositoryClass="XLite\Model\Repo\Shipping\Method")
 * @Table  (name="shipping_methods",
 *      indexes={
 *          @Index (name="processor", columns={"processor"}),
 *          @Index (name="carrier", columns={"carrier"}),
 *          @Index (name="enabled", columns={"enabled"}),
 *          @Index (name="position", columns={"position"})
 *      }
 * )
 */
class Method extends \XLite\Model\Base\I18n
{
    /**
     * A unique ID of the method
     * 
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column         (type="integer")
     */
    protected $method_id;

    /**
     * Processor class name
     * 
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="string", length="255")
     */
    protected $processor = '';
    
    /**
     * Carrier of the method (for instance, "UPS" or "USPS")
     * 
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="string", length="255")
     */
    protected $carrier = '';

    /**
     * Unique code of shipping method (within processor space)
     * 
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="string", length="255")
     */
    protected $code = '';

    /**
     * Whether the method is enabled or disabled
     * 
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="boolean")
     */
    protected $enabled = false;

    /**
     * A position of the method among other registered methods
     * 
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="integer")
     */
    protected $position = 0;

    /**
     * Shipping rates (relation)
     * 
     * @var   \Doctrine\Common\Collections\ArrayCollection
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @OneToMany (targetEntity="XLite\Model\Shipping\Markup", mappedBy="shipping_method", cascade={"all"})
     */
    protected $shipping_markups;


    /** 
     * Shipping/Product classes
     * 
     * @var   \Doctrine\Common\Collections\ArrayCollection
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @ManyToMany (targetEntity="XLite\Model\ProductClass", inversedBy="methods")
     * @JoinTable (name="shipping_class_links",
     *      joinColumns={@JoinColumn(name="method_id", referencedColumnName="method_id")},
     *      inverseJoinColumns={@JoinColumn(name="class_id", referencedColumnName="id")}
     * )
     */
    protected $classes;


    /**
     * Constructor
     *            
     * @param array $data Entity properties OPTIONAL
     *                                     
     * @return void                        
     * @see    ____func_see____            
     * @since  1.0.0
     */                                    
    public function __construct(array $data = array())
    {                                                 
        $this->shipping_markups = new \Doctrine\Common\Collections\ArrayCollection();
        $this->classes          = new \Doctrine\Common\Collections\ArrayCollection();

        parent::__construct($data);
    }
}
