<?php

/**
 * W3C Payment Request (https://www.w3.org/TR/payment-request/)
 *
 * Add the W3C payment request api to Magento 2
 *
 * @package     ImaginationMedia\PaymentRequest
 * @author      Igor Ludgero Miura <igor@imaginationmedia.com>
 * @copyright   Copyright (c) 2019 Imagination Media (https://www.imaginationmedia.com/)
 * @license     https://opensource.org/licenses/OSL-3.0.php Open Software License 3.0
 */

declare(strict_types=1);

namespace ImaginationMedia\PaymentRequest\CustomerData;

use ImaginationMedia\PaymentRequest\ViewModel\Config;
use Magento\Customer\CustomerData\SectionSourceInterface;

class PaymentRequest implements SectionSourceInterface
{
    /**
     * @var Config
     */
    protected $paymentRequestConfig;

    /**
     * PaymentRequest constructor.
     * @param Config $paymentRequestConfig
     */
    public function __construct(
        Config $paymentRequestConfig
    ) {
        $this->paymentRequestConfig = $paymentRequestConfig;
    }

    /**
     * Get section config
     * @return array
     */
    public function getSectionData()
    {
        try {
            $data = [
                "paymentRequestApi" => [
                    "enabled" => $this->paymentRequestConfig->isEnabled(),
                    "cardConfig" => $this->paymentRequestConfig->getCardConfig(),
                    "paypalConfig" => $this->paymentRequestConfig->getPayPalConfig(),
                    "urls" => $this->paymentRequestConfig->getUrls(),
                    "buttonMode" => $this->paymentRequestConfig->getButtonMode(),
                    "cartItems" => $this->paymentRequestConfig->getQuoteItems(),
                    "quoteTotal" => $this->paymentRequestConfig->getQuoteTotal(),
                    "currency" => $this->paymentRequestConfig->getCurrency(),
                    "discount" => $this->paymentRequestConfig->getDiscount(),
                    "customerId" => $this->paymentRequestConfig->getCustomerId()
                ]
            ];

            $data["paymentRequestApi"]["quoteId"] = $this->paymentRequestConfig->getQuoteId();
            return $data;
        } catch (\Exception $ex) {
            return [];
        }
    }
}
