<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Tower;

/**
	|Tower Game| By @ArtourBabaev31
*/

class TowerController extends Controller
{
	const CHANCES = [
		'1' => 70,
		'2' => 40,
		'3' => 20,
		'4' => 10
	];

	const COEFF = [
	    '1' => [1.25, 1.562, 1.953, 2.441, 3.051, 3.814, 4.768, 5.96, 7.45, 9.313],
	    '2' => [1.666, 2.777, 4.629, 7.716, 12.86, 21.433, 35.722, 59.537, 99.229, 165.381],
	    '3' => [2.5, 6.25, 15.625, 39.062, 97.656, 244.14, 610.351, 1525.878, 3814.697, 9536.743],
	    '4' => [5, 25, 125, 625, 3125, 15625, 78125, 390625, 1953125, 9765625]
	];

    public function index()
    {
    	return view('pages.tower');
    }

    public function init()
    {
		if (Auth::guest()) return ['game' => null];

		$game = Tower::where('user_id', $this->user->id)->where('status', 0)->first();
		if (!$game) return ['game' => null];

		return ['game' => $this->getGame($game->id)];
    }

    public function newGame(Request $r)
    {
    	if (Auth::guest()) return ['success' => false, 'message' => 'Conecte-se'];

		$bombs = preg_replace('/[^0-9.]/', '', $r->bombs);
        $bet = preg_replace('/[^0-9.]/', '', round($r->bet, 2));
        $balType = $r->balance;

		if($bet < $this->settings->tower_min_bet) return response()->json(['success' => false, 'message' => 'Quantidade mínima de aposta é de R$ '.$this->settings->tower_min_bet.'!']);
		if($bet > $this->settings->tower_max_bet) return response()->json(['success' => false, 'message' => 'Valor Máximo da aposta é de R$ '.$this->settings->tower_max_bet.'!']);
		if ($balType !== 'balance' && $balType !== 'bonus') return ['success' => false, 'message' => 'Falha ao determinar o tipo do seu saldo!'];
		if ($bombs < 1 || $bombs > 4) return ['success' => false, 'message' => 'Falha ao identificar Numero de Minas!'];
		if (!$bet) return ['success' => false, 'message' => 'Você não inseriu o valor da aposta!'];
		if (!$bombs) return ['success' => false, 'message' => 'Você não especificou o Numero de Minas'];
		if ($this->user[$balType] < $bet) return ['success' => false, 'message' => 'Saldo insuficiente para aposta'];
		$lastGame = Tower::where('user_id', $this->user->id)->where('status', 0)->first();
		if ($lastGame) return ['success' => false, 'message' => 'Termine o último jogo'];

		$this->user[$balType] -= $bet;
		$this->user->save();

		if ($balType === 'balance') {
			$this->user->requery += round(floatval($bet)/100*$this->settings->requery_bet_perc, 3);
			$this->user->save();
			$this->redis->publish('updateBalance', json_encode([
				'unique_id' => $this->user->unique_id,
				'balance' 	=> round($this->user->balance, 2)
			]));
		}

		if ($balType === 'bonus') {
			$this->redis->publish('updateBonus', json_encode([
				'unique_id' => $this->user->unique_id,
				'bonus' 	=> round($this->user->bonus, 2)
			]));
		}

		$game = Tower::create([
			'user_id' => $this->user->id,
			'bet' => $bet,
			'bombs' => $bombs,
			'currency' => $balType,
			'field' => json_encode([]),
			'revealed' => json_encode([])
		]);

		return [
			'success' => true,
			'game' => $this->getGame($game->id)
		];
    }

    public function next(Request $r)
    {
    	if (Auth::guest()) return ['success' => false, 'message' => 'Conecte-se'];

		$game = Tower::where('user_id', $this->user->id)->where('status', 0)->first();
		if (!$game) return ['success' => false, 'message' => 'Ocorreu um erro, atualize a página'];

		$slot = intval($r->slot);
		if ($slot < 0 || $slot > 4) return ['success' => false, 'message' => 'Ocorreu um erro, atualize a página'];

		$revealed = json_decode($game->revealed);
		$field = json_decode($game->field);

		$rand = mt_rand(0, 100);

		if ($rand < self::CHANCES[$game->bombs]) {
			$slots = [0, 1, 2, 3, 4];
			unset($slots[$slot]);
			$revealed[] = $slot;
			$fiels = [];
			for ($i = 0; $i < $game->bombs; $i++) {
				$rand = array_rand($slots, 1);
				unset($slots[$rand]);
				$fiels[] = $rand;
			}
			$field[] = $fiels;
			$game->update([
				'revealed' => json_encode($revealed),
				'field' => json_encode($field)
			]);
		} else {
			for ($i = count($revealed); $i <= 10; $i++) {
				$slots = [0, 1, 2, 3, 4];
				if ($i === count($revealed)) {
					unset($slots[$slot]);
					$fiels = [$slot];
					for ($l = 0; $l < $game->bombs - 1; $l++) {
						$rand = array_rand($slots, 1);
						unset($slots[$rand]);
						$fiels[] = $rand;
					}
				} else {
					$fiels = [];
					for ($l = 0; $l < $game->bombs; $l++) {
						$rand = array_rand($slots, 1);
						unset($slots[$rand]);
						$fiels[] = $rand;
					}
				}
				$field[] = $fiels;
			}
			$game->update([
				'field' => json_encode($field),
				'status' => 2
			]);
		}

		if (count($revealed) === 10) {
			$coeff = round(self::COEFF[$game->bombs][count($revealed) - 1] - self::COEFF[$game->bombs][count($revealed) - 1] * 5 / 100, 2);
			$winSum = round($game->bet * $coeff, 2);

			$game->update([
				'status' => 1,
				'coeff' => $coeff,
				'field' => json_encode($field)
			]);

			$balType = $game->currency;

			$this->user[$balType] += $winSum;
			$this->user->save();

			if ($balType === 'balance') {
				$this->user->requery += round(floatval($game->bet)/100*$this->settings->requery_bet_perc, 3);
				$this->user->save();
				$this->redis->publish('updateBalance', json_encode([
					'unique_id' => $this->user->unique_id,
					'balance' 	=> round($this->user->balance, 2)
				]));
			}

			if ($balType === 'bonus') {
				$this->redis->publish('updateBonus', json_encode([
					'unique_id' => $this->user->unique_id,
					'bonus' 	=> round($this->user->bonus, 2)
				]));
			}
		}

		return [
			'success' => true,
			'game' => $this->getGame($game->id)
		];
    }

    public function claim()
    {
    	if (Auth::guest()) return ['success' => false, 'message' => 'Conecte-se'];

		$game = Tower::where('user_id', $this->user->id)->where('status', 0)->first();
		if (!$game) return ['success' => false, 'message' => 'Ocorreu um erro, atualize a página'];

		$revealed = json_decode($game->revealed);
		if (count($revealed) === 0) return ['success' => false, 'message' => 'Abra 1 célula'];!

		$field = json_decode($game->field);
		$coeff = round(self::COEFF[$game->bombs][count($revealed) - 1] - self::COEFF[$game->bombs][count($revealed) - 1] * 5 / 100, 2);
		$winSum = round($game->bet * $coeff, 2);

		for ($i = count($revealed) + 1; $i <= 10; $i++) {
			$slots = [0, 1, 2, 3, 4];
			$fiels = [];
			for ($l = 0; $l < $game->bombs; $l++) {
				$rand = array_rand($slots, 1);
				unset($slots[$rand]);
				$fiels[] = $rand;
			}
			$field[] = $fiels;
		}

		$game->update([
			'status' => 1,
			'coeff' => $coeff,
			'field' => json_encode($field)
		]);

		$balType = $game->currency;

		$this->user[$balType] += $winSum;
		$this->user->save();

		if ($balType === 'balance') {
			$this->user->requery += round(floatval($game->bet)/100*$this->settings->requery_bet_perc, 3);
			$this->user->save();
			$this->redis->publish('updateBalance', json_encode([
				'unique_id' => $this->user->unique_id,
				'balance' 	=> round($this->user->balance, 2)
			]));
		}

		if ($balType === 'bonus') {
			$this->redis->publish('updateBonus', json_encode([
				'unique_id' => $this->user->unique_id,
				'bonus' 	=> round($this->user->bonus, 2)
			]));
		}

		return [
			'success' => true,
			'game' => $this->getGame($game->id)
		];
    }

    public function getGame($id)
    {
    	$game = Tower::find($id);
    	$revealed = json_decode($game->revealed);
    	$active = 1;
    	$coeff = 0;

    	if (count($revealed) === 0) {
    		$claim = $game->bet;
    	} else {
    		$claim = round($game->bet * round(self::COEFF[$game->bombs][count($revealed) - 1] - self::COEFF[$game->bombs][count($revealed) - 1] * 5 / 100, 2), 2);
    	}

    	if ($game->status > 0) {
    		$active = 0;
    		$claim = 0;
    	}

    	if ($game->status === 1) {
    		$coeff = round(self::COEFF[$game->bombs][count($revealed) - 1] - self::COEFF[$game->bombs][count($revealed) - 1] * 5 / 100, 2);
    	}

    	return [
    		'active' => $active,
    		'coeff' => $coeff,
    		'status' => $game->status,
    		'currency' => $game->currency,
    		'bet' => $game->bet,
    		'id' => $id,
    		'state' => [
    			'claim' => $claim,
    			'count' => $game->bombs,
    			'field' => json_decode($game->field),
    			'revealed' => $revealed
    		]
    	];
    }
}
