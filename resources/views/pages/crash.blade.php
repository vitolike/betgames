@extends('layout')

@section('content')
<meta name="game" content="crash" />
@if($bet)
<script>
	window.bet = parseInt('{{ $bet->price }}');
	window.isCashout = false;
	window.withdraw = parseFloat('{{ $bet->withdraw }}');
</script>
@endif
<link rel="stylesheet" href="{{ asset('/css/crash.css') }}">

<audio id="myAudio" preload="auto">
    <source src="/crashou3.mp3" type="audio/mpeg">
</audio>

<script src="https://cdn.jsdelivr.net/npm/chart.js@2.7.3/dist/Chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/howler@2.1.3/dist/howler.min.js"></script>


<script type="text/javascript" src="{{ asset('/js/crash.js') }}"></script>
<div class="section game-section">
    <div class="container">
        <div class="game crash-prefix">
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

                                    <div class="form-row">
										<label>
											<div class="form-label"><span>Multiplicador</span></div>
											<div class="form-field">
												<div class="input-valid">
													<input class="input-field" value="5" id="betout"><div class="input-suffix"><span>5</span>&nbsp;x</div>
												</div>
											</div>
										</label>
                                    </div>
                                </label>
                            </div>
                            <button type="button" class="btn btn-green btn-play" style="@if(!is_null($bet)) display : none; @endif"><span>Apostar</span></button>
                            <button type="button" class="btn btn-green btn-withdraw" style="@if(is_null($bet)) display : none; @endif"><span>Retirar</span></button>
                        </div>
						<br>

						</button>
                        <div class="bet-footer">
									<button id="toggleAudioButton" class="btn btn-light" style="justify-content: center;"><i class="fas fa-volume-up" style="margin-right: 10px;"></i>

                            <button type="button" class="btn btn-light" data-toggle="modal" data-target="#fairModal">
                                <svg class="icon icon-fairness">
                                    <use xlink:href="/img/symbols.svg#icon-fairness"></use>
                                </svg><span>Fairplay</span>
                            </button>

                        </div>
                    </div>
                </div>
            </div>

			<div class="game-component">
				<div class="game-block">
					<div class="game-area__wrap">
						<div class="game-area">
							<div class="game-area-content">
								<div class="crash__connected">
											<audio id="myAudio">
												<source src="/crashou3.mp3" type="audio/mpeg">
												</audio>
									<canvas id="crashChart" height="642" width="800" style="background-color: #000; width: 100%; height: auto; border-radius: 7px; padding: 10px;border-color: #d1113f; border-style: solid;"></canvas>
									<h2><span id="chartInfo" style="margin:auto; align-items: center; justify-content: center; text-align: center;">Conectando... </span></h2>
								</div>
								<div class="hash">
									<span class="title">HASH:</span> <span class="text">{{ $game['hash'] }}</span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="game-history__wrap">
					<div class="game-history">
						@foreach($history as $m)
						<div class="item checkGame" data-hash="{{$m->hash}}">
							<div class="item-bet" style="color: {{$m->color}};">x{{ number_format($m->multiplier, 2, '.', '') }}</div>
						</div>
						@endforeach
					</div>
				</div>
			</div>
        </div>
    </div>
</div>


<div class="section bets-section">
	<div class="container">
		<div class="game-stats">
			<div class="table-heading">
				<div class="thead">
					<div class="tr">
						<div class="th">Jogador</div>
						<div class="th">Valor</div>
						<div class="th">Multiplicador</div>
						<div class="th">Lucrou</div>
					</div>
				</div>
			</div>
			<div class="table-stats-wrap" style="min-height: 530px; max-height: 100%;">
				<div class="table-wrap" style="transform: translateY(0px);">
					<table class="table">
						<tbody id="bets">
							@foreach($game['bets'] as $bet)
							<tr>
								<td class="username">
									<button type="button" class="btn btn-link" data-id="{{ $bet['user']['unique_id'] }}">
										<span class="sanitize-user">
											<div class="sanitize-avatar"><img src="{{ $bet['user']['avatar'] }}" alt=""></div>
											<span class="sanitize-name">{{ $bet['user']['username'] }}</span>
										</span>
									</button>
								</td>
								<td>
									<div class="bet-number">
										<span class="bet-wrap">
											<span>R$ {{ $bet['price'] }}</span>
										</span>
									</div>
								</td>
								<td>
                                    @if($bet['status'] == 1)
                                        {{ $bet['withdraw'] }}x
                                    @else
                                        ~
                                    @endif
                                </td>
								<td>
									@if($bet['status'] == 1)
									<span class="bet-wrap win">
										<span>R$ {{ $bet['won'] }}</span>
									</span>
									@else
									<span class="bet-wrap wait">
									</span>
									@endif
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

<script src="//code.jivosite.com/widget/CqTxJBPTjs" async></script>