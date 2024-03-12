<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class KenoController extends Controller
{
    public function index()
    {
    //     \Cache::put('kenoGame.bank', 50);
    // \Cache::put('minesGame.bank', 50);

    // \Cache::put('kenoGame.profit', 0);
    // \Cache::put('minesGame.profit', 0);

        return view('pages.keno');
    }

    public function play(Request $r)
    {
        $bank_game = \Cache::get('kenoGame.bank') ?? 50;
        $profit_game = \Cache::get('kenoGame.profit') ?? 0;

        if (Auth::guest()){
            return response()->json([
                'message' => 'Conecte-se'
            ], 401);
        }


        $bet = preg_replace('/[^0-9.]/', '', round($r->bet, 2));
        $balType = $r->balance;
        $selectKeno = json_decode($r->selectKeno);

        if(count($selectKeno) < 1 or count($selectKeno) > 10){
            return response()->json([
                'message' => 'Número de células de 1 a 10'
            ], 401);
        }

        $coeffsKeno = [
                        [],
                        [0, 3.42],
                        [0, 1.78, 4.92],
                        [0, 1.25, 2.91, 8.25],
                        [0, 1.85, 4.5, 12.15, 20],
                        [0, 1.35, 3.9, 9.5, 16.65, 37],
                        [0, 0.5, 2.75, 6.9, 14.25, 25, 49.3],
                        [0.5, 0, 0.85, 1.9, 5.3, 13, 38.4, 60],
                        [0.75, 0.25, 0, 1.25, 3.48, 11, 25.25, 48.75, 74],
                        [1, 0, 0.25, 0.8, 2.15, 6.37, 13.75, 25, 49.5, 85],
                        [1.5, 0.25, 0, 0.5, 1.5, 3.32, 10.25, 18.73, 39.45, 74, 100]
                      ];

        $coeffs = $coeffsKeno[count($selectKeno)];

        if($bet < 0.01 or !is_numeric($bet)){
            return response()->json([
                'message' => 'Insira um valor antes de iniciar sua aposta.'
            ], 401);
        }

        $userBalance = $this->user[$balType];

        if($userBalance < $bet){
            return response()->json([
                'message' => 'Saldo Insuficiente'
            ], 401);
        }

        $this->user[$balType] -= $bet;
        $this->user->save();

        if($this->user->is_youtuber != 1){
            \Cache::put('kenoGame.bank', $bank_game + (round($bet, 2) * 0.8));
            \Cache::put('kenoGame.profit', $profit_game + (round($bet, 2) * 0.2));
        }



        $gems = range(1,36);
        shuffle($gems);
        $gems = array_slice($gems,0,10);

        $lose_gems = [];
        $win_gems = [];

        // foreach ($selectKeno as $s) {
        //     if (in_array($s, $gems)){
        //         $win_gems[] = $s;
        //     }
        // }

        foreach ($gems as $g){
            if (in_array($g, $selectKeno)){
                $win_gems[] = $g;
            }else{
                $lose_gems[] = $g;
            }
        }



        $coeff = $coeffs[count($win_gems)];

        $win = $bet * $coeff;

        $bank_game = \Cache::get('kenoGame.bank') ?? 50;
        $profit_game = \Cache::get('kenoGame.profit') ?? 0;


        if($bank_game < $win && $this->user->is_youtuber != 1){
            $f = true;
            $no_take_gems = [];
            foreach ($lose_gems as $l) {
                $no_take_gems[] = $l;
            }

            foreach ($win_gems as $l) {
                $no_take_gems[] = $l;
            }

            $st = 0;

            while ($f) {
                if($bank_game > $win){
                    $f = false;
                }else{

                    array_shift($win_gems);

                    $allow_gems = array_diff(range(1, 36), $no_take_gems);
                    shuffle($allow_gems);

                    $gg = $allow_gems[0];
                    $no_take_gems[] = $gg;

                    if (in_array($gg, $selectKeno)){
                        $win_gems[] = $gg;
                    }else{
                        $lose_gems[] = $gg;
                    }


                    // echo $win_gems;

                    $coeff = $coeffs[count($win_gems)];

                    $win = $bet * $coeff;
                }
            }
        }




        if($this->user->is_youtuber != 1){
            \Cache::put('kenoGame.bank', $bank_game - round($win, 2));
        }


        $this->user[$balType] += $win;
        $this->user->save();

        if ($balType === 'balance') {
            $this->redis->publish('updateBalance', json_encode([
                'unique_id' => $this->user->unique_id,
                'balance'   => round($this->user->balance, 2)
            ]));
        }

        if ($balType === 'bonus') {
            $this->redis->publish('updateBonus', json_encode([
                'unique_id' => $this->user->unique_id,
                'bonus'     => round($this->user->bonus, 2)
            ]));
        }



        // $lose_gems = json_encode($lose_gems);
        // $win_gems = json_encode($win_gems);

        return response()->json([
            'type' => 'start',
            'win_gems' => $win_gems,
            'lose_gems' => $lose_gems,
            'coeff' => $coeff,
            'win' => round($win, 2)
        ], 200);
    }
}


// \Cache::put('kenoGame.bank', 50);
//     \Cache::put('minesGame.bank', 50);

//     \Cache::put('kenoGame.profit', 0);
//     \Cache::put('minesGame.profit', 0);

