<?php

namespace App\Http\Controllers;

use App\Models\BookLesson;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Stripe\StripeClient;

class StripeCheckoutController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function stripeCheckout(Request $request)
    {
        Stripe::setApiKey(config('services.stripe_api_key'));
        try {
            $checkout_session = Session::create([
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'Session with ' . $request->coachName,
                        ],
                        'unit_amount' => $request->price * 100,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => $request->successUrl . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => $request->cancelUrl,
            ]);

            return response()->json(['url' => $checkout_session->url]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }



    public function handleSuccess(Request $request)
    {
        Stripe::setApiKey(config('services.stripe_api_key'));
        $session_id = $request->get('session_id');

        // Retrieve the session from Stripe
        $session = \Stripe\Checkout\Session::retrieve($session_id);

        if ($session->payment_status == 'paid') {
            $lesson = BookLesson::where('id', $request->lesson_id)
                ->where('student_id', Auth::id())
                ->first();

            if ($lesson) {
                $lesson->status = 'approved';
                $lesson->save();
                $lesson->load('coach');
                return response()->json($lesson);
            }
        }

        return response()->json(['error' => 'Payment not successful'], 400);
    }
}
