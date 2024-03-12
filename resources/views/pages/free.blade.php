@extends('layout')

@section('content')
<link rel="stylesheet" href="/css/free.css">
<script src="https://d3js.org/d3.v3.min.js"></script>
<script type="text/javascript" src="/js/bonus.js"></script>
<script>
	var check = {{$check}};
</script>
<div class="section">
    <div class="dailyFree_dailyFree">
        <div class="quest-banner daily">
            <div class="caption">
                <h1><span>Bônus Diário</span></h1></div>
            <div class="info"><span>Conclua missões únicas e diárias e ganhe dinheiro para jogar na Rosh Bet</span></div>
        </div>
        <div class="dailyFree_wrap">
            <div class="dailyFree_free">
                <div class="form_container">
                    <div class="wheel_half">
                        <div class="wheel_wheel">
                            <div id="fortuneWheel" class="wheel_flex">
                            
                            </div>
                            <div class="wheel_ring">
                                <div class="wheel_ringInner"></div>
                            </div>
                            <div class="wheel_pin">
                                <svg width="22" height="47" viewBox="0 0 22 47" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M21.78 10.89c0 6.01-10.9 35.37-10.9 35.37S0 16.9 0 10.89a10.9 10.9 0 0 1 21.78 0z" fill="#FFD400"></path>
                                    <circle fill="#E4A51C" cx="10.89" cy="10.48" r="6.44"></circle>
                                    <circle fill="#FFF" id="dotCircle" cx="10.89" cy="10.48" r="4.1"></circle>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="form_info">
                        <div class="form_wrapper group" style="display: none">
                        	<div class="form_text">
								<span>Digitar <a href="{{$settings->vk_url}}" target="_blank">para o nosso grupo</a></span>
								<br><span>e levante-se para <strong>{{$max}} moedas para conta de bônus</strong></span>
                        	</div>
							<div class="form_block">
								@if(!$check)
								<div class="form_value">{{$settings->bonus_group_time}} eu<div class="form_text">recarrega</div></div>
								<span id="spin-wheel-button" class=""><button type="button" class="btn" data-toggle="modal" data-target="#captchaModal">gira a roda</button></span>
                       			@else
                       			<div class="form_recharge"><span>Recarregue via:</span><div class="form_timeLeft">00:00:00</div></div>
                       			@endif
                        	</div>
                        </div>
                        <div class="form_wrapper refs" style="display: none">
                        	<div class="form_text">
                            convidar <strong>{{$settings->max_active_ref}} referências ativas <div class="popover-tip-block" id="purposeTip"><div class="popover-tip-icon"><svg class="icon icon-help"><use xlink:href="/img/symbols.svg#icon-help"></use></svg></div></div></strong>
                        		<br> e chegar a <strong>{{$max_refs}} moedas para conta de bônus</strong>
                        	</div>
                        	<div class="form_block">
                        		@if(!$refLog)
                        		<div class="form_value">{{$activeRefs}} / {{$settings->max_active_ref}}<div class="form_text">referência</div></div>
                        		<span id="spin-wheel-button" class=""><button type="button" class="btn" data-toggle="modal" data-target="#captchaModal">Крутить колесо</button></span>
                        		@else
                        		<div class="form_recharge">Вы получили данный бонус!</div>
                        		@endif
                        	</div>
                        </div>
                    </div>
                </div>
                <div class="list_list">
                    <div class="list_item group" data-bonus="group">
                        <svg class="icon icon-faucet">
                            <use xlink:href="/img/symbols.svg#icon-faucet"></use>
                        </svg>
                        <div class="list_text"><span>Вступите в нашу группу вк</span> <span>и получайте до <strong>{{$max}} монет на бонусный счет</strong></span> <span>раз в {{$settings->bonus_group_time}} мин</span></div>
                    </div>
                    <div class="list_item refs" data-bonus="refs">
                        <svg class="icon icon-faucet">
                            <use xlink:href="/img/symbols.svg#icon-faucet"></use>
                        </svg>
                        <div class="list_text"><span>Пригласите <strong>{{$settings->max_active_ref}} рефералов</strong> <br> и получите до <strong>{{$max_refs}} монет на бонусный счет</strong></span></div>
                    </div>
                    <div class="list_item list_disabled">
                        <div class="list_notAvailable">Недоступно</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection