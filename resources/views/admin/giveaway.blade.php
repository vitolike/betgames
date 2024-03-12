@extends('admin')

@section('content')
<script src="/dash/js/dtables.js" type="text/javascript"></script>
<div class="kt-subheader kt-grid__item" id="kt_subheader">
	<div class="kt-subheader__main">
		<h3 class="kt-subheader__title">Sorteios</h3>
	</div>
</div>

<div class="kt-content kt-grid__item kt-grid__item--fluid" id="kt_content">
	<div class="kt-portlet kt-portlet--mobile">
		<div class="kt-portlet__head kt-portlet__head--lg">
			<div class="kt-portlet__head-label">
				<span class="kt-portlet__head-icon">
					<i class="kt-font-brand flaticon2-hourglass-1"></i>
				</span>
				<h3 class="kt-portlet__head-title">
					Lista de Sorteios
				</h3>
			</div>
			<div class="kt-portlet__head-toolbar">
				<div class="kt-portlet__head-wrapper">
					<div class="kt-portlet__head-actions">
						<a data-toggle="modal" href="#new" class="btn btn-success btn-elevate btn-icon-sm">
							<i class="la la-plus"></i>
							Adicionar
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
						<th>Valor</th>
						<th>Data do sorteio</th>
						<th>Vencedor</th>
						<th>Ações</th>
					</tr>
				</thead>
				<tbody>
					@foreach($giveaway as $gv)
					<tr>
						<td>{{$gv->id}}</td>
						<td> R$: {{$gv->sum}} (Tipo: {{ $gv->type == 'balance' ? 'Saldo Real' : 'Saldo Bônus' }})</td>
						<td>{{ \Carbon\Carbon::parse($gv->time_to)->setTimezone('Europe/Moscow')->format('d.m.Y H:i:s') }}</td>
						<td>@if($gv->winner_id)<a href="/admin/user/{{$gv->winner_id}}">@endif{{ ($gv->winner_id ? \App\User::getUser($gv->winner_id)->username : 'Não Determinado') }}@if($gv->winner_id)</a>@endif</td>
						<td><a class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Editar" data-toggle="modal" href="#edit_{{$gv->id}}"><i class="la la-edit"></i></a><a href="/admin/giveaway/delete/{{$gv->id}}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="APAGAR"><i class="la la-trash"></i></a></td>
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
                <h5 class="modal-title" id="exampleModalLongTitle">Novo Sorteio</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="kt-form-new" method="post" action="/admin/giveaway/new">
				<div class="modal-body">
					<div class="form-group">
						<label for="name">Valor:</label>
						<input type="text" class="form-control" placeholder="Insira o valor" name="sum">
					</div>
					<div class="form-group">
						<label for="name">Tipo de Saldo:</label>
						<select class="form-control" name="type">
							<option value="balance">Saldo Real</option>
							<option value="bonus">Saldo Bônus</option>
						</select>
					</div>
					<div class="form-group">
						<label>Verificações:</label>
						<div class="kt-checkbox-inline">
							<label class="kt-checkbox">
								<input type="checkbox" name="group_sub"> Verifique o usuario
								<span></span>
							</label>
							<label class="kt-checkbox">
								<input type="checkbox" class="minCheck"> Verifique o valor já depositado pelo usuario.
								<span></span>
							</label>
						</div>
					</div>
					<div class="form-group checkedDep" style="display: none;">
						<label for="name">Quantidade mínima de depositos pelo usuario ate o
dia do sorteio:</label>
						<input type="text" class="form-control" placeholder="Valor" name="min_dep">
					</div>
					<div class="form-group">
						<label>Data do sorteio:</label>
						<input type="text" class="form-control kt_datetimepicker" readonly data-z-index="1100" placeholder="selecione data e hora" name="time_to" />
					</div>
					<div class="form-group">
						<label for="name">Escolher um vencedor???:</label>
						<select class="form-control" name="winner_id">
							<option value="null">Selecione um bot ou usuario para ganhar.</option>
							<option disabled style="background: #5867dd; color: #fff;">--- Fakes ---</option>
							@foreach($fake as $fk)
							<option value="{{$fk->id}}">{{$fk->username}}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Voltar</button>
					<button type="submit" class="btn btn-primary">Criar</button>
				</div>
            </form>
        </div>
    </div>
</div>
@foreach($giveaway as $gv)
<div class="modal fade" id="edit_{{$gv->id}}" tabindex="-1" role="dialog" aria-labelledby="newLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Editando Sorteios</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="kt-form-new" method="post" action="/admin/giveaway/save">
				<input type="hidden" value="{{$gv->id}}" name="id">
				<div class="modal-body">
					<div class="form-group">
						<label for="name">Сумма Sorteios:</label>
						<input type="text" class="form-control" placeholder="Сумма" name="sum" value="{{$gv->sum}}">
					</div>
					<div class="form-group">
						<label for="name">Тип баланса:</label>
						<select class="form-control" name="type">
							<option value="balance" {{ $gv->type == 'balance' ? 'selected' : '' }}>Реальный</option>
							<option value="bonus" {{ $gv->type == 'bonus' ? 'selected' : '' }}>Бонусный</option>
						</select>
					</div>
					<div class="form-group">
						<label>Проверки:</label>
						<div class="kt-checkbox-inline">
							<label class="kt-checkbox">
								<input type="checkbox" name="group_sub" {{ $gv->group_sub ? 'checked' : '' }}> Проверять подписку на группу
								<span></span>
							</label>
							<label class="kt-checkbox">
								<input type="checkbox" class="minCheck" {{ $gv->min_dep > 0 ? 'checked' : '' }}> Проверять сумму пополнения за текущий день
								<span></span>
							</label>
						</div>
					</div>
					<div class="form-group checkedDep" style="display: {{ $gv->min_dep > 0 ? 'block' : 'none' }};">
						<label for="name">Quantidade mínima
 пополнения за текущий день:</label>
						<input type="text" class="form-control" placeholder="Сумма" value="{{$gv->min_dep}}" name="min_dep">
					</div>
					<div class="form-group">
						<label>Время окончания:</label>
						<input type="text" class="form-control kt_datetimepicker" value="{{ \Carbon\Carbon::parse($gv->time_to)->setTimezone('Europe/Moscow')->format('d.m.Y H:i') }}" readonly data-z-index="1100" placeholder="Выбрать дату и время" name="time_to" />
					</div>
					<div class="form-group">
						<label for="name">Выбор победителя:</label>
						<select class="form-control" name="winner_id">
							<option value="null">Нет</option>
							<option disabled style="background: #5867dd; color: #fff;">--- Jogadores ---</option>
							@foreach(\App\GiveawayUsers::where('giveaway_id', $gv->id)->get() as $gvu)
							<option value="{{$gvu->user_id}}" {{ $gv->winner_id == $gvu->user_id ? 'selected' : '' }}>{{ \App\User::getUser($gvu->user_id)->username }}</option>
							@endforeach
							<option disabled style="background: #5867dd; color: #fff;">--- Фейки ---</option>
							@foreach($fake as $fk)
							<option value="{{$fk->id}}" {{ $gv->winner_id == $fk->id ? 'selected' : '' }}>{{$fk->username}}</option>
							@endforeach
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
<script>
$(document).ready(function() {
    $('#edit_{{$gv->id}} .minCheck').click(function() {
        if($(this).prop('checked') == true){
            $('#edit_{{$gv->id}} .minCheck').parent().parent().parent().parent().find('.checkedDep').slideDown();
        } else {
            $('#edit_{{$gv->id}} .minCheck').parent().parent().parent().parent().find('.checkedDep').slideUp();
        }
    });
});
</script>
@endforeach

@endsection
