<?php $__env->startSection('content'); ?>
<link rel="stylesheet" href="/css/dice.css?v=1">
<script type="text/javascript" src="/js/dice.js?v=1"></script>
<div class="section game-section">
    <div class="container">
        <div class="game">
			<div class="game-component">
				<div class="game-block">
					<div class="game-area__wrap">
						<div class="game-area">
							<div class="progress-wrap">
								<div class="progress-item left">
									<div class="title">Aposta Mínima: <span id="minBet"><?php echo e($settings->dice_min_bet); ?></span> <svg class="icon icon-coin balance balance"><use xlink:href="/img/symbols.svg#icon-coin"></use></svg></div>
									<div class="title">Aposta Máxima: <span id="maxBet"><?php echo e($settings->dice_max_bet); ?></span> <svg class="icon icon-coin balance balance"><use xlink:href="/img/symbols.svg#icon-coin"></use></svg></div>
								</div>
							</div>
							<div class="top-corners"></div>
							<div class="bottom-corners"></div>
							<div class="game-area-content">
								<div class="dice">
									<div class="game-bar">

										<div class="bet-component">
											<div class="bet-form">
												<div class="two-cols">
													<div class="two-cols">
														<div class="form-row">
															<label>
																<div class="form-label"><span>Valor</span></div>
																<div class="form-row">
																	<div class="form-field">
																		<input type="text" name="sum" class="input-field no-bottom-radius" value="0.10" id="sum">
																		<button type="button" class="btn btn-bet-clear" data-action="clear">
																			<svg class="icon icon-close">
																				<use xlink:href="/img/symbols.svg#icon-close"></use>
																			</svg>
																		</button>
																		<div class="buttons-group no-top-radius">
																			<button type="button" class="btn btn-action" data-action="plus" data-value="1.00">+1</button>

																			<button type="button" class="btn btn-action" data-action="multiply" data-value="2">2X</button>
																			<button type="button" class="btn btn-action" data-action="divide" data-value="2">1/2</button>
																			<button type="button" class="btn btn-action" data-action="all">MAX</button>
																		</div>
																	</div>
																</div>
															</label>
														</div>
														<div class="form-row">
															<label>
																<div class="form-label"><span>Porcentagem</span></div>
																<div class="form-field">
																	<div class="input-valid">
																		<input type="text" name="sum" class="input-field" value="90" id="chance">
																		<div class="input-suffix"><span id="chance_val">90</span> </div>
																		<div class="valid"></div>
																	</div>
																	<div class="buttons-group no-top-radius">
																		<button type="button" class="btn btn-perc" data-action="min">MIN</button>
																		<button type="button" class="btn btn-perc" data-action="multiply" data-value="2">2X</button>
																		<button type="button" class="btn btn-perc" data-action="divide" data-value="2">1/2</button>
																		<button type="button" class="btn btn-perc" data-action="max">MAX</button>
																	</div>
																</div>
															</label>
														</div>
													</div>
													<div class="form-row">
														<div class="form-row">
															<label class="nvuti-exp">
																<span class="number" id="win">0.00</span>
																<div class="form-label"><span>Possível Lucro</span></div>
															</label>
														</div>
														<div class="two-cols">
															<div class="form-row">
																<button type="button" class="btn btn-green btn-play" data-type="min">
																	<div class="bet-chance">
																		<div class="chance-text">
																			<span>Abaixo</span>

																		</div>
																	</div>
																</button>
															</div>
															<div class="form-row">
																<button type="button" class="btn btn-green btn-play" data-type="max">
																	<div class="bet-chance">
																		<div class="chance-text">
																			<span>Acima</span>

																		</div>
																	</div>
																</button>
															</div>
														</div>
													</div>
												</div>
												<div class="game-dice"><span class="result"></span></div>
											</div>
											<div class="bet-footer">
												<button type="button" class="btn btn-light" data-toggle="modal" data-target="#fairModal">
													<svg class="icon icon-fairness">
														<use xlink:href="/img/symbols.svg#icon-fairness"></use>
													</svg><span>Fairplay</span>
												</button>
											</div>
										</div>

									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="game-history__wrap">
					<div class="hash">
						<span class="title">HASH:</span> <span class="text"><?php echo e($hash); ?></span>
					</div>
				</div>
				<?php if(auth()->guard()->guest()): ?>
				<div class="game-sign">
					<div class="game-sign-wrap">
						<div class="game-sign-block auth-buttons">
							Você precisa estar logado para jogar
							<button type="button" class="btn" id="loginRegister">Entrar ou Cadastrar</button>
							</a>
						</div>
					</div>
				</div>
				<?php endif; ?>
			</div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php /* /var/www/html/resources/views/pages/dice.blade.php */ ?>