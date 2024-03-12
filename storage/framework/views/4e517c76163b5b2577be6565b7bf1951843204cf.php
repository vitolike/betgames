

<?php $__env->startSection('content'); ?>
<link rel="stylesheet" href="/css/free.css">
<script src="https://d3js.org/d3.v3.min.js"></script>
<script type="text/javascript" src="/js/bonus.js"></script>
<script>
	var check = <?php echo e($check); ?>;
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
								<span>Digitar <a href="<?php echo e($settings->vk_url); ?>" target="_blank">para o nosso grupo</a></span>
								<br><span>e levante-se para <strong><?php echo e($max); ?> moedas para conta de bônus</strong></span>
                        	</div>
							<div class="form_block">
								<?php if(!$check): ?>
								<div class="form_value"><?php echo e($settings->bonus_group_time); ?> eu<div class="form_text">recarrega</div></div>
								<span id="spin-wheel-button" class=""><button type="button" class="btn" data-toggle="modal" data-target="#captchaModal">gira a roda</button></span>
                       			<?php else: ?>
                       			<div class="form_recharge"><span>Recarregue via:</span><div class="form_timeLeft">00:00:00</div></div>
                       			<?php endif; ?>
                        	</div>
                        </div>
                        <div class="form_wrapper refs" style="display: none">
                        	<div class="form_text">
                            convidar <strong><?php echo e($settings->max_active_ref); ?> referências ativas <div class="popover-tip-block" id="purposeTip"><div class="popover-tip-icon"><svg class="icon icon-help"><use xlink:href="/img/symbols.svg#icon-help"></use></svg></div></div></strong>
                        		<br> e chegar a <strong><?php echo e($max_refs); ?> moedas para conta de bônus</strong>
                        	</div>
                        	<div class="form_block">
                        		<?php if(!$refLog): ?>
                        		<div class="form_value"><?php echo e($activeRefs); ?> / <?php echo e($settings->max_active_ref); ?><div class="form_text">referência</div></div>
                        		<span id="spin-wheel-button" class=""><button type="button" class="btn" data-toggle="modal" data-target="#captchaModal">Крутить колесо</button></span>
                        		<?php else: ?>
                        		<div class="form_recharge">Вы получили данный бонус!</div>
                        		<?php endif; ?>
                        	</div>
                        </div>
                    </div>
                </div>
                <div class="list_list">
                    <div class="list_item group" data-bonus="group">
                        <svg class="icon icon-faucet">
                            <use xlink:href="/img/symbols.svg#icon-faucet"></use>
                        </svg>
                        <div class="list_text"><span>Вступите в нашу группу вк</span> <span>и получайте до <strong><?php echo e($max); ?> монет на бонусный счет</strong></span> <span>раз в <?php echo e($settings->bonus_group_time); ?> мин</span></div>
                    </div>
                    <div class="list_item refs" data-bonus="refs">
                        <svg class="icon icon-faucet">
                            <use xlink:href="/img/symbols.svg#icon-faucet"></use>
                        </svg>
                        <div class="list_text"><span>Пригласите <strong><?php echo e($settings->max_active_ref); ?> рефералов</strong> <br> и получите до <strong><?php echo e($max_refs); ?> монет на бонусный счет</strong></span></div>
                    </div>
                    <div class="list_item list_disabled">
                        <div class="list_notAvailable">Недоступно</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php /* /var/www/html/resources/views/pages/free.blade.php */ ?>