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

namespace ImaginationMedia\PaymentRequest\Model;

use Magento\Directory\Model\CountryFactory;
use Magento\Directory\Model\ResourceModel\Region\CollectionFactory as RegionCollectionFactory;

class Address
{
    /**
     * @var CountryFactory
     */
    protected $countryFactory;

    /**
     * @var RegionCollectionFactory
     */
    protected $regionCollectionFactory;

    /**
     * Address constructor.
     * @param CountryFactory $countryFactory
     * @param RegionCollectionFactory $regionCollectionFactory
     */
    public function __construct(
        CountryFactory $countryFactory,
        RegionCollectionFactory $regionCollectionFactory
    ) {
        $this->countryFactory = $countryFactory;
        $this->regionCollectionFactory = $regionCollectionFactory;
    }

    /**
     * Convert payment request address to the Magento format
     * @param array $address
     * @param array $contactInfo
     * @param int $customerId
     * @return array
     */
    public function convertAddressToMagentoAdress(
        array $address,
        array $contactInfo = [],
        int $customerId = null
    ) : array {
        if (!empty($contactInfo)) {
            $fullName = $contactInfo["name"];
            $names = explode(" ", $fullName);
        } else {
            $names = [
                "payment",
                "request"
            ];
        }

        $country = $this->countryFactory->create()->loadByCode($address["country"]);

        $regionCollection = $this->regionCollectionFactory->create()
            ->addFieldToFilter("country_id", $address["country"])
            ->addFieldToFilter("code", $address["region"]);
        $region = $regionCollection->getFirstItem();

        return [
            "firstname" => $names[0],
            "lastname" => end($names),
            "country_id" => $country->getId(),
            "region_id" => ($region->getData("region_id"))
                ? $region->getData("region_id") : null,
            "region" => $address["region"],
            "city" => $address["city"],
            "postcode" => $address["postalCode"],
            "customer_id" => ($customerId !== null)
                ? $customerId : null,
            "street" => (key_exists("addressLine", $address))
                ? $address["addressLine"][0] : "",
            "telephone" => $address["phone"]
        ];
    }
}
