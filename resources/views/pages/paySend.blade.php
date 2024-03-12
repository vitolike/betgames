@extends('layout')

@section('content')
<div class="sendmoney">
<div class="head-game">
</div>
<div class="cont-b">
	<div class="ref">
		<div class="info">
			<h3 class="title">Хотите попросить монеты у друзей?</h3>
			<div class="desc">Скопируйте ваш ID и отправьте его своему другу!</div>
		</div>
		<div class="code">
			<div class="code-title">Ваш уникальный ID:</div>
			<div class="value">
				<input type="text" value="{{$u->user_id}}" id="userID" readonly="" style="text-align: center;border-color: #fff;height: 32px;background-color: #e8e8e8;border-radius: 25px;padding: 15px;outline: none;margin-bottom: 16px;border: 2px solid transparent;">
				<i class="fas fa-copy tooltip tooltipstered" onclick="copyToClipboard('#userID')"></i>
			</div>
		</div>
		<div class="info">
			<h3 class="title">Перевод монет</h3>
			<div class="desc">Для перевода монет Jogadorу вам достаточно знать его уникальный ID</div>
		</div>
		<div class="code">
			<div class="code-title">Введите ID полуChatеля:</div>
			<div class="value">
				<input type="text" placeholder="Уникальный идентификатор" class="targetID" style="text-align: center;border-color: #fff;height: 32px;background-color: #e8e8e8;border-radius: 25px;padding: 15px;outline: none;margin-bottom: 16px;border: 2px solid transparent;">
			</div>
		</div>
		<div class="code">
			<div class="code-title">Cумму перевода:</div>
			<div class="value">
				<input type="text" placeholder="Желаемая сумма" class="sum" id="sumToSend" style="text-align: center;border-color: #fff;height: 32px;background-color: #e8e8e8;border-radius: 25px;padding: 15px;outline: none;margin-bottom: 16px;border: 2px solid transparent;">
			</div>
		</div>
		<div class="info">
			<h3 class="title">Будет списанно: <span id="minusSum">0</span> <i class="fas fa-coins"></i></h3>
			<h3 class="title" style="font-size: 12px; color: #949494;">(комиссия 5%)</h3>
		</div>
		<div class="info">
			<div class="desc">
				Quantidade mínima
 перевода 20 монет<br>
				Для выполнения перевода нужно сделать вывод минимум на 250 рублей
			</div>
		</div>
		<a class="btn sendButton" style="margin-top: 10px;">ПЕРЕВЕСТИ</a>
	</div>
</div>
</div>
@endsection