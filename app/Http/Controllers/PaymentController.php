<?php namespace App\Http\Controllers;

use App\User;
use Auth;
use App\Payments;
use App\PaymentsProviders;
use App\Filter;
use App\Exchanges;
use Carbon\Carbon;
use http\Env\Response;
use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use DB;

class PaymentController extends Controller
{
    private $paymentsProviders;

    public function __construct(PaymentsProviders $paymentsProviders)
    {
        $this->paymentsProviders = $paymentsProviders;
    }

    /**
     * IPN | Return of payments providers, auto-detect and run private function.
     *
     * @param Request $r
     * @return \Illuminate\Http\JsonResponse
     */
    public function return(Request $r)
    {
        // Get POST fields
        $postFields = $r->post();

        // Iugu
        if (isset($postFields['event'])) {
            return $this->Iugu_return($postFields); // Array
        }

        // MercadoPago
        if (isset($postFields['data']['id'])) {
            return $this->MercadoPago_return($postFields); // Array
        }

        // Paggue
        if (isset($postFields['paid_at'])) {
            return $this->Paggue_return($postFields); // Array
        }

        return response()->json(['status' => 'false', 'error' => 'Provider not identified, please, contact the admin!']);
    }

    /**
     * Process payment return | IPN
     * > MercadoPago (PIX)
     *
     * @param Request $r
     * @return \Illuminate\Http\JsonResponse
     */
    private function MercadoPago_return(array $params)
    {
        // Get credential of provider
        // Obs: The function "getClientCredentials" auto check if is LIVE or DEMO and return the keys of current mode.
        $providerCredentials = $this->paymentsProviders->getClientCredentials('MercadoPago');

        // Identifier of payment
        $paymentID = $params['data']['id'] ?? NULL; //$r->post('data')['id'] ?? NULL;

        // Validate
        if ($paymentID == NULL) {
            return response()->json(['status' => 'false', 'error' => 'Missing ID of payment!']);
        }

        // Search this payment in history orders
        $payment = Payments::where('secret', $paymentID)->first();

        if (!isset($payment)) {
            return response()->json(['status' => 'false', 'error' => 'This order not exist!']);
        }

        // Check current 'status' of this payment
        /* if ($payment->status != 0){
             return response()->json(['status' => 'false', 'error' => 'This payment are not Pending!']);
         }*/

        // Search this order in Gateway Provider
        $data['url'] = 'https://api.mercadopago.com/v1/payments/' . $payment->secret;
        $data['isPOST'] = true;
        $data['client_token'] = $providerCredentials['client_token'];
        $response = $this->SendCURL($data);

        // Get user of this Order
        $user = User::where('id', $payment->user_id)->first();

        // Check if type is Payment and not another type (E.g: Transfer)
        if ($params['type'] != 'payment') {
            return response()->json(['status' => 'false', 'error' => 'This notification does not have a Payment type!']);
        }

        // Save new data (https://www.mercadopago.com.br/developers/pt/docs/woocommerce/payment-status)
        if ($response->status == 'approved') // pending | approved
        {
            // Update status of this payment order
            Payments::where('secret', $paymentID)->update([
                'status' => 1
            ]);

            // Update new balance of User
            User::where('id', $payment->user_id)->update([
                'balance' => $user->balance + $response->transaction_amount
            ]);

            // Deposit reward for Ref account
            if ($user->ref_id != NULL) {
                // Search account of this Ref
                $userRef = User::where('unique_id', $user->ref_id)->first();

                // Get count order of guest account
                $paymentCount = Payments::where('user_id', $user->id)->where('status', 1)->count();

                // Check if is first deposit
                if ($paymentCount == 1) {
                    $userRef->ref_money_all += 1; // 10 BRL
                    $userRef->ref_money += 1; // 10 BRL
                    $userRef->save();
                } else {
                    $userRef->ref_money_all += $response->transaction_amount / 3; // 3%
                    $userRef->ref_money += $response->transaction_amount / 3; // 3%
                    $userRef->save();
                }
            }
            ////////

            return response()->json(['status' => 'true', 'msg' => 'Success!']);
        } elseif ($response->status == 'cancelled') {
            // Update status of this payment order
            Payments::where('secret', $paymentID)->update([
                'status' => 5
            ]);

            return response()->json(['status' => 'true', 'msg' => 'Order status is Expired or Cancelled!']);
        } elseif ($response->status == 'inprocess' || $response->status == 'inmediation') {
            // Update status of this payment order
            Payments::where('secret', $paymentID)->update([
                'status' => 2
            ]);

            return response()->json(['status' => 'true', 'msg' => 'Order status is Inprocess']);
        } elseif ($response->status == 'Rejected') {
            // Update status of this payment order
            Payments::where('secret', $paymentID)->update([
                'status' => 4
            ]);

            return response()->json(['status' => 'true', 'msg' => 'Order status is Rejected']);
        } elseif ($response->status == 'refunded' || $response->status == 'Chargedback') {
            // Update status of this payment order
            Payments::where('secret', $paymentID)->update([
                'status' => 6
            ]);

            User::where('id', $payment->user_id)->update([
                'balance' => $user->balance - $response->transaction_amount
            ]);

            return response()->json(['status' => 'true', 'msg' => 'Order status is Refunded/Chargedback']);
        } else
            return response()->json(['status' => 'false', 'error' => 'This payment is not Approved, try again later!']);
    }

    private function Paggue_return(array $params)
    {
        // Identifier of payment
        $paymentID = $params['external_id'] ?? NULL; //$r->post('data')['id'] ?? NULL;

        // Validate
        if ($paymentID == NULL) {
            return response()->json(['status' => 'false', 'error' => 'Missing ID of payment!']);
        }

        // Search this payment in history orders
        $payment = Payments::where('order_id', $paymentID)->first();

        if (!isset($payment)) return response()->json(['status' => 'false', 'error' => 'This order not exist!']);
        if ($payment->status === 1) return response()->json(['status' => 'false', 'error' => 'This order already is paid!']);


        // Get user of this Order
        $user = User::where('id', $payment->user_id)->first();

       $depositValeu = $params['amount']/100;

        if ($params['status'] == 1) // pending | approved
        {
            // Update status of this payment order
            Payments::where('order_id', $paymentID)->update([
                'status' => 1
            ]);

            // Update new balance of User
            User::where('id', $payment->user_id)->update([
                'balance' => $user->balance + $depositValeu
            ]);

            // Deposit reward for Ref account
            if ($user->ref_id != NULL) {
                // Search account of this Ref
                $userRef = User::where('unique_id', $user->ref_id)->first();

                // Get count order of guest account
                $paymentCount = Payments::where('user_id', $user->id)->where('status', 1)->count();

                // Check if is first deposit
                if ($paymentCount == 1) {
                    $userRef->ref_money_all += 10; // 10 BRL
                    $userRef->ref_money += 10; // 10 BRL
                    $userRef->save();
                } else {
                    $userRef->ref_money_all += $depositValeu / 3; // 3%
                    $userRef->ref_money += $depositValeu / 3; // 3%
                    $userRef->save();
                }
            }
            ////////

            return response()->json(['status' => 'true', 'msg' => 'Success!']);
        } else
            return response()->json(['status' => 'false', 'error' => 'This payment is not Approved, try again later!']);
    }

    private function Iugu_return(array $params)
    {
        // Identifier of payment
        $paymentID = $params['data']['id'] ?? NULL; //$r->post('data')['id'] ?? NULL;

        // Validate
        if ($paymentID == NULL) {
            return response()->json(['status' => 'false', 'error' => 'Missing ID of payment!']);
        }

        // Search this payment in history orders
        $payment = Payments::where('order_id', $paymentID)->first();

        if (!isset($payment)) return response()->json(['status' => 'false', 'error' => 'This order not exist!']);
        if ($payment->status === 1) return response()->json(['status' => 'false', 'error' => 'This order already is paid!']);


        // Get user of this Order
        $user = User::where('id', $payment->user_id)->first();

       $depositValeu = $payment->sum;

        if ($params['data']['status'] == 'paid') // pending | approved
        {
            // Update status of this payment order
            Payments::where('order_id', $paymentID)->update([
                'status' => 1
            ]);

            // Update new balance of User
            User::where('id', $payment->user_id)->update([
                'balance' => $user->balance + $depositValeu
            ]);

            // Deposit reward for Ref account
            if ($user->ref_id != NULL) {
                // Search account of this Ref
                $userRef = User::where('unique_id', $user->ref_id)->first();

                // Get count order of guest account
                $paymentCount = Payments::where('user_id', $user->id)->where('status', 1)->count();

                // Check if is first deposit
                if ($paymentCount == 1) {
                    $userRef->ref_money_all += 10; // 10 BRL
                    $userRef->ref_money += 10; // 10 BRL
                    $userRef->save();
                } else {
                    $userRef->ref_money_all += $depositValeu / 3; // 3%
                    $userRef->ref_money += $depositValeu / 3; // 3%
                    $userRef->save();
                }
            }
            ////////

            return response()->json(['status' => 'true', 'msg' => 'Success!']);
        } else
            return response()->json(['status' => 'false', 'error' => 'This payment is not Approved, try again later!']);
    }
}
