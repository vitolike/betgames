<?php $__env->startSection('content'); ?>
    <link rel="stylesheet" href="/css/profileHistory.css">
    <script type="text/javascript" src="/js/profileHistory.js"></script>
    <div class="section">
        <div class="wallet_container">
            <div class="wallet_component">
                <div class="history_nav">
                    <button type="button" class="btn isActive" data-tab="with"><span>Histórico de Saques</span>
                    </button>
                    <button type="button" class="btn" data-tab="dep"><span>Histórico de Depósitos</span></button>
                </div>

                <div class="history_wrapper with">
                    <div class="withPager">
                        <div class="list">
                            <?php if($withdraws->count() > 0): ?>
                                <div class="history_scroll">
                                    <table class="history_table">
                                        <thead>
                                        <tr>
                                            <th>Data | Hora</th>
                                            <th>Metódo</th>
                                            <th>Valor</th>
                                            <th class="text-right">Status</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $__currentLoopData = $withdraws; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $w): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e(Carbon\Carbon::parse($w->created_at)->format('d.m.Y | h:i')); ?>hs</td>
                                                <td>
                                                    <div class="history_system">
                                                        <div><?php echo e($w->system); ?></div>
                                                        <span class="popover-tip-block" data-toggle="popover-info"
                                                              data-placement="top"
                                                              data-contenthtml="<?php echo e($w->wallet); ?>">
                                                            <span
                                                                class="popover-tip-icon"><svg class="icon"><use
                                                                        xlink:href="/img/symbols.svg#icon-info"></use></svg>
                                                            </span>
                                                        </span>
                                                    </div>
                                                </td>
                                                <td>R$ <?php echo e($w->valueWithCom); ?></td>
                                                <td class="text-right withStatus_<?php echo e($w->id); ?>">
                                                    <?php if($w->status == 0): ?>
                                                        <div class="history_button" data-id="<?php echo e($w->id); ?>">
                                                            <div class="history_icon">
                                                                <svg class="icon">
                                                                    <use xlink:href="/img/symbols.svg#icon-timer"></use>
                                                                </svg>
                                                            </div>
                                                            <div class="btn">
                                                                <span>Cancelar</span>
                                                            </div>
                                                        </div>
                                                    <?php elseif($w->status == 1): ?>
                                                        <span class="color-green">PAGO</span>
                                                    <?php else: ?>
                                                        CANCELADO
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="history_empty">
                                    <h4>N/A</h4>Você não fez nenhum deposito ainda.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="history_wrapper dep" style="display: none;">
                    <div class="withPager">
                        <div class="list">
                            <?php if($pays->count() > 0): ?>
                                <div class="history_scroll">
                                    <table class="history_table">
                                        <thead>
                                        <tr>
                                            <th>Data | Hora</th>
                                            <th>Identificador</th>
                                            <th>Método</th>
                                            <th>Valor</th>
                                            <th class="text-right">Status</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $__currentLoopData = $pays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e(Carbon\Carbon::parse($p->created_at)->format('d.m.Y | h:i')); ?>hs </td>
                                                <td>#<?php echo e($p->secret); ?></td>
                                                <td>
                                                    <div class="history_system"><?php echo e($p->system); ?></div>
                                                </td>
                                                <td>R$ <?php echo e($p->sum); ?></td>
                                                <td class="text-right depStatus_<?php echo e($p->id); ?>">
                                                    <?php if($p->status == 'Pendente'): ?>
                                                        <div class="history_button_dep" data-id="<?php echo e($p->id); ?>">
                                                            <div class="history_icon">
                                                                <svg class="icon">
                                                                    <use xlink:href="/img/symbols.svg#icon-timer"></use>
                                                                </svg>
                                                            </div>
                                                            <div class="btn">
                                                                <span>Cancelar</span>
                                                            </div>
                                                        </div>
                                                    <?php elseif($p->status == 'Aprovado'): ?>
                                                        <span class="color-green"><?php echo e($p->status); ?></span>
                                                    <?php else: ?>
                                                        <?php echo e($p->status); ?>

                                                    <?php endif; ?>
                                                        </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="history_empty">
                                    <h4>N/A</h4>Você não fez nenhum retirada ainda.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php /* /var/www/html/resources/views/pages/profileHistory.blade.php */ ?>