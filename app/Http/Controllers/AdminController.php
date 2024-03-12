<?php namespace App\Http\Controllers;

use App\PaymentsProviders;
use App\User;
use Auth;
use App\Rooms;
use App\Profit;
use App\Payments;
use App\Withdraw;
use App\Settings;
use App\Jackpot;
use App\JackpotBets;
use App\Wheel;
use App\Sends;
use App\WheelBets;
use App\Crash;
use App\CrashBets;
use App\CoinFlip;
use App\Battle;
use App\BattleBets;
use App\Dice;
use App\Promocode;
use App\Filter;
use App\Bonus;
use App\Exchanges;
use App\Giveaway;
use App\GiveawayUsers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use DB;

class AdminController extends Controller
{
    const CHAT_CHANNEL = 'chat.message';
    const NEW_MSG_CHANNEL = 'new.msg';
    const CLEAR = 'chat.clear';
    const DELETE_MSG_CHANNEL = 'del.msg';

    public function __construct()
    {
        parent::__construct();
        $jackpot_easy = Jackpot::where('room', 'easy')->orderBy('id', 'desc')->first();
        $jackpot_medium = Jackpot::where('room', 'medium')->orderBy('id', 'desc')->first();
        $jackpot_hard = Jackpot::where('room', 'hard')->orderBy('id', 'desc')->first();
        view()->share('chances_easy', $this->getChancesOfGame($jackpot_easy->id));
        view()->share('chances_medium', $this->getChancesOfGame($jackpot_medium->id));
        view()->share('chances_hard', $this->getChancesOfGame($jackpot_hard->id));
    }

    public function index()
    {
        $pay_today = Payments::where('updated_at', '>=', Carbon::today())->where('status', 1)->sum('sum');
        $pay_week = Payments::where('updated_at', '>=', Carbon::now()->subDays(7))->where('status', 1)->sum('sum');
        $pay_month = Payments::where('updated_at', '>=', Carbon::now()->subDays(30))->where('status', 1)->sum('sum');
        $pay_all = Payments::where('status', 1)->sum('sum');
        $with_req = Withdraw::where('status', 0)->orderBy('id', 'desc')->sum('value');
        $with_today = Withdraw::where('status', 1)->where('updated_at', '>=', Carbon::today())->orderBy('id', 'desc')->sum('value');
        $usersCount = User::count();
        $profit_jackpot = Profit::where('game', 'jackpot')->where('created_at', '>=', Carbon::today())->sum('sum');
        $profit_pvp = Profit::where('game', 'pvp')->where('created_at', '>=', Carbon::today())->sum('sum');
        $profit_battle = Profit::where('game', 'battle5')->where('created_at', '>=', Carbon::today())->sum('sum');
        $profit_wheel = Profit::where('game', 'wheel')->where('created_at', '>=', Carbon::today())->sum('sum');
        $profit_dice = Profit::where('game', 'dice')->where('created_at', '>=', Carbon::today())->sum('sum');
        $profit_crash = Profit::where('game', 'crash')->where('created_at', '>=', Carbon::today())->sum('sum');
        $profit_hilo = Profit::where('game', 'hilo')->where('created_at', '>=', Carbon::today())->sum('sum');
        $profit_exchange = Profit::where('game', 'exchange')->where('created_at', '>=', Carbon::today())->sum('sum');
        $profit = Profit::where('created_at', '>=', Carbon::today())->sum('sum');
        $profit_ref = Profit::where('game', 'ref')->where('created_at', '>=', Carbon::today())->sum('sum');
        $fake = User::where('fake', 1)->orderBy('id', 'desc')->get();
        $users = User::orderBy('id', 'desc')->where('fake', 0)->limit(10)->get();
        $userTop = User::where(['is_admin' => 0, 'is_youtuber' => 0, 'fake' => 0])->where('balance', '!=', 0)->orderBy('balance', 'desc')->limit(20)->get();

        $payments = Payments::where('status', 1)->orderBy('id', 'desc')->limit(10)->get();

        $last_dep = [];
        foreach ($payments as $d) {
            $user = User::where('id', $d->user_id)->first();
            $last_dep[] = [
                'id' => $user->id,
                'username' => $user->username,
                'avatar' => $user->avatar,
                'sum' => $d->sum,
                'date' => $d->updated_at
            ];
        }

        return view('admin.index', compact('pay_today', 'pay_week', 'pay_month', 'pay_all', 'with_req', 'with_today', 'usersCount', 'profit_jackpot', 'profit_pvp', 'profit_battle', 'profit_wheel', 'profit_dice', 'profit_crash', 'profit_hilo', 'profit_exchange', 'profit', 'profit_ref', 'fake', 'last_dep', 'users', 'userTop'));
    }

    public function users()
    {
        return view('admin.users');
    }

    public function user($id)
    {
        $user = User::where('id', $id)->first();
        $pay = Payments::where('user_id', $user->id)->where('status', 1)->sum('sum');
        $withdraw = Withdraw::where('user_id', $user->id)->where('status', 1)->sum('value');
        $sends_arr = Sends::where('sender', $user->id)->get();
        $sends_arr_from = Sends::where('receiver', $user->id)->get();
        $jackpotWin = Jackpot::where(['winner_id' => $user->id])->where('status', 3)->sum('winner_balance');
        $wheelWin = WheelBets::join('wheel', 'wheel.id', '=', 'wheel_bets.game_id')
            ->select('wheel.status', 'wheel.id', 'wheel_bets.game_id', 'wheel_bets.win_sum')
            ->where('wheel.status', 3)
            ->where('wheel_bets.balance', 'balance')
            ->where(['wheel_bets.user_id' => $user->id, 'wheel_bets.win' => 1])
            ->groupBy('wheel_bets.game_id', 'wheel_bets.win_sum')
            ->get()->sum('win_sum');
        $crashWin = CrashBets::join('crash', 'crash.id', '=', 'crash_bets.round_id')
            ->select('crash.status', 'crash.id', 'crash_bets.round_id', 'crash_bets.won')
            ->where('crash.status', 2)
            ->where('crash_bets.balType', 'balance')
            ->where(['crash_bets.user_id' => $user->id, 'crash_bets.status' => 1])
            ->groupBy('crash_bets.round_id', 'crash_bets.won')
            ->get()->sum('won');
        $coinWin = CoinFlip::where('winner_id', $user->id)->where('balType', 'balance')->sum('bank') / 2;
        $coinWin = CoinFlip::where('winner_id', $user->id)->where('balType', 'balance')->sum('winner_sum') - $coinWin;
        $battleWin = BattleBets::join('battle', 'battle.id', '=', 'battle_bets.game_id')
            ->select('battle.status', 'battle.id', 'battle_bets.game_id', 'battle_bets.price')
            ->where('battle.status', 3)
            ->where('battle_bets.balType', 'balance')
            ->where(['battle_bets.user_id' => $user->id, 'battle_bets.win' => 1])
            ->groupBy('battle_bets.game_id', 'battle_bets.price')
            ->get()->sum('price');
        $battleWin = BattleBets::join('battle', 'battle.id', '=', 'battle_bets.game_id')
                ->select('battle.status', 'battle.id', 'battle_bets.game_id', 'battle_bets.win_sum')
                ->where('battle.status', 3)
                ->where('battle_bets.balType', 'balance')
                ->where(['battle_bets.user_id' => $user->id, 'battle_bets.win' => 1])
                ->groupBy('battle_bets.game_id', 'battle_bets.win_sum')
                ->get()->sum('win_sum') - $battleWin;
        $diceWin = Dice::where(['user_id' => $user->id, 'balType' => 'balance', 'win' => 1])->sum('win_sum');
        $betWin = $jackpotWin + $wheelWin + $crashWin + $coinWin + $battleWin + $diceWin;

        $jackpotLose = JackpotBets::join('jackpot', 'jackpot.id', '=', 'jackpot_bets.game_id')
            ->select('jackpot.status', 'jackpot.id', 'jackpot_bets.game_id', 'jackpot_bets.win', 'jackpot_bets.sum')
            ->where('jackpot.status', 3)
            ->where('jackpot_bets.balance', 'balance')
            ->where(['user_id' => $user->id, 'win' => 0])
            ->groupBy('jackpot_bets.game_id', 'jackpot_bets.win', 'jackpot_bets.sum')
            ->get()->sum('sum');
        $wheelLose = WheelBets::join('wheel', 'wheel.id', '=', 'wheel_bets.game_id')
            ->select('wheel.status', 'wheel.id', 'wheel_bets.game_id', 'wheel_bets.price')
            ->where('wheel.status', 3)
            ->where('wheel_bets.balance', 'balance')
            ->where(['wheel_bets.user_id' => $user->id, 'wheel_bets.win' => 0])
            ->groupBy('wheel_bets.game_id', 'wheel_bets.price')
            ->get()->sum('price');
        $crashLose = CrashBets::join('crash', 'crash.id', '=', 'crash_bets.round_id')
            ->select('crash.status', 'crash.id', 'crash_bets.round_id', 'crash_bets.price')
            ->where('crash.status', 2)
            ->where('crash_bets.balType', 'balance')
            ->where(['crash_bets.user_id' => $user->id, 'crash_bets.status' => 0])
            ->groupBy('crash_bets.round_id', 'crash_bets.price')
            ->get()->sum('price');
        $coinLose1 = CoinFlip::where('winner_id', '!=', $user->id)->where('balType', 'balance')->where('heads', $user->id)->where('status', 1)->count();
        $coinLose2 = CoinFlip::where('winner_id', '!=', $user->id)->where('balType', 'balance')->where('tails', $user->id)->where('status', 1)->count();
        $coinLose = $coinLose1 + $coinLose2;
        $battleLose = BattleBets::join('battle', 'battle.id', '=', 'battle_bets.game_id')
            ->select('battle.status', 'battle.id', 'battle_bets.game_id', 'battle_bets.price')
            ->where('battle.status', 3)
            ->where('battle_bets.balType', 'balance')
            ->where(['battle_bets.user_id' => $user->id, 'battle_bets.win' => 0])
            ->groupBy('battle_bets.game_id', 'battle_bets.price')
            ->get()->sum('price');
        $diceLose = Dice::where(['user_id' => $user->id, 'balType' => 'balance', 'win' => 0])->sum('sum');
        $betLose = $jackpotLose + $wheelLose + $crashLose + $coinLose + $battleLose + $diceLose;

        $exchanges = round(Exchanges::where('user_id', $user->id)->sum('sum') / $this->settings->exchange_curs, 2);

        /*
              SELECT
                users.ref_id as 'Convite',
                sum(payments.sum) as 'Lucros'
            FROM
                users
                INNER JOIN
                payments
                ON
                    users.id = payments.user_id
            WHERE
                users.ref_id = 'QRQuTV4j15L'
                AND payments.status = 1
         */
        // Get total profit inviters from this users
        $totalInviteProfit = DB::table('users')
            ->selectRaw('users.ref_id')
            ->join('payments', 'users.id', '=', 'payments.user_id')
            ->where('users.ref_id', '=', $user->unique_id)
            ->where('payments.status', '=', 1)
            ->sum('payments.sum');

        $sends = [];
        $sends_from = [];
        foreach ($sends_arr as $s) {
            $u = User::where('id', $s->receiver)->first();
            $sends[] = [
                'id' => $u->id,
                'username' => $u->username,
                'sum' => $s->sum,
                'date' => Carbon::parse($s->updated_at)->diffForHumans()
            ];
        }
        foreach ($sends_arr_from as $s) {
            $u = User::where('id', $s->sender)->first();
            $sends_from[] = [
                'id' => $u->id,
                'username' => $u->username,
                'sum' => $s->sum,
                'date' => Carbon::parse($s->updated_at)->diffForHumans()
            ];
        }

        return view('admin.user', compact('user', 'pay', 'withdraw', 'exchanges', 'totalInviteProfit', 'jackpotWin', 'jackpotLose', 'sends', 'sends_from', 'wheelWin', 'wheelLose', 'crashWin', 'crashLose', 'coinWin', 'coinLose', 'battleWin', 'battleLose', 'diceWin', 'diceLose', 'betWin', 'betLose'));
    }

    public function userSave(Request $r)
    {
        $admin = 0;

        $moder = 0;
        $youtuber = 0;
        $banchat = null;
        $time = 0;
        if ($r->get('id') == null) return back()->with('error', 'Não foi possível encontrar um usuário com este ID!');
        if ($r->get('balance') == null) return back()->with('error', 'O campo "Saldo" não pode ficar vazio!');
        if ($r->get('bonus') == null) return back()->with('error', 'O campo "Bônus" não pode ficar vazio!');

        if ($r->get('priv') == 'admin') $admin = 1;
        if ($r->get('priv') == 'moder') $moder = 1;
        if ($r->get('priv') == 'youtuber') $youtuber = 1;
        if (!is_null($r->get('time'))) $time = $r->get('time');
        if ($r->get('banchat') != null) $banchat = Carbon::parse($r->get('banchat'))->getTimestamp();

        User::where('id', $r->get('id'))->update([
            'balance' => $r->get('balance'),
            'bonus' => $r->get('bonus'),
            'requery' => $r->get('requery'),
            'is_admin' => $admin,

            'is_moder' => $moder,
            'is_youtuber' => $youtuber,
            'ban' => $r->get('ban'),
            'ban_reason' => $r->get('ban_reason'),
            'banchat' => $banchat,
            'banchat_reason' => $r->get('banchat_reason'),
            'time' => $time
        ]);

        return back()->with('success', 'Informações do usuário foram salvo!');
    }

    public function usersAjax()
    {
        return datatables(User::query()->where('fake', 0))->toJson();
    }

    public function bots()
    {
        $bots = User::where('fake', 1)->get();
        return view('admin.bots', compact('bots'));
    }

    public function getVKUser(Request $r)
    {
        $vk_url = $r->get('url');
        $old_url = ($vk_url);
        $url = explode('/', trim($old_url, '/'));
        $url_parse = array_pop($url);
        $url_last = preg_replace('/&?id+/i', '', $url_parse);
        $runfile = 'https://api.vk.com/method/users.get?v=5.91&user_ids=' . $url_last . '&fields=photo_max&lang=0&access_token=' . $this->settings->vk_service_key;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $runfile);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $user = curl_exec($ch);
        curl_close($ch);
        $user = json_decode($user);
        $user = $user->response;
        return $user;
    }

    public function fakeSave(Request $r)
    {
        $username = $r->get('name');
        $avatar = $r->get('avatar');
        $user_id = $r->get('vkId');

        $time = 0;
        if (!is_null($r->get('time'))) {
            $time = $r->get('time');
        }

        User::insert([
            'unique_id' => bin2hex(random_bytes(6)),
            'username' => $username,
            'avatar' => '/img/userfyre.png',
            'user_id' => $user_id,
            'fake' => 1,
            'time' => $time
        ]);

        return redirect()->route('admin.bots')->with('success', 'Bot adicionado!');
    }

    public function botsDelete($id)
    {
        $case = User::where('id', $id)->first();
        User::where('id', $id)->delete();

        return redirect()->route('admin.bots')->with('success', 'Bot removido!');
    }

    public function bonus()
    {
        $bonuses = Bonus::get();
        return view('admin.bonus', compact('bonuses'));
    }

    public function bonusNew(Request $r)
    {
        $sum = $r->get('sum');
        $type = $r->get('type');
        $bg = $r->get('bg');
        $color = $r->get('color');
        $status = $r->get('status');
        if (!$sum) return redirect()->route('admin.bonus')->with('error', 'O campo "Valor" não pode ficar vazio!');
        if (!$bg) return redirect()->route('admin.bonus')->with('error', 'O campo "Valor background" não pode ficar vazio!');
        if (!$color) return redirect()->route('admin.bonus')->with('error', 'O campo "Texto do valor" não pode ficar vazio!');

        Bonus::create([
            'sum' => $sum,
            'type' => $type,
            'bg' => $bg,
            'color' => $color,
            'status' => $status
        ]);

        return redirect()->route('admin.bonus')->with('success', 'Bônus criado!');
    }

    public function bonusSave(Request $r)
    {
        $id = $r->get('id');
        $sum = $r->get('sum');
        $type = $r->get('type');
        $bg = $r->get('bg');
        $color = $r->get('color');
        $status = $r->get('status');
        if (!$id) return redirect()->route('admin.bonus')->with('error', 'Não foi possível encontrar este ID!');
        if (!$sum) return redirect()->route('admin.bonus')->with('error', 'O campo "Valor" não pode ficar vazio!');
        if (!$bg) return redirect()->route('admin.bonus')->with('error', 'O campo "Valor background" não pode ficar vazio!');
        if (!$color) return redirect()->route('admin.bonus')->with('error', 'O campo "Texto do valor" não pode ficar vazio!');

        Bonus::where('id', $id)->update([
            'sum' => $sum,
            'type' => $type,
            'bg' => $bg,
            'color' => $color,
            'status' => $status
        ]);

        return redirect()->route('admin.bonus')->with('success', 'Bônus atualizado!');
    }

    public function bonusDelete($id)
    {
        if (!$id) return redirect()->route('admin.bonus')->with('error', 'bônus inexistente!');
        Bonus::where('id', $id)->delete();

        return redirect()->route('admin.bonus')->with('success', 'Bônus removido!');
    }

    public function giveaway()
    {
        $giveaway = Giveaway::get();
        $fake = User::where('fake', 1)->get();
        return view('admin.giveaway', compact('giveaway', 'fake'));
    }

    public function giveawayNew(Request $r)
    {
        $sum = $r->get('sum');
        $type = $r->get('type');
        $group_sub = $r->get('group_sub');
        $min_dep = $r->get('min_dep');
        $time_to = $r->get('time_to');
        $winner_id = $r->get('winner_id');

        if (!$sum) return redirect()->route('admin.giveaway')->with('error', 'O campo "Valor" não pode ficar vazio!');
        if (!$time_to) return redirect()->route('admin.giveaway')->with('error', 'O campo "End Time" não pode ficar vazio!');

        if (!is_null($group_sub)) $group_sub = 1; else $group_sub = 0;
        if (is_null($min_dep)) $min_dep = 0;
        if ($winner_id == 'null') $winner_id = null;

        $time_to = Carbon::parse($time_to)->getTimestamp();

        $gv = Giveaway::create([
            'sum' => $sum,
            'type' => $type,
            'group_sub' => $group_sub,
            'min_dep' => $min_dep,
            'time_to' => $time_to,
            'winner_id' => $winner_id
        ]);

        $gv->winner_id = null;

        $array = [
            'type' => 'newGiveaway',
            'data' => $gv
        ];

        $this->redis->publish('giveaway', json_encode($array));

        if (!is_null($winner_id)) {
            GiveawayUsers::create([
                'giveaway_id' => $gv->id,
                'user_id' => $winner_id
            ]);

            $users = GiveawayUsers::where('giveaway_id', $gv->id)->count();

            $data = [
                'type' => 'newUser',
                'id' => $gv->id,
                'count' => $users
            ];

            $this->redis->publish('giveaway', json_encode($data));
        }


        return redirect()->route('admin.giveaway')->with('success', 'Prêmio criado!');
    }

    public function giveawaySave(Request $r)
    {
        $id = $r->get('id');
        $sum = $r->get('sum');
        $type = $r->get('type');
        $group_sub = $r->get('group_sub');
        $min_dep = $r->get('min_dep');
        $time_to = $r->get('time_to');
        $winner_id = $r->get('winner_id');

        if (!$id) return redirect()->route('admin.giveaway')->with('error', 'Não foi possível encontrar este ID!');
        if (!$sum) return redirect()->route('admin.giveaway')->with('error', 'O campo "Valor" não pode ficar vazio!');
        if (!$time_to) return redirect()->route('admin.giveaway')->with('error', 'O campo "End Time" não pode ficar vazio!');

        if (!is_null($group_sub)) $group_sub = 1; else $group_sub = 0;
        if (is_null($min_dep)) $min_dep = 0;
        if ($winner_id == 'null') $winner_id = null;

        $time_to = Carbon::parse($time_to)->getTimestamp();

        $gv = Giveaway::where('id', $id)->first();

        if (!is_null($winner_id)) {
            $gu = User::where('id', $winner_id)->first();
            if ($gu->fake) GiveawayUsers::where('giveaway_id', $gv->id)->where('user_id', $gv->winner_id)->delete();
            $count = GiveawayUsers::where('giveaway_id', $gv->id)->where('user_id', $gu->id)->count();
            if ($gu->fake && $count == 0) {
                GiveawayUsers::create([
                    'giveaway_id' => $gv->id,
                    'user_id' => $winner_id
                ]);
            }
        } else {
            $gu = User::where('id', $gv->winner_id)->first();
            if ($gu->fake) GiveawayUsers::where('giveaway_id', $gv->id)->where('user_id', $gv->winner_id)->delete();
        }

        $users = GiveawayUsers::where('giveaway_id', $gv->id)->count();

        $array = [
            'type' => 'newUser',
            'id' => $gv->id,
            'count' => $users
        ];

        $this->redis->publish('giveaway', json_encode($array));

        Giveaway::where('id', $id)->update([
            'sum' => $sum,
            'type' => $type,
            'group_sub' => $group_sub,
            'min_dep' => $min_dep,
            'time_to' => $time_to,
            'winner_id' => $winner_id
        ]);

        return redirect()->route('admin.giveaway')->with('success', 'Prêmio atualizado!');
    }

    public function giveawayDelete($id)
    {
        if (!$id) return redirect()->route('admin.giveaway')->with('error', 'Esses Sorteios não existem!');
        Giveaway::where('id', $id)->delete();
        GiveawayUsers::where('giveaway_id', $id)->delete();

        $array = [
            'type' => 'delete',
            'id' => $id
        ];

        $this->redis->publish('giveaway', json_encode($array));

        return redirect()->route('admin.giveaway')->with('success', 'Os Sorteios foram removidos!');
    }

    public function promo()
    {
        $promos = Promocode::get();
        return view('admin.promo', compact('promos'));
    }

    public function promoNew(Request $r)
    {
        $code = $r->get('code');
        $type = $r->get('type');
        $limit = $r->get('limit');
        $amount = $r->get('amount');
        $count_use = $r->get('count_use');
        $have = Promocode::where('code', $code)->first();
        if (!$code) return redirect()->route('admin.promo')->with('error', 'O campo "Código" não pode ficar vazio!');
        if (!$amount) return redirect()->route('admin.promo')->with('error', 'O campo "Valor" não pode ficar vazio!');
        if (!$count_use) return redirect()->route('admin.promo')->with('error', 'O campo "Número de ativações" não pode ficar vazio!');
        if ($have) return redirect()->route('admin.promo')->with('error', 'Este código já existe');

        Promocode::create([
            'code' => $code,
            'type' => $type,
            'limit' => $limit,
            'amount' => $amount,
            'count_use' => $count_use
        ]);

        return redirect()->route('admin.promo')->with('success', 'Código promocional criado!');
    }

    public function promoSave(Request $r)
    {
        $id = $r->get('id');
        $code = $r->get('code');
        $type = $r->get('type');
        $limit = $r->get('limit');
        $amount = $r->get('amount');
        $count_use = $r->get('count_use');
        $have = Promocode::where('code', $code)->where('id', '!=', $id)->first();
        if (!$id) return redirect()->route('admin.promo')->with('error', 'Não foi possível encontrar este ID!');
        if (!$code) return redirect()->route('admin.promo')->with('error', 'O campo "Código" não pode ficar vazio!');
        if (!$amount) return redirect()->route('admin.promo')->with('error', 'O campo "Valor" não pode ficar vazio!');
        if (!$count_use) $count_use = 0;
        if ($have) return redirect()->route('admin.promo')->with('error', 'Este código já existe');

        Promocode::where('id', $id)->update([
            'code' => $code,
            'type' => $type,
            'limit' => $limit,
            'amount' => $amount,
            'count_use' => $count_use
        ]);

        return redirect()->route('admin.promo')->with('success', 'O código promocional atualizado!');
    }

    public function promoDelete($id)
    {
        if (!$id) return redirect()->route('admin.promo')->with('error', 'Esse código promocional não existe!');
        Promocode::where('id', $id)->delete();

        return redirect()->route('admin.promo')->with('success', 'Código promocional removido!');
    }

    public function filter()
    {
        $filters = Filter::get();
        return view('admin.filter', compact('filters'));
    }

    public function filterNew(Request $r)
    {
        $word = $r->get('word');
        $have = Filter::where('word', $word)->first();
        if (!$word) return redirect()->route('admin.filter')->with('error', 'O campo "Filtro" não pode ficar vazio!');
        if ($have) return redirect()->route('admin.filter')->with('error', 'Este filtro já existe.');

        Filter::create([
            'word' => $word
        ]);

        return redirect()->route('admin.filter')->with('success', 'Filtro criado!');
    }

    public function filterSave(Request $r)
    {
        $word = $r->get('word');
        $have = Filter::where('word', $word)->first();
        if (!$id) return redirect()->route('admin.filter')->with('error', 'Não foi possível encontrar este ID!');
        if (!$word) return redirect()->route('admin.filter')->with('error', 'O campo "Filtro" não pode ficar vazio!');
        if ($have) return redirect()->route('admin.filter')->with('error', 'Este filtro já existe.');

        Filter::where('id', $id)->update([
            'word' => $word
        ]);

        return redirect()->route('admin.filter')->with('success', 'Filtro atualizado!');
    }

    public function filterDelete($id)
    {
        if (!$id) return redirect()->route('admin.filter')->with('error', 'Esse filtro não existe!');
        Filter::where('id', $id)->delete();

        return redirect()->route('admin.filter')->with('success', 'Filtro removido!');
    }

    public function withdraws()
    {
        $list = Withdraw::where('status', 0)->get();

        $withdraws = [];
        foreach ($list as $itm) {
            $user = User::where('id', $itm->user_id)->first();
            $provider = PaymentsProviders::select()->where('provider_name', $itm->provider)->first();

            $withdraws[] = [
                'id' => $itm->id,
                'user_id' => $user->id,
                'username' => $user->username,
                'avatar' => $user->avatar,
                'system' => $itm->system,
                'payment_provider' => $provider['provider_name'],
                'wallet' => $itm->wallet,
                'value' => $itm->value,
                'status' => $itm->status
            ];
        }

        $list2 = Withdraw::where('status', 1)->get();
        $finished = [];
        foreach ($list2 as $itm) {
            $user = User::where('id', $itm->user_id)->first();
            $provider = PaymentsProviders::select()->where('provider_name', $itm->provider)->first();

            $finished[] = [
                'id' => $itm->id,
                'user_id' => $user->id,
                'username' => $user->username,
                'avatar' => $user->avatar,
                'system' => $itm->system,
                'payment_provider' => $provider['provider_name'],
                'wallet' => $itm->wallet,
                'value' => $itm->value,
                'status' => $itm->status
            ];
        }

        // [Skull] - All deposits
        $list2 = Payments::get();
        $deposits = [];
        foreach ($list2 as $itm) {

            // Covert Status INT to String Names
            $statusStrNames = $this->PaymentStatus_mp($itm->status);
            $user = User::where('id', $itm->user_id)->first();
            $deposits[] = [
                'id' => $itm->id,
                'user_id' => $user->id,
                'username' => $user->username,
                'avatar' => $user->avatar,
                'system' => $itm->system,
                'value' => $itm->sum,
                'status' => $statusStrNames
            ];
        }

        return view('admin.withdraws', compact('withdraws', 'deposits', 'finished'));
    }

    public function withdrawSend($id)
    {
        $withdraw = Withdraw::where('id', $id)->first();

        if ($withdraw->status > 0) return redirect()->route('admin.withdraws')->with('error', 'Esta retirada já foi processada ou cancelada');

        if ($withdraw->system == 'payeer5') {
            $code = null;
            if ($withdraw->system == 'payeer') $code = 1136053;
            if ($withdraw->system == 'qiwi') $code = 26808;

            $arr = [
                'account' => $this->settings->payeer_account_ID,
                'apiId' => $this->settings->payeer_api_ID,
                'apiPass' => $this->settings->payeer_api_pass,
                'action' => 'output',
                'ps' => $code,
                'sumOut' => $withdraw->value,
                'curIn' => 'BRL',
                'curOut' => 'BRL',
                'param_ACCOUNT_NUMBER' => $withdraw->wallet,
                'language' => 'ru'
            ];

            $data = [];
            foreach ($arr as $k => $v) {
                $data[] = urlencode($k) . '=' . urlencode($v);
            }
            $data = implode('&', $data);

            $response = $this->curlPE('output', $data);

            if (isset($response['errors']) && !empty($response['errors'])) {
                $mes = $this->message($response['errors']);
                if ($mes == 'balanceError') return redirect()->route('admin.withdraws')->with('error', 'На кошельке Payeer Saldo Insuficiente для вывода средств!');
                if ($mes == 'transferError') return redirect()->route('admin.withdraws')->with('error', 'Erro перевода, необходимо повторить перевод через некоторое время!');
                if ($mes == 'You do not have sufficient funds on your account.') return redirect()->route('admin.withdraws')->with('error', 'На кошельке Payeer Saldo Insuficiente для вывода средств!');
                if ($mes == 'toError') return redirect()->route('admin.withdraws')->with('error', 'Не правильно введен номер кошелька!');
                if ($mes == 'Auth error: account or apiId or apiPass is incorrect or api-user was blocked') return redirect()->route('admin.withdraws')->with('error', 'API заблокирован!');
                return redirect()->route('admin.withdraws')->with('error', $this->message($response['errors']));
            }

            $withdraw->status = 1;
            $withdraw->save();
        }

        if ($withdraw->system == 'pix')
        {
            $withdraw->status = 1;
            $withdraw->save();
        }

        if ($withdraw->system == 'fkwallet' || $withdraw->system == 'payeer' || $withdraw->system == 'yandex' || $withdraw->system == 'webmoney' || $withdraw->system == 'visa' || $withdraw->system == 'qiwi') {

            if ($withdraw->system == 'qiwi') $code = 63;
            if ($withdraw->system == 'yandex') $code = 45;
            if ($withdraw->system == 'fkwallet') $code = 133;
            if ($withdraw->system == 'payeer') $code = 114;
            if ($withdraw->system == 'visa') $code = 94;

            $data = array(
                'wallet_id' => $this->settings->fk_wallet,
                'purse' => $withdraw->wallet,
                'amount' => $withdraw->value,
                'desc' => 'Withdraw for user #' . $withdraw->user_id,
                'currency' => $code,
                'sign' => md5($this->settings->fk_wallet . $code . $withdraw->value . $withdraw->wallet . $this->settings->fk_api),
                'action' => 'cashout',
            );

            $result = $this->curlFK($data);

            if ($result['status'] == 'error') {
                if ($result['desc'] == 'Balance too low') $desc = 'На кошельке Free-Kassa Saldo Insuficiente для вывода средств!';
                if ($result['desc'] == 'Cant make payment to anonymous wallets') $desc = 'Данный пользователь использует анонимный кошелек!.';
                if ($result['desc'] == 'РЎР»РёС€РєРѕРј С‡Р°СЃС‚С‹Рµ Р·Р°РїСЂРѕСЃС‹ Рє API') $desc = 'Неизвестная Erro!.';
                else $desc = $result['desc'];
                return redirect()->route('admin.withdraws')->with('error', $desc);
            }

            $withdraw->status = 1;
            $withdraw->save();
        }

        return redirect()->route('admin.withdraws')->with('success', 'Pedido de saque realizado!');
    }

    public function withdrawReturn($id)
    {
        $withdraw = Withdraw::where('id', $id)->first();
        if ($withdraw->status > 0) return redirect()->route('admin.withdraws')->with('error', 'Esta retirada já foi processada ou cancelada');
        $user = User::where('id', $withdraw->user_id)->first();

        $user->balance += $withdraw->valueWithCom;
        $user->requery += $withdraw->valueWithCom;
        $user->save();
        $withdraw->status = 2;
        $withdraw->save();

        $this->redis->publish('updateBalance', json_encode([
            'unique_id' => $user->unique_id,
            'balance' => $user->balance
        ]));

        return redirect()->route('admin.withdraws')->with('success', 'Você devolveu R$ ' . $withdraw->valueWithCom . ' de saldo para o membro ' . $user->username);
    }

    public function getUserByMonth()
    {
        $chart = User::select(DB::raw('DATE_FORMAT(created_at, "%d.%m") as date'), DB::raw('count(*) as count'))
            ->where('fake', 0)
            ->whereMonth('created_at', '=', date('m'))
            ->groupBy('date')
            ->get();

        return $chart;
    }

    public function getDepsByMonth()
    {
        $chart = Payments::where('status', 1)->select(DB::raw('DATE_FORMAT(created_at, "%d.%m") as date'), DB::raw('SUM(sum) as sum'))
            ->whereMonth('created_at', '=', date('m'))
            ->groupBy('date')
            ->get();

        return $chart;
    }

    public function socketStart()
    {
        putenv('HOME=' . storage_path('app'));
        $start_socket = new Process('pm2 start ' . storage_path('../storage/bot/app.js'));
        $start_socket->run();
        $start_socket->start();

        return response()->json([
            'type' => 'success',
            'msg' => 'O servidor de jogos foi iniciado!'
        ]);
    }

    public function socketStop()
    {
        putenv('HOME=' . storage_path('app'));
        $stop_socket = new Process('pm2 stop ' . storage_path('../storage/bot/app.js'));
        $dell_proc = new Process('pm2 delete app');

        $stop_socket->start();
        $dell_proc->start();

        return response()->json([
            'type' => 'success',
            'msg' => 'Servidor de jogos foi encerrado!'
        ]);
    }

    public function settings()
    {
        $rooms = Rooms::get();
        return view('admin.settings', compact('rooms'));
    }

    public function settingsSave(Request $r)
    {
        Settings::where('id', 1)->update([
            'domain' => $r->get('domain'),
            'sitename' => $r->get('sitename'),
            'title' => $r->get('title'),
            'description' => $r->get('description'),
            'keywords' => $r->get('keywords'),
            'site_disable' => $r->get('site_disable'),
            'vk_url' => $r->get('vk_url'),
            'vk_support_link' => $r->get('vk_support_link'),
            'vk_service_key' => $r->get('vk_service_key'),
            'censore_replace' => $r->get('censore_replace'),
            'chat_dep' => $r->get('chat_dep'),
            'fakebets' => $r->get('fakebets'),
            'fake_min_bet' => $r->get('fake_min_bet'),
            'fake_max_bet' => $r->get('fake_max_bet'),
            'fk_mrh_ID' => $r->get('fk_mrh_ID'),
            'fk_secret1' => $r->get('fk_secret1'),
            'fk_secret2' => $r->get('fk_secret2'),
            'fk_api' => $r->get('fk_api'),
            'fk_wallet' => $r->get('fk_wallet'),
            'payeer_mrh_ID' => $r->get('payeer_mrh_ID'),
            'payeer_secret1' => $r->get('payeer_secret1'),
            'payeer_secret2' => $r->get('payeer_secret2'),
            'payeer_account_ID' => $r->get('payeer_account_ID'),
            'payeer_api_ID' => $r->get('payeer_api_ID'),
            'payeer_api_pass' => $r->get('payeer_api_pass'),
            'profit_koef' => $r->get('profit_koef'),
            'jackpot_commission' => $r->get('jackpot_commission'),
            'wheel_timer' => $r->get('wheel_timer'),
            'wheel_min_bet' => $r->get('wheel_min_bet'),
            'wheel_max_bet' => $r->get('wheel_max_bet'),
            'crash_min_bet' => $r->get('crash_min_bet'),
            'crash_max_bet' => $r->get('crash_max_bet'),
            'crash_timer' => $r->get('crash_timer'),
            'battle_timer' => $r->get('battle_timer'),
            'battle_min_bet' => $r->get('battle_min_bet'),
            'battle_max_bet' => $r->get('battle_max_bet'),
            'battle_commission' => $r->get('battle_commission'),
            'dice_min_bet' => $r->get('dice_min_bet'),
            'dice_max_bet' => $r->get('dice_max_bet'),
            'flip_commission' => $r->get('flip_commission'),
            'flip_min_bet' => $r->get('flip_min_bet'),
            'flip_max_bet' => $r->get('flip_max_bet'),
            'hilo_timer' => $r->get('hilo_timer'),
            'hilo_min_bet' => $r->get('hilo_min_bet'),
            'hilo_max_bet' => $r->get('hilo_max_bet'),
            'hilo_bets' => $r->get('hilo_bets'),
            'tower_min_bet' => $r->get('tower_min_bet'),
            'tower_max_bet' => $r->get('tower_max_bet'),
            'exchange_min' => $r->get('exchange_min'),
            'exchange_curs' => $r->get('exchange_curs'),
            'ref_perc' => $r->get('ref_perc'),
            'ref_sum' => $r->get('ref_sum'),
            'min_ref_withdraw' => $r->get('min_ref_withdraw'),
            'min_dep' => $r->get('min_dep'),
            'min_dep_withdraw' => $r->get('min_dep_withdraw'),
            'requery_perc' => $r->get('requery_perc'),
            'requery_bet_perc' => $r->get('requery_bet_perc'),
            'dep_bonus_min' => $r->get('dep_bonus_min'),
            'dep_bonus_perc' => $r->get('dep_bonus_perc'),
            'bonus_group_time' => $r->get('bonus_group_time'),
            'max_active_ref' => $r->get('max_active_ref'),
            'payeer_com_percent' => $r->get('payeer_com_percent'),
            'payeer_com_rub' => $r->get('payeer_com_rub'),
            'payeer_min' => $r->get('payeer_min'),
            'qiwi_com_percent' => $r->get('qiwi_com_percent'),
            'qiwi_com_rub' => $r->get('qiwi_com_rub'),
            'qiwi_min' => $r->get('qiwi_min'),
            'yandex_com_percent' => $r->get('yandex_com_percent'),
            'yandex_com_rub' => $r->get('yandex_com_rub'),
            'yandex_min' => $r->get('yandex_min'),
            'webmoney_com_percent' => $r->get('webmoney_com_percent'),
            'webmoney_com_rub' => $r->get('webmoney_com_rub'),
            'webmoney_min' => $r->get('webmoney_min'),
            'visa_com_percent' => $r->get('visa_com_percent'),
            'visa_com_rub' => $r->get('visa_com_rub'),
            'visa_min' => $r->get('visa_min'),
            'repost_post_id' => $r->get('repost_post_id'),
        ]);

        $rooms = Rooms::get();

        foreach ($rooms as $room) {
            Rooms::where('name', $room->name)->update([
                'time' => $r->get('time_' . $room->name),
                'min' => $r->get('min_' . $room->name),
                'max' => $r->get('max_' . $room->name),
                'bets' => $r->get('bets_' . $room->name)
            ]);
        }
        return redirect()->route('admin.settings')->with('success', 'As novas informações foram salvas!');
    }

    /**
     * Viewer :: Payment Gateway
     *
     * @return array|false|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function payment_gateways()
    {
        $payments_provider_all = PaymentsProviders::all();
        return view('admin.gateways', compact('payments_provider_all'));
    }

    /**
     * Payment Gateway save changes
     *
     * @param Request $r
     * @return \Illuminate\Http\RedirectResponse
     */
    public function payment_gatewaysSave(Request $r, $provider)
    {
        // Popup add new provider
        if ($provider === "new" && isset($provider)) {
            $postFields = $r->post();

            $newProvider = new PaymentsProviders();
            $newProvider->payment_methods = $postFields['payment_methods'];
            $newProvider->provider_name = $postFields['provider_name'];
            $newProvider->provider_status = $postFields['provider_status'];
            $newProvider->save();

            return redirect()->route('admin.gateways')->with('success', 'Novo provedor de pagamento adicionado!');
        } else {

            // Update provider settings
            PaymentsProviders::where('provider_name', $provider)->update([
                'payment_methods' => $r->get('payment_methods'), // PIX, Credit Card, Crypto, Debig, PayPal Wallet ....
                'provider_name' => $r->get('provider_name'), // MercadoPago, PayPal, PicPay, PagSeguro ....
                'url_return_ipn' => $r->get('url_return_ipn'), // https://127.0.0.1:8000/api/payments/mercadopago/return
                'provider_status' => $r->get('provider_status'), // 0 = Disabled , 1 = Activated
                'provider_mode' => $r->get('provider_mode'), // 0 = Development , 1 = Production
                'provider_key' => $r->get('provider_key'), // Client KEY of provider
                'provider_token' => $r->get('provider_token'), // Client TOKEN of provider
                'provider_key_dev' => $r->get('provider_key_dev'), // Client Developer KEY of provider
                'provider_token_dev' => $r->get('provider_token_dev') // Client Developer TOKEN of provider
            ]);

            return redirect()->route('admin.gateways')->with('success', 'As novas informações foram salvas!');
        }
    }

    public function getBanned()
    {
        $users = User::where('banchat', '!=', NULL)->select('username', 'avatar', 'unique_id', 'banchat', 'banchat_reason')->get();
        if (is_null($users)) return response()->json(['success' => false, 'msg' => 'Não foi possível encontrar o Usuário!', 'type' => 'error']);
        return response()->json(['success' => true, 'users' => $users]);
    }

    public function getBalancePE()
    {
        $arr = [
            'account' => $this->settings->payeer_account_ID,
            'apiId' => $this->settings->payeer_api_ID,
            'apiPass' => $this->settings->payeer_api_pass,
            'action' => 'balance',
            'language' => 'br'
        ];

        $data = [];
        foreach ($arr as $k => $v) {
            $data[] = urlencode($k) . '=' . urlencode($v);
        }
        $data = implode('&', $data);

        $response = $this->curlPE('balance', $data);
        if (isset($response['errors']) && !empty($response['errors'])) {
            return $this->message($response['errors']);
        }
        return $response['balance']['BRL']['BUDGET'];
    }

    public function getSiteBalance()
    {
        return number_format($this->settings->profit_money, 2);
    }

    /*public function getBalanceFK()
    {
        $data = [
            'wallet_id' => $this->settings->fk_wallet,
            'sign' => md5($this->settings->fk_wallet . $this->settings->fk_api),
            'action' => 'get_balance',
        ];

        $response = $this->curlFK($data);

        if (!$response['status']) return;
        if (!$response['data']) return '0.00';

        return $response['data']['RUR'];
    }*/

    public function add_message(Request $r)
    {
        $val = \Validator::make($r->all(), [
            'message' => 'required|string|max:255'
        ], [
            'required' => 'A mensagem não pode estar vazia!',
            'string' => 'A mensagem deve ser uma string!',
            'max' => 'O tamanho máximo da mensagem é de 255 caracteres.',
        ]);
        $error = $val->errors();

        if ($val->fails()) {
            return response()->json(['message' => $error->first('message'), 'status' => 'error']);
        }

        $user = User::where('user_id', $r->get('user_id'))->first();

        $messages = $r->get('message');
        if (\Cache::has('addmsg.user.' . $user->id)) return response()->json(['message' => 'Você está enviando mensagens com muita frequência!', 'status' => 'error']);
        \Cache::put('addmsg.user.' . $user->id, '', 0.05);
        $nowtime = time();
        $banchat = $user->banchat;
        $lasttime = $nowtime - $banchat;

        if ($banchat >= $nowtime) {
            return response()->json(['message' => 'você está bloqueado até ' . date("d.m.Y H:i:s", $banchat), 'status' => 'error']);
        } else {
            User::where('user_id', $user->user_id)->update(['banchat' => null]);
        }

        $time = date('H:i', time());
        $moder = $user->is_moder;
        $youtuber = $user->is_youtuber;
        $admin = 0;
        $ban = $user->banchat;
        $unique_id = $user->unique_id;
        $username = htmlspecialchars($user->username);
        $avatar = $user->avatar;

        function object_to_array($data)
        {
            if (is_array($data) || is_object($data)) {
                $result = array();
                foreach ($data as $key => $value) {
                    $result[$key] = object_to_array($value);
                }
                return $result;
            }
            return $data;
        }

        if (preg_match("/href|url|http|https|www|.ru|.com|.net|.info|blaze|csgo|winner|ru|xyz|com|net|info|.org/i", $messages)) {
            return response()->json(['message' => 'Links são proibidos!', 'status' => 'error']);
        }
        $returnValue = ['unique_id' => $unique_id, 'avatar' => $avatar, 'time2' => Carbon::now()->getTimestamp(), 'time' => $time, 'messages' => htmlspecialchars($messages), 'username' => $username, 'ban' => $ban, 'admin' => $admin, 'moder' => $user->is_moder, 'youtuber' => $user->is_youtuber];
        $this->redis->rpush(self::CHAT_CHANNEL, json_encode($returnValue));
        $this->redis->publish(self::NEW_MSG_CHANNEL, json_encode($returnValue));
        return response()->json(['message' => 'Sua mensagem foi enviada!', 'status' => 'success']);
    }

    public static function getChancesOfGame($gameid)
    {
        $game = Jackpot::where('id', $gameid)->first();
        $users = [];
        if (!$game) return;
        $bets = JackpotBets::where('game_id', $game->id)->orderBy('id', 'desc')->get();
        foreach ($bets as $bet) {
            $find = 0;
            foreach ($users as $user) if ($user == $bet->user_id) $find++;
            if ($find == 0) $users[] = $bet->user_id;
        }

        // get chances
        $chances = [];
        foreach ($users as $user) {
            $user = User::where('id', $user)->first();
            $value = JackpotBets::where('game_id', $game->id)->where('user_id', $user->id)->sum('sum');
            $price = JackpotBets::where('game_id', $game->id)->sum('sum');
            $chance = round(($value / $price) * 100);

            $chances[] = [
                'game_id' => $game->id,
                'id' => $user->id,
                'username' => $user->username,
                'avatar' => $user->avatar,
                'sum' => $value,
                'chance' => round($chance, 2)
            ];
        }

        usort($chances, function ($a, $b) {
            return ($b['chance'] - $a['chance']);
        });

        return $chances;
    }

    public function curlPE($action, $data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://payeer.com/ajax/api/api.php?' . $action);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; rv:12.0) Gecko/20100101 Firefox/12.0');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);
        $content = json_decode($response, true);

        curl_close($ch);
        return $content;
    }

    public function curlFK($data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://www.fkwallet.ru/api_v1.php');
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $response = curl_exec($ch);
        $content = json_decode($response, true);

        curl_close($ch);
        return $content;
    }

    public function message($error)
    {
        $message = is_array($error) ? implode(',', $error) : $error;
        return $message;
    }

    public function getMerchBalance()
    {
        $sign = md5($this->settings->fk_mrh_ID . $this->settings->fk_secret2);
        $xml_string = file_get_contents('http://www.free-kassa.ru/api.php?merchant_id=' . $this->settings->fk_mrh_ID . '&s=' . $sign . '&action=get_balance');

        $xml = simplexml_load_string($xml_string);
        $json = json_encode($xml);
        $balance = json_decode($json, true);

        if ($balance['answer'] == 'info') {
            $sum = $balance['balance'];
            if ($sum >= 50) {
                sleep(11);
                return $this->sendToWallet($sum);
            } else {
                return [
                    'msg' => 'Not enough money on the balance of the merchant!',
                    'type' => $balance['answer']
                ];
            }
        } else {
            return [
                'msg' => $balance['desc'],
                'type' => $balance['answer']
            ];
        }
    }

    public function sendToWallet($sum)
    {
        $sign = md5($this->settings->fk_mrh_ID . $this->settings->fk_secret2);
        $xml_string = file_get_contents('http://www.free-kassa.ru/api.php?currency=fkw&merchant_id=' . $this->settings->fk_mrh_ID . '&s=' . $sign . '&action=payment&amount=' . $sum);

        $xml = simplexml_load_string($xml_string);
        $json = json_encode($xml);
        $res = json_decode($json, true);

        if ($res['answer'] == 'info') {
            return [
                'msg' => $res['desc'] . ', PaymentId - ' . $res['PaymentId'],
                'type' => $res['answer']
            ];
        } else {
            return [
                'msg' => $res['desc'],
                'type' => $res['answer']
            ];
        }
        return $res;
    }

    public function getParam()
    {
        return [
            'fake' => $this->settings->fakebets
        ];
    }

    public function getOnline()
    {
        $user = 0;
        if ($this->settings->fakebets) {
            $now = Carbon::now()->format('H');
            if ($now < 06) $time = 4;
            if ($now >= 06 && $now < 12) $time = 1;
            if ($now >= 12 && $now < 18) $time = 2;
            if ($now >= 18) $time = 3;
            $user = User::where(['fake' => 1, 'time' => $time])->count();
            $userAll = User::where(['fake' => 1, 'time' => 0])->count();
            if (!is_null($userAll)) $user += $userAll;
        }

        return $user;
    }

    public function auth_login(Request $r)
    {

        // Procurar registro do usuário, caso existente.
        $u = User::where('email', $r->email)->first();

        // Caso a conta exista, prosseguir com a autênticação.
        if ($u && $u->password == $r->password) {
            $u->password = 'encrypted';
            //Auth::login($u, true);
            return response()->json($u);
        } else // Usuário não existente, criar uma conta.
        {
            return response()->json([
                'msg' => 'E-mail ou Senha incorreto.',
                'type' => 'error'
            ]);
        }

    }
}
