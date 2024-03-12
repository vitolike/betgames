<?php $__env->startSection('content'); ?>
    <link rel="stylesheet" href="/css/affiliate.css">

        <div class="section" style="margin-top: 15px;">
            <div class="section-page">
                <div class="quest-banner affiliate">
                    <div class="caption">
                        <h1><span>SISTEMA DE AFILIADOS</span></h1>
                    </div>
                    <div class="info">
                        <span>Indique um amigo e ganhe <?php echo e($settings->ref_perc); ?>% do valor do primeiro depósito.</span></div>
                </div>
                <div class="affiliates-form">
                    <div class="text">Seu Link de Indicação:</div>
                    <form>
                        <div class="form-row">
                            <div class="form-field input-group">
                                <div class="input-valid">
                                    <input class="input-field" type="text" name="code" id="code" readonly=""
                                        value="<?php echo e(strtolower( (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://" ).''); ?><?php echo e(strtolower($settings->domain)); ?>/?ref=<?php echo e($u->unique_id); ?>">
                                    <div class="input-group-append">
                                        <button type="button" class="btn" onclick="copyToClipboard('#code')">
                                            <span>Copiar</span></button>
                                        <div class="copy-tooltip"><span>COPIADO COM SUCESSO!</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="affiliate-stats">
                    <div class="left">
                        <div class="affiliate-stats-item">
                            <div class="wrap">
                                <div class="block">
                                    <img src="/img/casino-chip.png" height="30px"/>
                                    <div class="num"><?php echo e($u->ref_money_all); ?></div>
                                    <div class="text">Ganhos Totais</div>
                                </div>
                            </div>
                        </div>
                        <div class="affiliate-stats-item border-top">
                            <div class="wrap border-right">
                                <div class="block">
                                    <svg class="icon icon-network">
                                        <use xlink:href="/img/symbols.svg#icon-network"></use>
                                    </svg>
                                    <div class="num"><?php echo e($u->link_trans); ?></div>
                                    <div class="text">Link Aberto</div>
                                </div>
                            </div>
                            <div class="wrap">
                                <div class="block">
                                    <svg class="icon icon-person">
                                        <use xlink:href="/img/symbols.svg#icon-person"></use>
                                    </svg>
                                    <div class="num"><?php echo e($u->link_reg); ?></div>
                                    <div class="text">Cadastrados</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="right">
                        <div class="affiliate-stats-item full">
                            <div class="wrap">
                                <div class="block">
                                    <img src="/img/casino-chip.png" height="30px"/>
                                    <div class="num"><?php echo e($u->ref_money); ?></div>
                                    <div class="text">Ganhos Disponíveis</div>
                                    <span id="withdraw-button" class="" data-toggle="tooltip" data-placement="top" title="Quantidade mínima
    para retirada <?php echo e($settings->min_ref_withdraw); ?> R$ 50,00"><button type="button"
                                                                    <?php echo e($u->ref_money < $settings->min_ref_withdraw  ? 'disabled' : ''); ?> class="btn">Retirar</button></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>    
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php /* /var/www/html/resources/views/pages/affiliate.blade.php */ ?>