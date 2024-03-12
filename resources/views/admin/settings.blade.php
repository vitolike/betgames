@extends('admin')

@section('content')
<div class="kt-subheader kt-grid__item" id="kt_subheader">
	<div class="kt-subheader__main">
		<h3 class="kt-subheader__title">CONFIGURAÇÕES DO SITE</h3>
	</div>
</div>

<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
	<div class="kt-portlet kt-portlet--tabs">
		<div class="kt-portlet__head">
			<div class="kt-portlet__head-toolbar">
				<ul class="nav nav-tabs nav-tabs-line nav-tabs-line-danger nav-tabs-line-2x nav-tabs-line-right" role="tablist">
					<li class="nav-item">
						<a class="nav-link active" data-toggle="tab" href="#site" role="tab" aria-selected="true">
							Configurações Gerais
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#affiliates" role="tab" aria-selected="false">
							Afiliados
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#wheel" role="tab" aria-selected="false">
							Roleta
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#double" role="tab" aria-selected="false">
							Double
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#crash" role="tab" aria-selected="false">
							Crash
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
					<!--<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#hilo" role="tab" aria-selected="false">
							HiLo
						</a>
					</li>-->
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#tower" role="tab" aria-selected="false">
							Tower
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#fake" role="tab" aria-selected="false">
							Sistema de apostas dos Bots
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
								Configurações:
							</h3>
							<div class="form-group row">
								<div class="col-lg-4">
									<label>Nome do Domínio:</label>
									<input type="text" class="form-control" placeholder="dominio.com" value="{{$settings->domain}}" name="domain">
								</div>
								<div class="col-lg-4">
									<label>Nome do site:</label>
									<input type="text" class="form-control" placeholder="nome_do_site.com" value="{{$settings->sitename}}" name="sitename">
								</div>
								<div class="col-lg-4">
									<label>Titulo do Cabeçalho:</label>
									<input type="text" class="form-control" placeholder="nome_do_site.com - Pequena descrição" value="{{$settings->title}}" name="title">
								</div>
							</div>
							<div class="form-group row">
								<div class="col-lg-4">
									<label>Descrição para sites de buscas (google, yahoo e etc.):</label>
									<input type="text" class="form-control" placeholder="Descrição para o site..." value="{{$settings->description}}" name="description">
								</div>
								<div class="col-lg-4">
									<label>Palavras chaves para sites de buscas:</label>
									<input type="text" class="form-control" placeholder="website, nome, domínio, etc...." value="{{$settings->keywords}}" name="keywords">
								</div>
								<div class="col-lg-4">
									<label>Substituir palavras proíbidas do chat por:</label>
									<input type="text" class="form-control" placeholder="i ❤ site" value="{{$settings->censore_replace}}" name="censore_replace">
								</div>
							</div>
							<div class="form-group row">
								<div class="col-lg-4">
									<label>Sistema de Bots</label>
									<select class="form-control" name="fakebets">
										<option value="0" {{ ($settings->fakebets == 0) ? 'selected' : '' }}>Desligado</option>
										<option value="1" {{ ($settings->fakebets == 1) ? 'selected' : '' }}>Ligado</option>
									</select>
								</div>
								<div class="col-lg-4">
									<label>Valor mínimo de bônus para trocar por saldo real:</label>
									<input type="text" class="form-control" placeholder="1000" value="{{$settings->exchange_min}}" name="exchange_min">
								</div>
								<div class="col-lg-4">
									<label>Taxa cambial do bônus convertendo para saldo real:</label>
									<input type="text" class="form-control" placeholder="2" value="{{$settings->exchange_curs}}" name="exchange_curs">
								</div>
							</div>
							<div class="form-group row">
								<div class="col-lg-4">
									<label>Quantidade de referências ativas para receber o bônus:</label>
									<input type="text" class="form-control" placeholder="8" value="{{$settings->max_active_ref}}" name="max_active_ref">
								</div>
								<div class="col-lg-4">
									<label>Quantidade de valor depositado nescessario para falar no chat (0 = desativado):</label>
									<input type="text" class="form-control" placeholder="0" value="{{$settings->chat_dep}}" name="chat_dep">
								</div>
							</div>
							<div class="form-group row">
								<div class="col-lg-4">
									<label>Quantidade mínima de deposito para para sacar os bônus (0 = desativado)</label>
									<input type="text" class="form-control" placeholder="0" value="{{$settings->dep_bonus_min}}" name="dep_bonus_min">
								</div>
								<div class="col-lg-4">
									<label>Valor que usuario recebe em % de bônus ao depositar saldo:</label>
									<input type="text" class="form-control" placeholder="0" value="{{$settings->dep_bonus_perc}}" name="dep_bonus_perc">
								</div>
								<div class="col-lg-4">
									<label>Porcentagem da quantidade de vitoria dos úsuarios</label>
									<input type="text" class="form-control" placeholder="0" value="{{$settings->requery_perc}}" name="requery_perc">
								</div>
							</div>
							<div class="form-group row">
								<div class="col-lg-4">
									<label>Porcentagem do valor/quantidade de aposta para o apostador</label>
									<input type="text" class="form-control" placeholder="0" value="{{$settings->requery_bet_perc}}" name="requery_bet_perc">
								</div>
								<div class="col-lg-4">
									<label>Manutenção</label>
									<select class="form-control" name="site_disable">
										<option value="0" {{ ($settings->site_disable == 0) ? 'selected' : '' }}>Desativado</option>
										<option value="1" {{ ($settings->site_disable == 1) ? 'selected' : '' }}>Ativar</option>
									</select>
								</div>
								<div class="col-lg-4">
									<label>ID para compartilhar</label>
									<input type="text" class="form-control" placeholder="1" value="{{$settings->repost_post_id}}" name="repost_post_id">
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
									<input type="text" class="form-control" placeholder="Valor" value="{{$settings->min_dep}}" name="min_dep">
								</div>
								<div class="col-lg-4">
									<label>Quantidade de depósitos para realizar um saque:</label>
									<input type="text" class="form-control" placeholder="Valor" value="{{$settings->min_dep_withdraw}}" name="min_dep_withdraw">
								</div>
								<div class="col-lg-4">
									<label>Quanto fornecer do saldo depositado (Coeficiente DEPOSITO X SAQUE)::</label>
									<input type="text" class="form-control" placeholder="Valor" value="{{$settings->profit_koef}}" name="profit_koef">
								</div>
							</div>
						</div>
					</div>
					<div class="tab-pane" id="affiliates" role="tabpanel">
						<div class="form-group row">
							<div class="col-lg-4">
								<label>Qual a porcentagem de ganhos que o convidado recebe:</label>
								<input type="text" class="form-control" placeholder="30" value="{{$settings->ref_perc}}" name="ref_perc">
							</div>
							<div class="col-lg-4">
								<label>Qual o valor que o convidado no primeiro depósito:</label>
								<input type="text" class="form-control" placeholder="20" value="{{$settings->ref_sum}}" name="ref_sum">
							</div>
							<div class="col-lg-4">
								<label>Valor mínimo de retirada de da conta de afiliado:</label>
								<input type="text" class="form-control" placeholder="100" value="{{$settings->min_ref_withdraw}}" name="min_ref_withdraw">
							</div>
						</div>
					</div>
					<div class="tab-pane" id="wheel" role="tabpanel">
						<div class="form-group row">
							<div class="col-lg-4">
								<label>Cronômetro:</label>
								<input type="text" class="form-control" placeholder="Таймер" value="{{$settings->wheel_timer}}" name="wheel_timer">
							</div>
							<div class="col-lg-4">
								<label>Valor minimo de aposta:</label>
								<input type="text" class="form-control" placeholder="valor" value="{{$settings->wheel_min_bet}}" name="wheel_min_bet">
							</div>
							<div class="col-lg-4">
								<label>Valor máximo da aposta:</label>
								<input type="text" class="form-control" placeholder="valor" value="{{$settings->wheel_max_bet}}" name="wheel_max_bet">
							</div>
						</div>
					</div>
					<div class="tab-pane" id="double" role="tabpanel">
						<div class="form-group row">
							<div class="col-lg-4">
								<label>Cronômetro:</label>
								<input type="text" class="form-control" placeholder="Таймер" value="{{$settings->wheel_timer}}" name="wheel_timer">
							</div>
							<div class="col-lg-4">
								<label>Valor minimo de aposta:</label>
								<input type="text" class="form-control" placeholder="valor" value="{{$settings->wheel_min_bet}}" name="wheel_min_bet">
							</div>
							<div class="col-lg-4">
								<label>Valor máximo da aposta:</label>
								<input type="text" class="form-control" placeholder="valor" value="{{$settings->wheel_max_bet}}" name="wheel_max_bet">
							</div>
						</div>

						<div class="form-group row">
							<div class="col-lg-3">
								<label>Porcentagem Red:</label>
								<input type="text" class="form-control" placeholder="valor" value="{{$settings->double_red_percent}}" name="double_red_percent">
							</div>
							<div class="col-lg-3">
								<label>Porcentagem Black:</label>
								<input type="text" class="form-control" placeholder="valor" value="{{$settings->double_black_percent}}" name="double_black_percent">
							</div>
							<div class="col-lg-3">
								<label>Porcentagem White:</label>
								<input type="text" class="form-control" placeholder="valor" value="{{$settings->double_white_percent}}" name="double_white_percent">
							</div>
							<div class="col-lg-3">
								<label>Próximo resultado:</label>
								<input type="text" class="form-control" placeholder="valor" value="{{$settings->double_next_result}}" name="double_next_result">
							</div>
						</div>
					</div>
					<div class="tab-pane" id="crash" role="tabpanel">
						<div class="form-group row">
							<div class="col-lg-4">
								<label>Cronômetro:</label>
								<input type="text" class="form-control" placeholder="Таймер" value="{{$settings->crash_timer}}" name="crash_timer">
							</div>
							<div class="col-lg-4">
								<label>Valor mínimo da aposta:</label>
								<input type="text" class="form-control" placeholder="Quantidade mínima" value="{{$settings->crash_min_bet}}" name="crash_min_bet">
							</div>
							<div class="col-lg-4">
								<label>Valor máximo da aposta:</label>
								<input type="text" class="form-control" placeholder="Valor da Aposta" value="{{$settings->crash_max_bet}}" name="crash_max_bet">
							</div>
						</div>
					</div>
					<div class="tab-pane" id="battle" role="tabpanel">
						<div class="form-group row">
							<div class="col-lg-3">
								<label>Cronômetro:</label>
								<input type="text" class="form-control" placeholder="tempo" value="{{$settings->battle_timer}}" name="battle_timer">
							</div>
							<div class="col-lg-3">
								<label>Valor mínimo da aposta:</label>
								<input type="text" class="form-control" placeholder="valor mínimo" value="{{$settings->battle_min_bet}}" name="battle_min_bet">
							</div>
							<div class="col-lg-3">
								<label>Valor máximo da aposta:</label>
								<input type="text" class="form-control" placeholder="valor máximo da aposta" value="{{$settings->battle_max_bet}}" name="battle_max_bet">
							</div>
							<div class="col-lg-3">
								<label>Comissão da casa em%:</label>
								<input type="text" class="form-control" placeholder="comissão em %" value="{{$settings->battle_commission}}" name="battle_commission">
							</div>
						</div>
					</div>
					<div class="tab-pane" id="dice" role="tabpanel">
						<div class="form-group row">
							<div class="col-lg-6">
								<label>Valor mínimo da aposta:</label>
								<input type="text" class="form-control" placeholder="valor da aposta" value="{{$settings->dice_min_bet}}" name="dice_min_bet">
							</div>
							<div class="col-lg-6">
								<label>valor máximo da Aposta:</label>
								<input type="text" class="form-control" placeholder="Valor da Aposta" value="{{$settings->dice_max_bet}}" name="dice_max_bet">
							</div>
						</div>
					</div>
					<div class="tab-pane" id="hilo" role="tabpanel">
						<div class="form-group row">
							<div class="col-lg-3">
								<label>Cronômetro:</label>
								<input type="text" class="form-control" placeholder="Таймер" value="{{$settings->hilo_timer}}" name="hilo_timer">
							</div>
							<div class="col-lg-3">
								<label>Valor mínimo da aposta:</label>
								<input type="text" class="form-control" placeholder="valor da aposta" value="{{$settings->hilo_min_bet}}" name="hilo_min_bet">
							</div>
							<div class="col-lg-3">
								<label>Valor máximo da Aposta:</label>
								<input type="text" class="form-control" placeholder="Valor da Aposta" value="{{$settings->hilo_max_bet}}" name="hilo_max_bet">
							</div>
							<div class="col-lg-3">
								<label>Número de apostas para 1 Jogadorа:</label>
								<input type="text" class="form-control" placeholder="valor" value="{{$settings->hilo_bets}}" name="hilo_bets">
							</div>
						</div>
					</div>
					<div class="tab-pane" id="tower" role="tabpanel">
						<div class="form-group row">
							<div class="col-lg-3">
								<label>Valor mínimo da aposta:</label>
								<input type="text" class="form-control" placeholder="valor da aposta" value="{{$settings->tower_min_bet}}" name="tower_min_bet">
							</div>
							<div class="col-lg-3">
								<label>Valor máximo da Aposta:</label>
								<input type="text" class="form-control" placeholder="Valor da Aposta" value="{{$settings->tower_max_bet}}" name="tower_max_bet">
							</div>
						</div>
					</div>
					<div class="tab-pane" id="fake" role="tabpanel">
						<div class="form-group row">
							<div class="col-lg-6">
								<label>Quantidade mínima
 para apostas bots:</label>
								<input type="text" class="form-control" placeholder="Quantidade mínima
 ставки для фейка" value="{{$settings->fake_min_bet}}" name="fake_min_bet">
							</div>
							<div class="col-lg-6">
								<label>Valor Máximo da Aposta para os bots é de R$:</label>
								<input type="text" class="form-control" placeholder="Valor Máximo da Aposta é de R$ для фейка" value="{{$settings->fake_max_bet}}" name="fake_max_bet">
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="kt-portlet__foot">
				<div class="kt-form__actions">
					<button type="submit" class="btn btn-primary">Salvar</button>
					<button type="reset" class="btn btn-secondary">REDEFINIR TUDO</button>
				</div>
			</div>
		</form>
	</div>
</div>
@endsection
