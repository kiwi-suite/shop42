<?php
namespace Shop42\Ixopay;

use Ixopay\Client\Client;

class Ixopay
{
    /**
     * @var array
     */
    protected $config;

    /**
     * Ixopay constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @param string $countryCode
     * @return array
     */
    public function getAvailablePaymentProviders($countryCode)
    {
        if (!isset($this->config['payments'][$countryCode])) {
            return [];
        }

        return $this->config['payments'][$countryCode];
    }

    /**
     * @param string $paymentProvider
     * @param string $countryCode
     * @param string $lang
     * @return Client
     * @throws \Exception
     */
    public function getClient($paymentProvider, $countryCode, $lang)
    {
        if (!isset($this->config['payments'][$countryCode][$paymentProvider])) {
            throw new \Exception("Invalid countryCode/paymentProvider combination");
        }
        return new Client(
            $this->config['credentials']['username'],
            $this->config['credentials']['password'],
            $this->config['payments'][$countryCode][$paymentProvider]['apiKey'],
            $this->config['payments'][$countryCode][$paymentProvider]['apiSecret'],
            $lang
        );
    }

    /**
     * @param string $paymentProvider
     * @param string $countryCode
     * @return array
     */
    public function getExtraParams($paymentProvider, $countryCode)
    {
        if (!isset($this->config['payments'][$countryCode][$paymentProvider]['extra_data'])) {
            return [];
        }

        if (!is_array($this->config['payments'][$countryCode][$paymentProvider]['extra_data'])) {
            return [];
        }

        return $this->config['payments'][$countryCode][$paymentProvider]['extra_data'];
    }
}
