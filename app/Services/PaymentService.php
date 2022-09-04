<?php

namespace App\Services;

use Adyen\AdyenException;
use Adyen\Service\Checkout;

class PaymentService
{
    /**
     * @var array<int|string, mixed>
     */
    public array $issuers = [];

    public function __construct(public Checkout $checkout)
    {
    }

    /**
     * @return array<int|string, mixed>
     *
     * @throws AdyenException
     */
    public function getIdealIssuers(): array
    {
        if (! empty($this->issuers)) {
            return $this->issuers;
        }

        $paymentMethods = $this->getPaymentMethods();

        $issuers = [];
        foreach ($paymentMethods as $paymentMethod) {
            if ($paymentMethod['type'] !== 'ideal') {
                continue;
            }

            foreach (current($paymentMethod['details'])['items'] as $item) {
                $issuers[$item['id']] = $item['name'];
            }
        }

        $this->issuers = $issuers;

        return $this->issuers;
    }

    /**
     * @return array<string, mixed>
     *
     * @throws AdyenException
     */
    private function getPaymentMethods(): array
    {
        /** @var array<string, mixed> $response */
        $response = $this->checkout->paymentMethods([
            'channel' => 'Web',
            'countryCode' => 'NL',
        ]);

        return $response['paymentMethods'];
    }
}
