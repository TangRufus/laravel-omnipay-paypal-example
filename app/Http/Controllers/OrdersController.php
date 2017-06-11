<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Omnipay;
use Redirect;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Once the transaction has been approved, we need to complete it.
        $transaction = Omnipay::completePurchase([
            'payerId' => $request->input('PayerID'),
            'transactionReference' => $request->input('paymentId'),
        ]);

        $finalResponse = $transaction->send();

        if ($finalResponse->isSuccessful()) {
            echo "Transaction was successful!\n";

            $id = $finalResponse->getTransactionReference();

            return Redirect::to("/orders/$id");
        }

        return $finalResponse->getData();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $transaction = Omnipay::purchase([
            'returnUrl' => 'http://paypal.app/orders',
            'cancelUrl' => 'http://paypal.app/cancel',
            'amount' => 30.00 * 2,
            'currency' => 'USD',
            'description' => 'This is a test authorize transaction.',
            'items' => [
                [
                    'name' => 'sub for xxxxxxxxxxx.xom',
                    'description' => '2 months for xxx',
                    'quantity' => 2,
                    'price' => 30.00,
                    'currency' => 'USD',
                ],
            ],
        ]);

        $response = $transaction->send();

        if ($response->isRedirect()) {
            // Yes it's a redirect.  Redirect the customer to this URL:
            $redirectUrl = $response->getRedirectUrl();

            return Redirect::to($redirectUrl);
        } else {
            dd($response);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $transaction = Omnipay::fetchPurchase();
        $transaction->setTransactionReference($id);
        $response = $transaction->send();

        return $response->getData();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
