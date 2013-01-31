<?php

namespace XLite\Module\Education\Skin\Model;

class Product extends \XLite\Model\Product implements \XLite\Base\IDecorator
{
    /**
     *
     * @var boolean
     *
     * @Column(type="boolean")
     */
    protected $hiddenFlag;

    /**
     *
     * @var string
     *
     * @Column(type="string", length=128, unique=false)
     */
    protected $companyName = '';
}
