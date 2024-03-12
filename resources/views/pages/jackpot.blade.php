@extends('layout')

@section('content')
<link rel="stylesheet" href="/css/jackpot.css">
<script type="text/javascript" src="/js/chart.min.js"></script>
<script type="text/javascript" src="/js/chartjs-plugin-labels.js"></script>
<script type="text/javascript" src="/js/jquery.kinetic.min.js"></script>
<script type="text/javascript" src="/js/jackpot.js"></script>
<div class="section game-section">
    <div class="container">
        <div class="game jackpot-prefix">
            <div class="game-sidebar">
                <div class="sidebar-block">
                    <div class="bet-component">
                        <div class="bet-form">
                            <div class="form-row">
                                <label>
                                    <div class="form-label"><span>Сумма ставки</span></div>
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
                                            <div class="input-validation top"><span>Недостаточно монет</span></div>
                                        </div>
                                    </div>
                                </label>
                            </div>
							<div class="button-group__wrap">
								<div class="button-group__content rooms">
									@foreach($rooms->sortBy('id') as $r)
									<a class="btn {{$r->name}}" data-room="{{$r->name}}"><span>{{$r->title}}</span></a>
									@endforeach
								</div>
								<span class="button-group-label"><span>Комнаты</span></span>
							</div>
                            <button type="button" class="btn btn-green btn-play"><span>Сделать ставку</span></button>
                        </div>
                        <div class="bet-footer">
                            <button type="button" class="btn btn-light" data-toggle="modal" data-target="#fairModal">
                                <svg class="icon icon-fairness">
                                    <use xlink:href="/img/symbols.svg#icon-fairness"></use>
                                </svg><span>Честная игра</span>
                            </button>
                            <a class="btn btn-light" href="/jackpot/history">
                                <svg class="icon icon-history">
                                    <use xlink:href="/img/symbols.svg#icon-history"></use>
                                </svg><span>История игр</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
			<div class="game-component">
				<div class="game-block">
					<div class="progress-wrap">
						<div class="progress-item left">
							<div class="title">Мин. сумма: <span id="minBet">0</span> <svg class="icon icon-coin"><use xlink:href="/img/symbols.svg#icon-coin"></use></svg></div>
							<div class="title">Макс. сумма: <span id="maxBet">0</span> <svg class="icon icon-coin"><use xlink:href="/img/symbols.svg#icon-coin"></use></svg></div>
						</div>
						<div class="progress-item right">
							<div class="title">Игра #<span id="gameId">0</span></div>
						</div>
					</div>
					<div class="game-area__wrap">
						<div class="game-area">
							<div class="game-area-content">
								<div class="circle">
									<div class="fix-circle">
										<canvas id="circle" class="circle_jackpot"></canvas>
									</div>
									<div class="time">
										<div class="spinner" style="transform: rotate(0deg);">
											<svg class="icon"><use xlink:href="/img/symbols.svg#icon-picker"></use></svg>
										</div>
										<div class="block">
											<div class="title">Банк игры</div>
											<div class="value" id="value">???</div>
											<div class="line"></div>
											<div class="title">До начала</div>
											<div class="value" id="timer">00:00</div>
										</div>
									</div>
								</div>
								<div class="hash">
									<span class="title">HASH:</span> <span class="text" id="hash">???</span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="jackpot-hits">
					<div class="carousel slide" id="carousel">
						<button type="button" class="btn btn-prev">
							<svg class="icon icon-left">
								<use xlink:href="/img/symbols.svg#icon-left"></use>
							</svg>
						</button>
						<div class="carousel-inner chances">
							<div class="carousel-item active" id="chances">
							</div>
						</div>
						<button type="button" class="btn btn-next">
							<svg class="icon icon-left">
								<use xlink:href="/img/symbols.svg#icon-left"></use>
							</svg>
						</button>
					</div>
				</div>
				@guest
				<div class="game-sign">
					<div class="game-sign-wrap">
						<div class="game-sign-block auth-buttons">
							Чтобы играть, необходимо быть авторизованным 
							<a href="/auth/vkontakte" class="btn">
								Войти через
								<svg class="icon icon-vk">
									<use xlink:href="/img/symbols.svg#icon-vk"></use>
								</svg>
							</a>
						</div>
					</div>
				</div>
				@endguest
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
						<div class="th">Игрок</div>
						<div class="th">Ставка</div>
						<div class="th">Процент</div>
						<div class="th">Билеты</div>
					</div>
				</div>
			</div>
			<div class="table-stats-wrap" style="min-height: 530px; max-height: 100%;">
				<div class="table-wrap" style="transform: translateY(0px);">
					<table class="table">
						<tbody id="bets">

						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection