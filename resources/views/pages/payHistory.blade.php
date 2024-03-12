@extends('layout')

@section('content')
<div class="head-game">
	<span class="game-name">Histórico счета</span>
</div>
<div class="cont-b">
	<div class="second-title"><span>Активные запросы</span></div>
	@if($active != '[]')
	<div class="payHistory">
		<table class="list">
			<thead>
				<tr>
					<th>Номер</th>
					<th>Система<br>Кошелек</th>
					<th>Сумма</th>
					<th>Действие</th>
				</tr>
			</thead>
			<tbody>
				@foreach($active as $a)
				<tr>
					<td><div class="id">{{$a->id}}</div></td>
					<td><div class="system">{{$a->wallet}} ({{$a->system}})</div></td>
					<td><div class="sum {{ $a->status ? 'ok' : 'dec' }}">@if($a->status == 2) +{{$a->value}} @else -{{$a->value}} @endif</div></td>
					<td><div class="status"><a class="buttoninzc" href="/withdraw/cancel/{{$a->id}}"><i class="fas fa-times"></i></a></div></td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
	@endif
	<div class="second-title"><span>Histórico depositos</span></div>
	@if($pays != '[]')
	<div class="payHistory">
		<table class="list">
			<thead>
				<tr>
					<th>Numero</th>
					<th>Tipo</th>
					<th>Método<br>Carteira</th>
					<th>Valor</th>
					<th>Status</th>
				</tr>
			</thead>
			<tbody>
				@foreach($pays as $pay)
				<tr>
					<td><div class="id">{{$pay->id}}</div></td>
					<td><div class="type ok">@if($pay->status == 1) Пополнение баланса @elseif($pay->status == 2) Реферальный код @else Промокод @endif</div></td>
					<td><div class="system">@if($pay->status == 1) XMPAY @else {{$pay->code}} @endif</div></td>
					<td><div class="sum ok">+{{$pay->price}}</div></td>
					<td><div class="status ok">Выполнен</div></td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
	@endif
	<div class="second-title"><span>Histórico retiradas</span></div>
	@if($withdraws != '[]')
	<div class="payHistory">
		<table class="list">
			<thead>
				<tr>
					<th>Numero</th>
					<th>Tipo</th>
					<th>Método<br>Carteira</th>
					<th>Valor</th>
					<th>Status</th>
				</tr>
			</thead>
			<tbody>
				@foreach($withdraws as $with)
				<tr>
					<td><div class="id">{{$with->id}}</div></td>
					<td><div class="type {{ $with->status ? 'ok' : 'dec' }}">Вывод</div></td>
					<td><div class="system">{{$with->system}}</div></td>
					<td><div class="sum {{ $with->status ? 'ok' : 'dec' }}">@if($with->status == 2) +{{$with->value}} @else -{{$with->value}} @endif</div></td>
					<td><div class="status {{ $with->status ? 'ok' : 'dec' }}">@if($with->status == 0) На модерации @elseif($with->status == 1) Выполнен @else Возвращен @endif</div></td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
	@endif
</div>
@endsection