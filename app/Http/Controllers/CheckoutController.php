<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PayPalService;

class CheckoutController extends Controller
{
    protected $paypal;

    public function __construct(PayPalService $paypal)
    {
        $this->paypal = $paypal;
    }

    // Create PayPal Order
    public function createPayPalOrder(Request $request)
{
    $request->validate([
        'cart_item_ids' => 'required|array|min:1',
        'cart_item_ids.*' => 'integer|exists:carts,id'
    ]);

    $user = $request->user();
    $cartItems = \App\Models\Cart::with('product')->whereIn('id', $request->cart_item_ids)->where('user_id', $user->id)->get();

    $total = 0;
    foreach ($cartItems as $item) {
        if ($item->product && is_numeric($item->product->price)) {
            $total += $item->product->price * $item->quantity;
        }
    }

    $totalFormatted = number_format($total, 2, '.', '');

    if (!is_numeric($totalFormatted)) {
        \Log::error('Total formatted is not numeric', ['totalFormatted' => $totalFormatted]);
        return response()->json(['message' => 'Total calculation error'], 422);
    }

    $returnUrl = route('checkout.paypal.execute');
    $cancelUrl = route('checkout.paypal.cancel');

    \Log::info('Creating PayPal Payment', [
        'total' => $totalFormatted,
        'returnUrl' => $returnUrl,
        'cancelUrl' => $cancelUrl,
        'cart_item_ids' => $request->cart_item_ids,
    ]);

    try {
        $payment = $this->paypal->createPayment($totalFormatted, $returnUrl, $cancelUrl);
        $approvalUrl = collect($payment->getLinks())->firstWhere('rel', 'approval_url')->getHref();
        return response()->json(['approval_url' => $approvalUrl]);
    } catch (\Exception $e) {
        \Log::error('PayPal payment creation failed', ['error' => $e->getMessage()]);
        return response()->json(['message' => 'PayPal payment creation failed', 'error' => $e->getMessage()], 500);
    }
}

    // Add this (even if just a stub for now)
    public function executePayPalOrder(Request $request)
    {
        // You'll likely implement order finalization here after PayPal redirects
        return response()->json(['message' => 'Payment executed!']);
    }

    // Add this if not present
    public function cancel()
    {
        // Optional: custom logic for cancellation
        return response()->json(['message' => 'Payment cancelled!']);
    }
}
