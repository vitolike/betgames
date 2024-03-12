<?php namespace App\Http\Controllers;

use App\User;
use Auth;
use DB;

//use http\Env\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

use App\Http\Controllers\EmailController;

class AuthController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function login(Request $request)
    {
        // Validate if request is fom Ajax
        if (!$request->ajax()) return response()->json(['success' => false, 'msg' => 'Request need be ajax!', 'type' => 'error'], 400);

        $email = $request->get('email');
        $PostPassword = $request->get('password');

        if (!$user = User::where('email', $email)->first()) {
            return response()->json([
                'success' => false,
                'type' => 'error',
                'input_error' => [
                    'email' => 'E-mail não encontrado ou inválido!'
                ]
            ], 404);
        }

        if (is_null($user)) {
            return response()->json([
                'success' => false,
                'type' => 'error',
                'input_error' => [
                    'others' => 'Usuário não existe!'
                ]
            ], 404);
        }

        // Check if password is match.
        if (!Hash::check($PostPassword, $user->password)) {
            return response()->json([
                'success' => false,
                'type' => 'error',
                'input_error' => [
                    'password' => 'Senha de acesso incorreto!'
                ]
            ], 400);
        }


        Auth::login($user, true);

        return response()->json(['success' => true, 'msg' => 'Logado com sucesso!', 'type' => 'success', 'url' => '/', 'user' => [
            $user
        ]], 200);
    }

    public function register(Request $request)
    {
        // Validate if request is fom Ajax
        if (!$request->ajax())  return response()->json(['success' => false, 'msg' => 'Request need be ajax!', 'type' => 'error'], 400);

        $email = $request->get('email');
        $real_name = $request->get('first_name') . ' ' . $request->get('last_name');
        $username = $request->get('username');
        $number_sms = $this->formatPhoneNumber($request->get('number_sms'));
        $PostPassword = Hash::make($request->get('password'));

        $rules = [
            'username' => 'required|unique:users,username',
            'first_name' => 'required',
            'last_name' => 'required',
            'number_sms' => 'required|unique:users,number_phone',
            'email' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            $errors = $validator->errors();

            $inputErrors = [
                'success' => false,
                'type' => 'error'
            ];

            foreach ($errors->messages() as $field => $messages) {
                foreach ($messages as $message) {
                    $inputErrors['input_error'] = [
                        $field => $message
                    ];
                }
            }

            return response()->json($inputErrors, 400);
        }

        $user = User::where('email', $email)->first();

        // Check if this phone already used
        //$checkPhone = User::where('number_phone', $number_sms)->first();

        if (is_null($PostPassword) || empty($PostPassword)) return response()->json([
            'success' => false,
            'type' => 'error',
            'input_error' => [
                'password' => 'Digite uma senha válida'
            ]
        ], 400);

        if (is_null($user)) {

            // Generate own ref code
            $gen_unique_id = User::generateRef();

            // Identifier ref guest
            $getRefCookie = Cookie::get('ref');

            if ($getRefCookie) {

                $userRef = User::where('unique_id', $getRefCookie)->first(); // Get user of this Ref
                $userRef->link_reg += 1;
                $userRef->save();

                $user = User::create([
                    'unique_id' => $gen_unique_id,
                    'email' => $email,
                    'real_name' => $real_name,
                    'username' => $username,
                    'password' => $PostPassword,
                    'number_phone' => $number_sms,
                    'ref_id' => $getRefCookie
                ]);
            } else {
                $user = User::create([
                    'unique_id' => $gen_unique_id,
                    'email' => $email,
                    'real_name' => $real_name,
                    'username' => $username,
                    'number_phone' => $number_sms,
                    'password' => $PostPassword
                ]);
            }

            /*$emailController = new EmailController();
            $emailController->sendSMTP([
                'template' => 'WelcomeMail',
                'subject'=>'Seja Bem Vindo(a) Betfyre',
                'category'=>'Welcome Email',
                'extra'=> [
                    'support_email' => 'contato@betfyre.com'
                ]
            ], $user);*/

            Auth::login($user, true);

            return response()->json(['success' => true, 'msg' => 'Sua conta foi criado!', 'type' => 'success', 'url' => '/', 'user' => [
                $user
            ]], 200);

        } /*else if (!is_null($checkPhone)) {
            return response()->json([
                'success' => false,
                'type' => 'error',
                'input_error' => [
                    'number_sms' => $number_sms . ' indisponivel, tente outro!'
                ]
            ], 400);
        }*/ else if (!is_null($user)) {
            return response()->json([
                'success' => false,
                'type' => 'error',
                'input_error' => [
                    'email' => 'E-mail já cadastrado!'
                ]
            ], 400);
        } else {
            return response()->json([
                'success' => false,
                'type' => 'error',
                'input_error' => [
                    'others' => 'Erro interno, contate o suporte!'
                ]
            ], 400);
        }
    }

    public function forgotPassword()
    {
        $email = $_POST['email'];

        $user = User::where('email', $email)->first();

        if (is_null($user))
            return redirect('/')->with('error', 'E-mail não cadastrado!');

        $gen_unique_token = User::generateRef(26);
        //$cookie = Cookie::make('token_password', $gen_unique_token, 360);
        $url = 'https://betfyre.com/reset-password/' . $gen_unique_token;

        DB::beginTransaction();
        try {

            $user->token_new_pass = $gen_unique_token;
            $user->save();

            DB::commit();

            //$emailController = new EmailController();
            (new EmailController)->sendSMTP([
                'template' => 'ForgotPasswordMail',
                'subject'=>'Esqueceu sua senha?',
                'category'=>'Forgot Password',
                'extra'=> [
                    'link' => $url
                ]
            ], $user);

            //return redirect('/')->with('error', 'Sistema em manutenção.');
            return redirect('/')->with('success', 'E-mail enviado, aguarde até 10 minutos para recebimento.');

        } catch (\PDOException $e) {
            DB::rollback();
            return redirect('/')->with('error', 'Erro de congestionamento, tente mais tarde!');
        }

        return redirect('/')->with('success', 'E-mail enviado com sucesso!');
    }

    public function logout()
    {
        Cache::flush();
        Auth::logout();
        Session::flush();
        return redirect()->intended('/');
    }

    public function formatPhoneNumber($phoneNumber)
    {

        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

        if (strlen($phoneNumber) > 10) {
            $countryCode = substr($phoneNumber, 0, strlen($phoneNumber) - 10);
            $areaCode = substr($phoneNumber, -10, 3);
            $nextThree = substr($phoneNumber, -7, 3);
            $lastFour = substr($phoneNumber, -4, 4);

            $phoneNumber = '' . $countryCode . '' . $areaCode . '' . $nextThree . '' . $lastFour;
        } else if (strlen($phoneNumber) == 10) {
            $areaCode = substr($phoneNumber, 0, 3);
            $nextThree = substr($phoneNumber, 3, 3);
            $lastFour = substr($phoneNumber, 6, 4);

            $phoneNumber = '' . $areaCode . '' . $nextThree . '' . $lastFour;
        } else if (strlen($phoneNumber) == 7) {
            $nextThree = substr($phoneNumber, 0, 3);
            $lastFour = substr($phoneNumber, 3, 4);

            $phoneNumber = $nextThree . '' . $lastFour;
        }

        return $phoneNumber;
    }
}
