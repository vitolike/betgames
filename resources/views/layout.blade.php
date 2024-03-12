
<script src="https://code.jquery.com/jquery-3.3.1.min.js"
integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
crossorigin="anonymous">
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.1.1/socket.io.js"></script>
@if(!Auth::check() && $settings->site_disable || $settings->site_disable && Auth::check() && !$u->is_admin)

    <!DOCTYPE html>
<html>
<head>
    <title>{{$settings->title}}</title>
    <meta charset="utf-8">
    <meta content="ie=edge" http-equiv="x-ua-compatible">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <link href="/favicon.ico" rel="shortcut icon">

    <link href="css/pre.css" rel="stylesheet">

</head>
<body>
<div class="logo">
    <img src="/img/logo.png" alt="">
    <span class="title">SITE EM MANUTENÇÃO</span>
</div>
</body>
</html>

@else

    @if(Auth::user() && $u->ban)
        <!DOCTYPE html>
        <html>
        <head>
            <title>{{$settings->title}}</title>
            <meta charset="utf-8">
            <meta content="ie=edge" http-equiv="x-ua-compatible">
            <meta content="width=device-width, initial-scale=1" name="viewport">
            <meta name="csrf-token" content="63rWLQI6W2YMswWrBZLCww00RRFrqaq8AjeJtALr" />
            @if(Auth::user())
            <meta name="logged" content="1" />
            @else
            <meta name="logged" content="0" />
            @endif
            <meta name="email" content="{{$u->email}}" />
            <meta name="csrf-token" content="63rWLQI6W2YMswWrBZLCww00RRFrqaq8AjeJtALr" />
            <link href="/favicon.ico" rel="shortcut icon">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
            <link href="css/pre.css" rel="stylesheet">
        </head>
        <body>
        <div class="logo">
            <img src="/img/logo.png" alt="">
            <span class="title">Sua conta está bloqueada!</span>
            @if($u->ban_reason)
                <span class="text">{{$u->ban_reason}}</span>
            @endif
            <a href="{{$settings->vk_url}}" class="vk" target="_blank"><span>Ir para o grupo </span><i
                    class="fab fa-vk"></i></a>
            <a href="/logout" class="vk" target="_blank"><span>Sair</span></a>
        </div>
        </body>
        @else

            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
                <meta name="description" content="">
                <title>{{$settings->title}}</title>

                <link rel="stylesheet" href="/css/main.css?v=5">
                <link rel="stylesheet" href="/css/icon.css">
                <link rel="stylesheet" href="/css/notify.css">
                <link rel="stylesheet" href="/css/animation.css">
                <link rel="stylesheet" href="/css/media.css?v=1">
                
                {!! NoCaptcha::renderJs() !!}

                <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
                <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
                
                <script type="text/javascript" src="/js/perfect-scrollbar.min.js"></script>
                <script type="text/javascript" src="/js/wnoty.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
                @if(Auth::user() and $u->is_admin == 1 || $u->is_moder == 1)
                    <script type="text/javascript" src="/js/moderatorOptions.js"></script>
                @endif
                <script>
                    @auth
                    const USER_ID = '{{ $u->unique_id }}';
                    const youtuber = '{{ $u->is_youtuber }}';
                    const admin = '{{ $u->is_admin }}';
                    const moder = '{{ $u->is_moder }}';
                    @else
                    const USER_ID = null;
                    const youtuber = null;
                    const admin = null;
                    const moder = null;
                    @endauth
                    const settings = {!! json_encode($gws) !!};
                </script>

                <script type="text/javascript" src="/js/request/requests-forms.js"></script>
                <script src="templates/default/js/vendor.min.js" type="text/javascript"></script>
                <script src="templates/default/js/scriptsfaed.js?v=675056" type="text/javascript"></script>
                <script src="templates/default/js/datepicker.js" type="text/javascript"></script>
                <script src="templates/default/js/betnew/mainfaed.js?v=675056"></script>
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
                <audio id="myAudio" preload="auto">
                    <source src="/crashou3.mp3" type="audio/mpeg">
                </audio>

            </head>
            <body>
            <div class="wrapper">
                <div class="header">
                    <div class="logoheader">
                        <a href="/"> <img src="/img/logo.png" width="150px"> </a>
                    </div>

                    <div class="header-inner">
                        <div class="header-block">

                            <div class="top-nav-wrapper">
                                <button class="opener">
                                    <div class="bar"></div>
                                    <div class="bar"></div>
                                    <div class="bar"></div>
                                </button>

                                <ul class="top-nav">

                                    <div class="btn-swap">
                                        <a href="/" class="one-btn-swap active">
                                            <svg data-v-3749ce64="" width="24" height="24" focusable="false"
                                                 aria-hidden="true" class="">
                                                <use data-v-3749ce64=""
                                                     xlink:href="/templates/default/img/betnew/svg-sprite.e1149d9.svg#icon-casino"
                                                     class="svg-use"></use>
                                            </svg>
                                            Cassino
                                        </a>
                                    </div>

                                </ul>
                            </div>

                        </div>
                        @guest
                            <div class="auth-buttons">
                                <button type="button" class="btn button-pulse" id="loginRegister">Entrar ou Cadastrar
                                </button>
                            </div>
                        @else

                            <div class="deposit-wrap">
                                <div class="bottom-start rounded dropdown">
                                    <button type="button" aria-haspopup="true" aria-expanded="false"
                                            class="dropdown-toggle btn btn-secondary" data-toggle="#">
                                        <div class="selected balance">
                                            <img src="/img/MOEDA_01.png" height="30px"/>
                                        </div>
                                    </button>
                                    <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu"
                                         x-placement="bottom-start">
                                        <button type="button" data-id="balance" tabindex="0" role="menuitem"
                                                class="dropdown-item">
                                            <div class="balance-item balance">
                                                
                                                <img src="/img/MOEDA_01.png" height="30px"/>
                                                <span>Saldo Real</span>
                                                <div class="value" id="balance_bal"><strong>{{$u->balance}}</strong>
                                                </div>
                                            </div>
                                        </button>
                                    </div>
                                </div>
                                <div class="deposit-block">
                                    <div class="select-field"><span id="balance">0</span></div>
                                </div>
                            </div>
                        @endguest
                    </div>
                </div>

                <!-- #END header -->
                <div class="page">


                    <div class="main-content">
                        <div class="main-content-top">

                            @yield('content')
                        </div>
                        <div class="main-content-footer">
                            <div class="footer-counters">
                                <div class="container">
                                    <div class="row">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- header -->
                    <div class="left-sidebar">
                        <ul class="side-nav">

                            <div class="imgbannersleft">
                                <img style="border-radius: 5px;" src="/img/indique_e_ganhe.png" height="45px">
                                <img style="border-radius: 5px;" src="/img/jogue_e_lucre.png" height="45px">
                            </div>
                            <div class="listmenu"></div>

                            <div class="one-menu">
                                <a href="/crash">
                                    <div class="img-one-menu">
                                        <svg width="24" height="24" focusable="false" aria-hidden="true" class="">
                                            <use xlink:href="/img/symbols.svg#icon-crash" class="svg-use"></use>
                                        </svg>
                                    </div>
                                    Crash</a>
                            </div>

                            
                            <div class="one-menu">
                                <a href="/double">
                                    <div class="img-one-menu">
                                        <i class="fa fa-circle-dot" style="color: #55657e; font-size: 24px"></i>
                                    </div>
                                    Double</a>
                            </div>


                            {{-- <div class="one-menu">
                                <a href="/wheel">
                                    <div class="img-one-menu">
                                        <svg width="24" height="24" focusable="false" aria-hidden="true" class="">
                                            <use xlink:href="/img/symbols.svg#icon-roulette" class="svg-use"></use>
                                        </svg>
                                    </div>
                                    Roleta</a>
                            </div> --}}


                            <div class="one-menu">
                                <a href="/battle">
                                    <div class="img-one-menu">
                                        <svg width="24" height="24" focusable="false" aria-hidden="true" class="">
                                            <use xlink:href="/img/symbols.svg#icon-flip" class="svg-use"></use>
                                        </svg>
                                    </div>
                                    Battle Fish</a>
                            </div>


                            <div class="one-menu">
                                <a href="/mines">
                                    <div class="img-one-menu">
                                        <svg width="24" height="24" focusable="false" aria-hidden="true" class="">
                                            <use xlink:href="/img/symbols.svg#icon-mines" class="svg-use"></use>
                                        </svg>
                                    </div>
                                    Mines</a>
                            </div>


                            <div class="one-menu">
                                <a href="/wheel">
                                    <div class="img-one-menu">
                                        <svg width="24" height="24" focusable="false" aria-hidden="true" class="">
                                            <use xlink:href="/img/symbols.svg#icon-roulette" class="svg-use"></use>
                                        </svg>
                                    </div>
                                    Roleta</a>
                            </div>


                            <div class="one-menu">
                                <a href="/tower">
                                    <div class="img-one-menu">
                                        <svg width="24" height="24" focusable="false" aria-hidden="true" class="">
                                            <use xlink:href="/img/symbols.svg#icon-tower" class="svg-use"></use>
                                        </svg>
                                    </div>
                                    Tower</a>
                            </div>
                            @auth
                                <div class="listmenu">
                                </div>

                                <div class="one-menu">
                                    <a href="/affiliate">
                                        <div class="img-one-menu">
                                            <svg width="24" height="24" focusable="false" aria-hidden="true" class="">
                                                <use
                                                    xlink:href="/templates/default/img/betnew/svg-sprite.e1149d9.svg#icon-earn"
                                                    class="svg-use"></use>
                                            </svg>
                                        </div>
                                        Afiliados</a>
                                </div>

                                <div class="one-menu">
                                    <a data-toggle="modal" data-target="#promoModal">
                                        <div class="img-one-menu">
                                            <svg width="24" height="24" focusable="false" aria-hidden="true" class="">
                                                <use
                                                    xlink:href="/templates/default/img/betnew/svg-sprite.e1149d9.svg#icon-funfury-competition"
                                                    class="svg-use"></use>
                                            </svg>
                                        </div>
                                        Código Promocional</a>
                                </div>

                                <div class="one-menu open-menu to-open-d ">
                                    <div class="h-one-open-menu">
                                        <a href="javascript:;" class="a-to-open-d">
                                            <div class="img-one-menu">
                                                <svg data-v-10d563d5="" width="24" height="24" focusable="false"
                                                     aria-hidden="true" class="">
                                                    <use data-v-10d563d5="" xlink:href="/img/symbols.svg#icon-person"
                                                         class="svg-use"></use>
                                                </svg>
                                            </div>
                                            Meu Perfil
                                            <svg data-v-10d563d5="" width="24" height="24" focusable="false"
                                                 aria-hidden="true" class="svg-open">
                                                <use data-v-10d563d5=""
                                                     xlink:href="/templates/default/img/betnew/svg-sprite.e1149d9.svg#icon-arrow-right-small"
                                                     class=""></use>
                                            </svg>
                                        </a>
                                    </div>

                                    <div class="one-open-menu">
                                        <a href="/profile">
                                            <div class="img-one-menu">
                                                <svg width="16" height="16" focusable="false" aria-hidden="true"
                                                     class="">
                                                    <use
                                                        xlink:href="/templates/default/img/betnew/svg-sprite.e1149d9.svg#icon-staking"
                                                        class="svg-use"></use>
                                                </svg>
                                            </div>
                                            Minha Conta</a>
                                    </div>
                                    <div class="one-open-menu">
                                        <a href="/profile/history">
                                            <div class="img-one-menu">
                                                <svg width="16" height="16" focusable="false" aria-hidden="true"
                                                     class="">
                                                    <use
                                                        xlink:href="/templates/default/img/betnew/svg-sprite.e1149d9.svg#icon-earn"
                                                        class="svg-use"></use>
                                                </svg>
                                            </div>
                                            Hist. Saque/Depositos</a>
                                    </div>
                                    <div class="one-open-menu">
                                        <a href="/logout">
                                            <div class="img-one-menu">
                                                <img src="/img/excluir.png"/>
                                            </div>
                                            Sair</a>
                                    </div>
                                </div>

                                @if(Auth::check() && $u->is_admin)
                                    <div class="one-menu">
                                        <a href="/admin">
                                            <div class="img-one-menu">
                                                <svg width="24" height="24" focusable="false" aria-hidden="true"
                                                     class="">
                                                    <use
                                                        xlink:href="/img/symbols.svg#icon-fairness"
                                                        class="svg-use"></use>
                                                </svg>
                                            </div>
                                            Painel do Administrador</a>
                                    </div>
                                @endif

                            @endauth


                            <div class="listmenu">
                            </div>
                            <div class="support-menu">
                                <a href="https://jivo.chat/CqTxJBPTjs" target="_blank">
                                    <svg data-v-7e25d36a="" focusable="false" aria-hidden="true" class="">
                                        <use data-v-7e25d36a=""
                                             xlink:href="/templates/default/img/betnew/svg-sprite.e1149d9.svg#icon-live-support"
                                             class="svg-use"></use>
                                    </svg>
                                    Suporte</a>
                            </div>

                            <div class="support-menu">
                                <a href="javascript:;">
                                    <svg data-v-7e25d36a="" focusable="false" aria-hidden="true" class="">
                                        <use data-v-7e25d36a="" xlink:href="/img/symbols.svg#icon-person"
                                             class="svg-use"></use>
                                    </svg>
                                    <div class="chat-online">Jogadores Online: <span>0</span></div>
                                </a>
                            </div>


                            <div class="listmenu">
                            </div>


                            <div class="social-menu">
                                <a href="https://t.me/+roshbet" target="_blank">
                                    <svg data-v-7627c91c="" width="20" height="20" focusable="false" aria-hidden="true"
                                         class="chats-channels__icon">
                                        <use data-v-7627c91c=""
                                             xlink:href="/templates/default/img/betnew/svg-sprite.e1149d9.svg#icon-telegram"
                                             class="svg-use"></use>
                                    </svg>
                                </a>
                                <a href="https://instagram.com/roshbet=" target="_blank">
                                    <svg data-v-a9c109c4="" width="24" height="24" focusable="false" aria-hidden="true"
                                         class="">
                                        <use data-v-a9c109c4=""
                                             xlink:href="/templates/default/img/betnew/svg-sprite.e1149d9.svg#icon-instagram"
                                             class="svg-use"></use>
                                    </svg>
                                </a>
                            </div>

                        </ul>
                    </div>
                </div>


            </div>


            <div class="right-sidebar">
                <!-- ANTES DE REMOVER A DIV , VERIFICAR PORQUE AS POPUP ESTÃO ABRINDO FORA DA TELA APOS REMOVER ESTA DIV -->
                <div class="sidebar-container">
                    <div class="chat tab current">
                        <div class="chat-conversation">
                            <div class="scrollbar-container chat-conversation-inner ps">
                            </div>
                        </div>
                    </div>

                    <div class="user-profile tab">
                        @auth
                            <div class="user-block">
                                <div class="user-avatar">
                                    <button class="close-btn">
                                        <svg class="icon icon-close">
                                            <use xlink:href="/img/symbols.svg#icon-close"></use>
                                        </svg>
                                    </button>
                                    <div class="avatar"><img src="{{$u->avatar}}" alt=""></div>
                                </div>
                                <div class="user-name">
                                    <div class="nickname">{{$u->username}}</div>
                                </div>
                            </div>
                            <ul class="profile-nav">
                                <div class="give-block">
                                    <a data-toggle="modal" data-target="#giveawayModal" class="btn-give">
                                        <svg class="icon">
                                            <use xlink:href="/img/symbols.svg#icon-giveaway"></use>
                                        </svg>
                                        <span>Prêmios</span></a>
                                </div>
                                <li>
                                    <a class="" href="/profile/history">
                                        <div class="item-icon">
                                            <svg class="icon icon-history">
                                                <use xlink:href="/img/symbols.svg#icon-history"></use>
                                            </svg>
                                        </div>
                                        <span>Histórico</span>
                                    </a>
                                </li>
                            </ul>
                            <a href="/logout" class="btn btn-logout">
                                <div class="item-icon">
                                    <svg class="icon icon-logout">
                                        <use xlink:href="/img/symbols.svg#icon-logout"></use>
                                    </svg>
                                </div>
                                <span>Sair</span>
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
            </div>
            <div class="mobile-nav-component">
                @auth
                    <div class="pull-out other">
                        <button class="close-btn">
                            <svg class="icon icon-close">
                                <use xlink:href="/img/symbols.svg#icon-close"></use>
                            </svg>
                        </button>
                        <ul class="pull-out-nav">
                            <li>
                                <a href="/affiliate">
                                    <svg class="icon icon-affiliate">
                                        <use xlink:href="/img/symbols.svg#icon-affiliate"></use>
                                    </svg>
                                    Afiliados
                                </a>
                            </li>
                            <li>
                                <a data-toggle="modal" data-target="#promoModal">
                                    <svg class="icon icon-promo">
                                        <use xlink:href="/img/symbols.svg#icon-affiliate"></use>
                                    </svg>
                                    Código Promocional
                                </a>
                            </li>
                            <li>
                                <a href="/profile/history">
                                    <svg class="icon icon-coin">
                                        <use xlink:href="/img/symbols.svg#icon-coin"></use>
                                    </svg>
                                    Hist. Saques/Depositos
                                </a>
                            </li>
                            <li>
                                <a href="/logout">
                                    <svg class="icon icon-close">
                                        <use xlink:href="/img/symbols.svg#icon-close"></use>
                                    </svg>
                                    Sair
                                </a>
                            </li>
                            @if($settings->vk_support_url)
                                <li>
                                    <a href="" target="_blank">
                                        <svg class="icon icon-support">
                                            <use xlink:href="/img/symbols.svg#icon-support"></use>
                                        </svg>
                                        EM BREVE
                                    </a>
                                </li>
                            @endif

                        </ul>
                    </div>
                @endauth
                <div class="pull-out game">
                    <button class="close-btn">
                        <svg class="icon icon-close">
                            <use xlink:href="/img/symbols.svg#icon-close"></use>
                        </svg>
                    </button>
                    <ul class="pull-out-nav">
                        <li>
                            <a href="/wheel">
                                <svg class="icon">
                                    <use xlink:href="/img/symbols.svg#icon-roulette"></use>
                                </svg>
                                Roleta
                            </a>
                        </li>
                        <li>
                            <a href="/double" style="padding: 20px 10;">
                                <i class="fa fa-circle-dot" style="color: #55657e; font-size: 14px; margin-right: 5px"></i>
                                Double
                            </a>
                        </li>
                        
                        <li>
                            <a href="/crash">
                                <svg class="icon">
                                    <use xlink:href="/img/symbols.svg#icon-crash"></use>
                                </svg>
                                Crash
                            </a>
                        </li>

                        <li>
                            <a href="/tower">
                                <svg class="icon">
                                    <use xlink:href="/img/symbols.svg#icon-tower"></use>
                                </svg>
                                Tower
                            </a>
                        </li>
                        <li>
                            <a href="/">
                                <svg class="icon">
                                    <use xlink:href="/img/symbols.svg#icon-dice"></use>
                                </svg>
                                Dice
                            </a>
                        </li>


                        <li>
                            <a href="/mines">
                                <svg class="icon">
                                    <use xlink:href="/img/symbols.svg#icon-mines"></use>
                                </svg>
                                Mines
                            </a>
                        </li>


                    </ul>
                </div>
                <div class="mobile-nav-menu-wrapper">
                    <ul class="mobile-nav-menu">
                        <li>
                            <button id="gamesMenu">
                                <svg class="icon icon-gamepad">
                                    <use xlink:href="/img/symbols.svg#icon-gamepad"></use>
                                </svg>
                                Jogos
                            </button>
                        </li>
                        @auth
                            <li>
                                <button id="profileMenu">
                                    <svg class="icon icon-person">
                                        <use xlink:href="/img/symbols.svg#icon-person"></use>
                                    </svg>
                                    <a href="profile"> Meu Perfil </a>
                                </button>
                            </li>
                            <li>
                                <button id="otherMenu">
                                    <svg class="icon icon-more">
                                        <use xlink:href="/img/symbols.svg#icon-more"></use>
                                    </svg>
                                    <span>Mais</span>
                                </button>
                            </li>
                        @endauth
                        <li>
                            <a href="https://jivo.chat/CqTxJBPTjs">
                                <svg data-v-7e25d36a="" focusable="false" aria-hidden="true" class="" style="fill: #828f9a"> <use data-v-7e25d36a="" xlink:href="/templates/default/img/betnew/svg-sprite.e1149d9.svg#icon-live-support" class="svg-use"></use> </svg>
                                <a href="profile"> Suporte </a>
                            </a>
                        </li>
                        <li>
                            <a href="https://instagram.com/">
                                <svg data-v-a9c109c4="" width="24" height="24" focusable="false" aria-hidden="true" class="" style="fill: #828f9a"> <use data-v-a9c109c4="" xlink:href="/templates/default/img/betnew/svg-sprite.e1149d9.svg#icon-instagram" class="svg-use"></use> </svg>
                                <a href="profile"> Instagram </a>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="footer">

                <div class="footer">

                    <div class="container-mobile" style="display: none;">
                        <div class="license">
                            <img style="margin-bottom: 8px;" src="/img/logo.png" alt="">
                            <img src="/img/curacao.png" alt="">
                        </div>
                    </div>
                    <div class="container-info">
                        <div class="license">
                            <img style="margin-bottom: 8px;" src="/img/logo.png" alt="">
                            <img src="/img/curacao.png" alt="">
                        </div>
                        <div class="games">
                            <div class="title">Jogos</div>
                            <div class="game-list">
                                <a href="/crash">
                                    <div class="rocket txt-label">Crash</div>
                                </a>
                                
                                <a href="/double">
                                    <div class="double txt-label">Double</div>
                                </a>
                            
    
                                <a href="/">
                                    <div class="double txt-label">Dice</div>
                                </a>
                                 <a href="/mines">
                                    <div class="double txt-label">Mines</div>
                                </a>
                                 <a href="/wheel">
                                    <div class="double txt-label">Roleta</div>
                                </a>
                                 <a href="/tower">
                                    <div class="double txt-label">Tower</div>
                                </a>
                            </div>
                        </div>
                        <div class="options">
                            <div class="title">Opções</div>
                            <div class="options-list">
                                <a href="/profile">
                                    <div class="config txt-label ">Perfil</div>
                                </a>
                                <div class="deposit txt-label deposit-modal" style="cursor:pointer">Depositar</div>
                                <div class="withdraw txt-label withdraw-modal" style="cursor:pointer">Sacar</div>
                            </div>
                        </div>
                        <div class="help">
                            <div class="title">Central de Ajuda</div>
                            <div class="options-list">
                                <div class="support txt-label"><a href="#"
                                                                  target="_blank" style="color:hsla(0, 0%, 100%, 0.5)">Suporte</a>
                                </div>
                                <a href="#">
                                    <div class="terms-of-use txt-label" data-toggle="modal" data-target="#tosModal">Termos de uso</div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="terms-responsability flex" style="justify-content: center">
                        <div class="txt-label w70" style="text-align: center;">
                            Este site oferece jogos com experiência de risco. Para ser um usuário do nosso site, você
                            deve ter mais de 18 anos. Não somos responsáveis pela violação de suas leis locais
                            relacionadas ao i-gambling.
                        </div>
                    </div>
                </div>
            </div>
            </div>

            </div>

            </div>
            </div>
            
            @auth
                <div class="modal fade" id="walletModal" tabindex="-1" role="dialog"
                     aria-labelledby="walletModalLabel"
                     aria-hidden="true">
                    <div class="modal-dialog deposit-modal modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <button class="modal-close" data-dismiss="modal" aria-label="Close">
                                <svg class="icon icon-close">
                                    <use xlink:href="/img/symbols.svg#icon-close"></use>
                                </svg>
                            </button>
                            <div class="deposit-modal-component">
                                <div class="wrap">
                                    <div class="tabs">
                                        <button type="button" class="btn btn-tab isActive">Depositar</button>
                                        <button type="button" class="btn btn-tab">Sacar</button>
                                    </div>
                                    <div class="deposit-section tab active" data-type="deposite">
                                        <form action="/pay" method="post" id="payment">
                                            @if($settings->dep_bonus_min > 0)
                                                <div class="form-row">
                                                    <label>

                                                    </label>
                                                </div>
                                            @endif

                                            <div class="form-row">
                                                <label>
                                                    <div class="form-label">Valor para depositar</div>
                                                    <div class="form-field">
                                                        <div class="input-valid">
                                                            <input class="input-field input-with-icon" name="amount"
                                                                   value="{{$settings->min_dep}}"
                                                                   placeholder="">
                                                            <div class="input-icon">
                                                            </div>
                                                            <div class="valid inline"></div>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>

                                            <div class="form-row">
                                                <div class="form-label">Método de Pagamento</div>
                                                <div class="select-payment">
                                                    <input type="hidden" name="type" value="" id="depositType">

                                                    <div class="bottom-start dropdown">

                                                        <button type="button" aria-haspopup="true"
                                                                aria-expanded="false"
                                                                class="dropdown-toggle btn btn-secondary"
                                                                id="dropdownMenuButton" data-toggle="dropdown"
                                                                aria-haspopup="true" aria-expanded="false">
                                                            Selecione um método de pagamento
                                                            <div class="opener">
                                                                <svg class="icon icon-down">
                                                                    <use
                                                                        xlink:href="{{ url('/') }}/img/symbols.svg#icon-down"></use>
                                                                </svg>
                                                            </div>
                                                        </button>

                                                        <div tabindex="-1" role="menu" aria-hidden="true"
                                                             class="dropdown-menu" x-placement="bottom-start"
                                                             data-placement="bottom-start">

                                                            @foreach($payment_provider as $pp)
                                                                <button type="button" data-id="5" tabindex="0"
                                                                        role="menuitem" class="dropdown-item"
                                                                        data-system="{{$pp->provider_name}}">
                                                                    <div class="image"><img
                                                                            src="{{ url('/') }}/img/wallets/pix.svg"
                                                                            alt="{{$pp->payment_methods}} do {{$pp->payment_methods}}">
                                                                    </div>
                                                                    <span>{{$pp->payment_methods}}</span>
                                                                </button>
                                                            @endforeach

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-row" {{$u->cpf == NULL ? '' : 'hidden'}}>
                                                <label>
                                                    <div class="form-label">CPF do Pagador</div>
                                                    <div class="form-field">
                                                        <div class="input-valid">
                                                            <input class="input-field input-with-icon" name="cpf"
                                                                   value="{{$u->cpf}}"
                                                                   placeholder="CPF do pagador">
                                                            <div class="valid inline"></div>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>

                                            <button type="submit" id="Depositar" class="btn btn-green">Depositar</button>
                                        </form>
                                    </div>
                                    <div class="deposit-section tab" data-type="withdraw">
                                        <div class="form-row">
                                            <label>
                                                <div class="form-label">Valor disponível para saque</div>
                                                <div class="form-field">
                                                    <div class="input-valid">
                                                        <input class="input-field input-with-icon"
                                                               value="{{$u->requery}}" readonly>
                                                        <div class="input-icon">
                                                        </div>
                                                        <small class="text-warning">Obs: Seu valor de saque é o
                                                            valor de
                                                            lucros.</small>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-label">Metodo de Saque</div>
                                            <div class="select-payment">
                                                <input type="hidden" name="type" value="" id="withdrawType">
                                                <div class="bottom-start dropdown">
                                                    <button type="button" aria-haspopup="true" aria-expanded="false"
                                                            class="dropdown-toggle btn btn-secondary"
                                                            id="dropdownMenuButton" data-toggle="dropdown"
                                                            aria-haspopup="true" aria-expanded="false">
                                                        Selecione um Método de Saque
                                                        <div class="opener">
                                                            <svg class="icon icon-down">
                                                                <use xlink:href="/img/symbols.svg#icon-down"></use>
                                                            </svg>
                                                        </div>
                                                    </button>
                                                    <div tabindex="-1" role="menu" aria-hidden="true"
                                                         class="dropdown-menu" x-placement="bottom-start"
                                                         style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 46px, 0px);"
                                                         data-placement="bottom-start">
                                                        <button type="button" data-id="6" tabindex="0"
                                                                role="menuitem"
                                                                class="dropdown-item" data-system="pix">
                                                            <div class="image"><img
                                                                    src="{{ url('/') }}/img/wallets/pix.svg"
                                                                    alt="pix"></div>
                                                            <span>Pix</span>
                                                        </button>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div>
                                            <div class="form-row">
                                                <label>
                                                    <div class="form-label">Digite o valor para Sacar (BRL)</div>
                                                    <div class="form-field">
                                                        <div class="input-valid">
                                                            <input class="input-field input-with-icon" name="amount"
                                                                   value="" id="valwithdraw"
                                                                   placeholder="Digite um Valor">
                                                            <div class="input-icon">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <label>
                                                <div class="form-label">
                                                    @if($u->cpf == NULL)
                                                        Digite sua Chave PIX
                                                    @else
                                                        Sua chave PIX cadastrada
                                                    @endif
                                                </div>
                                                <div class="form-field">
                                                    <div class="input-valid">
                                                        <input class="input-field"
                                                               {{$u->cpf != NULL ? 'readonly' : ''}} name="purse"
                                                               placeholder=""
                                                               value="{{$u->cpf != NULL ? $u->cpf : ''}}"
                                                               id="numwallet">
                                                    </div>
                                                </div>
                                            </label>
                                        </div>

                                        <button type="submit" disabled="" class="btn btn-green" id="submitwithdraw">
                                            Retirar
                                        </button>
                                        <div class="checkbox-block">
                                            <label>Aceito os termos de transferências.
                                                <input name="agree" type="checkbox" id="withdraw-checkbox"
                                                       value=""><span class="checkmark"></span></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="exchangeModal" tabindex="-1" role="dialog"
                     aria-labelledby="exchangeModalLabel" aria-hidden="true">
                    <div class="modal-dialog faucet-demo-modal modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <button class="modal-close" data-dismiss="modal" aria-label="Close">
                                <svg class="icon icon-close">
                                    <use xlink:href="/img/symbols.svg#icon-close"></use>
                                </svg>
                            </button>
                            <div class="faucet-container">
                                <h3 class="faucet-caption"><span>VALOR</span></h3>
                                <div class="caption-line"><span class="span"><img src="/img/MOEDA_01.png" height="30px"/></span></div>
                                <div class="faucet-modal-form">
                                    <div class="faucet-reload"><span>Digite Um Valor</span>
                                        <span>{{$settings->exchange_min}}</span>
                                        <svg class="icon icon-coin balance bonus">
                                            <use xlink:href="/img/symbols.svg#icon-coin"></use>
                                        </svg>
                                    </div>
                                </div>
                                <div class="faucet-modal-form">
                                    <div class="faucet-reload"><span>Valor</span>
                                        <span>{{$settings->exchange_curs}}</span>
                                        <img src="/img/MOEDA_01.png" height="30px"/>
                                        = <span>1</span>
                                        <img src="/img/MOEDA_01.png" height="30px"/>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <label>
                                        <div class="form-label">Valor Para trocar</div>
                                        <div class="form-field">
                                            <div class="input-valid">
                                                <input class="input-field input-with-icon" name="amount"
                                                       placeholder="Valor" id="exSum">
                                                <div class="input-icon">
                                                    <img src="/img/MOEDA_01.png" height="30px"/>
                                                </div>
                                                <div class="valid inline"></div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                <div class="faucet-modal-form">
                                    <div class="faucet-amount">
                                        <div class="faucet-reload"><span>T:</span> <span
                                                id="exTotal">0</span>
                                            <svg class="icon icon-coin balance balance">
                                                <use xlink:href="/img/symbols.svg#icon-coin"></use>
                                            </svg>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-green exchangeBonus"><span>Trocar</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="promoModal" tabindex="-1" role="dialog"
                     aria-labelledby="promoModalLabel"
                     aria-hidden="true">
                    <div class="modal-dialog faucet-demo-modal modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <button class="modal-close" data-dismiss="modal" aria-label="Close">
                                <svg class="icon icon-close">
                                    <use xlink:href="/img/symbols.svg#icon-close"></use>
                                </svg>
                            </button>
                            <div class="faucet-container">
                                <h3 class="faucet-caption"><span>Código Promocional</span></h3>
                                <div class="caption-line"><span class="span"><img src="/img/MOEDA_01.png" height="30px"/></span></div>
                                <div class="form-row">
                                    <label>
                                        <div class="form-field">
                                            <div class="input-valid">
                                                <input class="input-field input-with-icon" name="promo"
                                                       placeholder="Digite um código" id="promoInput">
                                                <div class="input-icon">
                                                    <svg class="icon icon-promo">
                                                        <use xlink:href="/img/symbols.svg#icon-promo"></use>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                <div class="faucet-modal-form">
                                    <button type="button" class="btn btn-green activatePromo">
                                        <span>RECEBER BÔNUS</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="giveawayModal" tabindex="-1" role="dialog"
                     aria-labelledby="giveawayModalLabel" aria-hidden="true">
                    <div class="modal-dialog faucet-demo-modal modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <button class="modal-close" data-dismiss="modal" aria-label="Close">
                                <svg class="icon icon-close">
                                    <use xlink:href="/img/symbols.svg#icon-close"></use>
                                </svg>
                            </button>
                            <div class="faucet-container">
                                <h3 class="faucet-caption"><span>Sorteios</span></h3>
                                <div class="caption-line"><span class="span"><svg class="icon icon-giveaway"><use
                                                xlink:href="/img/symbols.svg#icon-giveaway"></use></svg></span>
                                </div>
                                <div class="gv-list">
                                    @forelse($gives as $gv)
                                        <div class="faucet-modal-give {{$gv->status == 1 ? 'doneGive' : ''}}"
                                             id="gv_{{$gv->id}}">
                                            <div class="give-btn-block">
                                                <div class="faucet-reload">
                                                    <div class="faucet-cd">Sorteios #{{$gv->id}}</div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="bank-text">Valor:</div>
                                                    <div class="bank-amount">{{$gv->sum}}
                                                        <svg class="icon icon-coin balance {{$gv->type}}">
                                                            <use xlink:href="/img/symbols.svg#icon-coin"></use>
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="date-text">Participantes:</div>
                                                    <div
                                                        class="date-of">{{$gv->status == 0 ? $gv->time_to : 'Participantes'}}</div>
                                                </div>
                                            </div>
                                            @if($gv->status == 0)
                                                @if($gv->group_sub != 0 || $gv->min_dep != 0)
                                                    <div class="give-btn-block nowGive">
                                                        <div class="faucet-reload">
                                                            <div class="faucet-cd"></div>
                                                            <div class="text-left">
                                                                @if($gv->group_sub != 0 && $settings->vk_url)
                                                                    <div class="faucet-sm-text">• Participe em nosso
                                                                        canal <a href="{{$settings->vk_url}}"
                                                                                 target="_blank">Link Facebook</a>.
                                                                    </div>
                                                                @endif
                                                                @if($gv->min_dep != 0)
                                                                    <div class="faucet-sm-text">• Recarregue sua
                                                                        conta
                                                                        com
                                                                        quantia R$ {{$gv->min_dep}} para o dia
                                                                        atual.
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="give-btn-block nowGive">
                                                    <button type="button" class="btn btn-green joinGiveaway"
                                                            data-id="{{$gv->id}}"><span>PARTICIPAR</span></button>
                                                    <div class="faucet-sm-text">Participantes: <span
                                                            class="total_users">{{$gv->total}}</span></div>
                                                </div>
                                            @endif
                                            <div class="give-btn-block">
                                                <div class="faucet-reload">
                                                    <div class="faucet-cd">Vencedor:</div>
                                                    <div class="winnerGive">
                                                        @if($gv->status > 0 && !is_null($gv->winner_id))
                                                            <button type="button" class="btn btn-link"
                                                                    data-id="{{$gv->winner->unique_id}}">
                                                <span class="sanitize-user">
                                                    <div class="sanitize-avatar"><img src="{{$gv->winner->avatar}}" alt=""></div>
                                                    <span class="sanitize-name">{{$gv->winner->username}}</span>
                                                </span>
                                                            </button>
                                                        @else
                                                            Nenhum ganhador ainda...
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="faucet-modal-give giveNone">
                                            <div class="give-btn-block">
                                                <div class="faucet-reload">
                                                    <div class="faucet-cd">Sem sorteios ainda.</div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="captchaModal" tabindex="-1" role="dialog"
                     aria-labelledby="captchaModalLabel" aria-hidden="true">
                    <div class="modal-dialog captcha-need-modal modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="captcha-need-modal-container">
                                <div class="caption">Подтвердите,что Вы не робот!</div>
                                <div class="form">
                                    <div class="label">Нажмите "Я не робот", чтобы продолжить!</div>
                                    <div class="captcha">
                                        <div hl="ru">
                                            <div>
                                                <div style="width: 304px; height: 78px;">
                                                    {!! NoCaptcha::display(['data-callback' => 'recaptchaCallback']) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" disabled="" class="btn" id="submitBonus">Prosseguir
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if($u->is_admin == 1 || $u->is_moder == 1)
                    <div class="modal fade" id="bannedModal" tabindex="-1" role="dialog"
                         aria-labelledby="bannedModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <button class="modal-close" data-dismiss="modal" aria-label="Close">
                                    <svg class="icon icon-close">
                                        <use xlink:href="/img/symbols.svg#icon-close"></use>
                                    </svg>
                                </button>
                                <div class="faucet-container">
                                    <h3 class="faucet-caption"><span>Заблокированные пользователи</span></h3>
                                    <h3 class="faucet-caption">
                                        <div id="unbanName"></div>
                                    </h3>
                                    <div class="caption-line"><span class="span"><svg class="icon"><use
                                                    xlink:href="/img/symbols.svg#icon-ban"></use></svg></span></div>
                                    <div class="form-row">
                                        <div class="table-heading">
                                            <div class="thead">
                                                <div class="tr">
                                                    <div class="th">Пользователь</div>
                                                    <div class="th">Окончание блокировки</div>
                                                    <div class="th">Причина</div>
                                                    <div class="th">Действия</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="table-ban-wrap" style="max-height: 100%;">
                                            <div class="table-wrap" style="transform: translateY(0px);">
                                                <table class="table">
                                                    <tbody id="bannedList">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="banModal" tabindex="-1" role="dialog"
                         aria-labelledby="banModalLabel"
                         aria-hidden="true">
                        <div class="modal-dialog faucet-demo-modal modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <button class="modal-close" data-dismiss="modal" aria-label="Close">
                                    <svg class="icon icon-close">
                                        <use xlink:href="/img/symbols.svg#icon-close"></use>
                                    </svg>
                                </button>
                                <div class="faucet-container">
                                    <h3 class="faucet-caption"><span>Блокировка Chatа пользователю</span></h3>
                                    <h3 class="faucet-caption">
                                        <div id="banName"></div>
                                    </h3>
                                    <div class="caption-line"><span class="span"><svg class="icon"><use
                                                    xlink:href="/img/symbols.svg#icon-ban"></use></svg></span></div>
                                    <div class="form-row">
                                        <input type="hidden" name="user_ban_id">
                                        <label>
                                            <div class="form-label">Время бана в минутах</div>
                                            <div class="form-field">
                                                <div class="input-valid">
                                                    <input class="input-field input-with-icon" name="time"
                                                           placeholder="Время" id="banTime">
                                                    <div class="input-icon">
                                                        <svg class="icon">
                                                            <use xlink:href="/img/symbols.svg#icon-time"></use>
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="form-row">
                                        <input type="hidden" name="user_ban_id">
                                        <label>
                                            <div class="form-label">Причина бана</div>
                                            <div class="form-field">
                                                <div class="input-valid">
                                                    <input class="input-field input-with-icon" name="reason"
                                                           placeholder="Причина" id="banReason">
                                                    <div class="input-icon">
                                                        <svg class="icon">
                                                            <use xlink:href="/img/symbols.svg#icon-edit"></use>
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="form-row">
                                        <button type="button" class="btn btn-green banThis"><span>BANIR</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="unbanModal" tabindex="-1" role="dialog"
                         aria-labelledby="unbanModalLabel" aria-hidden="true">
                        <div class="modal-dialog faucet-demo-modal modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <button class="modal-close" data-dismiss="modal" aria-label="Close">
                                    <svg class="icon icon-close">
                                        <use xlink:href="/img/symbols.svg#icon-close"></use>
                                    </svg>
                                </button>
                                <div class="faucet-container">
                                    <h3 class="faucet-caption"><span>Desbloqueando o Chat para um usuário</span>
                                    </h3>
                                    <h3 class="faucet-caption">
                                        <div id="unbanName"></div>
                                    </h3>
                                    <div class="caption-line"><span class="span"><svg class="icon"><use
                                                    xlink:href="/img/symbols.svg#icon-ban"></use></svg></span></div>
                                    <div class="form-row">
                                        <input type="hidden" name="user_unban_id">
                                        <button type="button" class="btn btn-green unbanThis"><span>Desbanir</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endauth
            @guest
                <div class="modal fade" id="loginRegisterModal" tabindex="-1" role="dialog"
                     aria-labelledby="loginRegisterModalLabel" aria-hidden="true">
                    <div class="modal-dialog deposit-modal modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <button class="modal-close" data-dismiss="modal" aria-label="Close">
                                <svg class="icon icon-close">
                                    <use xlink:href="/img/symbols.svg#icon-close"></use>
                                </svg>
                            </button>

                            <div class="deposit-modal-component">

                                <div class="modal-win active">
                                    <div class="parent-modal-win">

                                        <div class="img-modal-win"></div>

                                        <div class="content-modal-win">

                                            <div class="btn-modal-win">
                                                <div class="btn-tab isActive"><span>Entrar</span></div>
                                                <div class="btn-tab"><span>Cadastrar</span></div>
                                                <div class="btn-tab"><span>Esqueci Senha</span></div>
                                            </div>

                                            <div class="deposit-section tab active" data-type="deposite">
                                                <div class="form-modal-win login-form">
                                                    <!-- <form action="/auth/login" method="POST" id="login"> -->

                                                    <div id="inputError-others" class="inputError"></div>

                                                    <div class="one-form-modal-win">
                                                        <input type="email" autocomplete="off" name="emailInput"
                                                               id="emailInput" class="new-input"
                                                               placeholder="E-mail de acesso">

                                                        <div id="inputError-email" class="inputError"></div>

                                                    </div>

                                                    <div class="one-form-modal-win">
                                                        <input type="password" autocomplete="off" name="passwordInput"
                                                               id="passwordInput" class="new-input"
                                                               placeholder="Senha de acesso">

                                                        <div id="inputError-password" class="inputError"></div>
                                                    </div>

                                                    <button type="submit" id="buttonLoginAccount" name="buttonLoginAccount" class="btn-submit">
                                                        Entrar
                                                    </button>

                                                    <!--</form>-->
                                                </div>
                                            </div>

                                            <div class="deposit-section tab" data-type="deposite">
                                                <div class="form-modal-win login-form">
                                                    <!-- <form action="/auth/register" method="POST" id="login"> -->

                                                    <div id="inputErrorReg-others" class="inputError"></div>

                                                    <div class="one-form-modal-win">
                                                        <input type="text" class="new-input" name="usernameInput" id="usernameInput" autocomplete="off"
                                                               placeholder="Nome de Usuário" require>

                                                        <div id="inputErrorReg-username" class="inputError"></div>
                                                    </div>

                                                    <div class="one-form-modal-win">
                                                        <input type="text" class="new-input" name="first_nameInput" id="first_nameInput" autocomplete="off"
                                                               placeholder="Primeiro Nome" require>

                                                        <div id="inputErrorReg-first_name" class="inputError"></div>
                                                    </div>

                                                    <div class="one-form-modal-win">
                                                        <input type="text" class="new-input" name="last_nameInput" id="last_nameInput" autocomplete="off"
                                                               placeholder="Sobrenome" require>

                                                        <div id="inputErrorReg-last_name" class="inputError"></div>
                                                    </div>

                                                    <div class="one-form-modal-win">
                                                        <input type="text" class="new-input" name="number_smsInput" id="number_smsInput" autocomplete="off"
                                                               placeholder="Número Celular" require>

                                                        <div id="inputErrorReg-number_sms" class="inputError"></div>
                                                    </div>

                                                    <div class="one-form-modal-win">
                                                        <input type="email" class="new-input" name="emailInputReg" id="emailInputReg" autocomplete="off"
                                                               placeholder="E-mail para acesso" require>

                                                        <div id="inputErrorReg-email" class="inputError"></div>
                                                    </div>

                                                    <div class="one-form-modal-win">
                                                        <input type="password" class="new-input" name="passwordInputReg" id="passwordInputReg" autocomplete="off"
                                                               placeholder="Senha para acesso" require >

                                                        <div id="inputErrorReg-password" class="inputError"></div>
                                                    </div>

                                                    <button id="registerNewAccount" class="btn-submit">
                                                        Cadastrar
                                                    </button>

                                                    <!-- </form> -->
                                                </div>
                                            </div>

                                            <div class="deposit-section tab" data-type="deposite">
                                                <div class="form-modal-win login-form">
                                                    <form action="/auth/forgot-password" method="POST" id="login">

                                                        <div class="one-form-modal-win">
                                                            <input type="email" class="new-input" name="email"
                                                                   placeholder="E-mail de acesso">
                                                        </div>

                                                        <button type="submit" class="btn-submit">Enviar E-mail</button>

                                                    </form>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            @endguest
            <div class="modal fade" id="tosModal" tabindex="-1" role="dialog" aria-labelledby="tosModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog tos-modal modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <button class="modal-close" data-dismiss="modal" aria-label="Close">
                            <svg class="icon icon-close">
                                <use xlink:href="/img/symbols.svg#icon-close"></use>
                            </svg>
                        </button>
                        <div class="tos-modal-container">
                            <div class="scrollbar-container tos-modal-block ps">
                                <h2>Termos de Uso</h2>
                                <p>1.1. Este contrato (doravante denominado "Contrato") rege os termos e condições,
                                    pela
                                    prestação de serviços do site
                                    «{{$settings->domain}}», doravante referido como
                                    "Organizador", e é dirigido a um indivíduo que deseja receber os serviços do
                                    site
                                    especificado (doravante denominado "Participante").</p>
                                <p>1.2. O organizador e o participante reconhecem o procedimento e a forma de
                                    celebração
                                    deste acordo
                                    equivalente em força legal a um acordo celebrado por escrito.</p>
                                <p>1.3. Os termos deste acordo são aceites pelos participantes na íntegra e sem
                                    quaisquer reservas, mediante a adesão ao acordo na forma que consta no site
                                    «{{$settings->domain}}»</p>
                                <h2>Termos e Definições</h2>
                                <p>2.1. O objeto deste Contrato é a prestação pelo organizador ao participante de
                                    serviços para organizar lazer e recreação no jogo em «{{$settings->domain}}»
                                    conforme
                                    os termos deste Acordo. Esses serviços incluem, em particular,
                                    o seguinte: serviços de compra e venda de saldos des jogos em
                                    ({{$settings->domain}}
                                    ),
                                    manter registros de informações significativas: movimentos na conta do jogo,
                                    garantir medidas para
                                    identificação e segurança dos participantes, desenvolvimento de software,
                                    integrado ao playground e aplicativos externos, informativos e outros
                                    serviços necessários à organização do jogo e atendimento ao participante em seu
                                    processo de
                                    local do organizador.</p>
                                <p>2.2. O jogo como um todo, bem como qualquer um de seus elementos ou qualquer jogo
                                    externo associado
                                    aplicativo criado exclusivamente para entretenimento. O participante reconhece
                                    que
                                    todos os tipos
                                    as atividades do jogo neste site são uma diversão para ele. Participante
                                    concorda que, dependendo das características de sua conta, o grau de sua
                                    participação no jogo estará disponível em vários graus.</p>
                                <p>2.3. O participante concorda que é pessoalmente responsável por todas as ações,
                                    feito com os jogos de ({{$settings->domain}}): comprando e vendendo,
                                    entrada e saída, bem como para ações de jogos no playground: criação,
                                    compra e venda, operações com todos os elementos do jogo e outros atributos e
                                    objetos do jogo,
                                    usado para jogabilidade.</p>
                                <p>2.4. O Membro reconhece que o grau e a capacidade de participar de entretenimento
                                    no
                                    servidor
                                    Os jogos são as principais qualidades do serviço prestado a ele.</p>
                                <h2>Direitos e obrigações das partes</h2>
                                <p>3.1 Direitos e obrigações do participante.</p>
                                <p>3.1.1. Participe do jogo «{{$settings->domain}}» apenas pessoas que atingiram
                                    capacidade civil de acordo com as leis do país de sua residência. Tudo
                                    as consequências do incumprimento desta condição são atribuídas ao
                                    participante.</p>
                                <p>3.1.2. O grau e o método de participação no jogo são determinados pelo próprio
                                    participante, mas não podem
                                    contradizer este Acordo e as regras do playground.</p>
                                <p>3.1.2. O participante é obrigado:</p>
                                <p>3.1.2.1. forneça informações verdadeiras sobre você durante o registro e mediante
                                    solicitação
                                    Organizador fornecer dados confiáveis sobre sua identidade, permitindo
                                    identificá-lo como o dono da conta no jogo;</p>
                                <p>3.1.2.2. não use o jogo para realizar quaisquer ações contrárias
                                    direito internacional e a lei do país de residência;</p>
                                <p>3.1.2.3. não use recursos não documentados (bugs) e erros de software
                                    garantir o jogo e notificar imediatamente o Organizador sobre eles, bem como
                                    sobre
                                    as pessoas
                                    usando esses erros;</p>
                                <p>3.1.2.4. não utilize programas externos de nenhum tipo, para obter benefícios em
                                    jogos;</p>
                                <p>3.1.2.5. não use seu link de afiliado para anunciar, bem como o recurso, sua
                                    contendo, mailing lists e outros tipos de mensagens para pessoas que não
                                    expressam
                                    consentimento
                                    receba-os (spam);</p>
                                <p>3.1.2.6. não tem o direito de restringir o acesso de outros participantes ou
                                    outras
                                    pessoas ao Jogo,
                                    obriga-se a tratar com respeito e correção os participantes do jogo, bem como a
                                    À Organizadora, seus sócios e funcionários, não interferir no trabalho
                                    recente;</p>
                                <p>3.1.2.7. não engane o Organizador e os participantes do jogo;</p>
                                <p>3.1.2.8. não use palavrões e insultos de qualquer forma;</p>
                                <p>3.1.2.9. não denegrir as ações de outros Jogadores e da Administração;</p>
                                <p>3.1.2.10. não ameace violência ou dano físico a ninguém;</p>
                                <p>3.1.2.11. não distribua materiais que promovam rejeição ou ódio por
                                    qualquer raça, religião, cultura, nação, povo, idioma, política, estado,
                                    ideologia ou movimento social;</p>
                                <p>3.1.2.12. não anuncie pornografia, drogas e recursos que contenham tais
                                    informações;</p>
                                <p>3.1.2.13. não usar ações, terminologia ou jargão para disfarçar uma violação das
                                    obrigações de um membro;</p>
                                <p>3.1.2.14. cuidar de forma independente das medidas necessárias de computador e
                                    outros
                                    segurança, manter segredo e não transferir para outra pessoa ou outro
                                    participante
                                    seus dados de identificação: login, senha da conta, etc., não permitem
                                    acesso não autorizado à caixa de correio especificada no perfil da conta
                                    participante. Todo o risco de consequências adversas da divulgação desses dados
                                    é
                                    suportado por
                                    participante, desde que o participante concorda que o sistema de segurança da
                                    informação
                                    a plataforma de jogos exclui a transferência de login, senha e informações de
                                    identificação
                                    conta de membro para terceiros;</p>
                                <p>3.1.2.15. assumir responsabilidade pessoal pela conduta de seus
                                    movimentações e movimentações financeiras, a Organizadora não se responsabiliza
                                    pelo
                                    transações financeiras entre Jogadores para a transferência de inventário de
                                    jogo e
                                    moeda do jogo,
                                    bem como outros atributos do jogo.</p>
                                <p>3.1.2.16. ser o primeiro a notificar o organizador de suas reivindicações e
                                    reclamações por escrito
                                    formulário através da página de Suporte.</p>
                                <p>3.1.2.17. regularmente se familiarizar com as notícias do jogo, bem como com
                                    mudanças neste Acordo e nas regras do jogo no playground.</p>
                                <p>3.1.2.18. não crie contas adicionais (multi-contas). Tais ações
                                    resultará no bloqueio da conta ou na sua reinicialização.</p>
                                <p>3.1.2.19. Venda/transferência de contas é proibida</p>
                                <p>3.1.2.20. "Conluios" de grupos de pessoas com o objetivo de obter benefícios para
                                    participantes/não participantes de conluio</p>
                                <p>3.1.2.21. "Conluios" - eles também são um cartel, uma conspiração criminosa, uma
                                    cooperativa. o
                                    o termo define um grupo de pessoas que, por meio da cooperação, buscam obter um
                                    benefício
                                    local. Se forem encontrados, todos os participantes serão banidos e redefinidos,
                                    também
                                    possivelmente uma punição definida pelos Administradores.</p>
                                <h3>Direitos e obrigações do organizador</h3>
                                <p>4.1.1. O organizador é obrigado:</p>
                                <p>4.1.1.1. assegurar, gratuitamente, o acesso do participante ao parque infantil e
                                    participação no jogo. O participante paga de forma independente pelo acesso à
                                    rede
                                    às suas próprias custas
                                    Internet e suporta outros custos associados a esta ação.</p>
                                <p>4.1.1.2. acompanhar o inventário do jogo ({{$settings->domain}}) na conta do jogo
                                    participante.</p>
                                <p>4.1.1.3. melhorar regularmente o complexo de hardware e software, mas não
                                    garante que o software do Jogo está livre de erros e que o hardware
                                    não sairá dos parâmetros de trabalho e funcionará sem problemas.</p>
                                <p>4.1.1.4. Observar o regime de confidencialidade dos dados pessoais do
                                    participante
                                    de acordo com a cláusula 6 deste contrato.</p>
                                <p>4.1.1.5. O recebimento de pagamentos pelo usuário pode ser limitado pela
                                    administração a seu próprio critério.
                                    critério.</p>
                                <p>4.1.1.6. Qualquer pessoa legalmente em posse de equipamento de jogo
                                    ({{$settings->domain}}),
                                    o pagamento é feito na quantia de dinheiro determinada pelo valor de mercado
                                    ({{$settings->domain}}), menos o custo desta operação.</p>
                                <p>4.1.2. O organizador tem o direito:</p>
                                <p>4.1.2.2. fornecer ao participante serviços pagos adicionais, cuja lista, bem como
                                    também o procedimento e as condições para o uso são determinados por este
                                    acordo,
                                    regras do playground e outros anúncios do organizador. Ao mesmo tempo, o
                                    organizador
                                    tem o direito de alterar o número e o escopo dos serviços pagos oferecidos a
                                    qualquer momento, seus
                                    custo, nome, tipo e efeito de uso.</p>
                                <p>4.1.2.3. suspender este acordo e desconectar o participante de
                                    participação no jogo durante a investigação sobre a suspeita de um participante
                                    em
                                    violação
                                    deste Acordo e as regras do parque infantil.</p>
                                <p>4.1.2.4. expulsar um participante do jogo se determinar que o participante violou
                                    este
                                    acordo ou regras estabelecidas na quadra de jogo, de acordo com o item 5.10
                                    deste
                                    acordos.</p>
                                <p>4.1.2.5. interromper parcial ou totalmente a prestação de serviços sem aviso
                                    prévio
                                    participante nos trabalhos de reconstrução, reparação e manutenção em
                                    local.</p>
                                <p>4.1.2.6. O organizador não se responsabiliza pelo mau funcionamento
                                    software de jogo. O participante usa o software
                                    princípio “COMO ESTÁ” (“COMO ESTÁ”). Se o organizador determinar que o jogo está
                                    com
                                    defeito
                                    (Erro) no trabalho do site, então os resultados que ocorreram durante o
                                    incorreto
                                    trabalho do software, pode ser cancelado ou ajustado de acordo com
                                    critério do organizador. O participante concorda em não apelar ao organizador
                                    sobre
                                    qualidade, quantidade, ordem e tempo das oportunidades de jogo fornecidas a ele
                                    e
                                    Serviços.</p>
                                <p>Garantias e responsabilidade 5.1. O Organizador não garante a permanência e
                                    continuidade
                                    acesso ao parque infantil e aos seus serviços em caso de problemas técnicos
                                    e/ou imprevistos, incluindo: trabalho defeituoso ou não
                                    funcionamento de provedores de Internet, servidores de informação, Banco e meios
                                    de
                                    pagamento
                                    sistemas, bem como ações ilegais de terceiros. O organizador fará todos os
                                    esforços
                                    para evitar falhas, mas não é responsável por falhas técnicas temporárias e
                                    interrupções no funcionamento do Jogo, independentemente dos motivos de tais
                                    falhas.</p>
                                <p>5.2. O participante concorda plenamente que o organizador não pode ser
                                    responsabilizado por
                                    perdas do participante decorrentes de ações ilegais de terceiros,
                                    visando violar o sistema de segurança de equipamentos eletrônicos e bases
                                    dados do jogo, ou devido a interrupções fora do controle do organizador,
                                    suspensão
                                    ou encerramento da operação dos canais e redes de comunicação utilizados para
                                    interagir com
                                    participante, bem como ações ilegais ou irracionais de sistemas de pagamento,
                                    bem
                                    como
                                    bem como de terceiros.</p>
                                <p>5.3. O organizador não é responsável por perdas incorridas como resultado de
                                    uso ou não uso por um participante de informações sobre o Jogo, regras do jogo e
                                    o Jogo em si e não é responsável por perdas ou outros danos incorridos pelo
                                    participante
                                    devido a suas ações não qualificadas e ignorância das regras do jogo ou de sua
                                    Erroх nos cálculos;</p>
                                <p>5.4. O participante concorda em usar o playground por sua própria vontade e
                                    por sua conta e risco. O organizador não dá ao participante qualquer garantia de
                                    que
                                    ele se beneficiará ou se beneficiará da participação no jogo. O grau de
                                    participação
                                    no Jogo é determinado
                                    pelo próprio participante.</p>
                                <p>5.5. O organizador não é responsável perante o participante pelas ações de
                                    terceiros
                                    participantes.</p>
                                <p>5.6. Em caso de disputas e desentendimentos no pátio de recreio, a decisão
                                    do organizador é final e o participante concorda plenamente com ela. Todas as
                                    disputas
                                    e disputas decorrentes ou relacionadas a este Contrato estarão sujeitas a
                                    resolução através de negociações. Se não for possível chegar a um acordo através
                                    negociações, disputas, desacordos e reivindicações decorrentes deste Contrato,
                                    estão sujeitos a resolução de acordo com a legislação atual do Brasil</p>
                                <p>5.7. O Organizador não arca com o ônus tributário do Participante. O participante
                                    compromete-se
                                    incluir de forma independente possíveis rendimentos recebidos na declaração de
                                    imposto em
                                    de acordo com as leis de seu país de residência.</p>
                                <p>5.8. O Organizador pode fazer alterações neste Acordo, nas regras do jogo
                                    sites e outros documentos unilateralmente. Em caso de alterações
                                    documentos O organizador coloca as versões mais recentes dos documentos no site
                                    do
                                    jogo
                                    sites. Todas as alterações entram em vigor a partir do momento da postagem. O
                                    participante tem o direito
                                    rescindir este Acordo dentro de 3 dias se ele não concordar com o feito
                                    mudanças. Neste caso, a rescisão do Contrato é feita de acordo com a cláusula
                                    5.9
                                    presente acordo. É da responsabilidade do Participante visitar regularmente
                                    o site oficial do Jogo para se familiarizar com os documentos oficiais e
                                    notícia.</p>
                                <p>5.9. O Participante tem o direito de rescindir este Contrato unilateralmente
                                    sem salvar uma conta de jogo. Neste caso, todos os custos associados à
                                    participação
                                    no jogo,
                                    O participante não será compensado ou devolvido.</p>
                                <p>5.10. O Organizador tem o direito de rescindir este Acordo unilateralmente
                                    ordem, bem como realizar outras ações que limitem as possibilidades no Jogo, em
                                    em relação a um participante ou grupo de participantes que são cúmplices de
                                    violações dos termos deste Acordo. Ao mesmo tempo, todos os atributos do jogo,
                                    inventário ({{$settings->domain}}) na conta e na conta do jogo
                                    participante ou grupo de participantes, bem como todas as despesas não
                                    reembolsáveis
                                    ​​e não reembolsáveis.
                                    compensado, a menos que o Organizador, a seu exclusivo critério, considere
                                    apropriado para compensar as despesas do participante ou grupo de
                                    participantes.</p>
                                <p>5.11. O Organizador e o Participante estão isentos de responsabilidade em caso de
                                    circunstâncias de força maior (circunstâncias de força maior), incluindo
                                    incluem, mas não estão limitados a: desastres naturais, guerras, incêndios
                                    (incêndios),
                                    inundações, explosões, terrorismo, motins, distúrbios civis, atos do governo
                                    ou autoridade reguladora, ataques de hackers, ausências, não funcionamento ou
                                    falhas
                                    operação de fonte de alimentação, provedores de serviços de Internet, redes de
                                    comunicação ou outros sistemas,
                                    redes e serviços. A parte experimentando tais circunstâncias deve, dentro de
                                    limites
                                    razoáveis
                                    termos e de forma acessível para notificar a outra parte de tais
                                    circunstâncias.</p>
                                <h2>Confidencialidade</h2>
                                <p>6.1. A condição de confidencialidade se aplica às informações que o Organizador
                                    pode receber sobre o Participante durante sua estada no site do Jogo e que pode
                                    ser associado a este usuário específico. Organizador automaticamente
                                    recebe e grava no servidor registra informações técnicas do seu navegador: IP
                                    endereço, endereço da página solicitada, etc. O organizador pode gravar
                                    "cookies" em
                                    computador do usuário e posteriormente usá-los. O organizador garante que
                                    os dados fornecidos pelo participante ao se registrar no Jogo serão usados
                                    Organizador apenas dentro do Jogo.</p>
                                <p>6.2. O Organizador tem o direito de transferir informações pessoais sobre o
                                    Participante para terceiros
                                    somente se:</p>
                                <p>6.2.1. O participante expressou o desejo de divulgar esta informação;</p>
                                <p>6.2.2. Sem isso, o Integrante não poderá utilizar o produto ou serviço desejado,
                                    em
                                    em particular - informações sobre nomes (apelidos), atributos do jogo - podem
                                    ser
                                    acessadas
                                    outros participantes;</p>
                                <p>6.2.3. Isso é exigido pela lei e/ou autoridades internacionais, sujeito a
                                    procedimento legal;</p>
                                <p>6.2.4. O Participante viola este Acordo e as regras do playground.</p>
                                <h2>Outras provisões</h2>
                                <p>7.1. A nulidade de uma parte ou parágrafo (alínea) deste contrato não implica
                                    nulidade de todas as outras partes e parágrafos (alíneas).</p>
                                <p>7.2. O prazo deste Contrato é definido para todo o período de validade
                                    playground, ou seja, por tempo indeterminado, e não implica em data de término
                                    deste acordo.</p>
                                <p>7.3. Ao inscrever-se e estar no parque infantil, o participante reconhece que
                                    leu, entendeu e aceita integralmente os termos deste Contrato, bem como as
                                    regras
                                    jogos e outros documentos oficiais.</p>
                                <p>7.4. É proibido o uso de correspondência temporária (descartável), para uso de
                                    tais
                                    a conta será excluída e medidas serão tomadas. O correio único é definido
                                    administração do local. De acordo com esta definição, correspondência entregue a
                                    domínios adquiridos, os domínios adquiridos são determinados pela administração
                                    do
                                    site.</p>
                                <p>7.4.1. É proibido cadastrar mais de uma conta através do site. Tais ações
                                    levar ao bloqueio de conta</p>
                                <p>7.4.2. Equilíbrio artificial brincando com scripts da Ajuda - categoricamente
                                    proibido. O membro que será visto será bloqueado</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="fairModal" tabindex="-1" role="dialog" aria-labelledby="tosModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog fair-modal modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <button class="modal-close" data-dismiss="modal" aria-label="Close">
                            <svg class="icon icon-close">
                                <use xlink:href="/img/symbols.svg#icon-close"></use>
                            </svg>
                        </button>
                        <div class="fair-modal__container">
                            <h1><span>Fairplay</span></h1><span>Nosso sistema de fair play garante que não podemos manipular o resultado de um jogo.<br><br>Assim como você corta o baralho em um cassino real. Esta implementação lhe dá total tranquilidade enquanto joga, sabendo que não podemos "ajustar" as apostas a nosso favor.
<br><br></span>
                            <div class="collapse-component">
                                <div class="form-field">
                                    <div class="input-valid">
                                        <input class="input-field input-with-icon" name="hash" id="gameHash"
                                               placeholder="Digite um Hash">
                                        <div class="input-icon">
                                            <svg class="icon icon-coin balance">
                                                <use xlink:href="/img/symbols.svg#icon-fairness"></use>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-rotate checkHash"><span>Verificar</span></button>
                            <div class="fair-table" style="display: none;">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th><span># ID </span></th>
                                        <th><span>NÚMERO GERADO</span></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td id="gameRound"></td>
                                        <td id="gameNumber"></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="whatisthisModal" tabindex="-1" role="dialog"
                 aria-labelledby="whatisthisModalLabel" aria-hidden="true">
                <div class="modal-dialog fair-modal modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <button class="modal-close" data-dismiss="modal" aria-label="Close">
                            <svg class="icon icon-close">
                                <use xlink:href="/img/symbols.svg#icon-close"></use>
                            </svg>
                        </button>
                        <div class="fair-modal__container">
                            <h1><span>Loteria</span></h1>
                            <span>{{$settings->min_dep_withdraw}}<br><br> {{$settings->requery_bet_perc}}{{$settings->requery_perc}}<br><br> </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog user-modal modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <button class="modal-close" data-dismiss="modal" aria-label="Close">
                            <svg class="icon icon-close">
                                <use xlink:href="/img/symbols.svg#icon-close"></use>
                            </svg>
                        </button>
                        <div class="user-modal__container"></div>
                    </div>
                </div>
            </div>
            @if(session('error'))
                <script>
                    $.notify({
                        type: 'error',
                        message: "{{ session('error') }}"
                    });
                </script>
            @elseif(session('success'))
                <script>
                    $.notify({
                        type: 'success',
                        message: "{{ session('success') }}"
                    });
                </script>
            @endif


            </body>
            </html>

            <style type="text/css">
                .chat {
                    height: calc(100% - 100px);
                }
            </style>
            <script type="text/javascript" src="/js/main.js?v=4"></script>
    @endif

@endif
