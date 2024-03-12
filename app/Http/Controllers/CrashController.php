<?php namespace App\Http\Controllers;

use App\Payments;
use App\User;
use App\Profit;
use App\Crash;
use App\CrashBets;
use App\SystemLevel;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use DB;

class CrashController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->game = Crash::orderBy('id', 'desc')->first();
        DB::connection()->getPdo()->exec('SET TRANSACTION ISOLATION LEVEL READ COMMITTED');
    }

	public function whiteListBlock(Request $r) {
		$blockIps = [env('WHITELIST_IP')];
		if (in_array($r->ip(), $blockIps)) {
            return 'continue';
        } else {
			return 'blocked';
		}
	}

    public function index()
    {
        if (is_null($this->game)) $this->game = Crash::create([
            'hash' => $this->getSecret()
        ]);
        $game = [
            'id' => $this->game->id,
            'hash' => $this->game->hash,
            'price' => CrashBets::where('round_id', $this->game->id)->sum('price'),
            'bets' => $this->getBets()
        ];
        $bet = ($this->user) ? CrashBets::where('user_id', $this->user->id)->where('round_id', $this->game->id)->where('status', 0)->first() : null;
        $history = $this->getHistory();

        return view('pages.crash', compact('game', 'bet', 'history'));
    }

    private function getBets()
    {
        $list = CrashBets::where('round_id', $this->game->id)->orderBy('id', 'desc')->get();
        $bets = [];
        foreach ($list as $bet) {
            $user = User::where('id', $bet->user_id)->first();
            if (!is_null($user)) $bets[] = [
                'user' => [
                    'username' => $user->username,
                    'avatar' => $user->avatar,
                    'unique_id' => $user->unique_id
                ],
                'price' => $bet->price,
                'withdraw' => round($bet->withdraw, 2),
                'color' => $this->getNumberColor($bet->withdraw),
                'won' => round($bet->won, 2),
                'balType' => $bet->balType,
                'status' => $bet->status
            ];
        }

        return $bets;
    }

    private function getNumberColor($n)
    {
        //if ($n > 6.49) return '#037cf3';
        //if ($n > 4.49) return '#1337d4';
        if ($n > 2.99) return '#7118d4';
        if ($n > 1.99) return '#a8128f';
        return '#7d8691';
    }

    public function newBet(Request $r)
    {
        //   if(!$this->user->is_admin) return response()->json(['msg' => 'Erro técnico, contato o suporte.', 'type' => 'error']);
        if ($this->game->status >= 1) return response()->json(['msg' => 'Esta rodada está encerrada!', 'type' => 'error']);

		if(\Cache::has('action.user.' . $this->user->id)) return response()->json(['msg' => 'Aguarde a ação anterior!', 'type' => 'error']);
        \Cache::put('action.user.' . $this->user->id, '', 2);

        if ($this->user->ban) return;
        if ($this->game->status > 0) return [
            'success' => false,
            'msg' => 'As apostas para esse round estão encerradas!'
        ];

        $balType = $r->get('balType');

        $countbets = CrashBets::where('round_id', $this->game->id)->where('user_id', $this->user->id)->count();
		if($countbets > 1) return response()->json(['msg' => 'Só pode apostar uma vez por rodada.', 'type' => 'error']);

        if ($balType != 'balance' && $balType != 'bonus') return [
            'success' => false,
            'msg' => 'Falha ao determinar o tipo do seu saldo!'
        ];

        if (floatval($r->get('bet')) < $this->settings->crash_min_bet) return [
            'success' => false,
            'msg' => 'Quantidade mínima de aposta é de R$ ' . $this->settings->crash_min_bet
        ];

        if ($this->settings->crash_max_bet > 0 && $this->settings->crash_max_bet < floatval($r->get('bet'))) return [
            'success' => false,
            'msg' => 'Valor Máximo da aposta é de R$ ' . $this->settings->crash_max_bet
        ];

        if (floatval($r->get('withdraw')) > 10000 && floatval($r->get('withdraw')) < 0) return [
            'success' => false,
            'msg' => 'Você digitou o valor errado para retirada automática!'
        ];

        if ($this->user[$balType] < floatval($r->get('bet'))) return [
            'success' => false,
            'msg' => 'Não há saldo suficiente!'
        ];

        DB::beginTransaction();

        try {
            $bet = DB::table('crash_bets')
                ->where('user_id', $this->user->id)
                ->where('round_id', $this->game->id)
                ->first();

            if (!is_null($bet)) {
                DB::rollback();
                return [
                    'success' => false,
                    'msg' => 'Você já fez uma aposta nesta rodada!'
                ];
            }

            // Skull:: Fixed the requery when BET.
            if ($balType == 'balance') {
                DB::table('users')->where('id', $this->user->id)->update([
                    'balance' => $this->user->balance - floatval($r->get('bet')),
                    'requery' => $this->user->requery + floatval($r->get('bet'))
                ]);

                if ($this->user['ref_id'] != NULL) {
					// Search account of this Ref
					$userRef = User::where('unique_id', $this->user['ref_id'])->first();
			
					$userRef->ref_money_all += floatval($r->get('bet')) * ($this->user->revenue_share_percent / 100); // 10 BRL
					$userRef->ref_money += floatval($r->get('bet')) * ($this->user->revenue_share_percent / 100); // 10 BRL
					$userRef->save();
	
					Profit::create([
						'game' => 'ref',
						'sum' => -(floatval($r->get('bet')) * ($this->user->revenue_share_percent / 100))
					]);
					
				}
            }

            if ($balType == 'bonus') {
                DB::table('users')->where('id', $this->user->id)->update([
                    'bonus' => $this->user->bonus - floatval($r->get('bet'))
                ]);
            }

            DB::table('crash_bets')->insert([
                'user_id' => $this->user->id,
                'round_id' => $this->game->id,
                'price' => floatval($r->get('bet')),
                'withdraw' => floatval($r->get('withdraw')),
                'balType' => $balType
            ]);

            DB::commit();

            // success commit
            $this->redis->publish('crash', json_encode([
                'type' => 'bet',
                'bets' => $this->getBets(),
                'price' => CrashBets::where('round_id', $this->game->id)->sum('price')
            ]));


            $this->user = User::find($this->user->id);
            if ($balType == 'balance') {
                $this->redis->publish('updateBalance', json_encode([
                    'unique_id' => $this->user->unique_id,
                    'balance' => round($this->user->balance, 2)
                ]));
            }
            if ($balType == 'bonus') {
                $this->redis->publish('updateBonus', json_encode([
                    'unique_id' => $this->user->unique_id,
                    'bonus' => round($this->user->bonus, 2)
                ]));
            }

            // Give xp
            //(new SystemLevel())->setLevelScoreForUser($this->user, floatval($r->get('bet')));

            return [
                'success' => true,
                'msg' => 'O valor da aposta foi inserido no jogo.',
                'bet' => floatval($r->get('bet'))
            ];


        } catch (Exception $e) {
            DB::rollback();
            return [
                'success' => false,
                'msg' => 'Algo deu errado...'
            ];
        }
    }

    public function roundToTheNearestAnything($value, $roundTo)
    {
        $mod = $value % $roundTo;
        return $value + ($mod < ($roundTo / 2) ? -$mod : $roundTo - $mod);
    }

    public function random_float($min, $max, $includeMax)
    {
        return $min + \mt_rand(0, (\mt_getrandmax() - ($includeMax ? 0 : 1))) / \mt_getrandmax() * ($max - $min);
    }

    /**
     * @OA\Post(
     *     tags={"/crash"},
     *     path="/api/crash/getUser",
     *     summary="Lista de BOT's no sistema.",
     *     description="Obter lista aleatoriamente de BOT's..",
     *     @OA\Response(
     *          response="200",
     *          description="Success!"
     *      )
     * )
     */
    private function getUser()
    {
        $user = User::where('fake', 1)->inRandomOrder()->first();

        /*if ($user->time != 0) {
            $now = Carbon::now()->format('H');
            if ($now < 06) $time = 4;
            if ($now >= 06 && $now < 12) $time = 1;
            if ($now >= 12 && $now < 18) $time = 2;
            if ($now >= 18) $time = 3;
            $user = User::where(['fake' => 1, 'time' => $time])->inRandomOrder()->first();
        }*/

        return $user;
    }

    /**
     * @OA\Post(
     *     tags={"/crash"},
     *     path="/api/crash/addBetFake",
     *     summary="Fazer apostas atráves de BOT's",
     *     description="Fazer com que algum BOT aleatoriamente faça uma aposta. Partida necessita estar em Espera e ter algum BOT disponível.",
     *     @OA\Response(
     *          response="200",
     *          description="Success!",
     *          content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     example={
     *                         "success": true,
     *                         "fake": 1,
     *                         "msg": "[Crash] O valor da aposta foi inserido no jogo."
     *                     }
     *                 )
     *             )
     *         }
     *      )
     * )
     */
    public function addBetFake(Request $r)
    {

        if($this->whiteListBlock($r) == 'blocked') return 'Ip bloqueado, tente novamente mais tarde :)';
        $min = 1.5;
        $max = 1099;
        $divisor = pow(10, 2); // Cents
        $randomFloat = mt_rand($min, $max * 2) / $divisor; // Bet
        $randomFloatWithdraw = mt_rand(2, 21); // Withdraw multiplier (IMPORTANT! Change it in fakeCashout() also ! ! !)

        $user = $this->getUser();
        $o = [5, 10, 15];
        $ar_o = array_rand($o, 2);
        $sum = $randomFloat;
        $withdraw = $randomFloatWithdraw;
        $countBet = CrashBets::where(['user_id' => $user->id, 'round_id' => $this->game->id])->count();

        if ($this->game->status > 0) return response()->json([
                'success' => false,
                'fake' => $this->settings->fakebets,
                'msg' => '[Crash] As apostas para esse round estão encerradas!'
            ], 200);

        if ($countBet == 5) return response()->json([
            'success' => false,
            'fake' => $this->settings->fakebets,
            'msg' => '[Crash] Este usuário já está apostando!'
        ], 200);

        if ($this->settings->crash_max_bet > 0 && $this->settings->crash_max_bet < floatval($sum)) return response()->json([
            'success' => false,
            'fake' => $this->settings->fakebets,
            'msg' => '[Crash] Valor Máximo da aposta é de R$ ' . $this->settings->crash_max_bet
        ], 200);

        DB::beginTransaction();

        try {
            $bet = DB::table('crash_bets')
                ->where('user_id', $user->id)
                ->where('round_id', $this->game->id)
                ->first();

            if (!is_null($bet)) {
                DB::rollback();

                return response()->json([
                    'success' => false,
                    'fake' => $this->settings->fakebets,
                    'msg' => '[Crash] Você já fez uma aposta nesta rodada!'
                ], 200);
            }

            DB::table('crash_bets')->insert([
                'user_id' => $user->id,
                'round_id' => $this->game->id,
                'price' => floatval($sum),
                'withdraw' => floatval($withdraw),
                'fake' => 1,
                'balType' => 'balance'
            ]);

            DB::commit();

            // success commit
            $this->redis->publish('crash', json_encode([
                'type' => 'bet',
                'bets' => $this->getBets(),
                'price' => CrashBets::where('round_id', $this->game->id)->sum('price')
            ]));

            return response()->json([
                'success' => true,
                'fake' => $this->settings->fakebets,
                'msg' => '[Crash] O valor da aposta foi inserido no jogo'
            ], 201);

        } catch (Exception $e) {
            DB::rollback();
            return [
                'success' => false,
                'fake' => $this->settings->fakebets,
                'msg' => '[Crash] Algo deu errado...'
            ];
        }
    }

    public function startSlider(Request $r)
    {
        if($this->whiteListBlock($r) == 'blocked') return 'Ip bloqueado, tente novamente mais tarde :)';
        if ($this->game->status == 1)
            return ['multiplier' => $this->game->multiplier, 'status' => $this->game->status];

        $this->game->status = 1;
        $this->game->save();

        $this->game->multiplier = $this->getFloat();
        $this->game->save();

        return ['multiplier' => $this->game->multiplier, 'status' => $this->game->status];
    }

    public function getFloat()
    {

        $profit_crash = Profit::where('game', 'crash')->where('created_at', '>=', Carbon::today())->sum('sum');
        $lastZero = Crash::where('multiplier', 1)->orderBy('id', 'desc')->first();
        $betsPrice = CrashBets::where('round_id', $this->game->id)->where('fake', 0)->sum('price');
        //if ($this->settings->profit_money <= 0 && $betsPrice > 0) return round('1.' . mt_rand(0, 3) . mt_rand(1, 9), 2);
        if (!is_null($lastZero) && ($lastZero->id >= ($this->game->id + mt_rand(3, 10)))) return round(mt_rand(1, 9).'.' . mt_rand(1, 9) . mt_rand(1, 9), 2);; // CRASHAR
        //if (is_null($lastZero) || ($this->game->id - $lastZero->id) >= mt_rand(1, 2)) return 1; // CRASHAR 1.0

        $list = [];
        for ($i = 0; $i < 60; $i++) $list[] = 1;
        for ($i = 0; $i < 15; $i++) $list[] = 2;
        for ($i = 0; $i < 10; $i++) $list[] = 3;
        for ($i = 0; $i < 9; $i++) $list[] = 4;
        for ($i = 0; $i < 3; $i++) $list[] = 5;
        for ($i = 0; $i < 2; $i++) $list[] = 10;
        for ($i = 0; $i < 1; $i++) $list[] = 300;
        shuffle($list);

        if ($this->game->multiplier) return $this->game->multiplier;
        $m = $list[mt_rand(0, count($list) - 1)];
        if ($m > 1) $m = mt_rand(1, $m);
        if ($m == 1) return $list[0] . '.' . mt_rand(0, 5) . mt_rand(0, 9);
        $num = round($m . '.' . mt_rand(0, 9) . mt_rand(1, 9), 2);
        return $num;
    }

    private function isTrue($chance)
    {
        $list = [];
        for ($i = 0; $i < $chance; $i++) $list[] = true;
        for ($i = 0; $i < (100 - $chance); $i++) $list[] = false;
        shuffle($list);
        return $list[mt_rand(0, count($list) - 1)];
    }

    public function Cashout()
    {
        if ($this->game->status == 0) return [
            'success' => false,
            'msg' => 'Aguarde a partida começar!'
        ];

        if ($this->game->status == 2) return [
            'success' => false,
            'msg' => 'Esta rodada está encerrada!'
        ];

        $bet = CrashBets::where('user_id', $this->user->id)->where('round_id', $this->game->id)->first();
        if (is_null($bet)) return [
            'success' => false,
            'msg' => 'Você não apostou nesta rodada!'
        ];

        if ($bet->status == 1) return [
            'success' => false,
            'msg' => 'Você já retirou sua aposta!'
        ];

        DB::beginTransaction();

        try {
            $cashout = floatval($this->redis->get('cashout'));
            if ($cashout == 0) {
                DB::rollback();
                return [
                    'success' => false,
                    'msg' => 'Você não pode retirar agora, pois a partida ainda nao começou ou já se encontra finalizada.'
                ];
            }

            $float = floatval($this->redis->get('float'));
            if ($bet->withdraw > 0 && $bet->withdraw < $float && $bet->withdraw < $this->game->multiplier) $float = $bet->withdraw;
            if ($float <= 0 && $bet->withdraw < $float && $bet->withdraw < $this->game->multiplier) $float = $bet->withdraw;
            if ($float <= 0) {
                DB::rollback();
                return [
                    'success' => false,
                    'msg' => 'Algo deu errado!'
                ];
            }

            if ($bet->balType == 'balance') {
                DB::table('users')
                    ->where('id', $this->user->id)
                    ->update([
                        'balance' => $this->user->balance + round($bet->price * $float, 2)
                    ]);

                // Skull:: Deleted duplicated
                //$this->user->requery += round(($bet->price / 100 * $this->settings->requery_bet_perc) + (($bet->price * $float) - $bet->price) / 100 * $this->settings->requery_perc, 3);
                //$this->user->save();

                $this->settings->profit_money -= round(($bet->price * $float) - $bet->price, 2);
                $this->settings->save();

                Profit::create([
                    'game' => 'crash',
                    'sum' => -round(($bet->price * $float) - $bet->price, 2)
                ]);

				if ($this->user['ref_id'] != NULL) {
					// Search account of this Ref
					$userRef = User::where('unique_id', $this->user['ref_id'])->first();
			
					$userRef->ref_money_all -= round($bet->price * $float, 2) * ($this->user->revenue_share_percent / 100); // 10 BRL
					$userRef->ref_money -= round($bet->price * $float, 2) * ($this->user->revenue_share_percent / 100); // 10 BRL
					$userRef->save();
	
					Profit::create([
						'game' => 'ref',
						'sum' => -(round($bet->price * $float, 2) * ($this->user->revenue_share_percent / 100))
					]);
					
				}
            }

            if ($bet->balType == 'bonus') {
                DB::table('users')
                    ->where('id', $this->user->id)
                    ->update([
                        'bonus' => $this->user->bonus + round($bet->price * $float, 2)
                    ]);
            }

            DB::table('crash_bets')
                ->where('id', $bet->id)
                ->update([
                    'withdraw' => $float,
                    'won' => round($bet->price * $float, 2),
                    'status' => 1
                ]);

            DB::commit();

            $this->redis->publish('crash', json_encode([
                'type' => 'bet',
                'bets' => $this->getBets(),
                'price' => CrashBets::where('round_id', $this->game->id)->sum('price')
            ]));

            $this->user = User::find($this->user->id);
            if ($bet->balType == 'balance') {
                $this->redis->publish('updateBalance', json_encode([
                    'unique_id' => $this->user->unique_id,
                    'balance' => round($this->user->balance, 2)
                ]));
            }
            if ($bet->balType == 'bonus') {
                $this->redis->publish('updateBonus', json_encode([
                    'unique_id' => $this->user->unique_id,
                    'bonus' => round($this->user->bonus, 2)
                ]));
            }

            return [
                'success' => true,
                'msg' => 'Você multiplicou o valor em ' . $float . 'x e Recebeu R$ ' . round($bet->price * $float, 2) . ' !'
            ];

        } catch (Exception $e) {
            DB::rollback();
            return [
                'success' => false,
                'msg' => 'Algo deu errado...'
            ];
        }
    }

    /**
     * @OA\Post(
     *     tags={"/crash"},
     *     path="/api/crash/fakeCashout",
     *     summary="Simular retirada de BOT.",
     *     description="Fazer com que algum BOT aleatoriamente faça retirada da aposta. Partida necessita estar em Execução e ter algum BOT disponível.",
     *     @OA\Response(
     *          response="200",
     *          description="Success!"
     *      )
     * )
     */
    public function fakeCashout(Request $r)
    {
        if($this->whiteListBlock($r) == 'blocked') return 'Ip bloqueado, tente novamente mais tarde :)';

        if ($this->game->status == 0) return [
            'success' => false,
            'msg' => 'Aguarde a partida começar!'
        ];

        if ($this->game->status == 2) return [
            'success' => false,
            'msg' => 'Esta rodada está encerrada!'
        ];

        $bets = CrashBets::where([
            'fake' => 1,
            'status' => 0,
            'round_id' => $this->game->id
        ])->inRandomOrder()->get();

        foreach ($bets as $bet) {

            if (is_null($bet)) return [
                'success' => false,
                'msg' => 'Sem apostas nesta rodada!'
            ];

            if ($bet->status == 1) return [
                'success' => false,
                'msg' => 'Todos os BOTs já efetuaram as retiradas!'
            ];

            $floatNow = floatval(100);

            $requestWithdraw = floatval($this->redis->get('float'));

            if ($bet->withdraw > 0 && $bet->withdraw < $requestWithdraw && $bet->withdraw < $this->game->multiplier) $requestWithdraw = $bet->withdraw;
            if ($requestWithdraw <= 0 && $bet->withdraw < $requestWithdraw && $bet->withdraw < $this->game->multiplier) $requestWithdraw = $bet->withdraw;

            // Check if round is Running (0-Waiting, 1-Running, 2-Crashed)
            if ($this->game->status == 1) {

                // Check the BOT withdraw value is less of current game.
                if ($floatNow >= $bet->withdraw) {

                    DB::beginTransaction();
                    try {
                        $cashout = floatval($this->redis->get('cashout'));

                        if ($cashout == 0) {
                            DB::rollback();
                            return [
                                'success' => false,
                                'msg' => 'A partida ainda nao começou ou já finalizou.'
                            ];
                        }

                        DB::table('crash_bets')
                            ->where('id', $bet->id)
                            ->update([
                                'withdraw' => $requestWithdraw,
                                'won' => round($bet->price * $requestWithdraw, 2),
                                'status' => 1
                            ]);

                        DB::commit();

                        $this->redis->publish('crash', json_encode([
                            'type' => 'bet',
                            'bets' => $this->getBets(),
                            'price' => CrashBets::where('round_id', $this->game->id)->sum('price')
                        ]));

                        return [
                            'success' => true,
                            'fake' => 1,
                            'msg' => 'BOT multiplicou o valor em ' . $requestWithdraw . 'x e Recebeu R$ ' . round($bet->price * $requestWithdraw, 2) . ' !'
                        ];

                    } catch (Exception $e) {
                        DB::rollback();
                        return [
                            'success' => false,
                            'msg' => 'Algo deu errado...'
                        ];
                    }
                }
            }

        } // Two foreach
    }

    public function newGame(Request $r)
    {
        if($this->whiteListBlock($r) == 'blocked') return 'Ip bloqueado, tente novamente mais tarde :)';

        $this->game->status = 2;
        $this->game->save();

	    //CrashBets::where('round_id', $this->game->id)->update(array('float' => $this->game->multiplier));

        $bets = CrashBets::where('round_id', $this->game->id)
            ->where('withdraw', '>', 0)
            ->where('status', 0)
            ->get();

        DB::beginTransaction();
        try {
            foreach ($bets as $bet) {
                $user = DB::table('users')->where('fake', 0)->where('id', $bet->user_id)->first();
                if (!is_null($user) && $bet->withdraw < $this->game->multiplier) {
                    $user = User::where('fake', 0)->where('id', $bet->user_id)->first();
                    $user[$bet->balType] += round($bet->price * $bet->withdraw, 2);

                    if ($bet->balType == 'balance') {
                        $user->requery += round(($bet->price / 100 * $this->settings->requery_bet_perc) + (($bet->price * $bet->withdraw) - $bet->price) / 100 * $this->settings->requery_perc, 3);
                        $user->save();
                    }

                    if ($bet->balType == 'balance' && $user->ref_id) {
                        $ref = User::where('unique_id', $user->ref_id)->first();
                        if ($ref) {
                            $ref_sum = round((($bet->price * $bet->withdraw) - $bet->price) / 100 * $this->settings->ref_perc, 2);
                            $ref->ref_money += $ref_sum;
                            $ref->ref_money_all += $ref_sum;
                            $ref->save();

                            Profit::create([
                                'game' => 'ref',
                                'sum' => -$ref_sum
                            ]);
                        }
                    }
                }
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
        }

        $bets = CrashBets::where('fake', 0)->where('round_id', $this->game->id)->get();
        $total = 0;
        foreach ($bets as $bet) if ($bet->status == 1 && $bet->balType == 'balance') $total -= $bet->won - $bet->price; else $total += $bet->price;

        if ($total < 0) {
            $this->settings->profit_money += $total;
            $this->settings->save();
        }

        if ($total != 0) {
            Profit::create([
                'game' => 'crash',
                'sum' => $total
            ]);
        }

        $this->game = Crash::create([
            'hash' => $this->getSecret()
        ]);

        $this->redis->publish('crash', json_encode([
            'type' => 'game',
            'id' => $this->game->id,
            'hash' => $this->game->hash,
            'history' => $this->getHistory()
        ]));

        return [
            'success' => true,
            'id' => $this->game->id
        ];
    }

    private function getHistory()
    {
        $list = Crash::select('multiplier', 'hash')->where('status', 2)->orderBy('id', 'desc')->limit(10)->get();
        for ($i = 0; $i < count($list); $i++) $list[$i]->color = $this->getColor($list[$i]->multiplier);
        return $list;
    }

    private function getColor($float)
    {
        //if ($float > 6.49) return '#eebef1';
        //if ($float > 4.49) return '#dcd0ff';
        if ($float > 2.49) return '#62CA5B';
        if ($float > 1.99) return '#62CA5B';
        return '#7d8691';
    }

    private function getSecret()
    {
        $str = bin2hex(random_bytes(16));
        $game = Crash::where('hash', $str)->first();
        if ($game) return $this->getSecret();
        return $str;
    }

    public function init()
    {
        return response()->json([
            'id' => $this->game->id,
            'status' => $this->game->status,
            'timer' => $this->settings->crash_timer
        ]);
    }

    public function getBank()
    {
        $crash = CrashBets::where('round_id', $this->game->id)->sum('price');
        return $crash ? $crash : 0;
    }

    public function gotThis(Request $r)
    {
        if($this->whiteListBlock($r) == 'blocked') return 'Ip bloqueado, tente novamente mais tarde :)';
        
        $multiplier = $r->get('multiplier');

        if ($this->game->status >= 2) return [
            'msg' => 'O jogo começou, você não pode apostar!',
            'type' => 'error'
        ];

        if (!$this->game->id) return [
            'msg' => 'Falha ao obter o número do jogo!',
            'type' => 'error'
        ];

        if (!$multiplier) return [
            'msg' => 'Falha ao obter o resultado!',
            'type' => 'error'
        ];

        Crash::where('id', $this->game->id)->update([
            'multiplier' => $multiplier
        ]);

        return [
            'msg' => 'Você multiplicou em ' . $multiplier . 'x!',
            'type' => 'success'
        ];
    }
}
