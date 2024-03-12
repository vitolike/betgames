@extends('admin')

@section('content')
<script src="/dash/js/ajax-users.js" type="text/javascript"></script>
<div class="kt-subheader kt-grid__item" id="kt_subheader">
	<div class="kt-subheader__main">
		<h3 class="kt-subheader__title">Úsuarios</h3>
	</div>
</div>

<div class="kt-content kt-grid__item kt-grid__item--fluid" id="kt_content">
	<div class="kt-portlet kt-portlet--mobile">
		<div class="kt-portlet__head kt-portlet__head--lg">
			<div class="kt-portlet__head-label">
				<span class="kt-portlet__head-icon">
					<i class="kt-font-brand flaticon-users"></i>
				</span>
				<h3 class="kt-portlet__head-title">
					Lista de úsuarios
				</h3>
			</div>
		</div>
		<div class="kt-portlet__body">

			<!--begin: Datatable -->
			<table class="table table-striped- table-bordered table-hover table-checkable" id="ajax-users">
				<thead>
					<tr>
						<th>ID</th>
						<th>Nome</th>
						<th>Nome</th>
						<th>Saldo Real</th>
						<th>Saldo Bônus</th>
						<th>Tipo de conta</th>
						<th></th>
						<th>IP</th>
						<th>Editar</th>
						<th></th>
					</tr>
				</thead>
			</table>

			<!--end: Datatable -->
		</div>
	</div>
</div>
@endsection