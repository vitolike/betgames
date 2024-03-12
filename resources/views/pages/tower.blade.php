@extends('layout')

@section('content')
<link rel="stylesheet" href="/css/tower.css">
<script src="/js/tower.js"></script>
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
                                            <input type="text" name="sum" class="input-field no-bottom-radius" value="0.00" id="sum">
                                            <button type="button" class="btn btn-bet-clear" data-action="clear">
												<svg class="icon icon-close">
													<use xlink:href="/img/symbols.svg#icon-close"></use>
												</svg>
                                            </button>
                                            <div class="buttons-group no-top-radius">
                                                <button type="button" class="btn btn-action" data-action="plus" data-value="0.10">+0.10</button>
                                                <button type="button" class="btn btn-action" data-action="plus" data-value="0.50">+0.50</button>
                                                <button type="button" class="btn btn-action" data-action="plus" data-value="1">+1.00</button>
                                                <button type="button" class="btn btn-action" data-action="multiply" data-value="2">2X</button>
                                                <button type="button" class="btn btn-action" data-action="divide" data-value="2">1/2</button>
                                                <button type="button" class="btn btn-action" data-action="all">MAX</button>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            </div>
							<div class="button-group__wrap">
								<div class="button-group__content rooms">
									<a class="btn selectBomb isActive" data-bomb="1"><span>1</span></a>
									<a class="btn selectBomb" data-bomb="2"><span>2</span></a>
									<a class="btn selectBomb" data-bomb="3"><span>3</span></a>
									<a class="btn selectBomb" data-bomb="4"><span>4</span></a>
								</div>
								<span class="button-group-label"><span>Numero de Bombas</span></span>
							</div>
                            <button type="button" class="btn btn-green btn-play"><span>Jogar</span></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="game-component">
				<div class="tower_tower__1ms3K">
					<!-- START LEFT -->
					<div class="tower_payouts__3WyFB">
						<div class="tower_payoutsWrap__1grO5">
							<div class="tower_payoutItem__1V4dZ" id="coeff_1"></div>
							<div class="tower_payoutItem__1V4dZ" id="coeff_2"></div>
							<div class="tower_payoutItem__1V4dZ" id="coeff_3"></div>
							<div class="tower_payoutItem__1V4dZ" id="coeff_4"></div>
							<div class="tower_payoutItem__1V4dZ" id="coeff_5"></div>
							<div class="tower_payoutItem__1V4dZ" id="coeff_6"></div>
							<div class="tower_payoutItem__1V4dZ" id="coeff_7"></div>
							<div class="tower_payoutItem__1V4dZ" id="coeff_8"></div>
							<div class="tower_payoutItem__1V4dZ" id="coeff_9"></div>
							<div class="tower_payoutItem__1V4dZ" id="coeff_10"></div>
						</div>
					</div>
					<!-- END LEFT -->

					<!-- START CENTER -->
					<div class="tower_component__3oM-1">
						<div class="tower_tiles__2V8Cz tile_notAutomated__2MIRN" id="TowerComponent">

						</div>
					</div>
					<!-- END CENTER -->
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
@endsection

<script src="//code.jivosite.com/widget/CqTxJBPTjs" async></script>
