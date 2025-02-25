<?php

// // Usage
// createCheckout(
//     74.55,
//     'EUR',
//     'YOUR_ENTITY_ID',
//     'YOUR_PAYMENT_METHOD',
//     'ORDER-1234',
//     'https://yourwebsite.com/return'
// );


namespace Sunnysideup\PaymentVerifone;

use stdClass;

class Connect
{

    function createCheckout(float $amount, string $currencyCode, string $entityId, string $paymentMethod, string $merchantReference, string $returnUrl): void
    {
        $url = 'https://YOUR_VERIFONE_API_ENDPOINT/v2/checkout';
        $apiKey = 'YOUR_API_KEY';

        $data = [
            'amount' => $amount,
            'currency_code' => $currencyCode,
            'entity_id' => $entityId,
            'configurations' => [
                $paymentMethod => new stdClass()
            ],
            'merchant_reference' => $merchantReference,
            'return_url' => $returnUrl,
            'interaction_type' => 'HPP'
        ];

        $options = [
            'http' => [
                'header' => "Content-Type: application/json\r\nAuthorization: Bearer $apiKey\r\n",
                'method' => 'POST',
                'content' => json_encode($data)
            ]
        ];

        $context = stream_context_create($options);
        $response = file_get_contents($url, false, $context);

        if ($response === false) {
            die('Error creating checkout session.');
        }

        $responseData = json_decode($response, true);
        $checkoutUrl = $responseData['url'];

        header("Location: $checkoutUrl");
        exit;
    }
}
