<?php namespace App\Http\Controllers;

use App\SystemLevel;
use App\User;
use App\Jackpot;
use App\Wheel;
use App\Crash;
use App\CoinFlip;
use App\Battle;
use App\Dice;
use App\Settings;
use App\PaymentsProviders;
use App\Withdraw;
use App\Giveaway;
use App\GiveawayUsers;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Redis;
use Auth;
use DB;
use Carbon\Carbon;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            view()->share('u', $this->user);
            return $next($request);
        });
        Carbon::setLocale('pt_BR');
        $this->redis = Redis::connection();
        $this->settings = Settings::first();
        view()->share('gws', $this->getWithSettings());
        view()->share('gives', self::getGiveaway());
        view()->share('messages', $this->chatMessage());
        view()->share('stats', $this->stats());
        view()->share('settings', $this->settings);
    }

    public function getWithSettings()
    {
        $settings = Settings::where('id', 1)
            ->select('vk_url', 'bonus_group_time', 'max_active_ref', 'exchange_min', 'exchange_curs', 'payeer_com_percent', 'payeer_com_rub', 'payeer_min', 'qiwi_com_percent', 'qiwi_com_rub', 'qiwi_min', 'yandex_com_percent', 'yandex_com_rub', 'yandex_min', 'webmoney_com_percent', 'webmoney_com_rub', 'webmoney_min', 'visa_com_percent', 'visa_com_rub', 'visa_min')->first();
        return $settings;
    }

    public function chatMessage()
    {
        $messages = ChatController::chat();
        return $messages;
    }

    public function stats()
    {
        $countUsers = User::count();
        $countUsersToday = User::where('created_at', '>=', Carbon::today()->setTimezone('America/Sao_Paulo'))->count();
        $jackpot = Jackpot::where('status', 3)->orderBy('id', 'desc')->count();
        $wheel = Wheel::where('status', 3)->orderBy('id', 'desc')->count();
        $crash = Crash::where('status', 2)->orderBy('id', 'desc')->count();
        $coin = CoinFlip::where('status', 1)->orderBy('id', 'desc')->count();
        $battle = Battle::where('status', 3)->orderBy('id', 'desc')->count();
        $dice = Dice::orderBy('id', 'desc')->count();
        $totalGames = $jackpot + $wheel + $crash + $coin + $battle + $dice;
        $totalWithdraw = Withdraw::where('status', 1)->sum('value');

        $data = [
            'countUsers' => $countUsers,
            'countUsersToday' => $countUsersToday,
            'totalGames' => $totalGames,
            'totalWithdraw' => $totalWithdraw
        ];
        return $data;
    }

    public function getGiveaway()
    {
        $giveaways = Giveaway::orderBy('id', 'desc')->where('status', 0)->get();
        $giveaways_end = Giveaway::orderBy('id', 'desc')->where('status', 1)->limit(3)->get();
        $list = [];
        foreach ($giveaways as $gv) {
            $users = GiveawayUsers::where('giveaway_id', $gv->id)->count();
            $time = $gv->time_to - time();
            $gv->total = $users;
            $gv->time_to = Carbon::parse($time)->format('H:i:s');
            $gv->winner = ($gv->winner_id ? User::where('id', $gv->winner_id)->first() : null);
        }
        foreach ($giveaways_end as $gv) {
            $users = GiveawayUsers::where('giveaway_id', $gv->id)->count();
            $time = $gv->time_to - time();
            $gv->total = $users;
            $gv->time_to = Carbon::parse($time)->format('H:i:s');
            $gv->winner = ($gv->winner_id ? User::where('id', $gv->winner_id)->first() : null);
        }

        $giveaways = $giveaways->merge($giveaways_end);
        return $giveaways;
    }

    public function probability($n1)
    {
        $list = [];
        for ($i = 0; $i < $n1; $i++) $list[] = true;
        for ($i = 0; $i < (100 - $n1); $i++) $list[] = false;
        shuffle($list);
        return $list[mt_rand(0, count($list) - 1)];
    }

    /**
     * Get status of payment in String using ID
     * > Valid for MercadoPago (https://www.mercadopago.com.br/developers/pt/docs/woocommerce/payment-status)
     *
     * @param int $status
     * @return string
     */
    public function PaymentStatus_mp(int $status)
    {
        switch ($status) {
            case "0":
                $name = "Pendente";
                break;
            case "1":
                $name = "Aprovado";
                break;
            case "2":
                $name = "Em Processamento";
                break;
            case "3":
                $name = "Aguardando Analise";
                break;
            case "4":
                $name = "Rejeitado";
                break;
            case "5":
                $name = "Cancelado";
                break;
            case "6":
                $IDs = "Devolvido";
                break;
            case "7":
                $name = "Estornado";
                break;
        }

        return $name;
    }

    /**
     * Send cURL
     *
     * @param array $data
     * @return mixed
     */
    public function SendCURL(array $data, $json_decode = true)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $data['url']);

        if (isset($data['post_fields']))
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data['post_fields']));

        if (isset($data['isPOST']))
            curl_setopt($ch, CURLOPT_POST, true);
        else
            curl_setopt($ch, CURLOPT_POST, false);

        if (isset($data['isPUT']))
            curl_setopt($ch, CURLOPT_PUT, true);
        else
            curl_setopt($ch, CURLOPT_PUT, false);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-type: application/json',
            'Authorization: Bearer ' . $data['client_token']
        ]);
        $response = curl_exec($ch);
        curl_close($ch);

        if ($json_decode === true)
            return json_decode($response, false);
        else
            return $response;
    }

    /**
     * Validar CPF
     *
     * @param $cpf
     * @return bool
     */
    function validateCPF($cpf) {

        // Elimina possivel mascara
        $cpf = preg_replace("/[^0-9]/", "", $cpf);
        $cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);

        // Verifica se foi informado todos os digitos corretamente
        if (strlen($cpf) != 11) {
            return false;
        }

        // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        // Faz o calculo para validar o CPF
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }
        return true;
    }

    /**
     * Formatar CPF
     *
     * @param $cpf
     * @return string
     */
    function formatCPF($cpf) {

        if(!validaCPF($cpf))
        {
            return 'Documento invalido';
        }

        $doc = preg_replace("/[^0-9]/", "", $cpf);
        $qtd = strlen($doc);

        if($qtd >= 11) {

            if($qtd === 11 ) {

                $docFormatado = substr($doc, 0, 3) . '.' .
                    substr($doc, 3, 3) . '.' .
                    substr($doc, 6, 3) . '.' .
                    substr($doc, 9, 2);
            } else {
                $docFormatado = substr($doc, 0, 2) . '.' .
                    substr($doc, 2, 3) . '.' .
                    substr($doc, 5, 3) . '/' .
                    substr($doc, 8, 4) . '-' .
                    substr($doc, -2);
            }

            return $docFormatado;

        } else {
            return 'Documento invalido';
        }
    }
}
