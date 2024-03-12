<?php $__env->startSection('content'); ?>
    <link rel="stylesheet" href="/css/profileHistory.css">
    <script type="text/javascript" src="/js/profileHistory.js"></script>
    <div class="section">
        <div class="wallet_container">
            <div class="wallet_component">
                <div class="history_nav">
                    <button type="button" class="btn isActive"  data-tab="my_profile"><span>Minha Conta</span></button>
                    <button type="button" class="btn" data-tab="change_pass"><span>Alterar Senha</span></button>
                </div>


				<div class="history_wrapper my_profile">
                    <div class="withPager">
                        <div class="form-data history_empty">

				<br>
				<div class="withPager">
					<div class="user-profile">
						<div class="user-block">
							<div class="user-avatar" style="">
								<button class="close-btn">
								</button>
								<div class="avatar"><img src="<?php echo e($u->avatar); ?>" alt=""></div>
							</div>
						</div>
					</div>
				</div>
                                <div class="form-row">
                                    <label for="exampleFormControlInput1" class="form-label">Seu Username</label>
                                    <input disabled="disabled" type="text" name="username" value="<?php echo e($u->username); ?>" class="input-field">

									<label for="exampleFormControlInput1" class="form-label">Seu Nome Real</label>
                                    <input disabled="disabled" type="text" name="username" value="<?php echo e($u->real_name); ?>" class="input-field">

                                    <label for="exampleFormControlInput1" class="form-label">Seu Email</label>
                                    <input disabled="disabled" type="text" name="email" value="<?php echo e($u->email); ?>" class="input-field">

								    <label for="exampleFormControlInput1" class="form-label">Seu CPF</label>
                                    <input disabled="disabled" type="text" name="email" value="<?php echo e($u->cpf); ?>" class="input-field">
                                </div>
                        </div>
                    </div>
                </div>

				<div class="history_wrapper change_pass" hidden>
                    <div class="withPager">
                        <div class="form-data history_empty">

                            <form class="kt-form" method="post" action="/profile/change-password" id="save">

                                <div class="form-row" st>
                                    <label for="exampleFormControlInput1" class="form-label">Digite a senha antiga</label>
                                    <input type="password" name="password" placeholder="Digite a senha antiga" class="input-field">

                                    <label for="exampleFormControlInput1" class="form-label">Digite a nova senha</label>
                                    <input type="password" name="newPassword" placeholder="Digite a nova senha" class="input-field">

                                </div>

                                <button type="submit" class="btn" style><span>Salvar Nova Senha</span></button>

                            </form>

                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php /* /var/www/html/resources/views/pages/user/profile.blade.php */ ?>