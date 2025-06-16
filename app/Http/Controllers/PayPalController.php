<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use GuzzleHttp\Client;
class PayPalController extends Controller
{
    // 1. Create PayPal order and return approval URL
    public function createOrder(Request $request)
    {
        $amount = $request->input('amount');
        $accessToken = $this->getAccessToken();

        $response = Http::withToken($accessToken)
            ->post(Config::get('paypal.base_url') . '/v2/checkout/orders', [
                'intent' => 'CAPTURE',
                'purchase_units' => [[
                    'amount' => [
                        'currency_code' => 'USD',
                        'value' => number_format($amount, 2, '.', ''),
                    ]
                ]],
                'application_context' => [
                    'return_url' => Config::get('paypal.return_url'),
                    'cancel_url' => Config::get('paypal.cancel_url'),
                ],
            ]);
        $data = $response->json();

        // Find approval URL
        $approvalUrl = null;
        foreach ($data['links'] ?? [] as $link) {
            if ($link['rel'] === 'approve') {
                $approvalUrl = $link['href'];
                break;
            }
        }

        return response()->json([
            'approvalUrl' => $approvalUrl,
            'orderId'     => $data['id'] ?? null,
        ]);
    }

    // 2. Capture PayPal order, create order/order_items, update stock
public function captureOrder(Request $request)
{
    $orderId = $request->input('orderId');
    // You do NOT need $items or $user for the PayPal call.
    try {
        $accessToken = $this->getAccessToken();
        $url = Config::get('paypal.base_url') . "/v2/checkout/orders/{$orderId}/capture";

        $client = new \GuzzleHttp\Client();
        // ABSOLUTELY NO 'Content-Type', 'json', 'body', or 'form_params'
        $res = $client->request('POST', $url, [
            'headers' => [
                'Authorization' => "Bearer $accessToken",
                'Accept'        => 'application/json',
            ],
            // Nothing else here!
        ]);

        $data = json_decode($res->getBody(), true);

        if (isset($data['status']) && $data['status'] === 'COMPLETED') {
            return response()->json([
                'success' => true,
                'paypal'  => $data,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'paypal'  => null,
                'error'   => $data,
            ]);
        }
    } catch (\Exception $e) {
        // Log and return the exact error message
        \Log::error('PayPal capture exception: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'paypal'  => null,
            'error'   => $e->getMessage(),
        ]);
    }
}




    // Helper: Get PayPal access token
   private function getAccessToken()
{
    $response = Http::withBasicAuth(
        Config::get('paypal.client_id'),
        Config::get('paypal.secret')
    )->asForm()->post(Config::get('paypal.base_url') . '/v1/oauth2/token', [
        'grant_type' => 'client_credentials'
    ]);
    return $response->json()['access_token'];
}

}
