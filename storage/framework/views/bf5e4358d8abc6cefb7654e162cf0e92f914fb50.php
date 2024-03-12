<?php $__env->startSection('content'); ?>
<script src="/dash/js/dtables.js" type="text/javascript"></script>
<div class="kt-subheader kt-grid__item" id="kt_subheader">
	<div class="kt-subheader__main">
		<h3 class="kt-subheader__title">Código Promocional</h3>
	</div>
</div>

<div class="kt-content kt-grid__item kt-grid__item--fluid" id="kt_content">
	<div class="kt-portlet kt-portlet--mobile">
		<div class="kt-portlet__head kt-portlet__head--lg">
			<div class="kt-portlet__head-label">
				<span class="kt-portlet__head-icon">
					<i class="kt-font-brand flaticon2-menu-2"></i>
				</span>
				<h3 class="kt-portlet__head-title">
					Lista de códigos promocionais
				</h3>
			</div>
			<div class="kt-portlet__head-toolbar">
				<div class="kt-portlet__head-wrapper">
					<div class="kt-portlet__head-actions">
						<a data-toggle="modal" href="#new" class="btn btn-success btn-elevate btn-icon-sm">
							<i class="la la-plus"></i>
							Criar Código Promocional
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
						<th>ID</th>
						<th>Tipo</th>
						<th>Código</th>
						<th>Limite</th>
						<th>Valor do Bônus</th>
						<th>Quantidade de uso</th>
						<th>Ações</th>
					</tr>
				</thead>
				<tbody>
					<?php $__currentLoopData = $promos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<tr>
						<td><?php echo e($code->id); ?></td>
						<td><?php echo e($code->type == 'balance' ? 'Saldo Real' : 'Bônus'); ?></td>
						<td><?php echo e($code->code); ?></td>
						<td><?php echo e($code->limit ? 'Por Quantidade' : 'Sem Limite'); ?></td>
						<td>R$ <?php echo e($code->amount); ?></td>
						<td><?php echo e($code->count_use); ?></td>
						<td><a class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Editar" data-toggle="modal" href="#edit_<?php echo e($code->id); ?>"><i class="la la-edit"></i></a><a href="/admin/promo/delete/<?php echo e($code->id); ?>" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="APAGAR"><i class="la la-trash"></i></a></td>
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
                <h5 class="modal-title" id="exampleModalLongTitle">Novo código promocional</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="kt-form-new" method="post" action="/admin/promo/new">
				<div class="modal-body">
					<div class="form-group">
						<label for="name">Código:</label>
						<input type="text" class="form-control" placeholder="Código" name="code">
					</div>
					<div class="form-group">
						<label for="name">Tipo:</label>
						<select class="form-control" name="type">
							<option value="balance">Valor Real</option>
							<option value="bonus">Valor Bônus</option>
						</select>
					</div>
					<div class="form-group">
						<label for="name">Limite:</label>
						<select class="form-control" name="limit">
							<option value="0">Sem Limite</option>
							<option value="1">Limite por quantidade</option>
						</select>
					</div>
					<div class="form-group">
						<label for="name">Valor:</label>
						<input type="text" class="form-control" placeholder="Valor" name="amount">
					</div>
					<div class="form-group">
						<label for="name">Número maximo de usos:</label>
						<input type="text" class="form-control" placeholder="Digite Um numero" name="count_use">
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Voltar</button>
					<button type="submit" class="btn btn-primary">Adicionar</button>
				</div>
            </form>
        </div>
    </div>
</div>
<?php $__currentLoopData = $promos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="modal fade" id="edit_<?php echo e($code->id); ?>" tabindex="-1" role="dialog" aria-labelledby="newLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Editando Código</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="kt-form-new" method="post" action="/admin/promo/save">
				<input type="hidden" value="<?php echo e($code->id); ?>" name="id">
				<div class="modal-body">
					<div class="form-group">
						<label for="name">Código:</label>
						<input type="text" class="form-control" placeholder="Código" name="code" value="<?php echo e($code->code); ?>">
					</div>
					<div class="form-group">
						<label for="name">Tipo:</label>
						<select class="form-control" name="type">
							<option value="balance" <?php echo e($code->type == 'balance' ? 'selected' : ''); ?>>Valor Real</option>
							<option value="bonus" <?php echo e($code->type == 'bonus' ? 'selected' : ''); ?>>Valor Bônus</option>
						</select>
					</div>
					<div class="form-group">
						<label for="name">Limite:</label>
						<select class="form-control" name="limit">
							<option value="0" <?php echo e($code->limit == 0 ? 'selected' : ''); ?>>Sem Limite</option>
							<option value="1" <?php echo e($code->limit == 1 ? 'selected' : ''); ?>>Por quantidade</option>
						</select>
					</div>
					<div class="form-group">
						<label for="name">Valor:</label>
						<input type="text" class="form-control" placeholder="Valor" name="amount" value="<?php echo e($code->amount); ?>">
					</div>
					<div class="form-group">
						<label for="name">Número de ativações (quando o limite é habilitado):</label>
						<input type="text" class="form-control" placeholder="Limite" name="count_use" value="<?php echo e($code->count_use); ?>">
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Voltar</button>
					<button type="submit" class="btn btn-primary">Modificar</button>
				</div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php /* /var/www/html/resources/views/admin/promo.blade.php */ ?>