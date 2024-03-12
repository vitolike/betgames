<?php $__env->startSection('content'); ?>
<script src="/dash/js/dtables.js" type="text/javascript"></script>
<div class="kt-subheader kt-grid__item" id="kt_subheader">
	<div class="kt-subheader__main">
		<h3 class="kt-subheader__title">Bônus</h3>
	</div>
</div>

<div class="kt-content kt-grid__item kt-grid__item--fluid" id="kt_content">
	<div class="kt-portlet kt-portlet--mobile">
		<div class="kt-portlet__head kt-portlet__head--lg">
			<div class="kt-portlet__head-label">
				<span class="kt-portlet__head-icon">
					<i class="kt-font-brand flaticon2-gift-1"></i>
				</span>
				<h3 class="kt-portlet__head-title">
					Lista de Bônus
				</h3>
			</div>
			<div class="kt-portlet__head-toolbar">
				<div class="kt-portlet__head-wrapper">
					<div class="kt-portlet__head-actions">
						<a data-toggle="modal" href="#new" class="btn btn-success btn-elevate btn-icon-sm">
							<i class="la la-plus"></i>
							ADICIONAR
						</a>
					</div>
				</div>
			</div>
		</div>
		<div class="kt-portlet__body">

			<!--begin: Datatable -->
			<table class="table table-striped- table-bordered table-hover table-checkable" id="dtable">
				<thead>
					<tr>
						<th>Тип</th>
						<th>Сумма</th>
						<th>Выпадает</th>
						<th>Valorа</th>
						<th>Действия</th>
					</tr>
				</thead>
				<tbody>
					<?php $__currentLoopData = $bonuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<tr>
						<td><?php echo e($b->type == 'group' ? 'Группа' : 'Afiliados'); ?></td>
						<td><?php echo e($b->sum); ?>р.</td>
						<td><?php echo e($b->status ? 'Да' : 'Нет'); ?></td>
						<td><div style="background: <?php echo e($b->bg); ?>; color: <?php echo e($b->color); ?>; font-weight: 600; text-align: center; padding: 3px 0;">Текст</div></td>
						<td><a class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Editar" data-toggle="modal" href="#edit_<?php echo e($b->id); ?>"><i class="la la-edit"></i></a><a href="/admin/bonus/delete/<?php echo e($b->id); ?>" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="APAGAR"><i class="la la-trash"></i></a></td>
					</tr>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</tbody>
			</table>

			<!--end: Datatable -->
		</div>
	</div>
</div>
<div class="modal fade" id="new" tabindex="-1" role="dialog" aria-labelledby="newLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Новый бонус</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="kt-form-new" method="post" action="/admin/bonus/new">
				<div class="modal-body">
					<div class="form-group">
						<label for="name">Сумма:</label>
						<input type="text" class="form-control" placeholder="Сумма" name="sum">
					</div>
					<div class="form-group">
						<label for="name">Тип:</label>
						<select class="form-control" name="type">
							<option value="group">Группа</option>
							<option value="refs">Afiliados</option>
						</select>
					</div>
					<div class="form-group">
						<label for="name">Valor фона:</label>
						<input type="text" class="form-control bgColor" placeholder="#000" name="bg">
					</div>
					<div class="form-group">
						<label for="name">Valor текста:</label>
						<input type="text" class="form-control textColor" placeholder="#000" name="color">
					</div>
					<div class="form-group">
						<label for="name">Пример:</label>
						<div class="exBg" style="background: #fff; text-align: center; padding: 3px 0;"><span class="exText" style="color: #fff; font-weight: 600;">Текст</span></div>
					</div>
					<div class="form-group">
						<label for="name">Выпадает:</label>
						<select class="form-control" name="status">
							<option value="1">Да</option>
							<option value="0">Нет</option>
						</select>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
					<button type="submit" class="btn btn-primary">Добавить</button>
				</div>
            </form>
        </div>
    </div>
</div>
<?php $__currentLoopData = $bonuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="modal fade" id="edit_<?php echo e($b->id); ?>" tabindex="-1" role="dialog" aria-labelledby="newLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Редактирование бонуса</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="kt-form-new" method="post" action="/admin/bonus/save">
				<input type="hidden" value="<?php echo e($b->id); ?>" name="id">
				<div class="modal-body">
					<div class="form-group">
						<label for="name">Сумма:</label>
						<input type="text" class="form-control" placeholder="Сумма" name="sum" value="<?php echo e($b->sum); ?>">
					</div>
					<div class="form-group">
						<label for="name">Тип:</label>
						<select class="form-control" name="type">
							<option value="group">Группа</option>
							<option value="refs">Afiliados</option>
						</select>
					</div>
					<div class="form-group">
						<label for="name">Valor фона:</label>
						<input type="text" class="form-control bgColor" placeholder="#000" name="bg" value="<?php echo e($b->bg); ?>">
					</div>
					<div class="form-group">
						<label for="name">Valor текста:</label>
						<input type="text" class="form-control textColor" placeholder="#000" name="color" value="<?php echo e($b->color); ?>">
					</div>
					<div class="form-group">
						<label for="name">Пример:</label>
						<div class="exBg" style="background: <?php echo e($b->bg); ?>; text-align: center; padding: 3px 0;"><span class="exText" style="color: <?php echo e($b->color); ?>; font-weight: 600;">Текст</span></div>
					</div>
					<div class="form-group">
						<label for="name">Выпадает:</label>
						<select class="form-control" name="status">
							<option value="1" <?php echo e($b->status ? 'selected' : ''); ?>>Да</option>
							<option value="0" <?php echo e(!$b->status ? 'selected' : ''); ?>>Нет</option>
						</select>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
					<button type="submit" class="btn btn-primary">Сохранить</button>
				</div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php /* /var/www/html/resources/views/admin/bonus.blade.php */ ?>