@extends('admin')

@section('content')
<div class="kt-subheader kt-grid__item" id="kt_subheader">
	<div class="kt-subheader__main">
		<h3 class="kt-subheader__title">Настройки</h3>
	</div>
</div>

<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
	<div class="kt-portlet kt-portlet--tabs">
		<div class="kt-portlet__head">
			<div class="kt-portlet__head-toolbar">
				<ul class="nav nav-tabs nav-tabs-line nav-tabs-line-danger nav-tabs-line-2x nav-tabs-line-right" role="tablist">
					<li class="nav-item">
						<a class="nav-link active" data-toggle="tab" href="#site" role="tab" aria-selected="true">
							Настройки сайта
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#jackpot" role="tab" aria-selected="false">
							Jackpot
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#wheel" role="tab" aria-selected="false">
							Wheel
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#crash" role="tab" aria-selected="false">
							Crash
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#pvp" role="tab" aria-selected="false">
							PvP
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#battle" role="tab" aria-selected="false">
							Battle
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#dice" role="tab" aria-selected="false">
							Dice
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#hilo" role="tab" aria-selected="false">
							HiLo
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#tower" role="tab" aria-selected="false">
							Tower
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#fake" role="tab" aria-selected="false">
							Система фейковых ставок
						</a>
					</li>
				</ul>
			</div>
		</div>
		<form class="kt-form" method="post" action="/admin/setting/save">
			<div class="kt-portlet__body">
				<div class="tab-content">
					<div class="tab-pane active" id="site" role="tabpanel">
						<div class="kt-section">
							<h3 class="kt-section__title">
								Общие настройки:
							</h3>
							<div class="form-group row">
								<div class="col-lg-4">
									<label>Доменное имя:</label>
									<input type="text" class="form-control" placeholder="domain.ru" value="{{$settings->domain}}" name="domain">
								</div>
								<div class="col-lg-4">
									<label>Имя сайта:</label>
									<input type="text" class="form-control" placeholder="sitename.ru" value="{{$settings->sitename}}" name="sitename">
								</div>
								<div class="col-lg-4">
									<label>Заголовок сайта (титул):</label>
									<input type="text" class="form-control" placeholder="sitename.ru - краткое описание" value="{{$settings->title}}" name="title">
								</div>
							</div>
							<div class="form-group row">
								<div class="col-lg-4">
									<label>Описание для поисковых систем:</label>
									<input type="text" class="form-control" placeholder="Описание для сайта..." value="{{$settings->description}}" name="description">
								</div>
								<div class="col-lg-4">
									<label>Ключевые слова для поисковых систем:</label>
									<input type="text" class="form-control" placeholder="сайт, имя, домен и тд..." value="{{$settings->keywords}}" name="keywords">
								</div>
								<div class="col-lg-4">
									<label>Замена цензурных слов в Chatе на:</label>
									<input type="text" class="form-control" placeholder="i ❤ site" value="{{$settings->censore_replace}}" name="censore_replace">
								</div>
							</div>
							<div class="form-group row">
								<div class="col-lg-4">
									<label>Sistema de apostas falsas</label>
									<select class="form-control" name="fakebets">
										<option value="0" {{ ($settings->fakebets == 0) ? 'selected' : '' }}>Выключены</option>
										<option value="1" {{ ($settings->fakebets == 1) ? 'selected' : '' }}>Включены</option>
									</select>
								</div>
								<div class="col-lg-4">
									<label>Valor mínimo para trocar por bônus:</label>
									<input type="text" class="form-control" placeholder="1000" value="{{$settings->exchange_min}}" name="exchange_min">
								</div>
								<div class="col-lg-4">
									<label>Taxa cambial do bônus/Taxa de câmbio dos bônus</label>
									<input type="text" class="form-control" placeholder="2" value="{{$settings->exchange_curs}}" name="exchange_curs">
								</div>
							</div>
							<div class="form-group row">
								<div class="col-lg-4">
									<label>Intervalo de entrega dos bônus por assinatura (A cada X minutos)</label>
									<input type="text" class="form-control" placeholder="15" value="{{$settings->bonus_group_time}}" name="bonus_group_time">
								</div>
								<div class="col-lg-4">
									<label>Quantidade de referências ativas para receber o bônus:</label>
									<input type="text" class="form-control" placeholder="8" value="{{$settings->max_active_ref}}" name="max_active_ref">
								</div>
								<div class="col-lg-4">
									<label>Сумма пополнения для использования Chatа. 0 - Отключено:</label>
									<input type="text" class="form-control" placeholder="0" value="{{$settings->chat_dep}}" name="chat_dep">
								</div>
							</div>
							<div class="form-group row">
								<div class="col-lg-4">
									<label>Quantidade mínima de deposito para para sacar os bônus 0 - Отключено</label>
									<input type="text" class="form-control" placeholder="0" value="{{$settings->dep_bonus_min}}" name="dep_bonus_min">
								</div>
								<div class="col-lg-4">
									<label>Porcentagem do valor depositado em quantidade de bônus:</label>
									<input type="text" class="form-control" placeholder="0" value="{{$settings->dep_bonus_perc}}" name="dep_bonus_perc">
								</div>
								<div class="col-lg-4">
									<label>Porcentagem da quantidade de vitórias para o apostador</label>
									<input type="text" class="form-control" placeholder="0" value="{{$settings->requery_perc}}" name="requery_perc">
								</div>
							</div>
							<div class="form-group row">
								<div class="col-lg-4">
									<label>Porcentagem do valor/quantidade de aposta para o apostador</label>
									<input type="text" class="form-control" placeholder="0" value="{{$settings->requery_bet_perc}}" name="requery_bet_perc">
								</div>
								<div class="col-lg-4">
									<label>Trabalhos Automáticos</label>
									<select class="form-control" name="site_disable">
										<option value="0" {{ ($settings->site_disable == 0) ? 'selected' : '' }}>Выключены</option>
										<option value="1" {{ ($settings->site_disable == 1) ? 'selected' : '' }}>Включены</option>
									</select>
								</div>
							</div>
						</div>
						<div class="kt-section">
							<h3 class="kt-section__title">
								Configurações do sistema de referência:
							</h3>
							<div class="form-group row">
								<div class="col-lg-4">
									<label>Qual a porcentagem de ganhos que o convidado recebe:</label>
									<input type="text" class="form-control" placeholder="Введите Porcentagem" value="{{$settings->ref_perc}}" name="ref_perc">
								</div>
								<div class="col-lg-4">
									<label>Qual o valor que o convidado recebe para a conta real</label>
									<input type="text" class="form-control" placeholder="Введите сумму" value="{{$settings->ref_sum}}" name="ref_sum">
								</div>
								<div class="col-lg-4">
									<label>Valor mínimo de retirada de da conta de referência:</label>
									<input type="text" class="form-control" placeholder="Введите сумму" value="{{$settings->min_ref_withdraw}}" name="min_ref_withdraw">
								</div>
							</div>
						</div>
						<div class="kt-section">
							<h3 class="kt-section__title">
								Outras configurações
							</h3>
							<div class="form-group row">
								<div class="col-lg-4">
									<label>Valor mínimo de depósito:</label>
									<input type="text" class="form-control" placeholder="Введите сумму" value="{{$settings->min_dep}}" name="min_dep">
								</div>
								<div class="col-lg-4">
									<label>Quantidade de depósitos para realizar um saque:</label>
									<input type="text" class="form-control" placeholder="Введите сумму" value="{{$settings->min_dep_withdraw}}" name="min_dep_withdraw">
								</div>
								<div class="col-lg-4">
									<label>Quanto fornecer do saldo depositado (1/N)::</label>
									<input type="text" class="form-control" placeholder="Введите сумму" value="{{$settings->profit_koef}}" name="profit_koef">
								</div>
							</div>
						</div>
						<div class="kt-section">
							<h3 class="kt-section__title">
								Настройки группы VK:
							</h3>
							<div class="form-group row">
								<div class="col-lg-4">
									<label>Ссылка на группу VK:</label>
									<input type="text" class="form-control" placeholder="https://vk.com/..." value="{{$settings->vk_url}}" name="vk_url">
								</div>
								<div class="col-lg-4">
									<label>Ссылка на сообщения группы VK:</label>
									<input type="text" class="form-control" placeholder="https://vk.com/im?media=&sel=..." value="{{$settings->vk_support_link}}" name="vk_support_link">
								</div>
								<div class="col-lg-4">
									<label>Сервисный ключ доступа приложения:</label>
									<input type="text" class="form-control" placeholder="1f27230c1f27230c1f27230c841..." value="{{$settings->vk_service_key}}" name="vk_service_key">
								</div>
							</div>
						</div>
						<div class="kt-section">
							<h3 class="kt-section__title">
								Настройки платежной системы FreeKassa:
							</h3>
							<div class="form-group row">
								<div class="col-lg-4">
									<label>ID Магазина FK:</label>
									<input type="text" class="form-control" placeholder="Fxxxxxx" value="{{$settings->fk_mrh_ID}}" name="fk_mrh_ID">
								</div>
								<div class="col-lg-4">
									<label>FK Secret 1:</label>
									<input type="text" class="form-control" placeholder="xxxxxxx" value="{{$settings->fk_secret1}}" name="fk_secret1">
								</div>
								<div class="col-lg-4">
									<label>FK Secret 2:</label>
									<input type="text" class="form-control" placeholder="xxxxxxx" value="{{$settings->fk_secret2}}" name="fk_secret2">
								</div>
							</div>
							<div class="form-group row">
								<div class="col-lg-6">
									<label>FK Кошелек:</label>
									<input type="text" class="form-control" placeholder="Pxxxxxx" value="{{$settings->fk_wallet}}" name="fk_wallet">
								</div>
								<div class="col-lg-6">
									<label>FK API Key:</label>
									<input type="text" class="form-control" placeholder="xxxxxxx" value="{{$settings->fk_api}}" name="fk_api">
								</div>
							</div>
						</div>
						<div class="kt-section">
							<h3 class="kt-section__title">
								Настройка комиссии Para retirada средств:
							</h3>
							<div class="form-group row">
								<div class="col-sm-1-5">
									<label>QIWI (+%)</label>
									<input type="text" class="form-control" name="qiwi_com_percent" value="{{$settings->qiwi_com_percent}}" placeholder="%">
									<label>QIWI (+руб)</label>
									<input type="text" class="form-control" name="qiwi_com_rub" value="{{$settings->qiwi_com_rub}}" placeholder="руб">
									<label>QIWI Мин. сумма</label>
									<input type="text" class="form-control" name="qiwi_min" value="{{$settings->qiwi_min}}" placeholder="Мин. сумма">
								</div>
								<div class="col-sm-1-5">
									<label>Yandex (+%)</label>
									<input type="text" class="form-control" name="yandex_com_percent" value="{{$settings->yandex_com_percent}}" placeholder="%">
									<label>Yandex (+руб)</label>
									<input type="text" class="form-control" name="yandex_com_rub" value="{{$settings->yandex_com_rub}}" placeholder="руб">
									<label>Yandex Мин. сумма</label>
									<input type="text" class="form-control" name="yandex_min" value="{{$settings->yandex_min}}" placeholder="Мин. сумма">
								</div>
								<div class="col-sm-1-5">
									<label>Payeer (+%)</label>
									<input type="text" class="form-control" name="payeer_com_percent" value="{{$settings->webmoney_com_percent}}" placeholder="%">
									<label>Payeer (+руб)</label>
									<input type="text" class="form-control" name="payeer_com_rub" value="{{$settings->webmoney_com_rub}}" placeholder="руб">
									<label>Payeer Мин. сумма</label>
									<input type="text" class="form-control" name="payeer_min" value="{{$settings->webmoney_min}}" placeholder="Мин. сумма">
								</div>
								<div class="col-sm-1-5">
									<label>VISA (+%)</label>
									<input type="text" class="form-control" name="visa_com_percent" value="{{$settings->visa_com_percent}}" placeholder="%">
									<label>VISA (+руб)</label>
									<input type="text" class="form-control" name="visa_com_rub" value="{{$settings->visa_com_rub}}" placeholder="руб">
									<label>VISA Мин. сумма</label>
									<input type="text" class="form-control" name="visa_min" value="{{$settings->visa_min}}" placeholder="Мин. сумма">
								</div>
							</div>
						</div>
					</div>
					<div class="tab-pane" id="jackpot" role="tabpanel">
						<div class="form-group">
							<label>Комиссия игры в %:</label>
							<input type="text" class="form-control" placeholder="Введите Porcentagem" value="{{$settings->jackpot_commission}}" name="jackpot_commission">
						</div>
						@foreach($rooms as $r)
						<div class="kt-section">
							<h3 class="kt-section__title">
								Комната "{{$r->title}}":
							</h3>
							<div class="form-group row">
								<div class="col-lg-3">
									<label>Таймер:</label>
									<input type="text" class="form-control" name="time_{{$r->name}}" value="{{$r->time}}" placeholder="Таймер">
								</div>
								<div class="col-lg-3">
									<label>Quantidade mínima
 ставки:</label>
									<input type="text" class="form-control" name="min_{{$r->name}}" value="{{$r->min}}" placeholder="Quantidade mínima
 ставки">
								</div>
								<div class="col-lg-3">
									<label>Максимальная Valor da Aposta:</label>
									<input type="text" class="form-control" name="max_{{$r->name}}" value="{{$r->max}}" placeholder="Максимальная Valor da Aposta">
								</div>
								<div class="col-lg-3">
									<label>Максимальное кол-во ставок для Jogadorа:</label>
									<input type="text" class="form-control" name="bets_{{$r->name}}" value="{{$r->bets}}" placeholder="Макс. кол-во ставок для Jogadorа">
								</div>
							</div>
						</div>
						@endforeach
					</div>
					<div class="tab-pane" id="wheel" role="tabpanel">
						<div class="form-group row">
							<div class="col-lg-4">
								<label>Таймер:</label>
								<input type="text" class="form-control" placeholder="Таймер" value="{{$settings->wheel_timer}}" name="wheel_timer">
							</div>
							<div class="col-lg-4">
								<label>Quantidade mínima
 ставки:</label>
								<input type="text" class="form-control" placeholder="Quantidade mínima
 ставки" value="{{$settings->wheel_min_bet}}" name="wheel_min_bet">
							</div>
							<div class="col-lg-4">
								<label>Максимальная Valor da Aposta:</label>
								<input type="text" class="form-control" placeholder="Максимальная Valor da Aposta" value="{{$settings->wheel_max_bet}}" name="wheel_max_bet">
							</div>
						</div>
					</div>
					<div class="tab-pane" id="crash" role="tabpanel">
						<div class="form-group row">
							<div class="col-lg-4">
								<label>Таймер:</label>
								<input type="text" class="form-control" placeholder="Таймер" value="{{$settings->crash_timer}}" name="crash_timer">
							</div>
							<div class="col-lg-4">
								<label>Quantidade mínima
 ставки:</label>
								<input type="text" class="form-control" placeholder="Quantidade mínima
 ставки" value="{{$settings->crash_min_bet}}" name="crash_min_bet">
							</div>
							<div class="col-lg-4">
								<label>Максимальная Valor da Aposta:</label>
								<input type="text" class="form-control" placeholder="Максимальная Valor da Aposta" value="{{$settings->crash_max_bet}}" name="crash_max_bet">
							</div>
						</div>
					</div>
					<div class="tab-pane" id="pvp" role="tabpanel">
						<div class="form-group row">
							<div class="col-lg-4">
								<label>Комиссия игры в %:</label>
								<input type="text" class="form-control" placeholder="Макс. кол-во активных игр для Jogadorа" value="{{$settings->flip_commission}}" name="flip_commission">
							</div>
							<div class="col-lg-4">
								<label>Quantidade mínima
 ставки:</label>
								<input type="text" class="form-control" placeholder="Quantidade mínima
 ставки" value="{{$settings->flip_min_bet}}" name="flip_min_bet">
							</div>
							<div class="col-lg-4">
								<label>Максимальная Valor da Aposta:</label>
								<input type="text" class="form-control" placeholder="Максимальная Valor da Aposta" value="{{$settings->flip_max_bet}}" name="flip_max_bet">
							</div>
						</div>
					</div>
					<div class="tab-pane" id="battle" role="tabpanel">
						<div class="form-group row">
							<div class="col-lg-3">
								<label>Таймер:</label>
								<input type="text" class="form-control" placeholder="Таймер" value="{{$settings->battle_timer}}" name="battle_timer">
							</div>
							<div class="col-lg-3">
								<label>Quantidade mínima
 ставки:</label>
								<input type="text" class="form-control" placeholder="Quantidade mínima
 ставки" value="{{$settings->battle_min_bet}}" name="battle_min_bet">
							</div>
							<div class="col-lg-3">
								<label>Максимальная Valor da Aposta:</label>
								<input type="text" class="form-control" placeholder="Максимальная Valor da Aposta" value="{{$settings->battle_max_bet}}" name="battle_max_bet">
							</div>
							<div class="col-lg-3">
								<label>Комиссия игры в %:</label>
								<input type="text" class="form-control" placeholder="Комиссия игры в %" value="{{$settings->battle_commission}}" name="battle_commission">
							</div>
						</div>
					</div>
					<div class="tab-pane" id="dice" role="tabpanel">
						<div class="form-group row">
							<div class="col-lg-6">
								<label>Quantidade mínima
 ставки:</label>
								<input type="text" class="form-control" placeholder="Quantidade mínima
 ставки" value="{{$settings->dice_min_bet}}" name="dice_min_bet">
							</div>
							<div class="col-lg-6">
								<label>Максимальная Valor da Aposta:</label>
								<input type="text" class="form-control" placeholder="Максимальная Valor da Aposta" value="{{$settings->dice_max_bet}}" name="dice_max_bet">
							</div>
						</div>
					</div>
					<div class="tab-pane" id="hilo" role="tabpanel">
						<div class="form-group row">
							<div class="col-lg-3">
								<label>Таймер:</label>
								<input type="text" class="form-control" placeholder="Таймер" value="{{$settings->hilo_timer}}" name="hilo_timer">
							</div>
							<div class="col-lg-3">
								<label>Quantidade mínima
 ставки:</label>
								<input type="text" class="form-control" placeholder="Quantidade mínima
 ставки" value="{{$settings->hilo_min_bet}}" name="hilo_min_bet">
							</div>
							<div class="col-lg-3">
								<label>Максимальная Valor da Aposta:</label>
								<input type="text" class="form-control" placeholder="Максимальная Valor da Aposta" value="{{$settings->hilo_max_bet}}" name="hilo_max_bet">
							</div>
							<div class="col-lg-3">
								<label>Кол-во ставок для 1 Jogadorа:</label>
								<input type="text" class="form-control" placeholder="Кол-во ставок" value="{{$settings->hilo_bets}}" name="hilo_bets">
							</div>
						</div>
					</div>
					<div class="tab-pane" id="tower" role="tabpanel">
						<div class="form-group row">
							<div class="col-lg-3">
								<label>Quantidade mínima
 ставки:</label>
								<input type="text" class="form-control" placeholder="Quantidade mínima
 ставки" value="{{$settings->tower_min_bet}}" name="tower_min_bet">
							</div>
							<div class="col-lg-3">
								<label>Максимальная Valor da Aposta:</label>
								<input type="text" class="form-control" placeholder="Максимальная Valor da Aposta" value="{{$settings->tower_max_bet}}" name="tower_max_bet">
							</div>
						</div>
					</div>
					<div class="tab-pane" id="fake" role="tabpanel">
						<div class="form-group row">
							<div class="col-lg-6">
								<label>Quantidade mínima
 ставки для фейка:</label>
								<input type="text" class="form-control" placeholder="Quantidade mínima
 ставки для фейка" value="{{$settings->fake_min_bet}}" name="fake_min_bet">
							</div>
							<div class="col-lg-6">
								<label>Максимальная Valor da Aposta для фейка:</label>
								<input type="text" class="form-control" placeholder="Максимальная Valor da Aposta для фейка" value="{{$settings->fake_max_bet}}" name="fake_max_bet">
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="kt-portlet__foot">
				<div class="kt-form__actions">
					<button type="submit" class="btn btn-primary">Сохранить</button>
					<button type="reset" class="btn btn-secondary">Сбросить</button>
				</div>
			</div>
		</form>
	</div>
</div>
@endsection
