<?php

namespace App\Http\Controllers;
use App\Services\PayMongoService;
use Illuminate\Http\Request;

class GCashController extends Controller
{
    public function initiateGCash(PayMongoService $paymongo)
{
    $amount = 500; // in PHP
    $description = 'GCash Payment for Product XYZ';
    $redirect = [
        'success' => route('gcash.success'),
        'failed' => route('gcash.failed'),
    ];

    $response = $paymongo->createGCashPayment($amount, $description, $redirect);

    if (isset($response['data']['attributes']['redirect']['checkout_url'])) {
        return redirect($response['data']['attributes']['redirect']['checkout_url']);
    }

    return back()->with('error', 'Failed to initiate GCash payment.');
}

}
