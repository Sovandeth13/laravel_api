<?php

namespace App\Services;

use GuzzleHttp\Client;

class PayPalService
{
    protected $clientId;
    protected $secret;
    protected $baseUrl;

    public function __construct()
    {
        $this->clientId = config('services.paypal.client_id');
        $this->secret = config('services.paypal.secret');
        $this->baseUrl = config('services.paypal.mode') === 'live'
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';
    }

    protected function getAccessToken()
    {
        $client = new Client();
        $res = $client->post("{$this->baseUrl}/v1/oauth2/token", [
            'auth' => [$this->clientId, $this->secret],
            'form_params' => [
                'grant_type' => 'client_credentials'
            ],
        ]);
        $data = json_decode($res->getBody(), true);
        return $data['access_token'];
    }

    public function getOrder($orderId)
    {
        $accessToken = $this->getAccessToken();
        $client = new Client();
        $res = $client->get("{$this->baseUrl}/v2/checkout/orders/{$orderId}", [
            'headers' => [
                'Authorization' => "Bearer $accessToken",
                'Accept' => 'application/json',
            ]
        ]);
        $data = json_decode($res->getBody(), true);
        return $data;
    }
}
