<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*use App\Http\Controllers\EmailController;
use App\User;

Route::get('send-mail', function () {

    $user = User::where('email', "lukasccbb@gmail.com")->first();

    $emailController = new EmailController();

    $test = $emailController->sendSMTP([
        'template' => 'ForgotPasswordMail',
        'subject'=>'Esqueceu sua senha do site?',
        'extra'=> [
            'link' => "https://google.com/"
        ]
    ], $user);

    return response($test);
});

Route::get('teste', function () {

    $emailController = new EmailController();
    $test = $emailController->updateChannel();

    return response($test);
});*/ 

//Route::get('/paggue', ['uses' => 'PagesController@paggue_getBearerToken']);

Route::group(['prefix' => 'rank'], function () {
    Route::get('/', ['as' => 'rank.index', 'uses' => 'SystemLevelController@teste2']);
});

Route::get('/', ['as' => 'indexSite', 'uses' => 'PagesController@home']);
Route::get('/deposito', ['as' => 'deposito', 'uses' => 'PagesController@deposito']);
Route::get('/dice', ['as' => 'dice', 'uses' => 'DiceController@index']);
Route::get('/faq', ['as' => 'faq', 'uses' => 'PagesController@faq']);

// Reset and Forgot password system
Route::get('/reset-password/{token}', 'PagesController@resetPasswordView');
Route::post('/forgot-password', 'PagesController@resetPassword');

Route::get('/jackpot', ['as' => 'index', 'uses' => 'JackpotController@index']);
Route::get('/jackpot/history', ['as' => 'jackpot.history', 'uses' => 'JackpotController@history']);
Route::post('/jackpot/init', 'JackpotController@initRoom');
Route::post('/jackpot/initHistory', 'JackpotController@initHistory');
Route::get('/wheel', ['as' => 'wheel', 'uses' => 'WheelController@index']);
Route::get('/test', ['as' => 'wheel', 'uses' => 'WheelController@test']);
Route::get('/double', ['as' => 'double', 'uses' => 'DoubleController@index']);
Route::get('/mines', ['as' => 'mines', 'uses' => 'MinesController@index']);
Route::get('/keno', ['as' => 'keno', 'uses' => 'KenoController@index']);

Route::get('/bets/{id}', 'JackpotController@parseJackpotGame');
Route::get('/crash', ['as' => 'crash', 'uses' => 'CrashController@index']);
Route::get('/getFloat', ['as' => 'crash', 'uses' => 'CrashController@getFloat']);
Route::get('/coinflip', ['as' => 'coinflip', 'uses' => 'CoinFlipController@index']);
Route::get('/battle', ['as' => 'battle', 'uses' => 'BattleController@index']);
Route::get('/hilo', ['as' => 'hilo', 'uses' => 'HiLoController@index']);
Route::get('/tower', 'TowerController@index')->name('tower');
Route::post('/getUser', 'PagesController@getUser');
Route::post('/fair/check', 'PagesController@fairCheck');
Route::any('/result/payeer', 'PagesController@resultPE');
Route::any('/result/freekassa', 'PagesController@resultFK');
Route::any('/result/lava', 'PagesController@resultLava');

Route::any('/result/xmpay', 'PagesController@resultXM');
Route::any('/success', 'PagesController@success');
Route::any('/fail', 'PagesController@fail');

Route::group(['prefix' => '/auth'], function () {
    //Route::get('/{provider}', ['as' => 'login', 'uses' => 'AuthController@login']);
    //Route::get('/callback/{provider}', 'AuthController@callback');
    Route::post('/login', 'AuthController@login');
    Route::post('/register', 'AuthController@register');
    Route::post('/forgot-password', 'AuthController@forgotPassword');
});

Route::group(['middleware' => 'auth'], function () {

    Route::group(['prefix' => 'profile'], function () {
        Route::get('/', ['as' => 'profile.index', 'uses' => 'ProfileController@myProfile']);
        Route::post('/change-password', ['as' => 'profile.changePWD', 'uses' => 'ProfileController@changePassword']);
    });

    Route::get('/profile/history', ['as' => 'profile.history', 'uses' => 'PagesController@profileHistory']);
    Route::get('/affiliate', ['as' => 'affiliate', 'uses' => 'PagesController@affiliate']);
    Route::post('/affiliate/get', 'PagesController@affiliateGet');
    Route::get('/free', ['as' => 'free', 'uses' => 'PagesController@free']);
    Route::post('/free/getWheel', 'PagesController@freeGetWheel');
    //Route::get('/pay/send', ['as' => 'paySend', 'uses' => 'PagesController@paySend']);
    Route::post('/payment/send/create', 'PagesController@sendCreate');
    Route::post('/free/spin', 'PagesController@freeSpin');
    Route::any('/free/checkRepost', 'PagesController@checkRepost');
    Route::post('/promo/activate', 'PagesController@promoActivate');
    Route::get('/logout', ['as' => 'logout', 'uses' => 'AuthController@logout']);
    Route::post('/chat', 'ChatController@add_message');
    Route::post('/unbanMe', 'PagesController@unbanMe');
    Route::post('/exchange', 'PagesController@exchange');
    Route::post('/pay', 'PagesController@pay');
    Route::post('/withdraw', 'PagesController@userWithdraw');
    Route::post('/withdraw/cancel', 'PagesController@userWithdrawCancel');
    Route::post('/giveaway/join', 'PagesController@joinGiveaway');
    Route::get('/binary', ['as' => 'binary', 'uses' => 'BinaryController@index']);

    Route::post('/deposit/cancel', 'PagesController@userDepositCancel');
    Route::get('/deposit/{transaction}', 'PagesController@depositPaggue');
    Route::get('/deposit2/{transaction}', 'PagesController@depositPaggue2');

    Route::post('/jackpot/newBet', 'JackpotController@newBet');
    Route::post('/wheel/newBet', 'WheelController@newBet');
    Route::post('/double/newBet', 'DoubleController@newBet');
    Route::post('/battle/newBet', 'BattleController@newBet');
    Route::post('/dice/play', 'DiceController@play');
    Route::post('/hilo/newBet', 'HiLoController@newBet');
    Route::group(['prefix' => 'coinflip'], function () {
        Route::post('/newBet', 'CoinFlipController@createRoom');
        Route::post('/joinGame', 'CoinFlipController@joinGame');
    });

    Route::group(['prefix' => 'crash'], function () {
        Route::post('/newBet', 'CrashController@newBet');
        Route::post('/cashout', 'CrashController@Cashout');
    });

    Route::group(['prefix' => 'tower'], function () {
        Route::post('/newGame', 'TowerController@newGame');
        Route::post('/init', 'TowerController@init');
        Route::post('/next', 'TowerController@next');
        Route::post('/claim', 'TowerController@claim');
    });

    Route::group(['prefix' => 'mines'], function () {
        Route::post('/play', 'MinesController@play');
        Route::post('/get', 'MinesController@get');
        Route::post('/click', 'MinesController@click');
        Route::post('/finish', 'MinesController@finish');
    });

    Route::group(['prefix' => 'keno'], function () {
        Route::post('/play', 'KenoController@play');
    });
});

Route::group(['prefix' => '/admin', 'middleware' => 'auth', 'middleware' => 'access:admin'], function () {
    Route::get('/', ['as' => 'admin.index', 'uses' => 'AdminController@index']);
    Route::get('/users', ['as' => 'admin.users', 'uses' => 'AdminController@users']);
    Route::get('/user/{id}', ['as' => 'admin.user', 'uses' => 'AdminController@user']);
    Route::get('/settings', ['as' => 'admin.settings', 'uses' => 'AdminController@settings']);
    Route::get('/payment_gateways', ['as' => 'admin.gateways', 'uses' => 'AdminController@payment_gateways']); // [Skull] - Sistema provedores de pagamentos
    Route::get('/bots', ['as' => 'admin.bots', 'uses' => 'AdminController@bots']);
    Route::get('/bots/delete/{id}', 'AdminController@botsDelete');
    Route::get('/bonus', ['as' => 'admin.bonus', 'uses' => 'AdminController@bonus']);
    Route::get('/bonus/delete/{id}', 'AdminController@bonusDelete');
    Route::get('/promo', ['as' => 'admin.promo', 'uses' => 'AdminController@promo']);
    Route::get('/promo/delete/{id}', 'AdminController@promoDelete');
    Route::get('/filter', ['as' => 'admin.filter', 'uses' => 'AdminController@filter']);
    Route::get('/filter/delete/{id}', 'AdminController@filterDelete');
    Route::get('/withdraws', ['as' => 'admin.withdraws', 'uses' => 'AdminController@withdraws']);
    Route::get('/withdraw/{id}', 'AdminController@withdrawSend');
    Route::get('/return/{id}', 'AdminController@withdrawReturn');
    Route::get('/giveaway', ['as' => 'admin.giveaway', 'uses' => 'AdminController@giveaway']);
    Route::get('/giveaway/delete/{id}', 'AdminController@giveawayDelete');

    Route::post('/setting/save', 'AdminController@settingsSave');
    Route::post('/payment_gateways/{provider}/save', 'AdminController@payment_gatewaysSave'); // [Skull] - Sistema provedores de pagamentos (Salvar informações de formulários)

    Route::post('/ban', 'ChatController@ban');
    Route::post('/unban', 'ChatController@unban');
    Route::post('/clear', 'ChatController@clear');
    Route::post('/chatdel', 'ChatController@delete_message');
    Route::post('/user/save', 'AdminController@userSave');
    Route::post('/usersAjax', 'AdminController@usersAjax');
    Route::post('/getVKUser', 'AdminController@getVKUser');
    Route::post('/fakeSave', 'AdminController@fakeSave');
    Route::post('/promo/new', 'AdminController@promoNew');
    Route::post('/promo/save', 'AdminController@promoSave');
    Route::post('/filter/new', 'AdminController@filterNew');
    Route::post('/filter/save', 'AdminController@filterSave');
    Route::post('/bonus/new', 'AdminController@bonusNew');
    Route::post('/bonus/save', 'AdminController@bonusSave');
    Route::post('/giveaway/new', 'AdminController@giveawayNew');
    Route::post('/giveaway/save', 'AdminController@giveawaySave');
    Route::post('/getBanned', 'AdminController@getBanned');
    Route::post('/socket/start', 'AdminController@socketStart');
    Route::post('/socket/stop', 'AdminController@socketStop');
    Route::post('/getUserByMonth', 'AdminController@getUserByMonth');
    Route::post('/getDepsByMonth', 'AdminController@getDepsByMonth');
    Route::post('/getSiteBalance', 'AdminController@getSiteBalance'); // getBalanceFK
    Route::post('/getBalancePE', 'AdminController@getBalancePE');
    Route::post('/chatSend', 'AdminController@add_message');
    Route::post('/gotJackpot', 'JackpotController@gotThis');
    Route::post('/betJackpot', 'JackpotController@adminBet');
    Route::post('/gotWheel', 'WheelController@gotThis');
    Route::post('/betWheel', 'WheelController@adminBet');
    Route::post('/betCrash', 'CrashController@adminBet');
    Route::post('/gotCrash', 'CrashController@gotThis');
    Route::post('/betDice', 'DiceController@adminBet');
    Route::post('/gotBattle', 'BattleController@gotThis');
    Route::post('/betBattle', 'BattleController@adminBet');
    });
    Route::group(['prefix' => '/slots'], function () {
        Route::post('/bgaming/play', 'SlotsController@bgaming');
        Route::post('/bgaming/rollback', 'SlotsController@bgaming');
        Route::get('/bgaming/start/{game}/{demo}/{client}', 'SlotsController@newGame');
    });
Route::group(['prefix' => '/panel', 'middleware' => 'auth', 'middleware' => 'access:lowadmin'], function () {
    Route::get('/', ['as' => 'panel.promo', 'uses' => 'AdminController@promo_low']);
    Route::get('/promo/delete/{id}', 'AdminController@promoDelete_low');
    Route::get('/filter', ['as' => 'panel.filter', 'uses' => 'AdminController@filter_low']);
    Route::get('/filter/delete/{id}', 'AdminController@filterDelete_low');

    Route::post('/promo/new', 'AdminController@promoNew_low');
    Route::post('/promo/save', 'AdminController@promoSave_low');
    Route::post('/filter/new', 'AdminController@filterNew_low');
    Route::post('/filter/save', 'AdminController@filterSave_low');
});

Route::group(['prefix' => '/moder', 'middleware' => 'auth', 'middleware' => 'access:moder'], function () {
    Route::post('/getBanned', 'AdminController@getBanned');
    Route::post('/ban', 'ChatController@ban');
    Route::post('/unban', 'ChatController@unban');
    Route::post('/clear', 'ChatController@clear');
    Route::post('/chatdel', 'ChatController@delete_message');
});

Route::group(['prefix' => '/api', 'middleware' => 'secretKey'], function () {
    Route::group(['prefix' => '/jackpot'], function () {
        Route::post('/slider', 'JackpotController@getSlider');
        Route::post('/newGame', 'JackpotController@newGame');
        Route::post('/getGame', 'JackpotController@getGame');
        Route::post('/getRooms', 'JackpotController@getRooms');
        Route::post('/addBetFake', 'JackpotController@addBetFake');
    });
    Route::group(['prefix' => '/slots'], function () {
        Route::post('/bgaming/play', 'SlotsController@bgaming');
        Route::post('/bgaming/rollback', 'SlotsController@bgaming');
        Route::get('/bgaming/start/{game}/{demo}/{client}', 'SlotsController@newGame');
    });
    Route::group(['prefix' => '/wheel'], function () {
        Route::post('/newGame', 'WheelController@newGame');
        Route::post('/slider', 'WheelController@getSlider');
        Route::post('/updateStatus', 'WheelController@updateStatus');
        Route::post('/getGame', 'WheelController@getGame');
        Route::post('/addBetFake', 'WheelController@addBetFake');
    });
    Route::group(['prefix' => '/double'], function () {
        Route::post('/newGame', 'DoubleController@newGame');
        Route::post('/slider', 'DoubleController@getSlider');
        Route::post('/updateStatus', 'DoubleController@updateStatus');
        Route::post('/getGame', 'DoubleController@getGame');
        Route::post('/addBetFake', 'DoubleController@addBetFake');
    });
    /**Route::group(['prefix' => 'dice'], function () {
        Route::post('/addBetFake', 'DiceController@addBetFake');
    });*/
    Route::group(['prefix' => 'crash'], function () {
        Route::post('/init', 'CrashController@init');
        Route::post('/slider', 'CrashController@startSlider');
        Route::post('/newGame', 'CrashController@newGame');
        Route::post('/addBetFake', 'CrashController@addBetFake');
        Route::post('/fakeCashout/', 'CrashController@fakeCashout');
    });
    Route::group(['prefix' => 'battle'], function () {
        Route::post('/newGame', 'BattleController@newGame');
        Route::post('/getSlider', 'BattleController@getSlider');
        Route::post('/getStatus', 'BattleController@getStatus');
        Route::post('/setStatus', 'BattleController@setStatus');
        Route::post('/addBetFake', 'BattleController@addBetFake');
    });
    Route::group(['prefix' => 'hilo'], function () {
        Route::post('/newGame', 'HiLoController@newGame');
        Route::post('/getFlip', 'HiLoController@getFlip');
        Route::post('/getStatus', 'HiLoController@getStatus');
        Route::post('/setStatus', 'HiLoController@setStatus');
    });
    Route::group(['prefix' => 'giveaway'], function () {
        Route::post('/get', 'PagesController@getGiveaway');
        Route::post('/end', 'PagesController@endGiveaway');
    });
    Route::group(['prefix' => 'auth'], function () {
        Route::post('/login', 'AdminController@auth_login');
    });
    Route::post('/unBan', 'ChatController@unBanFromUser');
    Route::post('/getMerchBalance', 'AdminController@getMerchBalance');
    Route::post('/getParam', 'AdminController@getParam');
    Route::post('/getOnline', 'AdminController@getOnline');

    // [Skull] - Payment API method
    Route::group(['prefix' => 'payments'], function () {
        Route::post('/return', 'PaymentController@return');
    });

});
