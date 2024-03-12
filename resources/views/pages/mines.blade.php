@extends('layout')

@section('content')
    <link rel="stylesheet" href="/css/mines.css">
    <link rel="stylesheet" href="/css/tower.css">

    <div class="section game-section">
        <div class="container">
            <div class="game">
                <div class="game-sidebar">
                    <div class="sidebar-block">
                        <div class="bet-component">
                            <div class="bet-form">
                                <div class="form-row">
                                    <label>
                                        <div class="form-label"><span>Valor da Aposta</span></div>
                                        <div class="form-row">
                                            <div class="form-field">
                                                <input type="text" class="input-field no-bottom-radius" value="0.00"
                                                       id="sum">
                                                <button type="button" class="btn btn-bet-clear" data-action="clear">
                                                    <svg class="icon icon-close">
                                                        <use xlink:href="/img/symbols.svg#icon-close"></use>
                                                    </svg>
                                                </button>
                                                <div class="buttons-group no-top-radius">
                                                    <button type="button" class="btn btn-action" data-action="plus"
                                                            data-value="0.10">+0.10
                                                    </button>
                                                    <button type="button" class="btn btn-action" data-action="plus"
                                                            data-value="0.50">+0.50
                                                    </button>
                                                    <button type="button" class="btn btn-action" data-action="plus"
                                                            data-value="1">+1.00
                                                    </button>
                                                    <button type="button" class="btn btn-action" data-action="multiply"
                                                            data-value="2">2X
                                                    </button>
                                                    <button type="button" class="btn btn-action" data-action="divide"
                                                            data-value="2">1/2
                                                    </button>
                                                    <button type="button" class="btn btn-action" data-action="all">MAX
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                </div>


                                <div class="button-group__wrap">

                                    <div class="button-group__content rooms">
                                        <a class="btn selectBomb bomb_3 isActive"
                                           onclick="$('#BombMines').val(3);updateMinesX()"><span>3</span></a>
                                        <a class="btn selectBomb bomb_5"
                                           onclick="$('#BombMines').val(5);updateMinesX()"><span>5</span></a>
                                        <a class="btn selectBomb bomb_10"
                                           onclick="$('#BombMines').val(10);updateMinesX()"><span>10</span></a>
                                        <a class="btn selectBomb bomb_24"
                                           onclick="$('#BombMines').val(24);updateMinesX()"><span>24</span></a>
                                    </div>
                                    <input type="number" min=2 max=24 onkeyup="updateMinesX()" autocomplete="off"
                                           class="input-field no-bottom-radius" value="3" id="BombMines">
                                    <span class="button-group-label"><span>Numero de Bombas</span></span>
                                </div>
                                <button type="button" style="display: none;" class="btn btn-green btn-play"
                                        id="btnPlayMines" onclick="playMines()"><span>Jogar</span></button>
                                <button type="button" style="display: none;" class="btn btn-green btn-play"
                                        id="btnFinishMines" onclick="finishMines()"><span>Retirar <span id="winMines">0.00</span></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="game-component">

                    <div class="tower_tower__1ms3K">
                        <div class="mines_Component__2Yz2z">
                            <div class="mines_Wrapper__NRZQf">
                                @for($i = 0; $i < 25; $i++)
                                    <button onclick="clickMines({{$i}})" type="button" class="mines_Btn__2KHHl"
                                            disabled=""><span class="mines_Appear__RPiih"></span><span
                                            class="mines_BombFrame__3sPYa"></span><span
                                            class="mines_Main__IqyTI"></span></button>
                                @endfor
                            </div>
                            <div class="tip_Wrapper__1MsPX">
                                <div class="tip_Container__1EtUe">
                                    <div class="tip_multiplier__3ebYL">0.00×</div>
                                    <hr class="tip_hr__3uJW-"/>
                                    <div class="tip_Payout__2HHih">
                        <span class="tip_Value__Gs9aE">
                            <span class="bet_Bet__Jm8_0"><span class="bet_Content__3GG7k">0.00</span><span
                                    class="bet_Icon__cQKUb bet_widthAuto__3k9tk">₽</span></span>
                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="progress_Wrapper__3SvlG">
                                <div class="progress_Item__1LJTI">
                                    <div class="progress_Img__dzVuF"><img src="img/gemfyre.png" alt="" draggable="false"/>
                                    </div>
                                    <span class="progress_Number__3ktxc" id="count_gems">22</span>
                                </div>
                                <div class="progress_Item__1LJTI">
                                    <div class="progress_Img__dzVuF">
                                        <img src="img/bomb2.png" alt="" draggable="false"/></div>
                                    <span class="progress_Number__3ktxc" id="count_bombs">3</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="hits_Hits__1OApa">
                        <div style="position: relative;height: 60px;" class="">
                            <div class="xs hits_HitRow__2xXX"
                                 style="overflow-y: hidden;overflow-x: auto;position: absolute;">
                            </div>
                        </div>

                    </div>

                    @guest
                        <div class="game-sign">
                            <div class="game-sign-wrap">
                                <div class="game-sign-block auth-buttons">
                                    Você precisa estar logado para jogar
                                    <button type="button" class="btn" id="loginRegister">Entrar ou Cadastrar</button>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endguest
                </div>

            </div>
        </div>
    </div>
    <script type="text/javascript">
        function getCoff(count, steps) {
            var coeff = 1;
            for (var i = 0; i < (25 - count) && steps > i; i++) {
                coeff *= ((25 - i) / (25 - count - i));
            }
            return coeff;
        }

        function updateMinesX() {
            $('.hits_HitRow__2xXX').html('')
            $('.selectBomb').removeClass('isActive')
            BombMines = Number($('#BombMines').val())
            $('.selectBomb.bomb_' + BombMines).addClass('isActive')

            $('#count_bombs').html(BombMines);
            $('#count_gems').html(25 - BombMines);

            for (let i = 0; i < 25 - BombMines; i++) {
                coeffMine = getCoff(BombMines, i + 1)
                $('.hits_HitRow__2xXX').append('<div class="items hits_Item__1SvQF" data-p="1" data-mine="1.19">\
                <div class="hits_coeff__1lz94">×' + coeffMine.toFixed(2) + '</div>\
                <div class="hits_hit__2qdbe">' + Number(i + 1) + ' Hit</div>\
                </div>\
                ')
            }
        }

        updateMinesX();

        function playMines() {
            $.post('/mines/play', {
                balance: localStorage.getItem('balance') || 'balance',
                bet: $('#sum').val(),
                mines: $('#BombMines').val()
            }).then(e => {

                // $('.mines__grid-item').removeClass('mines__grid-item--lose mines__grid-item--win')
                $('.mines_Btn__2KHHl').removeClass('mines_isRevealed__2GrLE')
                $('.mines_Btn__2KHHl .mines_Main__IqyTI').removeClass('mines_isRevealed__2GrLE mines_isGem__27D6B mines_isAnimate__1Tj9o')
                $('.mines_Btn__2KHHl .mines_Main__IqyTI').removeClass('mines_isRevealed__2GrLE mines_isMine__3mq94 mines_isAnimate__1Tj9o')
                $('.mines_Btn__2KHHl .mines_BombFrame__3sPYa').removeClass('mines_isAnimate__1Tj9o')

                $('#btnFinishMines').show();
                $('#btnPlayMines').hide();

                $('.mines_Btn__2KHHl').removeAttr('disabled', '')


                $('.hits_Item__1SvQF').removeClass('active hits_current__1ROZd')


                $('.hits_HitRow__2xXX').stop().animate({
                    scrollLeft: `0px`
                }, 800);


            }).fail(e => {

                $.notify({
                    type: 'error',
                    message: JSON.parse(e.responseText).message
                });
            })
        }

        function getMines() {
            $.post('/mines/get').then(e => {

                $('#btnFinishMines').show();

                $('#sum').val(e.game['bet'])
                $('#BombMines').val(e.game['mines'])


                updateMinesX()

                $('.mines_Btn__2KHHl').removeAttr('disabled', '')

                step = e.game.step
                $('.hits_Item__1SvQF').removeClass('active hits_current__1ROZd')
                $('.hits_Item__1SvQF:eq(' + step + ')').addClass('active hits_current__1ROZd')

                $('.hits_HitRow__2xXX').stop().animate({
                    scrollLeft: `${(step - 1) * 108}px`
                }, 800);

                click = JSON.parse(e.game.click)
                click.forEach(function (item, i, arr) {
                    num = item - 1
                    $('.mines_Btn__2KHHl:eq(' + num + ')').addClass('mines_isRevealed__2GrLE')
                    $('.mines_Btn__2KHHl:eq(' + num + ') .mines_Main__IqyTI').addClass('mines_isRevealed__2GrLE mines_isGem__27D6B mines_isAnimate__1Tj9o')

                });

                $('#winMines').html(Number(e.game.win).toFixed(2))

            }).fail(e => {
                $('#btnPlayMines').show();
            })
        }

        getMines();

        function clickMines(mine) {
            $.post('/mines/click', {mine: mine + 1}).then(e => {
                if (e.type == 1) {
                    $('#winMines').html(Number(e.game.win).toFixed(2))
                    $('.mines_Btn__2KHHl:eq(' + mine + ')').addClass('mines_isRevealed__2GrLE')
                    $('.mines_Btn__2KHHl:eq(' + mine + ') .mines_Main__IqyTI').addClass('mines_isRevealed__2GrLE mines_isGem__27D6B mines_isAnimate__1Tj9o')

                    if (e.gameOff == 1) {
                        finishMines()
                    }

                    step = e.game.step
                    $('.hits_Item__1SvQF').removeClass('active hits_current__1ROZd')
                    $('.hits_Item__1SvQF:eq(' + step + ')').addClass('active hits_current__1ROZd')

                    $('.hits_HitRow__2xXX').stop().animate({
                        scrollLeft: `${(step - 1) * 108}px`
                    }, 800);

                } else {
                    $('#winMines').html('0.00')
                    $('.mines_Btn__2KHHl').attr('disabled', '')
                    $('.mines_Btn__2KHHl:eq(' + mine + ')').addClass('mines_isRevealed__2GrLE')

                    $('.mines_Btn__2KHHl:eq(' + mine + ') .mines_Main__IqyTI').addClass('mines_isRevealed__2GrLE mines_isMine__3mq94 mines_isAnimate__1Tj9o')

                    $('.mines_Btn__2KHHl:eq(' + mine + ') .mines_BombFrame__3sPYa').addClass('mines_isAnimate__1Tj9o')


                    mines = JSON.parse(e.game.mines)
                    mines.forEach(function (item, i, arr) {
                        num = item - 1
                        $('.mines_Btn__2KHHl:eq(' + num + ') .mines_Main__IqyTI').addClass('mines_isRevealed__2GrLE mines_isMine__3mq94 mines_isAnimate__1Tj9o')

                        $('.mines_Btn__2KHHl:eq(' + num + ') .mines_BombFrame__3sPYa').addClass('mines_isAnimate__1Tj9o')

                    });

                    $('#btnFinishMines').hide();
                    $('#btnPlayMines').show();
                }

            }).fail(e => {

                $.notify({
                    type: 'error',
                    message: JSON.parse(e.responseText).message
                });

            })
        }


        function finishMines() {
            $.post('/mines/finish', {balance: localStorage.getItem('balance') || 'balance'}).then(e => {


                $('#winMines').html('0.00')
                $('#btnFinishMines').hide();
                $('#btnPlayMines').show();

                mines = JSON.parse(e.game.mines)
                mines.forEach(function (item, i, arr) {
                    num = item - 1
                    $('.mines_Btn__2KHHl').attr('disabled', '')

                    $('.mines_Btn__2KHHl:eq(' + num + ') .mines_Main__IqyTI').addClass('mines_isRevealed__2GrLE mines_isMine__3mq94 mines_isAnimate__1Tj9o')

                    $('.mines_Btn__2KHHl:eq(' + num + ') .mines_BombFrame__3sPYa').addClass('mines_isAnimate__1Tj9o')
                });


            }).fail(e => {

                $.notify({
                    type: 'error',
                    message: JSON.parse(e.responseText).message
                });
            })
        }

    </script>
@endsection

<script src="//code.jivosite.com/widget/CqTxJBPTjs" async></script>