<?php namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\User;
use App\Profit;
use Auth;

//use Illuminate\Http\Request;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function login()
    {
        $email = $_POST['email'];
        $PostPassword = encrypt($_POST['password']); // Crypt the password

        $user = User::where('email', $email)->first();

        if (is_null($user))
            return redirect('/')->with('error', 'Usuário não existe!');

        // Check if password is match.
        $checkPass = User::checkPass($user->email, $PostPassword);
        if (!$checkPass)
            return redirect('/')->with('error', 'Senha de acesso incorreto!');

        Auth::login($user, true);
        return redirect('/')->with('success', 'Logado com sucesso!');
    }

    public function register()
    {
        $email = $_POST['email'];
        $real_name = $_POST['real_name'];
        $username = $_POST['username'];
        $PostPassword = encrypt($_POST['password']); // Crypt the password

        $user = User::where('email', $email)->first();

        // Se não existe uma conta, então criar uma
        if (is_null($user))
        {
            $gen_unique_id = User::generateRef();

            $user = User::create([
                'unique_id' => $gen_unique_id,
                'email' => $email,
                'real_name' => $real_name,
                'username' => $username,
                'password' => $PostPassword
            ]);

            Auth::login($user, true);
            return redirect('/')->with('success', 'Logado com sucesso!');
        }
        else if ($user->email == $email)
        {
            return redirect('/')->with('error', 'E-mail já cadastrado!');
        }
        else{
            return redirect('/')->with('error', 'Erro interno, contate o suporte!');
        }
    }

    public function logout()
    {
		Cache::flush();
        Auth::logout();
		Session::flush();
        return redirect()->intended('/');
    }
}
