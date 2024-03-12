@extends('admin')

@section('content')
<script src="/dash/js/dtables.js" type="text/javascript"></script>
<div class="kt-subheader kt-grid__item" id="kt_subheader">
	<div class="kt-subheader__main">
		<h3 class="kt-subheader__title">Bots</h3>
	</div>
</div>

<div class="kt-content kt-grid__item kt-grid__item--fluid" id="kt_content">
	<div class="kt-portlet kt-portlet--mobile">
		<div class="kt-portlet__head kt-portlet__head--lg">
			<div class="kt-portlet__head-label">
				<span class="kt-portlet__head-icon">
					<i class="kt-font-brand flaticon2-user"></i>
				</span>
				<h3 class="kt-portlet__head-title">
					Lista de Bots
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
						<th>Perfil</th>
						<th>Horas de atividade</th>
						<th>Ações</th>
					</tr>
				</thead>
				<tbody>
					@foreach($bots as $bot)
					<tr>
						<td>{{$bot->id}}</td>
						<td><img src="{{$bot->avatar}}" style="width:26px;border-radius:50%;margin-right:10px;vertical-align:middle;">{{$bot->username}}</td>
						<td>{{ $bot->time == 0 ? 'Tempo Todo' : '' }}{{ $bot->time == 1 ? '6:00 as  12:00' : '' }}{{ $bot->time == 2 ? '12:00 as 18:00' : '' }}{{ $bot->time == 3 ? '18:00 as 00:00' : '' }}{{ $bot->time == 4 ? '00:00  as 06:00' : '' }}</td>
						<td><a href="/admin/user/{{$bot->id}}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Editar"><i class="la la-edit"></i></a><a href="/admin/bots/delete/{{$bot->id}}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="APAGAR"><i class="la la-trash"></i></a></td>
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
                <h5 class="modal-title" id="exampleModalLongTitle">Criando um novo bot</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="kt-form-new" method="post" action="/admin/fakeSave" id="save">
				<div class="modal-body">
					<div class="form-group">
						<label for="name">Link Fake para Perfil</label>
						<input type="text" class="form-control" placeholder="insira uma url(opcional)" name="name" id="url">
					</div>
					<div class="form-group">
						<label for="name">Hora de Atividade</label>
						<select class="form-control" name="time">
							<option value="1">de manhã (das 6h as 12h)</option>
							<option value="2">de tarde (das 12h as 18h)</option>
							<option value="3">pela noite (das 18h as 00h)</option>
							<option value="4">pela madrugada (das 00h as 6h)</option>
							<option value="0">24 HORAS POR DIA</option>
						</select>
					</div>
					<div class="row" id="prof" style="display: ;">
						<div class="col-xl-12">
							<div class="kt-section__body">
								<div class="form-group row">
									<label class="col-xl-3 col-lg-3 col-form-label">Nome</label>
									<div class="col-lg-9 col-xl-9">
										<input class="form-control" type="text" value="" name="name" id="name">
									</div>
								</div>
							</div>
						</div>
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
@endsection
