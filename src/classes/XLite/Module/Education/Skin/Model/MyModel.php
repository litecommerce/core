<?php
// vim: set ts=4 sw=4 sts=4 et:

namespace XLite\Module\Education\Skin\Model;

/**
 * Education data storage
 *
 *
 * @Entity (repositoryClass="\XLite\Module\Education\Skin\Model\Repo\MyModel")
 * @Table  (name="education_skin_mymodel_data")
 */
class MyModel extends \XLite\Model\AEntity
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
     * Field to store some string info
     *
     * @var string
     * @Column(type="string", length=128, unique=true, nullable=false)
     */
    protected $field;

}
