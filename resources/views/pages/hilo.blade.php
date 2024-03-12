@extends('layout')

@section('content')
<link rel="stylesheet" href="/css/hilo.css">
<script type="text/javascript" src="/js/hilo.js"></script>
<div class="section game-section">
    <div class="container">
        <div class="game">
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
                                        </div>
                                    </div>
                                    <div class="bet-block btnToggle">
										<div class="hilo-bet">
											<button class="btn-bet bet-high" data-type="1">
												<div class="bet-chance">
													<div class="chance-icon">
														<svg class="icon icon-arrow-up">
															<use xlink:href="/img/symbols.svg#icon-arrow-up"></use>
														</svg>
													</div>
													<div class="chance-text"><span class="probability_hi">{{ $hilo['hi_perc'] }}%</span></div>
												</div>
												<div class="bet-text factor_hi">{{ $hilo['hi'] }}x</div>
											</button>
											<button class="btn-bet bet-low" data-type="2">
												<div class="bet-chance">
													<div class="chance-icon">
														<svg class="icon icon-arrow-down">
															<use xlink:href="/img/symbols.svg#icon-arrow-down"></use>
														</svg>
													</div>
													<div class="chance-text"><span class="probability_lo">{{ $hilo['lo_perc'] }}%</span></div>
												</div>
												<div class="bet-text factor_lo">{{ $hilo['lo'] }}x</div>
											</button>
										</div>
										<div class="hilo-bet">
											<button class="btn-bet bet-red" data-type="3">
												<div class="bet-chance">
													<div class="chance-text"><span>Красный</span></div>
												</div>
												<div class="bet-text">2.00x</div>
											</button>
											<button class="btn-bet bet-black" data-type="4">
												<div class="bet-chance">
													<div class="chance-text"><span>Черный</span></div>
												</div>
												<div class="bet-text">2.00x</div>
											</button>
										</div>
										<div class="hilo-bet">
											<button class="btn-bet bet-numb" data-type="5">
												<div class="bet-chance">
													<div class="chance-text"><span>2 - 9</span></div>
												</div>
												<div class="bet-text">1.50x</div>
											</button>
											<button class="btn-bet bet-letr" data-type="6">
												<div class="bet-chance">
													<div class="chance-text"><span>J Q K A</span></div>
												</div>
												<div class="bet-text">3.00x</div>
											</button>
										</div>
										<div class="hilo-bet">
											<button class="btn-bet bet-ka" data-type="7">
												<div class="bet-chance">
													<div class="chance-text"><span>K A</span></div>
												</div>
												<div class="bet-text">6.00x</div>
											</button>
											<button class="btn-bet bet-a" data-type="8">
												<div class="bet-chance">
													<div class="chance-text"><span>A</span></div>
												</div>
												<div class="bet-text">12.00x</div>
											</button>
										</div>
										<div class="hilo-bet">
											<button class="btn-bet bet-j" data-type="9">
												<div class="bet-chance">
													<div class="chance-text"><span>JOKER</span></div>
												</div>
												<div class="bet-text">24.00x</div>
											</button>
										</div>
                                    </div>
                                </label>
                            </div>
                            <button type="button" class="btn btn-green btn-play"><span>Сделать ставку</span></button>
                        </div>
                        <div class="bet-footer">
                            <button type="button" class="btn btn-light" data-toggle="modal" data-target="#fairModal">
                                <svg class="icon icon-fairness">
                                    <use xlink:href="/img/symbols.svg#icon-fairness"></use>
                                </svg><span>Честная игра</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
			<div class="game-component">
				<div class="game-block">
					<div class="hilo-history">
						@foreach($history as $item)
						<div class="hilo-history-card hilo-card hilo-card_sm hilo-card_{{$item->card_section}} checkGame" data-hash="{{$item->hash}}">
							<div class="hilo-card-num">
								{{ $item->card_name }}
							</div>
							<div class="hilo-history-feed">
								@if(!is_null($item->card_sign))
								<img src="/img/hilo-card-{{ ($item->card_sign == 'lo') ? 'arrow' : ($item->card_sign == 'hi') ? 'arrow' : 'eq' }}.png" class="hilo-history-feed-sign hilo-history-feed__comparison-sign_{{ ($item->card_sign == 'lo') ? 'lo' : (($item->card_sign == 'hi') ? 'hi' : 'eq') }}">
								@endif
							</div>
						</div>
						@endforeach
					</div>
					<div class="game-area__wrap">
						<div class="game-area">
							<div class="progress-wrap">
								<div class="progress-item left">
									<div class="title">Мин. сумма: <span id="minBet">{{$settings->hilo_min_bet}}</span> <svg class="icon icon-coin"><use xlink:href="/img/symbols.svg#icon-coin"></use></svg></div>
									<div class="title">Макс. сумма: <span id="maxBet">{{$settings->hilo_max_bet}}</span> <svg class="icon icon-coin"><use xlink:href="/img/symbols.svg#icon-coin"></use></svg></div>
								</div>
								<div class="progress-item right">
									<div class="title">Игра #<span id="gameId">{{$game->id}}</span></div>
								</div>
							</div>
							<div class="game-area-content">
								<div class="hilo-main">
									<div class="hilo-deck">
										<div class="hilo-deck-card placeholder-card"><div class="card-back"></div></div>
										<div class="hilo-deck-card placeholder-card"><div class="card-back"></div></div>
										<div class="hilo-deck-card placeholder-card"><div class="card-back"></div></div>
										<div class="hilo-deck-card placeholder-card"><div class="card-back"></div></div>
										<div class="hilo-deck-card placeholder-card"><div class="card-back"></div></div>
										<div class="hilo-deck-card placeholder-card"><div class="card-back"></div></div>
										<div class="hilo-card-region">
											<div class="hilo-deck-card">
												<div class="hilo-flipper" style="transform: rotateY(180deg) scale(1);">
													<div class="card-back"></div>
													@if($lastCard)
													<div class="card-front hilo-card hilo-card_{{ $lastCard->card_section }} hilo-card_bordered">
														<div class="hilo-label hilo-label_top">
															<div class="hilo-sign">{{ $lastCard->card_name }}</div>
														</div>
														<div class="hilo-countdown-wrapper">
															<div class="hilo-countdown">
																<svg style="display: block; width: 100%;" viewbox="0 0 100 100">
																	<path id="timer_back" d="M 50,50 m 0,-48.5 a 48.5,48.5 0 1 1 0,97 a 48.5,48.5 0 1 1 0,-97" fill-opacity="0" stroke="{{ ($lastCard->card_section == 'red') ? 'rgba(251, 15, 66, 0.4)' : (($lastCard->card_section == 'joker') ? 'rgba(255, 255, 255, 0.4)' : 'rgba(0, 0, 0, 0.4)') }}" stroke-width="3"></path>
																	<path id="timer" d="M 50,50 m 0,-48.5 a 48.5,48.5 0 1 1 0,97 a 48.5,48.5 0 1 1 0,-97" fill-opacity="0" stroke="{{ ($lastCard->card_section == 'red') ? '#fb0f42' : (($lastCard->card_section == 'joker') ? '#ffffff' : '#000000') }}" stroke-width="3" style="stroke-dasharray: 304.844, 304.844; stroke-dashoffset: 304.844;"></path>
																</svg>
															</div>
														</div>
														<div class="hilo-label hilo-label_bottom">
															<div class="hilo-sign">{{ $lastCard->card_name }}</div>
														</div>
													</div>
													@endif
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="hilo-statistics-region">
									<div class="hilo-statistics">
										<div class="hilo-statistics-top">
											<div class="hilo-stat_title">
												Статистика за 20 раундов
											</div>
											<div class="hilo-statistics-colors-ratio">
												<div class="ratio__red" style="width: {{ $stat['red_perc'] }}%;">
													{{ $stat['red_perc'] }}%
												</div>
												<div class="ratio__black" style="width: {{ $stat['black_perc'] }}%;">
													{{ $stat['black_perc'] }}%
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="hash">
									<span class="title">HASH:</span> <span class="text" id="hash">{{$game->hash}}</span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="hilo-footer">
					<div class="hilo-statistics__cards">
						<div class="card_stat_2">
							<div class="hilo-card hilo-card_sm hilo-card_spades">
								<div class="hilo-card__sign">2</div>
								<div class="hilo-statistics__card-frequency-progress" style="height: {{ $stat['cards']['two']['perc'] }}%;"></div>
							</div>
							<div class="hilo-statistics__card-label">
								<span>{{ $stat['cards']['two']['count'] }}</span>x
							</div>
						</div>
						<div class="card_stat_3">
							<div class="hilo-card hilo-card_sm hilo-card_spades">
								<div class="hilo-card__sign">3</div>
								<div class="hilo-statistics__card-frequency-progress" style="height: {{ $stat['cards']['three']['perc'] }}%;"></div>
							</div>
							<div class="hilo-statistics__card-label">
								<span>{{ $stat['cards']['three']['count'] }}</span>x
							</div>
						</div>
						<div class="card_stat_4">
							<div class="hilo-card hilo-card_sm hilo-card_spades">
								<div class="hilo-card__sign">4</div>
								<div class="hilo-statistics__card-frequency-progress" style="height: {{ $stat['cards']['four']['perc'] }}%;"></div>
							</div>
							<div class="hilo-statistics__card-label">
								<span>{{ $stat['cards']['four']['count'] }}</span>x
							</div>
						</div>
						<div class="card_stat_5">
							<div class="hilo-card hilo-card_sm hilo-card_spades">
								<div class="hilo-card__sign">5</div>
								<div class="hilo-statistics__card-frequency-progress" style="height: {{ $stat['cards']['five']['perc'] }}%;"></div>
							</div>
							<div class="hilo-statistics__card-label">
								<span>{{ $stat['cards']['five']['count'] }}</span>x
							</div>
						</div>
						<div class="card_stat_6">
							<div class="hilo-card hilo-card_sm hilo-card_spades">
								<div class="hilo-card__sign">6</div>
								<div class="hilo-statistics__card-frequency-progress" style="height: {{ $stat['cards']['six']['perc'] }}%;"></div>
							</div>
							<div class="hilo-statistics__card-label">
								<span>{{ $stat['cards']['six']['count'] }}</span>x
							</div>
						</div>
						<div class="card_stat_7">
							<div class="hilo-card hilo-card_sm hilo-card_spades">
								<div class="hilo-card__sign">7</div>
								<div class="hilo-statistics__card-frequency-progress" style="height: {{ $stat['cards']['seven']['perc'] }}%;"></div>
							</div>
							<div class="hilo-statistics__card-label">
								<span>{{ $stat['cards']['seven']['count'] }}</span>x
							</div>
						</div>
						<div class="card_stat_8">
							<div class="hilo-card hilo-card_sm hilo-card_spades">
								<div class="hilo-card__sign">8</div>
								<div class="hilo-statistics__card-frequency-progress" style="height: {{ $stat['cards']['eight']['perc'] }}%;"></div>
							</div>
							<div class="hilo-statistics__card-label">
								<span>{{ $stat['cards']['eight']['count'] }}</span>x
							</div>
						</div>
						<div class="card_stat_9">
							<div class="hilo-card hilo-card_sm hilo-card_spades">
								<div class="hilo-card__sign">9</div>
								<div class="hilo-statistics__card-frequency-progress" style="height: {{ $stat['cards']['nine']['perc'] }}%;"></div>
							</div>
							<div class="hilo-statistics__card-label">
								<span>{{ $stat['cards']['nine']['count'] }}</span>x
							</div>
						</div>
						<div class="card_stat_J">
							<div class="hilo-card hilo-card_sm hilo-card_spades">
								<div class="hilo-card__sign">J</div>
								<div class="hilo-statistics__card-frequency-progress" style="height: {{ $stat['cards']['J']['perc'] }}%;"></div>
							</div>
							<div class="hilo-statistics__card-label">
								<span>{{ $stat['cards']['J']['count'] }}</span>x
							</div>
						</div>
						<div class="card_stat_Q">
							<div class="hilo-card hilo-card_sm hilo-card_spades">
								<div class="hilo-card__sign">Q</div>
								<div class="hilo-statistics__card-frequency-progress" style="height: {{ $stat['cards']['Q']['perc'] }}%;"></div>
							</div>
							<div class="hilo-statistics__card-label">
								<span>{{ $stat['cards']['Q']['count'] }}</span>x
							</div>
						</div>
						<div class="card_stat_K">
							<div class="hilo-card hilo-card_sm hilo-card_spades">
								<div class="hilo-card__sign">K</div>
								<div class="hilo-statistics__card-frequency-progress" style="height: {{ $stat['cards']['K']['perc'] }}%;"></div>
							</div>
							<div class="hilo-statistics__card-label">
								<span>{{ $stat['cards']['K']['count'] }}</span>x
							</div>
						</div>
						<div class="card_stat_A">
							<div class="hilo-card hilo-card_sm hilo-card_spades">
								<div class="hilo-card__sign">A</div>
								<div class="hilo-statistics__card-frequency-progress" style="height: {{ $stat['cards']['A']['perc'] }}%;"></div>
							</div>
							<div class="hilo-statistics__card-label">
								<span>{{ $stat['cards']['A']['count'] }}</span>x
							</div>
						</div>
						<div class="card_stat_JOKER">
							<div class="hilo-card hilo-card_sm hilo-card_joker">
								<div class="hilo-card__sign">🃏</div>
								<div class="hilo-statistics__card-frequency-progress" style="height: {{ $stat['cards']['JOKER']['perc'] }}%;"></div>
							</div>
							<div class="hilo-statistics__card-label">
								<span>{{ $stat['cards']['JOKER']['count'] }}</span>x
							</div>
						</div>
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
						<div class="th">Тип</div>
						<div class="th">Коэф</div>
						<div class="th">Выигрыш</div>
					</div>
				</div>
			</div>
			<div class="table-stats-wrap" style="min-height: 530px; max-height: 100%;">
				<div class="table-wrap" style="transform: translateY(0px);">
					<table class="table">
						<tbody id="bets">
							@if($bets) @foreach($bets as $bet)
							<tr>
								<td class="username">
									<button type="button" class="btn btn-link" data-id="{{ $bet['unique_id'] }}">
										<span class="sanitize-user">
											<div class="sanitize-avatar"><img src="{{$bet['avatar']}}" alt=""></div>
											<span class="sanitize-name">{{$bet['username']}}</span>
										</span>
									</button>
								</td>
								<td>
									<div class="bet-number">
										<span class="bet-wrap">
											<span>0.03</span>
											<svg class="icon icon-coin {{$bet['balance']}}">
												<use xlink:href="/img/symbols.svg#icon-coin"></use>
											</svg>
										</span>
									</div>
								</td>
								<td>{{$bet['type']}}</td>
								<td>{{$bet['multipler']}}x</td>
								<td>
									<div class="bet-number">
										<span class="bet-wrap">
											<span>{{ $bet['win'] }}</span>
											<svg class="icon icon-coin {{$bet['balance']}}">
												<use xlink:href="/img/symbols.svg#icon-coin"></use>
											</svg>
										</span>
									</div>
								</td>
							</tr>
							@endforeach @endif
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection