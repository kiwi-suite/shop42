<?php
namespace Shop42\Model;

use Core42\Model\ModelInterface;
use Shop42\Billing\Bill;

interface OrderInterface extends ModelInterface
{
    const PAYMENT_STATUS_NEW = 'new';
    const PAYMENT_STATUS_PENDING = 'pending';
    const PAYMENT_STATUS_ERROR = 'error';
    const PAYMENT_STATUS_SUCCESS = 'success';

    const STATUS_OPEN = 'open';
    const STATUS_CANCEL = 'cancel';
    const STATUS_ERROR = 'error';
    const STATUS_FINISH = 'finish';
    const STATUS_INCOMPLETE = 'incomplete';
    const STATUS_MARK = 'mark';

    const GENDER_MALE = "m";
    const GENDER_FEMALE = "f";

    /**
     * @param mixed $id
     * @return $this
     */
    public function setId($id);

    /**
     * @return mixed
     */
    public function getId();

    /**
     * @return string
     */
    public function getUuid();

    /**
     * @param $uuid
     * @return $this
     */
    public function setUuid($uuid);

    /**
     * @param string $status
     * @return $this
     */
    public function setStatus($status);

    /**
     * @return string
     */
    public function getStatus();

    /**
     * @param string $paymentStatus
     * @return $this
     */
    public function setPaymentStatus($paymentStatus);

    /**
     * @return string
     */
    public function getPaymentStatus();

    /**
     * @param string $paymentProvider
     * @return $this
     */
    public function setPaymentProvider($paymentProvider);

    /**
     * @return string
     */
    public function getPaymentProvider();

    /**
     * @param string $billingFirstName
     * @return $this
     */
    public function setBillingFirstName($billingFirstName);

    /**
     * @return string
     */
    public function getBillingFirstName();

    /**
     * @param string $billingLastName
     * @return $this
     */
    public function setBillingLastName($billingLastName);

    /**
     * @return string
     */
    public function getBillingLastName();

    /**
     * @param string $billingCompany
     * @return $this
     */
    public function setBillingCompany($billingCompany);

    /**
     * @return string
     */
    public function getBillingCompany();

    /**
     * @return \DateTime
     */
    public function getBillingBirthDate();

    /**
     * @param \DateTime $billingBirthDate
     * @return $this
     */
    public function setBillingBirthDate(\DateTime $billingBirthDate);

    /**
     * @param string $billingEmail
     * @return $this
     */
    public function setBillingEmail($billingEmail);

    /**
     * @return string
     */
    public function getBillingEmail();

    /**
     * @return string
     */
    public function getBillingGender();

    /**
     * @param string $billingGender
     */
    public function setBillingGender($billingGender);

    /**
     * @param string $billingAddress1
     * @return $this
     */
    public function setBillingAddress1($billingAddress1);

    /**
     * @return string
     */
    public function getBillingAddress1();

    /**
     * @param string $billingAddress2
     * @return $this
     */
    public function setBillingAddress2($billingAddress2);

    /**
     * @return string
     */
    public function getBillingAddress2();

    /**
     * @param string $billingCity
     * @return $this
     */
    public function setBillingCity($billingCity);

    /**
     * @return string
     */
    public function getBillingCity();

    /**
     * @param string $billingPostcode
     * @return $this
     */
    public function setBillingPostcode($billingPostcode);

    /**
     * @return string
     */
    public function getBillingPostcode();

    /**
     * @param string $billingState
     * @return $this
     */
    public function setBillingState($billingState);

    /**
     * @return string
     */
    public function getBillingState();

    /**
     * @param string $billingCountry
     * @return $this
     */
    public function setBillingCountry($billingCountry);

    /**
     * @return string
     */
    public function getBillingCountry();

    /**
     * @param string $billingPhone
     * @return $this
     */
    public function setBillingPhone($billingPhone);

    /**
     * @return string
     */
    public function getBillingPhone();

    /**
     * @param string $shippingFirstName
     * @return $this
     */
    public function setShippingFirstName($shippingFirstName);

    /**
     * @return string
     */
    public function getShippingFirstName();

    /**
     * @param string $shippingLastName
     * @return $this
     */
    public function setShippingLastName($shippingLastName);

    /**
     * @return string
     */
    public function getShippingLastName();

    /**
     * @param string $shippingCompany
     * @return $this
     */
    public function setShippingCompany($shippingCompany);

    /**
     * @return string
     */
    public function getShippingCompany();

    /**
     * @param string $shippingEmail
     * @return $this
     */
    public function setShippingEmail($shippingEmail);

    /**
     * @return string
     */
    public function getShippingEmail();

    /**
     * @param string $shippingAddress1
     * @return $this
     */
    public function setShippingAddress1($shippingAddress1);

    /**
     * @return string
     */
    public function getShippingAddress1();

    /**
     * @param string $shippingAddress2
     * @return $this
     */
    public function setShippingAddress2($shippingAddress2);

    /**
     * @return string
     */
    public function getShippingAddress2();

    /**
     * @param string $shippingCity
     * @return $this
     */
    public function setShippingCity($shippingCity);

    /**
     * @return string
     */
    public function getShippingCity();

    /**
     * @param string $shippingPostcode
     * @return $this
     */
    public function setShippingPostcode($shippingPostcode);

    /**
     * @return string
     */
    public function getShippingPostcode();

    /**
     * @param string $shippingState
     * @return $this
     */
    public function setShippingState($shippingState);

    /**
     * @return string
     */
    public function getShippingState();

    /**
     * @param string $shippingCountry
     * @return $this
     */
    public function setShippingCountry($shippingCountry);

    /**
     * @return string
     */
    public function getShippingCountry();

    /**
     * @param string $shippingPhone
     * @return $this
     */
    public function setShippingPhone($shippingPhone);

    /**
     * @return string
     */
    public function getShippingPhone();

    /**
     * @param string $orderNumber
     * @return $this
     */
    public function setOrderNumber($orderNumber);

    /**
     * @return string
     */
    public function getOrderNumber();

    /**
     * @param string $invoiceNumber
     * @return $this
     */
    public function setInvoiceNumber($invoiceNumber);

    /**
     * @return string
     */
    public function getInvoiceNumber();

    /**
     * @param string $deliveryNumber
     * @return $this
     */
    public function setDeliveryNumber($deliveryNumber);

    /**
     * @return string
     */
    public function getDeliveryNumber();

    /**
     * @param int $totalQuantity
     * @return $this
     */
    public function setTotalQuantity($totalQuantity);

    /**
     * @return int
     */
    public function getTotalQuantity();

    /**
     * @param float $totalPriceBeforeTax
     * @return $this
     */
    public function setTotalPriceBeforeTax($totalPriceBeforeTax);

    /**
     * @return float
     */
    public function getTotalPriceBeforeTax();

    /**
     * @param float $totalPriceAfterTax
     * @return $this
     */
    public function setTotalPriceAfterTax($totalPriceAfterTax);

    /**
     * @return float
     */
    public function getTotalPriceAfterTax();

    /**
     * @param Bill $bill
     * @return $this
     */
    public function setBill(Bill $bill);

    /**
     * @return Bill
     */
    public function getBill();

    /**
     * @param \DateTime $payed
     * @return $this
     */
    public function setPayed(\DateTime $payed);

    /**
     * @return \DateTime|null
     */
    public function getPayed();

    /**
     * @param \DateTime $delivered
     * @return $this
     */
    public function setDelivered(\DateTime $delivered);

    /**
     * @return \DateTime|null
     */
    public function getDelivered();

    /**
     * @param \DateTime $created
     * @return $this
     */
    public function setCreated(\DateTime $created);

    /**
     * @return \DateTime
     */
    public function getCreated();
}
