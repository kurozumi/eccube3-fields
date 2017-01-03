<?php

namespace Plugin\Fields\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CustomerField
 */
class CustomerField extends \Eccube\Entity\AbstractEntity
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $text;

    /**
     * @var integer
     */
    private $del_flg = '0';

    /**
     * @var \DateTime
     */
    private $create_date;

    /**
     * @var \DateTime
     */
    private $update_date;

    /**
     * @var integer
     */
    private $customer_id;

    /**
     * @var integer
     */
    private $field_id;

    /**
     * @var \Eccube\Entity\Customer
     */
    private $Customer;

    /**
     * @var \Plugin\Fields\Entity\Field
     */
    private $Field;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set text
     *
     * @param string $text
     * @return CustomerField
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string 
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set del_flg
     *
     * @param integer $delFlg
     * @return CustomerField
     */
    public function setDelFlg($delFlg)
    {
        $this->del_flg = $delFlg;

        return $this;
    }

    /**
     * Get del_flg
     *
     * @return integer 
     */
    public function getDelFlg()
    {
        return $this->del_flg;
    }

    /**
     * Set create_date
     *
     * @param \DateTime $createDate
     * @return CustomerField
     */
    public function setCreateDate($createDate)
    {
        $this->create_date = $createDate;

        return $this;
    }

    /**
     * Get create_date
     *
     * @return \DateTime 
     */
    public function getCreateDate()
    {
        return $this->create_date;
    }

    /**
     * Set update_date
     *
     * @param \DateTime $updateDate
     * @return CustomerField
     */
    public function setUpdateDate($updateDate)
    {
        $this->update_date = $updateDate;

        return $this;
    }

    /**
     * Get update_date
     *
     * @return \DateTime 
     */
    public function getUpdateDate()
    {
        return $this->update_date;
    }

    /**
     * Set customer_id
     *
     * @param integer $customerId
     * @return CustomerField
     */
    public function setCustomerId($customerId)
    {
        $this->customer_id = $customerId;

        return $this;
    }

    /**
     * Get customer_id
     *
     * @return integer 
     */
    public function getCustomerId()
    {
        return $this->customer_id;
    }

    /**
     * Set field_id
     *
     * @param integer $fieldId
     * @return CustomerField
     */
    public function setFieldId($fieldId)
    {
        $this->field_id = $fieldId;

        return $this;
    }

    /**
     * Get field_id
     *
     * @return integer 
     */
    public function getFieldId()
    {
        return $this->field_id;
    }

    /**
     * Set Customer
     *
     * @param \Eccube\Entity\Customer $customer
     * @return CustomerField
     */
    public function setCustomer(\Eccube\Entity\Customer $customer = null)
    {
        $this->Customer = $customer;

        return $this;
    }

    /**
     * Get Customer
     *
     * @return \Eccube\Entity\Customer 
     */
    public function getCustomer()
    {
        return $this->Customer;
    }

    /**
     * Set Field
     *
     * @param \Plugin\Fields\Entity\Field $field
     * @return CustomerField
     */
    public function setField(\Plugin\Fields\Entity\Field $field = null)
    {
        $this->Field = $field;

        return $this;
    }

    /**
     * Get Field
     *
     * @return \Plugin\Fields\Entity\Field 
     */
    public function getField()
    {
        return $this->Field;
    }
}
