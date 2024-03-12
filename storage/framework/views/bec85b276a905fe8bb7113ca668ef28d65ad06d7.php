<?php $__env->startSection('content'); ?>
<meta name="game" content="wheel" />
<link rel="stylesheet" href="/css/wheel.css">
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
								<div class="button-group__content wheel btnToggle">
									<button class="btn btn-black btn-light" data-color="black"><span>x2</span></button>
									<button class="btn btn-red btn-light" data-color="red"><span>x3</span></button>
									<button class="btn btn-green btn-light" data-color="green"><span>x5</span></button>
									<button class="btn btn-yellow btn-light" data-color="yellow"><span>x50</span></button>
								</div>
								<span class="button-group-label"><span>Cor</span></span>
							</div>
                            <button type="button" class="btn btn-green btn-play"><span>Apostar</span></button>
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
			<div class="game-component">
				<div class="game_Wheel">
					<div class="progress-wrap">
						<!---<div class="progress-item left">
							<div class="title">Мин. сумма: <span id="minBet"><?php echo e($settings->wheel_min_bet); ?></span> <svg class="icon icon-coin balance"><use xlink:href="/img/symbols.svg#icon-coin"></use></svg></div>
							<div class="title">Макс. сумма: <span id="maxBet"><?php echo e($settings->wheel_max_bet); ?></span> <svg class="icon icon-coin balance"><use xlink:href="/img/symbols.svg#icon-coin"></use></svg></div>
						</div>
						<div class="progress-item right">
							<div class="title">Игра #<span id="gameId"><?php echo e($game->id); ?></span></div>
						</div>--->
					</div>
					<div class="wheel-game">
						<div class="wheel-content">
							<?php if($game->status == 2): ?>
							<script>
								setTimeout(() => {
									$('.wheel-game .wheel-img').css({
										'transition' : '-webkit-transform <?php echo e($coldwn); ?>s cubic-bezier(0.32, 0.64, 0.45, 1)',
										'transform' : 'rotate(<?php echo e($rotate2); ?>deg)'
									});
								}, 1);
							</script>
							<?php endif; ?>
							<div class="wheel-img" style="transform: rotate(<?php echo e($rotate); ?>deg);"><img src="/img/wheel.png" alt=""></div>
							<div class="arrow">
								<img src="/img/seta6.png"/>
							</div>
							<div class="time">
								<div class="block">
									<div class="title">Aguardando Apostas</div>
									<div class="value"><?php echo e($time[0]); ?>:<?php echo e($time[1]); ?></div>
								</div>
							</div>
						</div>
					</div>
					<div class="history_wrapper">
						<div class="history_history">
							<?php $__currentLoopData = $history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<div class="item history_item history_<?php echo e($l->winner_color); ?> checkGame" data-hash="<?php echo e($l->hash); ?>"></div>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</div>
					</div>
					<div class="hash">
						<span class="title">HASH:</span> <span class="text"><?php echo e($game->hash); ?></span>
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
<div class="section bets-section">
	<div class="container">
		<div class="game-stats">
			<div class="table-heading">
				<div class="thead">
					<div class="tr">
						<div class="th">Jogador</div>
						<div class="th">Valor da Aposta</div>
						<div class="th">Cor</div>
					</div>
				</div>
			</div>
			<div class="table-stats-wrap" style="min-height: 530px; max-height: 100%;">
				<div class="table-wrap" style="transform: translateY(0px);">
					<table class="table">
						<tbody>
							<?php $__currentLoopData = $bets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bet): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<tr data-userid="<?php echo e($bet->user_id); ?>">
								<td class="username">
									<button type="button" class="btn btn-link" data-id="<?php echo e($bet->unique_id); ?>">
										<span class="sanitize-user">
											<div class="sanitize-avatar"><img src="<?php echo e($bet->avatar); ?>" alt=""></div>
											<span class="sanitize-name"><?php echo e($bet->username); ?></span>
										</span>
									</button>
								</td>
								<td>
									<div class="bet-number">
										<span class="bet-wrap">
											<span>R$ <?php echo e($bet->sum); ?></span>
										</span>
									</div>
								</td>
								<td><span class="bet-type bet_<?php echo e($bet->color); ?>"><?php echo e($bet->color == 'black' ? 'x2' : ($bet->color == 'red' ? 'x3' : ($bet->color == 'green' ? 'x5' : 'x50'))); ?></span></td>
							</tr>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="/js/wheel.js"></script>
<?php $__env->stopSection(); ?>

<script src="//code.jivosite.com/widget/CqTxJBPTjs" async></script>
<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php /* /var/www/html/resources/views/pages/wheel.blade.php */ ?>