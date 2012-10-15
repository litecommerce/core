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

namespace XLite\Model;

/**
 * Address field model
 *
 *
 * @Entity (repositoryClass="\XLite\Model\Repo\AddressField")
 * @Table  (name="address_field")
 */
class AddressField extends \XLite\Model\Base\I18n
{
    /**
     * Unique id
     *
     * @var integer
     *
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column         (type="integer", nullable=false)
     */
    protected $id;

    /**
     * Service name for address field
     * For example: firstname, lastname, country_code and so on.
     *
     * The field is named with this value in the address forms.
     * Also the "service-name-{$serviceName}" CSS class is added to the field
     *
     * @var string
     * @Column(type="string", length=128, unique=true, nullable=false)
     */
    protected $serviceName;

    /**
     * Getter name for address field (for AView::getAddressSectionData)
     * For example:
     * country for country_code
     * state for state_id, custom_state
     *
     * The field is named with this value in the address forms.
     * Also the "service-name-{$serviceName}" CSS class is added to the field
     *
     * @var string
     * @Column(type="string", length=128, nullable=false)
     */
    protected $viewGetterName = '';

    /**
     * Schema class for "Form field widget".
     * This class will be used in form widgets
     *
     * Possible values are:
     *
     * \XLite\View\FormField\Input\Text
     * \XLite\View\FormField\Select\Country
     * \XLite\View\FormField\Select\Title
     *
     * For more information check "\XLite\View\FormField\*" class family.
     *
     * The '\XLite\View\FormField\Input\Text' class (standard input text field)
     * is taken for additional fields by default.
     *
     * @var string
     * @Column(type="string", length=256, nullable=false)
     */
    protected $schemaClass = '\XLite\View\FormField\Input\Text';

    /**
     * Flag if the field is an additional one (This field could be removed)
     *
     * @var boolean
     * @Column(type="boolean")
     */
    protected $additional = true;

    /**
     * Flag if the field is a required one
     *
     * @var boolean
     * @Column(type="boolean")
     */
    protected $required = true;

    /**
     * Flag if the field is an enabled one
     *
     * @var boolean
     * @Column(type="boolean")
     */
    protected $enabled = true;

    /**
     * Position
     *
     * @var integer
     *
     * @Column (type="integer")
     */
    protected $position = 0;


    /**
     * Return CSS classes for this field name entry
     *
     * @return string
     */
    public function getCSSFieldName()
    {
        return 'address-' . $this->getServiceName() . ($this->getAdditional() ? ' field-additional' : '');
    }

}
