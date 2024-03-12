@extends('panel')

@section('content')
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
					Список промокодов
				</h3>
			</div>
			<div class="kt-portlet__head-toolbar">
				<div class="kt-portlet__head-wrapper">
					<div class="kt-portlet__head-actions">
						<a data-toggle="modal" href="#new" class="btn btn-success btn-elevate btn-icon-sm">
							<i class="la la-plus"></i>
							Добавить
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
						<th>Тип</th>
						<th>Код</th>
						<th>Лимит</th>
						<th>Сумма</th>
						<th>Кол-во</th>
						<th>Действия</th>
					</tr>
				</thead>
				<tbody>
					@foreach($promos as $code)
					<tr>
						<td>{{$code->id}}</td>
						<td>{{$code->type == 'balance' ? 'Баланс' : 'Бонус'}}</td>
						<td>{{$code->code}}</td>
						<td>{{$code->limit ? 'По кол-ву' : 'Без лимита'}}</td>
						<td>{{$code->amount}}р.</td>
						<td>{{$code->count_use}}</td>
						<td><a class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Editar" data-toggle="modal" href="#edit_{{$code->id}}"><i class="la la-edit"></i></a><a href="/panel/promo/delete/{{$code->id}}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="APAGAR"><i class="la la-trash"></i></a></td>
					</tr>
					@endforeach
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
                <h5 class="modal-title" id="exampleModalLongTitle">Новый промокод</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="kt-form-new" method="post" action="/panel/promo/new">
				<div class="modal-body">
					<div class="form-group">
						<label for="name">Код (только английские символы):</label>
						<input type="text" class="form-control" placeholder="Код" name="code">
					</div>
					<div class="form-group">
						<label for="name">Тип:</label>
						<select class="form-control" name="type">
							<option value="balance">Баланс</option>
							<option value="bonus">Бонус</option>
						</select>
					</div>
					<div class="form-group">
						<label for="name">Лимит:</label>
						<select class="form-control" name="limit">
							<option value="0">Без лимита</option>
							<option value="1">По кол-ву</option>
						</select>
					</div>
					<div class="form-group">
						<label for="name">Сумма:</label>
						<input type="text" class="form-control" placeholder="Сумма" name="amount">
					</div>
					<div class="form-group">
						<label for="name">Кол-во активаций (При включеном лимите):</label>
						<input type="text" class="form-control" placeholder="Кол-во" name="count_use">
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
@foreach($promos as $code)
<div class="modal fade" id="edit_{{$code->id}}" tabindex="-1" role="dialog" aria-labelledby="newLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Редактирование промокода</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="kt-form-new" method="post" action="/panel/promo/save">
				<input type="hidden" value="{{$code->id}}" name="id">
				<div class="modal-body">
					<div class="form-group">
						<label for="name">Код (только английские символы):</label>
						<input type="text" class="form-control" placeholder="Код" name="code" value="{{$code->code}}">
					</div>
					<div class="form-group">
						<label for="name">Тип:</label>
						<select class="form-control" name="type">
							<option value="balance" {{$code->type == 'balance' ? 'selected' : ''}}>Баланс</option>
							<option value="bonus" {{$code->type == 'bonus' ? 'selected' : ''}}>Бонус</option>
						</select>
					</div>
					<div class="form-group">
						<label for="name">Лимит:</label>
						<select class="form-control" name="limit">
							<option value="0" {{$code->limit == 0 ? 'selected' : ''}}>Без лимита</option>
							<option value="1" {{$code->limit == 1 ? 'selected' : ''}}>По кол-ву</option>
						</select>
					</div>
					<div class="form-group">
						<label for="name">Сумма:</label>
						<input type="text" class="form-control" placeholder="Сумма" name="amount" value="{{$code->amount}}">
					</div>
					<div class="form-group">
						<label for="name">Кол-во активаций (При включеном лимите):</label>
						<input type="text" class="form-control" placeholder="Кол-во" name="count_use" value="{{$code->count_use}}">
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
@endforeach
@endsection
