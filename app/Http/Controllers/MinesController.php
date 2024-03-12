<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;


class MinesController extends Controller
{
    public function index()
    {
        return view('pages.mines');
    }

    function getCoeff($count, $steps, $level) {
        $coeff = 1;
        for ($i = 0; $i < ($level - $count) && $steps > $i; $i++) {
            $coeff *= (($level - $i) / ($level - $count - $i));
        }
        return $coeff;
    }

    public function play(Request $r){
        $balType = $r->balance;

        $bank_game = \Cache::get('minesGame.bank') ?? 50;
        $profit_game = \Cache::get('minesGame.profit') ?? 0;

        $bet = $r->bet;
        $bomb = $r->mines;
        $level = 25;

        if (Auth::guest()){
            return response()->json([
                'message' => 'Conecte-se'
            ], 401);
        }


        // $user = \Auth::user();

        if (\Cache::has('action.user.' . $this->user->id)){
            return response()->json([
                'message' => 'Aguarde a ação anterior'
            ], 401);
        }

        if($bet < 0.01 or !is_numeric($bet)){
            return response()->json([
                'message' => 'Insira um valor antes de iniciar sua aposta.'
            ], 401);
        }


        if(round($bomb) != $bomb or ($bomb < 2 or $bomb > $level - 1) or !is_numeric($bomb)){
            return response()->json([
                'message' => 'Digite o número correto de bombas'
            ], 401);
        }





        $games_on = \Cache::get('minesGame.user.'. $this->user->id.'start') ?? 0;

        // $games_on = 0;

        if($games_on > 0){
            return response()->json([
                'message' => 'Você tem jogos inacabados'
            ], 401);


        }


        $userBalance = $this->user[$balType];

        if($userBalance < $bet){
            return response()->json([
                'message' => 'Saldo insuficiente'
            ], 401);
        }




        $resultmines = range(1,$level);
        shuffle($resultmines);
        $resultmines = array_slice($resultmines,0,$bomb);
        $resultmines = json_encode($resultmines);

        $sum_bet = $bet;


        $this->user[$balType] -= $bet;
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

        \Cache::put('minesGame.user.'. $this->user->id.'start', 1);
        $cache_gameMine = array(
            'user_id'  => $this->user->id,
            'bet' => $sum_bet,
            'num_mines' => $bomb,
            'onOff' => 1,
            'step' => 0,
            'win' => 0,
            'level' => $level,
            'mines' => $resultmines,
            'click' => '[]'
        );

        \Cache::put('minesGame.user.'. $this->user->id.'game', json_encode($cache_gameMine));


        if($this->user->is_youtuber != 1){
            \Cache::put('minesGame.bank', $bank_game + (round($bet, 2) * 0.8));
            \Cache::put('minesGame.profit', $profit_game + (round($bet, 2) * 0.2));
        }
        return response()->json([
            'type' => 'start'
        ], 200);

    }


    public function get(){

        if (Auth::guest()){
            return response()->json([
                'message' => 'Conecte-se'
            ], 401);
        }

        $games_on = \Cache::get('minesGame.user.'.$this->user->id.'start') ?? 0;

        if($games_on == 0){
            return response()->json([
                'message' => 'Sem jogos'
            ], 401);
        }



        $cache_gameMine = \Cache::get('minesGame.user.'. $this->user->id.'game');
        $cache_gameMine = json_decode($cache_gameMine);

        $game = [];
        $game['click'] = $cache_gameMine->click;
        $game['win'] = $cache_gameMine->win;
        $game['bet'] = $cache_gameMine->bet;
        $game['mines'] = $cache_gameMine->num_mines;
        $game['step'] = $cache_gameMine->step;
        $game['level'] = $cache_gameMine->level;


        return response()->json([
            'game' => $game,
        ], 200);

    }

    public function finish(Request $r){

        $bank_game = \Cache::get('minesGame.bank') ?? 50;
        $profit_game = \Cache::get('minesGame.profit') ?? 0;



        if (\Cache::has('action.user.' . $this->user->id)){
            return response()->json([
                'message' => 'Aguarde a ação anterior'
            ], 401);

        }
        \Cache::put('action.user.' . $this->user->id, '', 1);



        $games_on = \Cache::get('minesGame.user.'. $this->user->id.'start') ?? 0;


        if($games_on == 0){

            return response()->json([
                'message' => 'Erro'
            ], 401);
        }



        $cache_gameMine = \Cache::get('minesGame.user.'. $this->user->id.'game');
        $cache_gameMine = json_decode($cache_gameMine);

        $step = $cache_gameMine->step;
        if($step < 1){
            return response()->json([
                'message' => 'Você deve clicar em pelo menos um campo'
            ], 401);

        }


        $win = $cache_gameMine->win;
        $bet = $cache_gameMine->bet;



        $game = [];
        $game['click'] = $cache_gameMine->click;
        $game['win'] = $cache_gameMine->win;
        $game['bet'] = $cache_gameMine->bet;
        $game['num_mines'] = $cache_gameMine->num_mines;
        $game['step'] = $cache_gameMine->step;
        $game['mines'] = $cache_gameMine->mines;


        \Cache::put('minesGame.user.'. $this->user->id.'game', '');
        \Cache::put('minesGame.user.'. $this->user->id.'start', 0);


        $balType = $r->balance;

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

        if($this->user->is_youtuber != 1){
            \Cache::put('minesGame.bank', $bank_game - round($win, 2));
        }



        return response()->json([
            'game' => $game
        ], 200);




    }

    public function click(Request $r){

        $bank_game = \Cache::get('minesGame.bank') ?? 50;
        $profit_game = \Cache::get('minesGame.profit') ?? 0;

        $mine = round($r->mine);


        if(\Cache::has('action.user.' . $this->user->id)){
            return response()->json([
                'message' => 'Aguarde um momento para clicar novamente'
            ], 401);
        }
        \Cache::put('action.user.' . $this->user->id, '', 2);


        $games_on = \Cache::get('minesGame.user.'. $this->user->id.'start') ?? 0;


        if($games_on == 0){
            return response()->json([
                'message' => 'Erro'
            ], 401);
        }

        $cache_gameMine = \Cache::get('minesGame.user.'. $this->user->id.'game');
        $cache_gameMine = json_decode($cache_gameMine);


        $click = json_decode($cache_gameMine->click);
        $win = $cache_gameMine->win;
        $level = $cache_gameMine->level;

        if($mine < 1 or $mine > $level){
            return response()->json([
                'message' => 'Erro'
            ], 401);
        }

        $bet = $cache_gameMine->bet;
        $num_mines = $cache_gameMine->num_mines;
        $step = $cache_gameMine->step;
        $bombs = json_decode($cache_gameMine->mines);

        if(in_array($mine, $click)){
            return response()->json([
                'message' => 'Você já clicou neste campo'
            ], 401);
        }



        if(in_array($mine, $bombs)){
            // LOSE


            \Cache::put('minesGame.user.'. $this->user->id.'game', '');
            \Cache::put('minesGame.user.'. $this->user->id.'start', 0);

            $game = [];
            $game['click'] = $cache_gameMine->click;
            $game['win'] = $cache_gameMine->win;
            $game['bet'] = $cache_gameMine->bet;
            $game['num_mines'] = $cache_gameMine->num_mines;
            $game['step'] = $cache_gameMine->step;
            $game['mines'] = $cache_gameMine->mines;




            response()->json([
                'message' => 'PERDEUUU'
            ], 401);
            return response()->json([
                'type' => 2,
                'game' => $game,
            ], 200);


        }else{
            // NEXT

            $click[] = $mine;
            $nexCoeff = self::getCoeff($num_mines, $step + 1, $level);


            $cache_gameMine->win = ($cache_gameMine->bet * $nexCoeff);
            $cache_gameMine->click = json_encode($click);
            $cache_gameMine->step = $cache_gameMine->step + 1;
            \Cache::put('minesGame.user.'. $this->user->id.'game', json_encode($cache_gameMine));

            $win_money = ($bet * self::getCoeff($num_mines, $step + 2, $level));

            if($this->user->is_youtuber == 1){
                $bank_game = $bet * 10000;
            }

            if($win_money > $bank_game){
                \Cache::put('minesGame.user.'. $this->user->id.'game', '');
                \Cache::put('minesGame.user.'. $this->user->id.'start', 0);

                $bombs[0] = $mine;

                $game = [];
                $game['click'] = $cache_gameMine->click;
                $game['win'] = $cache_gameMine->win;
                $game['bet'] = $cache_gameMine->bet;
                $game['num_mines'] = $cache_gameMine->num_mines;
                $game['step'] = $cache_gameMine->step;
                $game['mines'] = json_encode($bombs);




                return response()->json([
                    'type' => 2,
                    'game' => $game
                ], 200);
            }


            $game = [];
            $game['click'] = $cache_gameMine->click;
            $game['win'] = $cache_gameMine->win;
            $game['bet'] = $cache_gameMine->bet;
            $game['num_mines'] = $cache_gameMine->num_mines;
            $game['step'] = $cache_gameMine->step;

            $gameOff = 0;
            if($level - $num_mines - $step - 1 == 0){
                $gameOff = 1;
            }

            return response()->json([
                'type' => 1,
                'game' => $game,
                'gameOff' => $gameOff
            ], 200);


        }

    }
}
