<?php

namespace Tests\Feature\Services;

use Adyen\AdyenException;
use Adyen\Service\Checkout;
use App\Services\PaymentService;
use Mockery\MockInterface;
use Tests\TestCase;

class PaymentServiceTest extends TestCase
{
    private Checkout|MockInterface $checkout;

    private PaymentService $paymentService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->checkout = $this->mock(Checkout::class);

        $this->app->instance(Checkout::class, $this->checkout);

        $this->paymentService = app(PaymentService::class);
    }

    public function test_payment_service_get_issuers(): void
    {
        // Arrange
        $this->checkout
            ->shouldReceive('paymentMethods')
            ->with([
                'channel' => 'Web',
                'countryCode' => 'NL',
            ])
            ->andReturn($this->paymentMethodsResponse());

        // Act
        $issuers = $this->paymentService->getIdealIssuers();

        // Assert
        $expectedIssuers = [
            1 => 'Test Issuer 1',
            2 => 'Test Issuer 2',
            3 => 'Test Issuer 3',
        ];

        $this->assertEquals($expectedIssuers, $issuers);
    }

    public function test_payment_service_get_issuers_lazy_loaded(): void
    {
        // Arrange
        $issuers = [
            1 => 'Test Issuer 11',
            2 => 'Test Issuer 33',
        ];
        $this->paymentService->issuers = $issuers;

        $this->checkout->shouldNotReceive('paymentMethods');

        // Act
        $issuers = $this->paymentService->getIdealIssuers();

        // Assert
        $this->assertEquals($issuers, $issuers);
    }

    public function test_payment_service_get_issuers_throws_exception(): void
    {
        // Arrange
        $this->checkout->shouldReceive('paymentMethods')
            ->andThrow(AdyenException::class);

        // Act & Assert
        $this->expectException(AdyenException::class);

        $this->paymentService->getIdealIssuers();
    }

    private function paymentMethodsResponse(): array
    {
        return [
            'paymentMethods' => [
                [
                    'details' => [
                        [
                            'items' => [
                                ['id' => '1', 'name' => 'Test Issuer 1'],
                                ['id' => '2', 'name' => 'Test Issuer 2'],
                                ['id' => '3', 'name' => 'Test Issuer 3'],
                            ],
                            'key' => 'issuer',
                            'type' => 'select',
                        ],
                    ],
                    'name' => 'iDEAL',
                    'type' => 'ideal',
                ],
                [
                    'details' => [
                        [
                            'items' => [
                                ['id' => '4', 'name' => 'Test Issuer 4'],
                                ['id' => '5', 'name' => 'Test Issuer 5'],
                            ],
                            'key' => 'issuer',
                            'type' => 'select',
                        ],
                    ],
                    'name' => 'giropay',
                    'type' => 'giropay',
                ],
            ],
        ];
    }
}
