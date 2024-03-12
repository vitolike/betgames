@extends('admin')

@section('content')
    <script src="/dash/js/dtables.js" type="text/javascript"></script>
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">Retiradas</h3>
        </div>
    </div>

    <div class="kt-content kt-grid__item kt-grid__item--fluid" id="kt_content">
        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__head kt-portlet__head--lg">
                <div class="kt-portlet__head-label">
				<span class="kt-portlet__head-icon">
					<i class="kt-font-brand flaticon2-information"></i>
				</span>
                    <h3 class="kt-portlet__head-title">
                        Lista de Retiradas
                    </h3>
                </div>
            </div>
            <div class="kt-portlet__body">

                <!--begin: Datatable -->
                <table class="table table-striped- table-bordered table-hover table-checkable" id="dtable">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Valor</th>
                        <th>Sistema de pag.</th>
                        <th>Carteira</th>
                        <th>Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($withdraws as $withdraw)

                        <tr>
                            <td>{{$withdraw['id']}}</td>
                            <td><a href="/admin/user/{{$withdraw['user_id']}}"><img src="{{$withdraw['avatar']}}"
                                                                                    style="width:26px;border-radius:50%;margin-right:10px;vertical-align:middle;"> {{$withdraw['username']}}
                                </a></td>
                            <td>R$ {{$withdraw['value']}}</td>
                            <td>{{$withdraw['system']}}
                                ({{$withdraw['payment_provider'] }})
                            </td>
                            <td>{{$withdraw['wallet']}}</td>
                            <td>
                                <div class="row text-center">
                                    <div class="col-md-6"><a href="/admin/withdraw/{{$withdraw['id']}}"
                                                             class="btn btn-success btn-sm">Aprovar</a></div>
                                    <div class="col-md-6"><a href="/admin/return/{{$withdraw['id']}}"
                                                             class="btn btn-danger btn-sm">Cancelar</a></div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <!--end: Datatable -->
            </div>
        </div>
        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__head kt-portlet__head--lg">
                <div class="kt-portlet__head-label">
				<span class="kt-portlet__head-icon">
					<i class="kt-font-brand flaticon2-checkmark"></i>
				</span>
                    <h3 class="kt-portlet__head-title">
                        Lista de depósitos
                    </h3>
                </div>
            </div>
            <div class="kt-portlet__body">

                <!--begin: Datatable -->
                <table class="table table-striped- table-bordered table-hover table-checkable" id="dtable2">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Valor</th>
                        <th>Provedor</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($deposits as $finish)
                        <tr>
                            <td>{{$finish['id']}}</td>
                            <td><a href="/admin/user/{{$finish['user_id']}}" target="_blank"><img
                                        src="{{$finish['avatar']}}"
                                        style="width:26px;border-radius:50%;margin-right:10px;vertical-align:middle;"> {{$finish['username']}}
                                </a></td>
                            <td>R$ {{$finish['value']}}</td>
                            <td>{{$finish['system']}}</td>
                            <td>{{$finish['status']}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <!--end: Datatable -->
            </div>
        </div>
    </div>
@endsection
