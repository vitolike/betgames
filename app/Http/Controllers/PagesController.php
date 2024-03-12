<?php namespace App\Http\Controllers;

use Auth;
use App\User;
use App\Jackpot;
use App\JackpotBets;
use App\Wheel;
use App\WheelBets;
use App\Crash;
use App\CrashBets;
use App\CoinFlip;
use App\Battle;
use App\BattleBets;
use App\Hilo;
use App\HiloBets;
use App\Sends;
use App\Dice;
use App\Bonus;
use App\BonusLog;
use App\Payments;
use App\Exchanges;
use App\Withdraw;
use App\Profit;
use App\Promocode;
use App\PromoLog;
use App\Giveaway;
use App\GiveawayUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use DB;
use App\PaymentsProviders;

use function Psy\debug;

class PagesController extends Controller
{
    private $paymentsProviders;
    private $Paggue_Token;

    public function __construct(PaymentsProviders $paymentsProviders)
    {
        parent::__construct();
        DB::connection()->getPdo()->exec('SET TRANSACTION ISOLATION LEVEL READ COMMITTED');

        $this->paymentsProviders = $paymentsProviders;
    }

    public function home()
    {
        return view('pages.home');
    }

    public function depositPaggue($transaction)
    {
        // Search this payment
        $payment = Payments::where('order_id', $transaction)->first();

        return view('pages.deposit2', compact('payment'));
    }

    public function depositPaggue2($transaction)
    {
        // Search this payment
        $payment = Payments::where('order_id', $transaction)->first();

        return view('pages.deposit2', compact('payment'));
    }


    public function faq()
    {
        return view('pages.faq');
    }

    public function profileHistory()
    {
        $pays = Payments::where(['user_id' => $this->user->id])->orderBy('created_at', 'desc')->get();
        $withdraws = Withdraw::where('user_id', $this->user->id)->orderBy('id', 'desc')->get();

        // [Skull] - Made to show the name of payment status.
        // Obs¹ This is for 'MercadoPago (PIX)' system.
        foreach ($pays as $key => $value) {
            $pays[$key]['status'] = $this->PaymentStatus_mp($pays[$key]['status']);
        }

        return view('pages.profileHistory', compact('pays', 'withdraws'));
    }

    public function free()
    {
        $rotate = 0;
        $bonuses = Bonus::get();
        foreach ($bonuses as $key => $b) {
            $bonuses[$key]['rotate'] = $rotate;
            $rotate += 360 / $bonuses->count();
        }
        $max = Bonus::where('type', 'group')->max('sum');
        $max_refs = Bonus::where('type', 'refs')->max('sum');

        $bonusLog = BonusLog::where(['user_id' => $this->user->id, 'type' => 'group'])->orderBy('id', 'desc')->first();
        $check = 0;
        if ($bonusLog) {
            if ($bonusLog->remaining) {
                $nowtime = time();
                $time = $bonusLog->remaining;
                $lasttime = $nowtime - $time;
                if ($time >= $nowtime) {
                    $check = 1;
                }
            }
            $bonusLog->status = 2;
            $bonusLog->save();
        }

        $activeRefs = 0;
        $refs = User::where(['ban' => 0, 'ref_id' => $this->user->unique_id])->get();
        foreach ($refs as $a) {
            $pay = Payments::where(['user_id' => $a->id, 'status' => 1])->sum('sum');
            if ($pay >= 100) $activeRefs += 1;
        }

        $refLog = BonusLog::where(['user_id' => $this->user->id, 'type' => 'refs', 'status' => 3])->count();

        return view('pages.free', compact('bonuses', 'max', 'max_refs', 'check', 'activeRefs', 'refLog'));
    }

    public function freeGetWheel(Request $r)
    {
        $type = $r->get('type');
        $bonuses = Bonus::select('bg', 'sum', 'color')->where('type', $type)->get();
        $list = [];
        foreach ($bonuses as $b) {
            $list[] = [
                'sum' => $b->sum,
                'bgColor' => $b->bg,
                'iconColor' => $b->color
            ];
        }
        $bonusLog = BonusLog::where('user_id', $this->user->id)->where('type', $type)->orderBy('id', 'desc')->first();
        $remaining = isset($bonusLog) ? $bonusLog->remaining : 0;
        $data = [
            'data' => $list,
            'remaining' => $remaining,
            'type' => $type
        ];
        return $data;
    }

    public function checkRepost(Request $r)
    {
        if ($this->user->bonus_repost == 1) {
            return response()->json(['success' => false, 'msg' => 'Você já recebeu um bônus', 'type' => 'error']);
        }

        $vk_ckeck = $this->groupIsMember($this->user->user_id);

        if ($vk_ckeck == 0) return response()->json(['success' => false, 'msg' => 'Você não está participando do nosso grupo!', 'type' => 'error']);

        $t = $this->repostIsDone($this->settings->repost_post_id, $this->user->user_id);
        if ($t) {
            $this->user->bonus_repost = 1;
            $this->user->balance += 50;
            $this->user->save();

            $this->redis->publish('updateBalance', json_encode([
                'unique_id' => $this->user->unique_id,
                'balance' => round($this->user->balance, 2),
            ]));

            return response()->json(['success' => false, 'msg' => 'Sucesso! Bônus creditados no saldo. Não exclua uma postagem por 10 dias', 'type' => 'success']);
        } else {
            return response()->json(['success' => false, 'msg' => 'você não repostou', 'type' => 'error']);
        }


    }

    private function repostIsDone($id, $user)
    {
        //ПОЛУЧАЕМ СПИСОК ТЕХ, КТО СДЕЛАЛ РЕПОСТ
        $url = "https://api.vk.com/method/wall.get?v=5.131&owner_id=" . $user . "&access_token=" . $this->settings->vk_service_key;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_NOBODY, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $result = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($result);
        $result = $result->response->items;

        $user_id = $id;

        $vk_url = $this->settings->vk_url;
        if (!$vk_url) $group = NULL;
        $old_url = ($vk_url);
        $url = explode('/', trim($old_url, '/'));
        $url_parse = array_pop($url);
        $url_last = preg_replace('/&?club+/i', '', $url_parse);

        foreach ($result as $r) {
            if (array_key_exists('copy_history', $r)) {

                $id_post = $r->copy_history[0]->id;
                $group_post = $r->copy_history[0]->owner_id;

                if ($id_post == $id and $group_post == '-' . $url_last) {
                    return true;

                }
            }
        }
        return false;
    }

    public function freeSpin(Request $r)
    {
        if (\Cache::has('action.user.' . $this->user->id)) return response()->json(['msg' => 'Aguarde a ação anterior!', 'type' => 'error']);
        // \Cache::put('action.user.' . $this->user->id, '', 2);
        $validator = \Validator::make($r->all(), [
            'recapcha' => 'required|captcha',
        ]);
        if ($validator->fails()) return response()->json(['success' => false, 'msg' => 'Você não passou no teste do reCaptcha!', 'type' => 'error']);
        $type = $r->get('type');
        if ($type == 'group') {
            $vk_ckeck = $this->groupIsMember($this->user->user_id);

            if ($vk_ckeck == 0) return response()->json(['success' => false, 'msg' => 'Você não está participando do nosso grupo!', 'type' => 'error']);
            if ($vk_ckeck == NULL) return response()->json(['success' => false, 'msg' => 'A emissão de bônus não está funcionando temporariamente!', 'type' => 'error']);

            $bonuses = Bonus::select('bg', 'sum', 'color', 'status')->where('type', $type)->get();

            $bonusLog = BonusLog::where('user_id', $this->user->id)->where('type', $type)->orderBy('id', 'desc')->first();
            if ($bonusLog) {
                if ($bonusLog->remaining) {
                    $nowtime = time();
                    $time = $bonusLog->remaining;
                    $lasttime = $nowtime - $time;
                    if ($time >= $nowtime) {
                        return [
                            'success' => false,
                            'msg' => 'O próximo bônus que você pode obter: ' . date("d.m.Y H:i:s", $time),
                            'type' => 'error'
                        ];
                    }
                }
                $bonusLog->status = 2;
                $bonusLog->save();
            }

            $start = (360 / $bonuses->count()) / 2;
            foreach ($bonuses as $key => $b) {
                $bonuses[$key]['start'] = $start;
                $start += 360 / $bonuses->count();
            }

            $list = [];
            foreach ($bonuses as $b) {
                if ($b->status == 1) $list[] = [
                    'sum' => $b->sum,
                    'start' => $b->start
                ];
            }
            $win = $list[array_rand($list)];

            $remaining = Carbon::now()->addMinutes($this->settings->bonus_group_time)->getTimestamp();

            BonusLog::create([
                'user_id' => $this->user->id,
                'sum' => $win['sum'],
                'remaining' => $remaining,
                'status' => 1,
                'type' => $type
            ]);

            $this->user->balance += $win['sum'];
            $this->user->save();

            $this->redis->publish('updateBalanceAfter', json_encode([
                'unique_id' => $this->user->unique_id,
                'balance' => round($this->user->balance, 2),
                'timer' => 5
            ]));
        }

        if ($type == 'refs') {
            $bonuses = Bonus::select('bg', 'sum', 'color', 'status')->where('type', $type)->get();

            $activeRefs = 0;
            $refs = User::where(['ban' => 0, 'ref_id' => $this->user->unique_id])->get();
            foreach ($refs as $a) {
                $pay = Payments::where(['user_id' => $a->id, 'status' => 1])->sum('sum');
                if ($pay >= 100) $activeRefs += 1;
            }

            if ($activeRefs < $this->settings->max_active_ref) return response()->json(['success' => false, 'msg' => 'Referências ativas insuficientes. ' . $activeRefs . '/' . $this->settings->max_active_ref . '!', 'type' => 'error']);

            $bonusLog = BonusLog::where('user_id', $this->user->id)->where('type', $type)->orderBy('id', 'desc')->first();
            if ($bonusLog) {
                if ($bonusLog->status == 3) return response()->json(['success' => false, 'msg' => 'Você já recebeu este bônus!', 'type' => 'error']);
            }

            $start = (360 / $bonuses->count()) / 2;
            foreach ($bonuses as $key => $b) {
                $bonuses[$key]['start'] = $start;
                $start += 360 / $bonuses->count();
            }

            $list = [];
            foreach ($bonuses as $b) {
                if ($b->status == 1) $list[] = [
                    'sum' => $b->sum,
                    'start' => $b->start
                ];
            }
            $win = $list[array_rand($list)];

            $remaining = 0;

            BonusLog::create([
                'user_id' => $this->user->id,
                'sum' => $win['sum'],
                'remaining' => $remaining,
                'status' => 3,
                'type' => $type
            ]);

            $this->user->balance += $win['sum'];
            $this->user->save();

            $this->redis->publish('updateBalanceAfter', json_encode([
                'unique_id' => $this->user->unique_id,
                'balance' => round($this->user->balance, 2),
                'timer' => 5
            ]));
        }

        $this->redis->publish('bonus', json_encode([
            'unique_id' => $this->user->unique_id,
            'rotate' => 1440 + $win['start']
        ]));

        return response()->json(['success' => true, 'msg' => 'Sucesso!', 'type' => 'success', 'remaining' => $remaining, 'bonusType' => $type]);
    }

    public function paySend()
    {
        return view('pages.paySend');
    }

    public function sendCreate(Request $r)
    {
        if (\Cache::has('bet.user.' . $this->user->id)) return response()->json(['msg' => 'Aguarde a ação anterior!', 'type' => 'error']);
        \Cache::put('bet.user.' . $this->user->id, '', 0.10);
        $target = $r->get('target');
        $sum = $r->get('sum');
        $value = floor($sum * 1.05);

        $with = Withdraw::where('user_id', $this->user->id)->where('status', 1)->sum('value');
        $user = User::where('user_id', $target)->first();

        if ($value < 1) {
            return [
                'success' => false,
                'msg' => 'Você digitou o valor abaixo do minimo!',
                'type' => 'error'
            ];
        }

        if (!$this->user->is_admin && !$this->user->is_youtuber) {
            if ($with < 250) return [
                'success' => false,
                'msg' => 'Você precisa ter um saque no valor de R$ 250!',
                'type' => 'error'
            ];
        }

        if (!$user) return [
            'success' => false,
            'msg' => 'Não há nenhum usuário com este ID!',
            'type' => 'error'
        ];

        if ($target == $this->user->user_id) return [
            'success' => false,
            'msg' => 'Você não pode enviar saldo para si mesmo!',
            'type' => 'error'
        ];

        if ($value > $this->user->balance) return [
            'success' => false,
            'msg' => 'Você não pode enviar mais do que o seu saldo!',
            'type' => 'error'
        ];

        if ($value < 20) return [
            'success' => false,
            'msg' => 'Quantidade mínima de transferência R$ 20!',
            'type' => 'error'
        ];

        if (!$value || !$target) return [
            'success' => false,
            'msg' => 'Você não digitou um dos valores!',
            'type' => 'error'
        ];

        $this->user->balance -= $value;
        $this->user->save();

        $user->balance += $sum;
        $user->save();

        Sends::create([
            'sender' => $this->user->id,
            'receiver' => $user->id,
            'sum' => $sum
        ]);

        $this->redis->publish('updateBalance', json_encode([
            'id' => $this->user->id,
            'balance' => $this->user->balance
        ]));

        $this->redis->publish('updateBalance', json_encode([
            'id' => $user->id,
            'balance' => $user->balance
        ]));

        return [
            'success' => true,
            'msg' => 'Você transferiu ' . $sum . ' <i class="fas fa-coins"></i> do utilizador ' . $user->username . '!',
            'type' => 'success'
        ];
    }

    public function payHistory()
    {
        $pays = SuccessPay::where('user', $this->user->user_id)->where('status', '>=', 1)->get();
        $withdraws = Withdraw::where('user_id', $this->user->id)->where('status', '>', 0)->get();
        $active = Withdraw::where('user_id', $this->user->id)->where('status', 0)->get();
        return view('pages.payHistory', compact('pays', 'withdraws', 'active'));
    }

    public function promoActivate(Request $r)
    {
        if (\Cache::has('action.user.' . $this->user->id)) return response()->json(['success' => false, 'msg' => 'Aguarde a ação anterior!', 'type' => 'error']);
        // \Cache::put('action.user.' . $this->user->id, '', 5);

        $code = strtolower(htmlspecialchars($r->get('code')));
        if (!$code) return response()->json(['success' => false, 'msg' => 'Você não digitou um código!', 'type' => 'error']);

        $promocode = Promocode::where('code', $code)->first();
        if (!$promocode) return response()->json(['success' => false, 'msg' => 'Código Inexistente.', 'type' => 'error']);

        $money = $promocode->amount;
        $check = PromoLog::where('user_id', $this->user->id)->where('code', $code)->first();

        if ($check) return response()->json(['success' => false, 'msg' => 'Você já ativou o código!', 'type' => 'error']);
        if ($promocode->limit == 1 && $promocode->count_use <= 0) return response()->json(['success' => false, 'msg' => 'O código não é esta válido!', 'type' => 'error']);
        if ($promocode->user_id == $this->user->id) return response()->json(['success' => false, 'msg' => 'Você não pode RECEBER BÔNUS pelo seu código promocional!', 'type' => 'error']);

        if ($promocode->type == 'balance') {
            $this->user->balance += $money;
            $this->user->save();

            Profit::create([
                'game' => 'ref',
                'sum' => -$money
            ]);

            $this->redis->publish('updateBalance', json_encode([
                'unique_id' => $this->user->unique_id,
                'balance' => round($this->user->balance, 2)
            ]));
        }

        if ($promocode->type == 'bonus') {
            $this->user->bonus += $money;
            $this->user->save();

            $this->redis->publish('updateBonus', json_encode([
                'unique_id' => $this->user->unique_id,
                'bonus' => round($this->user->bonus, 2)
            ]));
        }

        if ($promocode->limit == 1 && $promocode->count_use > 0) {
            $promocode->count_use -= 1;
            $promocode->save();
        }

        PromoLog::insert([
            'user_id' => $this->user->id,
            'sum' => $money,
            'code' => $code,
            'type' => $promocode->type
        ]);

        return response()->json(['success' => true, 'msg' => 'Bônus Recebido !!!', 'type' => 'success']);
    }

    public function affiliate()
    {
        return view('pages.affiliate');
    }

    public function affiliateGet()
    {
        if (\Cache::has('action.user.' . $this->user->id)) return response()->json(['msg' => 'Aguarde a ação anterior!', 'type' => 'error']);
        // \Cache::put('action.user.' . $this->user->id, '', 5);
        if ($this->user->ref_money < $this->settings->min_ref_withdraw)
            return response()->json(['success' => false, 'msg' => 'Quantidade mínima de saques é de R$ ' . $this->settings->min_ref_withdraw, 'type' => 'error']);

        DB::beginTransaction();

        try {
            $this->user->balance += $this->user->ref_money;
            $this->user->requery += $this->user->ref_money;
            $this->user->ref_money = 0;
            $this->user->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return ['msg' => 'Algo deu errado...', 'type' => 'error'];
        }

        $userBalance = $this->user->balance;
        $id_u = $this->user->unique_id;

        $this->redis->publish('updateBalance', json_encode([
            'unique_id' => $id_u,
            'balance' => round($userBalance, 2)
        ]));

        return response()->json(['success' => true, 'msg' => 'Saldo transferidas para sua conta!', 'type' => 'success']);
    }

    public function exchange(Request $r)
    {
        if (\Cache::has('action.user.' . $this->user->id)) return response()->json(['msg' => 'Aguarde a ação anterior!', 'type' => 'error']);
        // \Cache::put('action.user.' . $this->user->id, '', 5);
        $sum = floatval($r->get('sum'));

        if ($sum < $this->settings->exchange_min) return ['msg' => 'Quantidade mínima para troca R$' . $this->settings->exchange_min, 'type' => 'error'];
        if ($this->user->bonus < $sum) return ['msg' => 'Saldo de bônus é insuficiente!', 'type' => 'error'];

        DB::beginTransaction();
        try {

            $exchange = new Exchanges();
            $exchange->user_id = $this->user->id;
            $exchange->sum = $sum;
            $exchange->save();

            $curs = round($sum / $this->settings->exchange_curs, 2);
            $this->user->bonus -= $sum;
            $this->user->balance += $curs;
            $this->user->save();

            Profit::create([
                'game' => 'exchange',
                'sum' => $sum - $curs
            ]);

            $this->redis->publish('updateBalance', json_encode([
                'unique_id' => $this->user->unique_id,
                'balance' => round($this->user->balance, 2)
            ]));

            $this->redis->publish('updateBonus', json_encode([
                'unique_id' => $this->user->unique_id,
                'bonus' => round($this->user->bonus, 2)
            ]));

            DB::commit();
        } catch (\PDOException $e) {
            DB::rollback();
            return ['msg' => 'Algo deu errado...', 'type' => 'error'];
        }

        return ['msg' => 'Você trocou ' . $sum . ' bônus por R$ ' . $curs . '!', 'type' => 'success'];
    }

    public function joinGiveaway(Request $r)
    {
        if (\Cache::has('action.user.' . $this->user->id)) return response()->json(['msg' => 'Aguarde a ação anterior!', 'type' => 'error']);
        // \Cache::put('action.user.' . $this->user->id, '', 5);

        $giveaway = Giveaway::where('id', $r->get('id'))->first();

        if (is_null($giveaway)) return ['msg' => 'Não foi possível encontrar um Prêmio!', 'type' => 'error'];
        if ($giveaway->status > 0) return ['msg' => 'Os Prêmio já acabaram!', 'type' => 'error'];

        $check = GiveawayUsers::where(['giveaway_id' => $giveaway->id, 'user_id' => $this->user->id])->count();
        if ($check > 0) return ['msg' => 'Você já está participando deste sorteio!', 'type' => 'error'];

        if ($giveaway->group_sub != 0) {
            $vk_ckeck = $this->groupIsMember($this->user->user_id);

            if ($vk_ckeck == 0) return response()->json(['msg' => 'Você não está participando do nosso grupo!', 'type' => 'error']);
            if ($vk_ckeck == NULL) return response()->json(['msg' => 'A emissão de bônus não está funcionando temporariamente!', 'type' => 'error']);
        }

        if ($giveaway->min_dep != 0) {
            $dep = Payments::where('user_id', $this->user->id)->where('updated_at', '>=', Carbon::today())->where('status', 1)->sum('sum');

            if ($dep < $giveaway->min_dep) {
                return response()->json(['msg' => 'Para participar da distribuição, você precisa ter um depósito em sua conta no valor R$ ' . $giveaway->min_dep . '!', 'type' => 'error']);
            }
        }

        DB::beginTransaction();
        try {

            $gv_user = new GiveawayUsers();
            $gv_user->giveaway_id = $giveaway->id;
            $gv_user->user_id = $this->user->id;
            $gv_user->save();

            $users = GiveawayUsers::where('giveaway_id', $giveaway->id)->count();

            $array = [
                'type' => 'newUser',
                'id' => $giveaway->id,
                'count' => $users
            ];

            $this->redis->publish('giveaway', json_encode($array));

            DB::commit();
        } catch (\PDOException $e) {
            DB::rollback();
            return ['msg' => 'Algo deu errado...', 'type' => 'error'];
        }

        return ['msg' => 'Você entrou em um sorteio #' . $giveaway->id . '!', 'type' => 'success'];
    }

    public function getGiveaway()
    {
        $giveaway = Giveaway::orderBy('id', 'desc')->where('status', 0)->get();
        return $giveaway;
    }

    public function endGiveaway(Request $r)
    {
        $id = $r->get('id');
        $gv = Giveaway::where('id', $id)->first();
        if (is_null($gv)) return ['msg' => 'Falha ao encontrar o prêmio com este ID!', 'type' => 'false'];
        if ($gv->status > 0) return ['msg' => 'Este prêmio já acabou!', 'type' => 'false'];
        $count = GiveawayUsers::where('giveaway_id', $gv->id)->count();

        $winner = null;
        if ($count >= 1) {

            if (!is_null($gv->winner_id)) {
                $winner_id = $gv->winner_id;
            } else {
                $gvu = GiveawayUsers::where('giveaway_id', $gv->id)->inRandomOrder()->first();
                $winner_id = $gvu->user_id;
            }

            $winner = User::getUser($winner_id);
            $w = User::where('id', $winner_id)->first();
            $gv->winner_id = $w->id;
            $gv->save();

            if (!$w->fake) {
                $w[$gv->type] += $gv->sum;
                $w->save();
                if ($gv->type == 'balance') {
                    Profit::create([
                        'game' => 'ref',
                        'sum' => -$gv->sum
                    ]);
                    $this->redis->publish('updateBalance', json_encode([
                        'unique_id' => $w->unique_id,
                        'balance' => round($w->balance, 2)
                    ]));
                }
                if ($gv->type == 'bonus') {
                    $this->redis->publish('updateBonus', json_encode([
                        'unique_id' => $w->unique_id,
                        'bonus' => round($w->bonus, 2)
                    ]));
                }
            }
        }

        $gv->status = 1;
        $gv->save();

        $array = [
            'type' => 'winner',
            'id' => $gv->id,
            'winner' => $winner
        ];

        $this->redis->publish('giveaway', json_encode($array));

        return ['msg' => 'Vencedor selecionado #' . $gv->id . '!', 'type' => 'success'];
    }

    public function fairCheck(Request $r)
    {
        $hash = $r->get('hash');
        if (!$hash) return [
            'success' => false,
            'type' => 'error',
            'msg' => 'O campo não pode estar vazio!'
        ];
        $jackpot = Jackpot::where(['hash' => $hash, 'status' => 3])->first();
        $wheel = Wheel::where(['hash' => $hash, 'status' => 3])->first();
        $crash = Crash::where(['hash' => $hash, 'status' => 2])->first();
        $coin = CoinFlip::where(['hash' => $hash, 'status' => 1])->first();
        $battle = Battle::where(['hash' => $hash, 'status' => 3])->first();
        $hilo = Hilo::where(['hash' => $hash, 'status' => 4])->first();
        $dice = Dice::where('hash', $hash)->first();

        if (!is_null($jackpot)) {
            $info = [
                'id' => $jackpot->game_id,
                'number' => $jackpot->winner_ticket
            ];
        } elseif (!is_null($wheel)) {
            $info = [
                'id' => $wheel->id,
                'number' => ($wheel->winner_color == 'black' ? 2 : ($wheel->winner_color == 'red' ? 3 : ($wheel->winner_color == 'green' ? 5 : 50)))
            ];
        } elseif (!is_null($crash)) {
            $info = [
                'id' => $crash->id,
                'number' => $crash->multiplier
            ];
        } elseif (!is_null($coin)) {
            $info = [
                'id' => $coin->id,
                'number' => $coin->winner_ticket
            ];
        } elseif (!is_null($battle)) {
            $info = [
                'id' => $battle->id,
                'number' => $battle->winner_ticket
            ];
        } elseif (!is_null($hilo)) {
            $info = [
                'id' => $hilo->id,
                'number' => ($hilo->card_section == 'joker' ? 'joker' : $hilo->card_name)
            ];
        } elseif (!is_null($dice)) {
            $info = [
                'id' => $dice->id,
                'number' => $dice->num
            ];
        } else {
            return [
                'success' => false,
                'type' => 'error',
                'msg' => 'Partida não encontrada'
            ];
        }

        return [
            'success' => true,
            'type' => 'success',
            'msg' => 'Partida encontrada',
            'round' => $info['id'],
            'number' => $info['number']
        ];
    }

    public function unbanMe()
    {
        if (\Cache::has('action.user.' . $this->user->id)) return response()->json(['msg' => 'Aguarde a ação anterior!', 'type' => 'error']);
        // \Cache::put('action.user.' . $this->user->id, '', 2);
        if (!$this->user->banchat) return [
            'success' => false,
            'type' => 'error',
            'msg' => 'Você não está banido do Chat!'
        ];
        if ($this->user->balance < 50) return [
            'success' => false,
            'type' => 'error',
            'msg' => 'Você não tem Saldos insuficiente para desbloquear!'
        ];

        $this->user->balance -= 50;
        $this->user->banchat = null;
        $this->user->save();

        $returnValue = ['unique_id' => $this->user->unique_id, 'ban' => 0];
        $this->redis->publish('ban.msg', json_encode($returnValue));

        $this->redis->publish('updateBalance', json_encode([
            'unique_id' => $this->user->unique_id,
            'balance' => $this->user->balance
        ]));

        return [
            'success' => false,
            'type' => 'success',
            'msg' => 'Você está desbloqueado no Chat!'
        ];
    }

    public function getUser(Request $r)
    {
        if (\Cache::has('action.user.' . $this->user->id)) return response()->json(['msg' => 'Aguarde a ação anterior!', 'type' => 'error']);
        // \Cache::put('action.user.' . $this->user->id, '', 2);
        if (is_null($r->get('id'))) return response()->json(['success' => false, 'msg' => 'Falha ao encontrar o usuário!', 'type' => 'error']);
        $user = User::where('unique_id', $r->get('id'))->select('username', 'avatar', 'unique_id', 'id')->first();
        if (is_null($user)) return response()->json(['success' => false, 'msg' => 'Falha ao encontrar o usuário!', 'type' => 'error']);

        $jackpotSum = JackpotBets::join('jackpot', 'jackpot.id', '=', 'jackpot_bets.game_id')
            ->select('jackpot.status', 'jackpot_bets.sum')
            ->where('jackpot.status', 3)
            ->where(['jackpot_bets.user_id' => $user->id])
            ->sum('jackpot_bets.sum');
        $wheelSum = WheelBets::join('wheel', 'wheel.id', '=', 'wheel_bets.game_id')
            ->select('wheel.status', 'wheel_bets.price')
            ->where('wheel.status', 3)
            ->where(['wheel_bets.user_id' => $user->id])
            ->sum('wheel_bets.price');
        $crashSum = CrashBets::join('crash', 'crash.id', '=', 'crash_bets.round_id')
            ->select('crash.status', 'crash_bets.price')
            ->where('crash.status', 2)
            ->where(['crash_bets.user_id' => $user->id])
            ->sum('price');
        $coinSum = CoinFlip::where('heads', $user->id)->orWhere('tails', $user->id)->where('status', 1)->sum('bank') / 2;
        $battleSum = BattleBets::join('battle', 'battle.id', '=', 'battle_bets.game_id')
            ->select('battle.status', 'battle_bets.price')
            ->where('battle.status', 3)
            ->where(['battle_bets.user_id' => $user->id])
            ->sum('battle_bets.price');
        $diceSum = Dice::where('user_id', $user->id)->sum('sum');
        $hiloSum = HiloBets::join('hilo', 'hilo.id', '=', 'hilo_bets.game_id')
            ->select('hilo.status', 'hilo_bets.sum')
            ->where('hilo.status', 4)
            ->where(['hilo_bets.user_id' => $user->id])
            ->sum('hilo_bets.sum');
        $betAmount = $jackpotSum + $wheelSum + $crashSum + $coinSum + $battleSum + $diceSum + $hiloSum;

        $jackpotCount = JackpotBets::join('jackpot', 'jackpot.id', '=', 'jackpot_bets.game_id')
            ->select('jackpot.status', 'jackpot.id', 'jackpot_bets.game_id')
            ->where('jackpot.status', 3)
            ->where(['jackpot_bets.user_id' => $user->id])
            ->groupBy('jackpot_bets.game_id')
            ->get()->count();
        $wheelCount = WheelBets::join('wheel', 'wheel.id', '=', 'wheel_bets.game_id')
            ->select('wheel.status', 'wheel.id', 'wheel_bets.game_id')
            ->where('wheel.status', 3)
            ->where(['wheel_bets.user_id' => $user->id])
            ->groupBy('wheel_bets.game_id')
            ->get()->count();
        $crashCount = CrashBets::join('crash', 'crash.id', '=', 'crash_bets.round_id')
            ->select('crash.status', 'crash.id', 'crash_bets.round_id')
            ->where('crash.status', 2)
            ->where(['crash_bets.user_id' => $user->id])
            ->groupBy('crash_bets.round_id')
            ->get()->count();
        $coinCount1 = CoinFlip::where('heads', $user->id)->where('status', 1)->count();
        $coinCount2 = CoinFlip::where('tails', $user->id)->where('status', 1)->count();
        $coinCount = $coinCount1 + $coinCount2;
        $battleCount = BattleBets::join('battle', 'battle.id', '=', 'battle_bets.game_id')
            ->select('battle.status', 'battle.id', 'battle_bets.game_id')
            ->where('battle.status', 3)
            ->where(['battle_bets.user_id' => $user->id])
            ->groupBy('battle_bets.game_id')
            ->get()->count();
        $diceCount = Dice::where('user_id', $user->id)->count();
        $wheelCount = HiloBets::join('hilo', 'hilo.id', '=', 'hilo_bets.game_id')
            ->select('hilo.status', 'hilo.id', 'hilo_bets.game_id')
            ->where('hilo.status', 4)
            ->where(['hilo_bets.user_id' => $user->id])
            ->groupBy('hilo_bets.game_id')
            ->get()->count();
        $betCount = $jackpotCount + $wheelCount + $crashCount + $coinCount + $battleCount + $diceCount + $wheelCount;

        $jackpotWin = Jackpot::where(['winner_id' => $user->id])->where('status', 3)->count();
        $wheelWin = WheelBets::join('wheel', 'wheel.id', '=', 'wheel_bets.game_id')
            ->select('wheel.status', 'wheel.id', 'wheel_bets.game_id')
            ->where('wheel.status', 3)
            ->where(['wheel_bets.user_id' => $user->id, 'wheel_bets.win' => 1])
            ->groupBy('wheel_bets.game_id')
            ->get()->count();
        $crashWin = CrashBets::join('crash', 'crash.id', '=', 'crash_bets.round_id')
            ->select('crash.status', 'crash.id', 'crash_bets.round_id')
            ->where('crash.status', 2)
            ->where(['crash_bets.user_id' => $user->id, 'crash_bets.status' => 1])
            ->groupBy('crash_bets.round_id')
            ->get()->count();
        $coinWin = CoinFlip::where('winner_id', $user->id)->count();
        $battleWin = BattleBets::join('battle', 'battle.id', '=', 'battle_bets.game_id')
            ->select('battle.status', 'battle.id', 'battle_bets.game_id')
            ->where('battle.status', 3)
            ->where(['battle_bets.user_id' => $user->id, 'battle_bets.win' => 1])
            ->groupBy('battle_bets.game_id')
            ->get()->count();
        $diceWin = Dice::where(['user_id' => $user->id, 'win' => 1])->count();
        $hiloWin = HiloBets::join('hilo', 'hilo.id', '=', 'hilo_bets.game_id')
            ->select('hilo.status', 'hilo.id', 'hilo_bets.game_id')
            ->where('hilo.status', 4)
            ->where(['hilo_bets.user_id' => $user->id, 'hilo_bets.win' => 1])
            ->groupBy('hilo_bets.game_id')
            ->get()->count();
        $betWin = $jackpotWin + $wheelWin + $crashWin + $coinWin + $battleWin + $diceWin + $hiloWin;

        $jackpotLose = JackpotBets::join('jackpot', 'jackpot.id', '=', 'jackpot_bets.game_id')
            ->select('jackpot.status', 'jackpot.id', 'jackpot_bets.game_id', 'jackpot_bets.win')
            ->where('jackpot.status', 3)
            ->where(['user_id' => $user->id, 'win' => 0])
            ->groupBy('jackpot_bets.game_id', 'jackpot_bets.win')
            ->get()->count();
        $wheelLose = WheelBets::join('wheel', 'wheel.id', '=', 'wheel_bets.game_id')
            ->select('wheel.status', 'wheel.id', 'wheel_bets.game_id')
            ->where('wheel.status', 3)
            ->where(['wheel_bets.user_id' => $user->id, 'wheel_bets.win' => 0])
            ->groupBy('wheel_bets.game_id')
            ->get()->count();
        $crashLose = CrashBets::join('crash', 'crash.id', '=', 'crash_bets.round_id')
            ->select('crash.status', 'crash.id', 'crash_bets.round_id')
            ->where('crash.status', 2)
            ->where(['crash_bets.user_id' => $user->id, 'crash_bets.status' => 0])
            ->groupBy('crash_bets.round_id')
            ->get()->count();
        $coinLose1 = CoinFlip::where('winner_id', '!=', $user->id)->where('heads', $user->id)->where('status', 1)->count();
        $coinLose2 = CoinFlip::where('winner_id', '!=', $user->id)->where('tails', $user->id)->where('status', 1)->count();
        $coinLose = $coinLose1 + $coinLose2;
        $battleLose = BattleBets::join('battle', 'battle.id', '=', 'battle_bets.game_id')
            ->select('battle.status', 'battle.id', 'battle_bets.game_id')
            ->where('battle.status', 3)
            ->where(['battle_bets.user_id' => $user->id, 'battle_bets.win' => 0])
            ->groupBy('battle_bets.game_id')
            ->get()->count();
        $diceLose = Dice::where(['user_id' => $user->id, 'win' => 0])->count();
        $hiloLose = HiloBets::join('hilo', 'hilo.id', '=', 'hilo_bets.game_id')
            ->select('hilo.status', 'hilo.id', 'hilo_bets.game_id')
            ->where('hilo.status', 4)
            ->where(['hilo_bets.user_id' => $user->id, 'hilo_bets.win' => 0])
            ->groupBy('hilo_bets.game_id')
            ->get()->count();
        $betLose = $jackpotLose + $wheelLose + $crashLose + $coinLose + $battleLose + $diceLose + $hiloLose;

        $info = [
            'unique_id' => $user->unique_id,
            'avatar' => $user->avatar,
            'username' => $user->username,
            'betAmount' => round($betAmount, 2),
            'totalGames' => $betCount,
            'wins' => $betWin,
            'lose' => $betLose
        ];

        return response()->json(['success' => true, 'info' => $info]);
    }

    /**
     * Payment and Request method
     * Providers configuration is here
     *
     * @param Request $r
     * @return \Illuminate\Http\JsonResponse
     */
    public function pay(Request $r)
    {
        if (\Cache::has('action.user.' . $this->user->id)) return response()->json(['msg' => 'Aguarde a ação anterior!', 'type' => 'error']);
        // \Cache::put('action.user.' . $this->user->id, '', 5);
        if ($r->get('amount') < $this->settings->min_dep) return response()->json(['success' => false, 'msg' => 'Quantidade mínima para depósito é R$ ' . $this->settings->min_dep . '!', 'type' => 'error']);
        if (!$r->get('type')) return response()->json(['success' => false, 'msg' => 'Você deve escolher um método de pagamento!', 'type' => 'error']);

        // CPF Validator
        $getPostCPF = $r->get('cpf');
        $cpf = preg_replace("/[^0-9]/", "", $getPostCPF);
        $cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);

        $validaCPF = $this->validateCPF($cpf);

        if (!$validaCPF) return response()->json(['success' => false, 'msg' => 'CPF incorreto!', 'type' => 'error']);

        if ($this->user->cpf == NULL) {
            $this->user->cpf = $cpf;
            $this->user->save();
        }

        if ($r->get('type') == 'payeer') {
            if (is_null($this->settings->payeer_mrh_ID)) return response()->json(['success' => false, 'msg' => 'Este método de pagamento não está disponível!', 'type' => 'error']);
            $m_shop = $this->settings->payeer_mrh_ID;
            $m_system = $r->get('type');
            $m_orderid = time() . mt_rand() . $this->user->id;
            $m_amount = number_format($r->get('amount'), 2, '.', '');
            $m_curr = 'BRL';
            $m_desc = base64_encode('Depósito de saldo no site ' . $this->settings->sitename);
            $m_key = $this->settings->payeer_secret1;
            $arHash = [
                $m_shop,
                $m_orderid,
                $m_amount,
                $m_curr,
                $m_desc,
                $m_key
            ];
            $sign = strtoupper(hash('sha256', implode(':', $arHash)));

            if ($m_amount != 0) {
                DB::beginTransaction();
                try {
                    $payment = new Payments();
                    $payment->user_id = $this->user->id;
                    $payment->secret = $sign;
                    $payment->order_id = $m_orderid;
                    $payment->sum = $m_amount;
                    $payment->system = $m_system;
                    $payment->save();

                    DB::commit();
                } catch (\PDOException $e) {
                    DB::connection()->getPdo()->rollBack();
                    return response()->json(['success' => false, 'msg' => $e->getMessage(), 'type' => 'error']);
                }
            }

            return response()->json(['success' => true, 'url' => 'https://payeer.com/merchant/?m_shop=' . $m_shop . '&m_orderid=' . $m_orderid . '&m_amount=' . $m_amount . '&m_curr=' . $m_curr . '&m_desc=' . $m_desc . '&m_sign=' . $sign . '&lang=ru']);

        } elseif ($r->get('type') == 'MercadoPago') {
            // [Skull] - Adicionar esta correção para definir limite minimo de depósito para mais flexibilidade.
            if ($this->settings->mp_provider_status === 0) return response()->json(['success' => false, 'msg' => 'Método de pagamento desativado!', 'type' => 'error']);
            if ($r->get('amount') < $this->settings->min_dep) return response()->json(['success' => false, 'msg' => 'Quantidade mínima para depósito é R$ ' . $this->settings->min_dep . '!', 'type' => 'error']);

            $m_orderid = time() * $this->user->id; // Create order unique ID
            $m_amount = number_format($r->get('amount'), 2, '.', ''); // Convert value to 0.00 float
            $amount = rtrim(rtrim($m_amount, '0'), '.'); // Remove x.00 value to unique INTEGER
            $username = explode(" ", $this->user->real_name); // username

            $userEmail = $this->user->email;

            $providerCredentials = $this->paymentsProviders->getClientCredentials('MercadoPago');

            $url = "https://api.mercadopago.com/v1/payments";

            // Request new payment with this body
            // https://DOMAIN_URI_SERVER/api/payments/mercadopago/return

            $data = [
                "transaction_amount" => (int)$amount,
                "description" => "Taxa de serviço de adição de crédito",
                "payment_method_id" => "pix",
                "notification_url" => $providerCredentials['url_return_ipn'],
                "payer" => [
                    "email" => $userEmail,
                    "first_name" => $username[0],
                    "last_name" => $username[1],
                    "identification" => [
                        "type" => "CPF",
                        "number" => $this->user->cpf
                    ]
                ],
                "external_reference" => $m_orderid
            ];

            /*
              "address" => [
                        "zip_code" =>  $this->user->zip_code,
                        "street_name" =>  $this->user->street_name,
                        "street_number" =>  $this->user->street_number,
                        "neighborhood" =>  $this->user->neighborhood,
                        "city" =>  $this->user->city,
                        "federal_unit" =>  $this->user->federal_unit
                    ]
                ]
             */

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-type: application/json',
                'Authorization: Bearer ' . $providerCredentials['client_token']
            ]);
            $response = json_decode(curl_exec($ch), true);
            curl_close($ch);

            // Save to database | Table: payments
            if ($m_amount != 0) {
                DB::beginTransaction();
                try {
                    $payment = new Payments();
                    $payment->user_id = $this->user->id;
                    $payment->secret = $response['id'];
                    $payment->order_id = $m_orderid;
                    $payment->sum = $m_amount;
                    $payment->system = 'MercadoPago (PIX)';
                    $payment->save();

                    DB::commit();
                } catch (\PDOException $e) {
                    DB::connection()->getPdo()->rollBack();
                    return response()->json(['success' => false, 'msg' => $e->getMessage(), 'type' => 'error']);
                }
            }

            //print_r($response);
            return response()->json(['success' => true, 'url' => $response['point_of_interaction']['transaction_data']['ticket_url']]);
        } elseif ($r->get('type') == 'Paggue') {
            // [Skull] - Adicionar esta correção para definir limite minimo de depósito para mais flexibilidade.
            if ($this->settings->mp_provider_status === 0) return response()->json(['success' => false, 'msg' => 'Método de pagamento desativado!', 'type' => 'error']);
            if ($r->get('amount') < $this->settings->min_dep) return response()->json(['success' => false, 'msg' => 'Quantidade mínima para depósito é R$ ' . $this->settings->min_dep . '!', 'type' => 'error']);

            $m_orderid = 'PAGGUE_' . time() * $this->user->id; // Create order unique ID
            $m_amount = number_format($r->get('amount'), 2, '.', ''); // Convert value to 0.00 float
            $amount = rtrim(rtrim($m_amount, '0'), '.'); // Remove x.00 value to unique INTEGER
            $username = explode(" ", $this->user->real_name); // username

            // Verifica se o token está valido
            $getDateNow = Date("Y-m-d H:m:i");
            if (!isset($this->Paggue_Token) || $this->expires_at < $getDateNow) {
                $this->paggue_getBearerToken();
            }

            $url = "https://ms.paggue.io/payments/api/billing_order";

            $providerCredentials = $this->paymentsProviders->getClientCredentials('Paggue');

            $data = [
                "payer_name" => $this->user->real_name,
                "amount" => (int)$amount * 100,
                "description" => "Taxa de serviço de adição de crédito",
                "external_id" => $m_orderid
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'X-Company-ID: 8699',
                'Authorization: Bearer ' . $providerCredentials['bearer_token']
            ]);
            $response = json_decode(curl_exec($ch), true);
            curl_close($ch);

            // Save to database | Table: payments
            if ($m_amount != 0) {
                DB::beginTransaction();
                try {
                    $payment = new Payments();
                    $payment->user_id = $this->user->id;
                    $payment->secret = $response['hash'];
                    $payment->order_id = $m_orderid;
                    $payment->sum = $m_amount;
                    $payment->system = 'Paggue (PIX)';
                    $payment->extra_info = $response['payment'];
                    $payment->save();

                    DB::commit();
                } catch (\PDOException $e) {
                    DB::connection()->getPdo()->rollBack();
                    return response()->json(['success' => false, 'msg' => $e->getMessage(), 'type' => 'error']);
                }
            }

            //print_r($response);
            $url_pay = "https://rosh.bet/deposit/" . $m_orderid;
            return response()->json(['success' => true, 'url' => $url_pay]);
        } elseif ($r->get('type') == 'Iugu') {
            // [Skull] - Adicionar esta correção para definir limite minimo de depósito para mais flexibilidade.
            if ($this->settings->mp_provider_status === 0) return response()->json(['success' => false, 'msg' => 'Método de pagamento desativado!', 'type' => 'error']);
            if ($r->get('amount') < $this->settings->min_dep) return response()->json(['success' => false, 'msg' => 'Quantidade mínima para depósito é R$ ' . $this->settings->min_dep . '!', 'type' => 'error']);

            $m_orderid = 'IUGU_' . time() * $this->user->id; // Create order unique ID
            $m_amount = number_format($r->get('amount'), 2, '.', ''); // Convert value to 0.00 float
            $amount = rtrim(rtrim($m_amount, '0'), '.'); // Remove x.00 value to unique INTEGER
            $username = explode(" ", $this->user->real_name); // username

            // Verifica se o token está valido
            $getDateNow = Date("Y-m-d H:m:i");

            $url = "https://api.iugu.com/v1/invoices?api_token=F7DBFDB574DDAC6BFBA5AA4A4B56C5D63EEEA5B05D35B808E9C04047BB142140";

            $data = [
                "email" => "betfyre@gmail.com",
                "due_date" => $getDateNow,
                "items" => [
                  [
                    "description" => "Créditos BetFyre",
                    "quantity" => 1,
                    "price_cents" => (int)$amount * 100
                  ]
                ],
                "payable_with" => "pix",
                "payer" => [
                  "cpf_cnpj" => $this->user->cpf,
                  "name" => $this->user->real_name,
                  "phone_prefix" => "11",
                  "phone" => "11989911000",
                  "email" => $this->user->email,
                  "address" => [
                    "zip_code" => "29190560",
                    "street" => "Rua A",
                    "number" => "100",
                    "district" => "Polivalente",
                    "city" => "São Paulo",
                    "state" => "SP",
                    "country" => "Brasil",
                    "complement" => "ap 32"
                  ]
                ]
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json'
            ]);

            $response = json_decode(curl_exec($ch), true);
            curl_close($ch);
            //return response()->json($response);
            // Save to database | Table: payments
            if ($m_amount != 0) {
                DB::beginTransaction();
                try {
                    $payment = new Payments();
                    $payment->user_id = $this->user->id;
                    $payment->secret = $m_orderid;
                    $payment->order_id = $response['id'];
                    $payment->sum = $m_amount;
                    $payment->system = 'Iugu (PIX)';
                    $payment->extra_info = $response['pix']['qrcode_text'];
                    $payment->save();

                    DB::commit();
                } catch (\PDOException $e) {
                    DB::connection()->getPdo()->rollBack();
                    return response()->json(['success' => false, 'msg' => $e->getMessage(), 'type' => 'error']);
                }
            }

            //print_r($response);
            $url_pay = "/deposit/" . $response['id'];
            return response()->json(['success' => true, 'url' => $url_pay]);
        }  elseif ($r->get('type') == 'Asaas') {
            // [Skull] - Adicionar esta correção para definir limite minimo de depósito para mais flexibilidade.
            if ($this->settings->mp_provider_status === 0) return response()->json(['success' => false, 'msg' => 'Método de pagamento desativado!', 'type' => 'error']);
            if ($r->get('amount') < $this->settings->min_dep) return response()->json(['success' => false, 'msg' => 'Quantidade mínima para depósito é R$ ' . $this->settings->min_dep . '!', 'type' => 'error']);

            $m_orderid = time() * $this->user->id; // Create order unique ID
            $m_amount = number_format($r->get('amount'), 2, '.', ''); // Convert value to 0.00 float
            $amount = rtrim(rtrim($m_amount, '0'), '.'); // Remove x.00 value to unique INTEGER
            $username = explode(" ", $this->user->real_name); // username
            $userEmail = $this->user->email;
            $providerCredentials = $this->paymentsProviders->getClientCredentials('Asaas');

            $asaas_domain = 'https://sandbox.asaas.com/'; // https://sandbox.asaas.com/ | https://www.asaas.com/

            $data = [
                "transaction_amount" => (int)$amount,
                "description" => "Adicionar crédito em " . $this->settings->title,
                "payment_method_id" => "pix",
                "notification_url" => $providerCredentials['url_return_ipn'],
                "payer" => [
                    "email" => $userEmail,
                    "first_name" => $username[0],
                    "last_name" => $username[1],
                    "identification" => [
                        "type" => "CPF",
                        "number" => $this->user->cpf
                    ]
                ],
                "external_reference" => $m_orderid
            ];

        } elseif ($r->get('type') == 'freekassa') {
            if (is_null($this->settings->fk_mrh_ID)) return response()->json(['success' => false, 'msg' => 'Este método de pagamento não está disponível!', 'type' => 'error']);
            $m_shop = $this->settings->fk_mrh_ID;
            $m_system = $r->get('type');
            $m_orderid = time() . mt_rand() . $this->user->id;
            $m_amount = number_format($r->get('amount'), 2, '.', '');
            $amount = rtrim(rtrim($m_amount, '0'), '.');
            $currency = 'BRL';
            $m_key = $this->settings->fk_secret1;
            $sign = md5($m_shop . ':' . $amount . ':' . $m_key . ':' . $currency . ':' . $m_orderid);

            if ($m_amount != 0) {
                DB::beginTransaction();
                try {
                    $payment = new Payments();
                    $payment->user_id = $this->user->id;
                    $payment->secret = $sign;
                    $payment->order_id = $m_orderid;
                    $payment->sum = $m_amount;
                    $payment->system = $m_system;
                    $payment->save();

                    DB::commit();
                } catch (\PDOException $e) {
                    DB::connection()->getPdo()->rollBack();
                    return response()->json(['success' => false, 'msg' => $e->getMessage(), 'type' => 'error']);
                }
            }
            return response()->json(['success' => true, 'url' => 'https://pay.freekassa.ru/?m=' . $m_shop . '&oa=' . $amount . '&o=' . $m_orderid . '&us_uid=' . $this->user->user_id . '&currency=' . $currency . '&s=' . $sign]);
        } else {
            return response()->json(['success' => false, 'msg' => 'Erro! contate o suporte imediatamente para analise e correção deste problema. #9009-0', 'type' => 'error']);
        }
    }

    public function resultPE(Request $r)
    {
        $ip = false;
        if (isset($_SERVER['HTTP_X_REAL_IP'])) {
            $ip = $this->getIpPE($_SERVER['HTTP_X_REAL_IP']);
        } else {
            $ip = $this->getIpPE($_SERVER['REMOTE_ADDR']);
        }
        if (!$ip) return ['msg' => 'Error check IP!', 'type' => 'error'];

        $m_shop = $r->get('m_shop');
        $m_orderid = $r->get('m_orderid');
        $m_amount = $r->get('m_amount');
        $m_curr = $r->get('m_curr');
        $m_desc = $r->get('m_desc');
        $checksum = $r->get('m_sign');
        $pay = Payments::where('order_id', $m_orderid)->first();

        $arHash = [
            $r->get('m_operation_id'),
            $r->get('m_operation_ps'),
            $r->get('m_operation_date'),
            $r->get('m_operation_pay_date'),
            $r->get('m_shop'),
            $r->get('m_orderid'),
            $r->get('m_amount'),
            $r->get('m_curr'),
            $r->get('m_desc'),
            $r->get('m_status')
        ];

        //if(isset($r->get('m_params'))) $arHash[] = $r->get('m_params');

        $arHash[] = $this->settings->payeer_secret1;

        $sign_hash = strtoupper(hash('sha256', implode(':', $arHash)));

        if ($r->get('m_status') != 'success') return $m_orderid . '|error';
        if (is_null($pay)) return $m_orderid . '|error';
        if ($sign_hash != $checksum) return $m_orderid . '|error';
        if ($pay->status != 0) return $m_orderid . '|error';
        if ($pay->sum != $m_amount) return $m_orderid . '|error';

        DB::beginTransaction();
        try {
            $pay->status = 1;
            $pay->update();
            $user = User::find($pay->user_id);
            $user->balance += $m_amount;
            $user->update();

            DB::commit();

            return $m_orderid . '|success';
        } catch (\PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            return $m_orderid . '|error';
        }
    }

    function getIpPE($ip)
    {
        $list = ['185.71.65.92', '185.71.65.189', '149.202.17.210', '80.71.252.176'];
        for ($i = 0; $i < count($list); $i++) {
            if ($list[$i] == $ip) return true;
        }
        return false;
    }

    public function resultLava(Request $r)
    {
        $secret = "1897-e0ee2a89b7056e350fb103fe036b52d4";

        $sign = md5($_REQUEST['WALLET_ID'] . ':' . $_REQUEST['SUM'] . ':' . $_REQUEST['ORDER_ID'] . ':' . $secret);

        if ($sign != $_REQUEST['SIGN']) {
            return ['msg' => 'Error sign!', 'type' => 'error'];
        }

        $order = $this->chechOrder($_REQUEST['ORDER_ID'], $_REQUEST['SUM']);
        if ($order['type'] == 'error') return ['msg' => $order['msg'], 'type' => 'error'];

        $merch = Payments::where('order_id', $_REQUEST['ORDER_ID'])->first();
        $user = User::find($merch->user_id);
        if (!$user) return ['msg' => 'User not found!', 'type' => 'error'];

        /* ADD Balance from user and partner */
        $sum = $_REQUEST['SUM'];
        $user->update([
            'balance' => $user->balance + $sum
        ]);

        Payments::where('order_id', $_REQUEST['ORDER_ID'])->update([
            'status' => 1
        ]);

        /* SUCCESS REDIRECT */
        return ['msg' => 'Seu pedido #' . $_REQUEST['ORDER_ID'] . ' foi pago com sucesso!', 'type' => 'success'];
    }

    public function resultFK(Request $r)
    {
        $ip = false;
        if (isset($_SERVER['HTTP_X_REAL_IP'])) {
            $ip = $this->getIpFK($_SERVER['HTTP_X_REAL_IP']);
        } else {
            $ip = $this->getIpFK($_SERVER['REMOTE_ADDR']);
        }
        if (!$ip) return ['msg' => 'Error check IP!', 'type' => 'error'];

        $order = $this->chechOrder($r->get('MERCHANT_ORDER_ID'), $r->get('AMOUNT'));
        if ($order['type'] == 'error') return ['msg' => $order['msg'], 'type' => 'error'];

        $merch = Payments::where('order_id', $r->get('MERCHANT_ORDER_ID'))->first();
        $user = User::find($merch->user_id);
        if (!$user) return ['msg' => 'User not found!', 'type' => 'error'];

        /* ADD Balance from user and partner */
        $sum = $r->get('AMOUNT');
        $user->update([
            'balance' => $user->balance + $sum
        ]);

        Payments::where('order_id', $r->get('MERCHANT_ORDER_ID'))->update([
            'status' => 1
        ]);

        /* SUCCESS REDIRECT */
        return ['msg' => 'Your order #' . $r->get('MERCHANT_ORDER_ID') . ' has been paid successfully!', 'type' => 'success'];
    }

    public function resultXM(Request $r)
    {
        // exit('NO HASH');
        $hash = hash('sha256', $_POST['shop_id'] . $_POST['amount'] . 'w6NcHynKeQbYA9V' . $_POST['id']); // генерируем hash на вашей стороне
        if ($hash != $r->post('hash')) { // сравниваем полученные hash
            exit('NO_HASH');
        }

        $order = $this->chechOrder($r->post('label'), $r->post('amount'));
        if ($order['type'] == 'error') return ['msg' => $order['msg'], 'type' => 'error'];


        $merch = Payments::where('order_id', $r->post('label'))->first();
        $user = User::find($merch->user_id);
        if (!$user) return ['msg' => 'User not found!', 'type' => 'error'];

        /* ADD Balance from user and partner */
        $sum = $r->post('amount');
        $user->update([
            'balance' => $user->balance + $sum
        ]);

        Payments::where('order_id', $r->post('label'))->update([
            'status' => 1
        ]);

        /* SUCCESS REDIRECT */
        return ['msg' => 'Your order #' . $r->post('label') . ' has been paid successfully!', 'type' => 'success'];
    }

    private function chechOrder($id, $sum)
    {
        $merch = Payments::where('order_id', $id)->first();
        if (!$merch) return ['msg' => 'Pedido verificado!', 'type' => 'success'];
        if ($sum != $merch->sum) return ['msg' => 'Você pagou outro pedido!', 'type' => 'error'];
        if ($merch->order_id == $id && $merch->status == 1) return ['msg' => 'Pedido atualmente pago!', 'type' => 'error'];

        return ['msg' => 'Pedido verificado!', 'type' => 'success'];
    }

    function getIpFK($ip)
    {
        $list = ['168.119.157.136', '168.119.60.227', '138.201.88.124', '178.154.197.79'];
        for ($i = 0; $i < count($list); $i++) {
            if ($list[$i] == $ip) return true;
        }
        return false;
    }

    public function userWithdraw(Request $r)
    {
        if (\Cache::has('action.user.' . $this->user->id)) return response()->json(['msg' => 'Aguarde a ação anterior!', 'type' => 'error']);
        // \Cache::put('action.user.' . $this->user->id, '', 5);

        if ($this->settings->min_dep_withdraw) {
            $dep = Payments::where('user_id', $this->user->id)->where('status', 1)->sum('sum');
            if ($dep < $this->settings->min_dep_withdraw) return ['success' => false, 'msg' => 'Para sacar, você precisa reabastecer o saldo em pelo menos R$ ' . $this->settings->min_dep_withdraw . '!', 'type' => 'error'];
        }

        $system = htmlspecialchars($r->get('system'));
        $wallet = htmlspecialchars($r->get('wallet'));
        $value = htmlspecialchars($r->get('value'));
        $sum = round(str_replace('/[^.0-9]/', '', $value), 2) ?? null;
        $com = null;
        $com_sum = null;
        $min = null;
        $max = 5000;

        if ($system == 'pix') {
            $com = $this->settings->mp_com_percent;
            $com_sum = $this->settings->mp_com_brl;
            $min = $this->settings->mp_min;
        }
        if ($system == 'payeer') {
            $com = $this->settings->payeer_com_percent;
            $com_sum = $this->settings->payeer_com_rub;
            $min = $this->settings->payeer_min;
        }
        if ($system == 'qiwi') {
            $com = $this->settings->qiwi_com_percent;
            $com_sum = $this->settings->qiwi_com_rub;
            $min = $this->settings->qiwi_min;
        }
        if ($system == 'yandex') {
            $com = $this->settings->yandex_com_percent;
            $com_sum = $this->settings->yandex_com_rub;
            $min = $this->settings->yandex_min;
        }
        if ($system == 'fkwallet') {
            $com = $this->settings->webmoney_com_percent;
            $com_sum = $this->settings->webmoney_com_rub;
            $min = $this->settings->webmoney_min;
        }
        if ($system == 'visa') {
            $com = $this->settings->visa_com_percent;
            $com_sum = $this->settings->visa_com_rub;
            $min = $this->settings->visa_min;
        }

        // Amount request to withdraw
        $sumCom = ($sum + ($sum / 100 * $com)) + $com_sum;

        if ($this->user->requery < $sumCom) return ['success' => false, 'msg' => 'Disponível para saques R$ ' . $this->user->requery . '!', 'type' => 'error'];
        if (is_null($wallet)) return ['success' => false, 'msg' => 'Número da carteira não inserido!', 'type' => 'error'];
        if (is_null($sum)) return ['success' => false, 'msg' => 'Valor para saque não inserido!', 'type' => 'error'];
        if (is_null($com)) return ['success' => false, 'msg' => 'Falha ao calcular a comissão!', 'type' => 'error'];
        if ($sum < $min) return ['success' => false, 'msg' => 'Quantidade mínima de saque é de R$ ' . $min . '!', 'type' => 'error'];
        if ($sum > $max) return ['success' => false, 'msg' => 'Valor máximo para saques R$ ' . $max . '!', 'type' => 'error'];

        if ($sumCom > $this->user->balance) return ['success' => false, 'msg' => 'Não há fundos suficientes para saques!', 'type' => 'error'];

        Withdraw::insert([
            'user_id' => $this->user->id,
            'value' => $sum,
            'valueWithCom' => $sumCom,
            'system' => $system,
            'wallet' => $wallet
        ]);

        $this->user->balance -= $sumCom;
        $this->user->requery -= $sumCom;
        $this->user->save();

        $this->redis->publish('updateBalance', json_encode([
            'unique_id' => $this->user->unique_id,
            'balance' => round($this->user->balance, 2)
        ]));

        return ['success' => true, 'msg' => 'O pagamento foi realizado para a carteira especificada!', 'type' => 'success'];
    }

    public function success($msg)
    {
        return redirect()->route('index')->with('success', 'Sucesso! Seu saldo está concluído!');
    }

    public function fail()
    {
        return redirect()->route('index')->with('error', 'Erro ao repor o saldo!');
    }

    public function userWithdrawCancel(Request $r)
    {
        if (\Cache::has('action.user.' . $this->user->id)) return response()->json(['success' => false, 'msg' => 'Aguarde a ação anterior!', 'type' => 'error']);
        // \Cache::put('action.user.' . $this->user->id, '', 2);
        $id = $r->get('id');
        $withdraw = Withdraw::where('id', $id)->first();

        if ($withdraw->status > 0) return response()->json(['success' => false, 'msg' => 'Você não pode desfazer este saque!', 'type' => 'error']);
        if ($withdraw->user_id != $this->user->id) return response()->json(['success' => false, 'msg' => 'Você não pode cancelar o saque de outro usuário!', 'type' => 'error']);

        $this->user->balance += $withdraw->valueWithCom;
        $this->user->requery += $withdraw->valueWithCom;
        $this->user->save();
        $withdraw->status = 2;
        $withdraw->save();

        return response()->json(['success' => true, 'msg' => 'Você cancelou o saque de R$ ' . $withdraw->valueWithCom, 'type' => 'success', 'id' => $id]);
    }

    public function userDepositCancel(Request $r)
    {
        if (\Cache::has('action.user.' . $this->user->id)) return response()->json(['success' => false, 'msg' => 'Aguarde a ação anterior!', 'type' => 'error']);
        \Cache::put('action.user.' . $this->user->id, '', 2); // [Skull] - Activated for test
        $id = $r->get('id');
        $deposit = Payments::where('id', $id)->first();

        if (isset($deposit)) return response()->json(['success' => false, 'msg' => 'Este pedido de depósito não existe!', 'type' => 'error']);
        if ($deposit->status > 0) return response()->json(['success' => false, 'msg' => 'Você não pode cancelar este deposito, pois não esta Pendente!', 'type' => 'error']);
        if ($deposit->user_id != $this->user->id) return response()->json(['success' => false, 'msg' => 'Você não pode cancelar o depósito de outro usuário!', 'type' => 'error']);

        $deposit->status = 5; // update to 'Cancelado'
        $deposit->save();

        // Send cancel status to Provider
        // MercadoPago

        // Get credential of provider
        // Obs: The function "getClientCredentials" auto check if is LIVE or DEMO and return the keys of current mode.
        /*$providerCredentials = $this->paymentsProviders->getClientCredentials('MercadoPago');

        // Search this order in Gateway Provider
        $data['url'] = 'https://api.mercadopago.com/v1/payments/'.$deposit->secret;
        $data['client_token'] = $providerCredentials['client_token'];
        $data['isPUT'] = true;
        $data['post_fields'] = [
            'status' => 'cancelled',
            'transaction_amount' => (int)$deposit->sum
        ];

        $response = $this->SendCURL($data, false);*/

        return response()->json(['success' => true, 'msg' => 'Você cancelou o saque de R$ ' . $deposit->sum, 'type' => 'success', 'id' => $id]);
    }

    private function groupIsMember($id)
    {
        $user_id = $id;
        $vk_url = $this->settings->vk_url;
        if (!$vk_url) $group = NULL;
        $old_url = ($vk_url);
        $url = explode('/', trim($old_url, '/'));
        $url_parse = array_pop($url);
        $url_last = preg_replace('/&?club+/i', '', $url_parse);
        $group = $this->curl('https://api.vk.com/method/groups.isMember?v=5.91&group_id=' . $url_last . '&user_id=' . $user_id . '&access_token=' . $this->settings->vk_service_key);

        if (isset($group['error'])) {
            $group = NULL;
        } else {
            $group = $group['response'];
        }
        return $group;
    }

    /**
     * cURL POST
     *
     * @param $url
     * @return mixed
     */
    public function curl($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $group = curl_exec($ch);
        curl_close($ch);
        return json_decode($group, true);
    }

    public function paggue_getBearerToken()
    {

        $providerCredentials = $this->paymentsProviders->getClientCredentials('Paggue');

        $data = [
            'client_key' => $providerCredentials['client_key'],
            'client_secret' => $providerCredentials['client_token']
        ];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://ms.paggue.io/payments/api/auth/login',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(),
        ));

        $response = json_decode(curl_exec($curl));

        curl_close($curl);


        $this->paymentsProviders->getBearerToken_Paggue("Paggue", $response->access_token);

        $this->Paggue_Token = $response;
        return $this->Paggue_Token;
    }

    // Reset user password by Forgot Password | View
    public function resetPasswordView($token)
    {
        if (empty($token) || !isset($token)) return redirect()->route('indexSite')->with('error', 'Token inválido!');

        $user = User::where('token_new_pass', $token)->first();

        if (empty($user) || !isset($user)) return redirect()->route('indexSite')->with('error', 'Token inválido!');

        return view('pages.forgot-password', compact('user'));
    }

    // Reset user password by Forgot Password | Backend
    public function resetPassword(Request $request)
    {
        $user = User::where('id', $request->id)->first();

        $newPwd = Hash::make($request->get('password'));
        //$newPwd = Hash::make($request->newPassword);

        $user->password = $newPwd;
        $user->token_new_pass = NULL;
        $user->save();

        /*return response()->json([
            'password' => $request->get('password'),
            'crypt' => $newPwd,
            'db_crypt' => $user->password
        ]);*/

        Auth::login($user, true);

        return redirect()->route('indexSite')->with('success', 'Senha alterada com sucesso!');
    }
}
