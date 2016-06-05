<?php
namespace Shop42\Model;

use Core42\Model\AbstractModel;
use Shop42\Billing\Bill;

abstract class AbstractOrder extends AbstractModel implements OrderInterface
{
    /**
     * @var array
     */
    protected $properties = [
        'id',
        'uuid',
        'status',
        'paymentStatus',
        'paymentProvider',
        'orderNumber',
        'invoiceNumber',
        'deliveryNumber',
        'totalQuantity',
        'totalPriceBeforeTax',
        'totalPriceAfterTax',
        'bill',
        'billingFirstName',
        'billingLastName',
        'billingCompany',
        'billingBirthDate',
        'billingEmail',
        'billingGender',
        'billingAddress1',
        'billingAddress2',
        'billingCity',
        'billingPostcode',
        'billingState',
        'billingCountry',
        'billingPhone',
        'shippingFirstName',
        'shippingLastName',
        'shippingCompany',
        'shippingBirthDate',
        'shippingEmail',
        'shippingGender',
        'shippingAddress1',
        'shippingAddress2',
        'shippingCity',
        'shippingPostcode',
        'shippingState',
        'shippingCountry',
        'shippingPhone',
        'payed',
        'delivered',
        'created',
    ];

    /**
     * @param mixed $id
     * @return $this
     */
    public function setId($id)
    {
        return $this->set('id', $id);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->get('id');
    }

    /**
     * @return string
     */
    public function getUuid()
    {
        return $this->get('uuid');
    }

    /**
     * @param $uuid
     * @return $this
     */
    public function setUuid($uuid)
    {
        return $this->set('uuid', $uuid);
    }

    /**
     * @param string $status
     * @return $this
     */
    public function setStatus($status)
    {
        return $this->set('status', $status);
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->get('status');
    }


    /**
     * @param string $paymentStatus
     * @return $this
     */
    public function setPaymentStatus($paymentStatus)
    {
        return $this->set('paymentStatus', $paymentStatus);
    }

    /**
     * @return string
     */
    public function getPaymentStatus()
    {
        return $this->get('paymentStatus');
    }

    /**
     * @param string $paymentProvider
     * @return $this
     */
    public function setPaymentProvider($paymentProvider)
    {
        return $this->set('paymentProvider', $paymentProvider);
    }

    /**
     * @return string
     */
    public function getPaymentProvider()
    {
        return $this->get('paymentProvider');
    }

    /**
     * @param string $billingFirstName
     * @return $this
     */
    public function setBillingFirstName($billingFirstName)
    {
        return $this->set('billingFirstName', $billingFirstName);
    }

    /**
     * @return string
     */
    public function getBillingFirstName()
    {
        return $this->get('billingFirstName');
    }

    /**
     * @param string $billingLastName
     * @return $this
     */
    public function setBillingLastName($billingLastName)
    {
        return $this->set('billingLastName', $billingLastName);
    }

    /**
     * @return string
     */
    public function getBillingLastName()
    {
        return $this->get('billingLastName');
    }

    /**
     * @param string $billingCompany
     * @return $this
     */
    public function setBillingCompany($billingCompany)
    {
        return $this->set('billingCompany', $billingCompany);
    }

    /**
     * @return string
     */
    public function getBillingCompany()
    {
        return $this->get('billingCompany');
    }

    /**
     * @return \DateTime
     */
    public function getBillingBirthDate()
    {
        return $this->get('billingBirthDate');
    }

    /**
     * @param \DateTime $billingBirthDate
     * @return $this
     */
    public function setBillingBirthDate(\DateTime $billingBirthDate)
    {
        return $this->set('billingBirthDate', $billingBirthDate);
    }

    /**
     * @param string $billingEmail
     * @return $this
     */
    public function setBillingEmail($billingEmail)
    {
        return $this->set('billingEmail', $billingEmail);
    }

    /**
     * @return string
     */
    public function getBillingEmail()
    {
        return $this->get('billingEmail');
    }

    /**
     * @return string
     */
    public function getBillingGender()
    {
        return $this->get('billingGender');
    }

    /**
     * @param string $billingGender
     * @return $this
     */
    public function setBillingGender($billingGender)
    {
        return $this->set('billingGender', $billingGender);
    }

    /**
     * @param string $billingAddress1
     * @return $this
     */
    public function setBillingAddress1($billingAddress1)
    {
        return $this->set('billingAddress1', $billingAddress1);
    }

    /**
     * @return string
     */
    public function getBillingAddress1()
    {
        return $this->get('billingAddress1');
    }

    /**
     * @param string $billingAddress2
     * @return $this
     */
    public function setBillingAddress2($billingAddress2)
    {
        return $this->set('billingAddress2', $billingAddress2);
    }

    /**
     * @return string
     */
    public function getBillingAddress2()
    {
        return $this->get('billingAddress2');
    }

    /**
     * @param string $billingCity
     * @return $this
     */
    public function setBillingCity($billingCity)
    {
        return $this->set('billingCity', $billingCity);
    }

    /**
     * @return string
     */
    public function getBillingCity()
    {
        return $this->get('billingCity');
    }

    /**
     * @param string $billingPostcode
     * @return $this
     */
    public function setBillingPostcode($billingPostcode)
    {
        return $this->set('billingPostcode', $billingPostcode);
    }

    /**
     * @return string
     */
    public function getBillingPostcode()
    {
        return $this->get('billingPostcode');
    }

    /**
     * @param string $billingState
     * @return $this
     */
    public function setBillingState($billingState)
    {
        return $this->set('billingState', $billingState);
    }

    /**
     * @return string
     */
    public function getBillingState()
    {
        return $this->get('billingState');
    }

    /**
     * @param string $billingCountry
     * @return $this
     */
    public function setBillingCountry($billingCountry)
    {
        return $this->set('billingCountry', $billingCountry);
    }

    /**
     * @return string
     */
    public function getBillingCountry()
    {
        return $this->get('billingCountry');
    }

    /**
     * @param string $billingPhone
     * @return $this
     */
    public function setBillingPhone($billingPhone)
    {
        return $this->set('billingPhone', $billingPhone);
    }

    /**
     * @return string
     */
    public function getBillingPhone()
    {
        return $this->get('billingPhone');
    }

    /**
     * @param string $shippingFirstName
     * @return $this
     */
    public function setShippingFirstName($shippingFirstName)
    {
        return $this->set('shippingFirstName', $shippingFirstName);
    }

    /**
     * @return string
     */
    public function getShippingFirstName()
    {
        return $this->get('billingFirstName');
    }

    /**
     * @param string $shippingLastName
     * @return $this
     */
    public function setShippingLastName($shippingLastName)
    {
        return $this->set('shippingLastName', $shippingLastName);
    }

    /**
     * @return string
     */
    public function getShippingLastName()
    {
        return $this->get('billingLastName');
    }

    /**
     * @param string $shippingCompany
     * @return $this
     */
    public function setShippingCompany($shippingCompany)
    {
        return $this->set('shippingCompany', $shippingCompany);
    }

    /**
     * @return string
     */
    public function getShippingCompany()
    {
        return $this->get('shippingCompany');
    }

    /**
     * @param string $shippingEmail
     * @return $this
     */
    public function setShippingEmail($shippingEmail)
    {
        return $this->set('shippingEmail', $shippingEmail);
    }

    /**
     * @return string
     */
    public function getShippingEmail()
    {
        return $this->get('shippingEmail');
    }

    /**
     * @param string $shippingAddress1
     * @return $this
     */
    public function setShippingAddress1($shippingAddress1)
    {
        return $this->set('shippingAddress1', $shippingAddress1);
    }

    /**
     * @return string
     */
    public function getShippingAddress1()
    {
        return $this->get('shippingAddress1');
    }

    /**
     * @param string $shippingAddress2
     * @return $this
     */
    public function setShippingAddress2($shippingAddress2)
    {
        return $this->set('shippingAddress2', $shippingAddress2);
    }

    /**
     * @return string
     */
    public function getShippingAddress2()
    {
        return $this->get('shippingAddress2');
    }

    /**
     * @param string $shippingCity
     * @return $this
     */
    public function setShippingCity($shippingCity)
    {
        return $this->set('shippingCity', $shippingCity);
    }

    /**
     * @return string
     */
    public function getShippingCity()
    {
        return $this->get('shippingCity');
    }

    /**
     * @param string $shippingPostcode
     * @return $this
     */
    public function setShippingPostcode($shippingPostcode)
    {
        return $this->set('shippingPostcode', $shippingPostcode);
    }

    /**
     * @return string
     */
    public function getShippingPostcode()
    {
        return $this->get('shippingPostcode');
    }

    /**
     * @param string $shippingState
     * @return $this
     */
    public function setShippingState($shippingState)
    {
        return $this->set('shippingState', $shippingState);
    }

    /**
     * @return string
     */
    public function getShippingState()
    {
        return $this->get('shippingState');
    }

    /**
     * @param string $shippingCountry
     * @return $this
     */
    public function setShippingCountry($shippingCountry)
    {
        return $this->set('shippingCountry', $shippingCountry);
    }

    /**
     * @return string
     */
    public function getShippingCountry()
    {
        return $this->get('shippingCountry');
    }

    /**
     * @param string $shippingPhone
     * @return $this
     */
    public function setShippingPhone($shippingPhone)
    {
        return $this->set('shippingPhone', $shippingPhone);
    }

    /**
     * @return string
     */
    public function getShippingPhone()
    {
        return $this->get('shippingPhone');
    }

    /**
     * @param string $orderNumber
     * @return $this
     */
    public function setOrderNumber($orderNumber)
    {
        return $this->set('orderNumber', $orderNumber);
    }

    /**
     * @return string
     */
    public function getOrderNumber()
    {
        return $this->get('orderNumber');
    }

    /**
     * @param string $invoiceNumber
     * @return $this
     */
    public function setInvoiceNumber($invoiceNumber)
    {
        return $this->set('invoiceNumber', $invoiceNumber);
    }

    /**
     * @return string
     */
    public function getInvoiceNumber()
    {
        return $this->get('invoiceNumber');
    }

    /**
     * @param string $deliveryNumber
     * @return $this
     */
    public function setDeliveryNumber($deliveryNumber)
    {
        return $this->set('deliveryNumber', $deliveryNumber);
    }

    /**
     * @return string
     */
    public function getDeliveryNumber()
    {
        return $this->get('deliveryNumber');
    }

    /**
     * @param int $totalQuantity
     * @return $this
     */
    public function setTotalQuantity($totalQuantity)
    {
        return $this->set('totalQuantity', $totalQuantity);
    }

    /**
     * @return int
     */
    public function getTotalQuantity()
    {
        return $this->get('totalQuantity');
    }

    /**
     * @param float $totalPriceBeforeTax
     * @return $this
     */
    public function setTotalPriceBeforeTax($totalPriceBeforeTax)
    {
        return $this->set('totalPriceBeforeTax', $totalPriceBeforeTax);
    }

    /**
     * @return float
     */
    public function getTotalPriceBeforeTax()
    {
        return $this->get('totalPriceBeforeTax');
    }

    /**
     * @param float $totalPriceAfterTax
     * @return $this
     */
    public function setTotalPriceAfterTax($totalPriceAfterTax)
    {
        return $this->set('totalPriceAfterTax', $totalPriceAfterTax);
    }

    /**
     * @return float
     */
    public function getTotalPriceAfterTax()
    {
        return $this->get('totalPriceAfterTax');
    }

    /**
     * @param Bill $bill
     * @return $this
     */
    public function setBill(Bill $bill)
    {
        return $this->set('bill', serialize($bill));
    }

    /**
     * @return Bill
     */
    public function getBill()
    {
        return unserialize($this->get('bill'));
    }

    /**
     * @param \DateTime $payed
     * @return $this
     */
    public function setPayed(\DateTime $payed)
    {
        return $this->set('payed', $payed);
    }

    /**
     * @return \DateTime|null
     */
    public function getPayed()
    {
        return $this->get('payed');
    }

    /**
     * @param \DateTime $delivered
     * @return $this
     */
    public function setDelivered(\DateTime $delivered)
    {
        return $this->set('delivered', $delivered);
    }

    /**
     * @return \DateTime|null
     */
    public function getDelivered()
    {
        return $this->get('delivered');
    }

    /**
     * @param \DateTime $created
     * @return $this
     */
    public function setCreated(\DateTime $created)
    {
        return $this->set('created', $created);
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->get('created');
    }
}
