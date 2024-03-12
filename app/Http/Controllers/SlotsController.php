<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\Slots;
use App\SlotsHash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SlotsController extends Controller
{
    public function spribeDeposit(Request $r) {
        $postFields = $r->post();
        
        $fp = fopen('test.txt', 'w');
        fwrite($fp, json_encode($postFields, true));
        fclose($fp);

        $lastid = Slots::lastId();

        $user = User::where('id', intval($postFields['user_id']))->first();
        $wallet = $user->balance;

        $wallet = ($wallet) + (intval($postFields['amount'])/1000);
        $user->balance += intval($postFields['amount']) / 1000;
        $user->save();

        Slots::insert(
            [
                'provider' => 'spribe',
                'game' => $postFields['game'],
                'game_id' => $postFields['provider_tx_id'],
                'user' => $postFields['user_id'],
                'action' => $postFields['action'],
                'action_id' => $postFields['action_id'],
                'charge' => intval($postFields['amount']),
                'status' => 1
            ]
        );
        return [
            "code" => 200,
            "message" => "OK",
            "data" => [
                "operator_tx_id" => $lastid+1,
                "new_balance" => $wallet*1000,
                "old_balance" => $user->balance,
                "user_id" => $user->id,
                "currency" => "BRL",
                "provider" => "betfyre",
                "provider_tx_id" => $postFields['provider_tx_id']
            ]
        ];
    }

    public function spribeWithdraw(Request $r) {
        $postFields = $r->post();
        
        $fp = fopen('test.txt', 'w');
        fwrite($fp, json_encode($postFields, true));
        fclose($fp);

        $lastid = Slots::lastId();

        $user = User::where('id', intval($postFields['user_id']))->first();
        $wallet = $user->balance;

        if($wallet - (intval($postFields['amount'])/1000) > 0) {
            $wallet = ($wallet) - (intval($postFields['amount'])/1000);
            $user->balance -= intval($postFields['amount']) / 1000;
            $user->requery += intval($postFields['amount']) / 1000;
            $user->save();

            Slots::insert(
                [
                    'provider' => 'spribe',
                    'game' => $postFields['game'],
                    'game_id' => $postFields['provider_tx_id'],
                    'user' => $postFields['user_id'],
                    'action' => $postFields['action'],
                    'action_id' => $postFields['action_id'],
                    'charge' => intval($postFields['amount']),
                    'status' => 1
                ]
            );
            return [
                "code" => 200,
                "message" => "OK",
                "data" => [
                    "operator_tx_id" => $lastid+1,
                    "new_balance" => $wallet*1000,
                    "old_balance" => $user->balance,
                    "user_id" => $user->id,
                    "currency" => "BRL",
                    "provider" => "betfyre",
                    "provider_tx_id" => $postFields['provider_tx_id']
                ]
            ];
        } else {

        }

    }

    public function spribeAuth(Request $r) {
        $postFields = $r->post();
        
        // $fp = fopen('test.txt', 'w');
        // fwrite($fp, json_encode($postFields, true));
        // fclose($fp);

        $user_hash = SlotsHash::lastHash($postFields['user_token']);
        if($user_hash) {
            $user = User::getUser($user_hash->user_id);
            return [
                "code" => 200,
                "message" => "ok",
                "data" => [
                    "user_id" => $user_hash->user_id,
                    "username" => $user->username,
                    "balance" => intval($user->balance * 1000),
                    "currency" => "BRL"
                ]
            ];
        } else {
            return [
                "code" => 401,
                "message" => "User token is invalid"
            ];
        }
    }

    public function spribeGame($game) {
        $game_url = '';
        if(Auth::user()) {
            $user = Auth::user();
            $hash = Hash::make($user->id . (new \DateTime('now'))->getTimestamp());
            SlotsHash::insert([
                'user_id' => $user->id,
                'hash' => $hash
            ]);

            $game_url = 'https://test.partners.casinomobule.com/'.$game.'?user=' . $user->id . '&token=' . $hash . '&lang=EN&currency=BRL&operator=betfyre&return_url=https:%2F%2Frosh.bet%2F';
        }
        
        return view('pages.aviator', compact('game_url'));
    }

    public function bgaming(Request $request) {
        
        
        $headers = array();
        foreach($_SERVER as $key => $value) {
            if (substr($key, 0, 5) <> 'HTTP_') {
                continue;
            }
            $header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
            $headers[$header] = $value;
        }

        
        if(!$request) return;
        $user = User::find($request->user_id);
        if(!$user) return ['msg' => 'User not found!', 'type' => 'error'];

        $wallet = floatval($user->balance) * 100;

        $auth_token = 'js6Dv6eTd6uvYbLr3QNKdK5e';

        $sign = hash_hmac('sha256', json_encode($request->all(), true), $auth_token, true);
        $sign = bin2hex($sign);

        
        $error = false;

        $actions = [];
        //return $headers;
        //if($headers['X-Request-Sign'] != $sign) return response()->json(["code" => 403,"message" => "Request sign doesn't match"], 403);
        
        if($request->actions) {
            foreach($request->actions as $action) {
                if($action['action'] == 'bet') {
                    
                    if($wallet - $action['amount'] < 0) $error = true;
                    if(!$error) {
                        if(!Slots::checkReplicated($action['action_id'])) { 
                            $wallet = $wallet - $action['amount'];
                            $user->requery += $action['amount']/100;
                        }

                        if(!Slots::checkReplicated($action['action_id'])) Slots::insert([
                            'user' => $user->id,
                            'game' => $request->game,
                            'game_id' => $request->game_id,
                            'action' => 'bet',
                            'action_id' => $action['action_id'],
                            'charge' => '-'.$action['amount']
                        ]);
                        $last_id = Slots::lastId();
                        array_push($actions, [
                            "action_id" => $action['action_id'],
                            "tx_id" => $last_id
                        ]);
                    }
                } else if($action['action'] == 'win') {
                    if(!$error && !Slots::checkReplicated($action['action_id'])) {
                        $wallet += $action['amount'];
                        $user->requery += $action['amount']/100;
                    }
    
                    if(!$error) {
                        if(!Slots::checkReplicated($action['action_id'])) Slots::insert([
                            'user' => $user->id,
                            'game' => $request->game,
                            'game_id' => $request->game_id,
                            'action' => 'win',
                            'action_id' => $action['action_id'],
                            'charge' => '+'.$action['amount']
                        ]);
                        $last_id = Slots::lastId();
                        array_push($actions, [
                            "action_id" => $action['action_id'],
                            "tx_id" => $last_id
                        ]);
                    }
                } else if($action['action'] == 'rollback') {
                    $action_rollback = Slots::checkReplicated($action['original_action_id']);
                
                    if(str_contains($action_rollback['charge'], '-')) {
                        $value = intval(str_replace('-', '', $action_rollback->charge));
                        $wallet += $value;
                        $user->requery += $value/100;

                        Slots::insert([
                            'user' => $user->id,
                            'game' => $request->game,
                            'game_id' => $request->game_id,
                            'action' => 'rollback',
                            'action_id' => $action['action_id'],
                            'charge' => '+'.$value
                        ]);
                        $last_id = Slots::lastId();
                        array_push($actions, [
                            "action_id" => $action['action_id'],
                            "tx_id" => $last_id
                        ]);
                    } else {
                        $value = intval(str_replace('+', '', $action_rollback->charge));
                        $wallet -= $value;
                        $user->requery += $value/100;

                        Slots::insert([
                            'user' => $user->id,
                            'game' => $request->game,
                            'game_id' => $request->game_id,
                            'action' => 'rollback',
                            'action_id' => $action['action_id'],
                            'charge' => '-'.$value
                        ]);
                        $last_id = Slots::lastId();
                        array_push($actions, [
                            "action_id" => $action['action_id'],
                            "tx_id" => $last_id
                        ]);
                    }
                }
            }
        }

        if($error) return response()->json(["code" => 100,"message" => "Not enough funds","balance" => floatval($user->balance) * 100], 412);

        $user->balance = $wallet/100;
        $user->save();

        if($request->actions) {
            return response()->json([
                "balance" => floatval($user->balance) * 100,
                "game_id" => $request->game_id,
                "transactions" => $actions
            ]);
        } else {
            return response()->json([
                "balance" => floatval($user->balance) * 100
            ]);
        }

    }


    public function newGame($game, $demo, $client) {
        if(!$this->user) return response()->json(['success' => false, 'msg' => 'Precisa estar logado.', 'type' => 'error']);

        $cassino_id = 'rosh';
        if($demo) $cassino_id = 'demo';
        if($client != 'desktop' && $client != 'mobile') $client = "desktop";
        
        $postData = [
            "casino_id" => $cassino_id,
            "game" => $game,
            "locale" => "br",
            "currency" => "BRL",
            "client_type"=> $client,
            "balance" => $this->user['balance'],
            "urls"=> [
                "deposit_url"=> "https://rosh.bet/deposit",
                "return_url"=> "https://rosh.bet"
            ],
            "user" =>  [
                "id" => $this->user['id'],
                "email" => $this->user['email'],
                "firstname" => $this->user['real_name'],
                "lastname" => "Doe",
                "nickname" => $this->user['username'],
                "city" => "",
                "country" => "BR",
                "date_of_birth" => "1980-12-26",
                "gender" => "m",
                "registered_at" => "2018-10-11"
            ]
        ];
        $ch = curl_init("https://bgaming-network.com/a8r/betfyre/sessions");
        
        $auth_token = 'js6Dv6eTd6uvYbLr3QNKdK5e';

        $sign = hash_hmac('sha256', json_encode($postData, true), $auth_token, true);
        $sign = bin2hex($sign);
 
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'X-Request-Sign: ' . strval($sign),
            'Content-Type: application/json'
        ));
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        
        //return $postData;
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData, true));
        
        $details = curl_exec($ch);
        $details = json_decode($details, true);
        curl_close($ch);
        
        //return response()->json($details);
        return redirect($details['launch_options']['game_url']);
        
    }
}
