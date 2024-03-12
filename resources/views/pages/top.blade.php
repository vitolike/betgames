@extends('layout')

@section('content')
<div class="head-game">
	<span class="game-name">Топ</span>
</div>
<div class="cont-b">
	<div class="second-title"><span>Ежедневный топ 20 игроков</span></div>
	<div class="rooms">
		<ul class="room-selector">
			<li class="room active">
				<a><div class="room-name">Jackpot</div></a>
			</li>
			<li class="room">
				<a><div class="room-name">Double</div></a>
			</li>
			<li class="room">
				<a><div class="room-name">PvP</div></a>
			</li>
			<li class="room">
				<a><div class="room-name">Battle</div></a>
			</li>
			<li class="room">
				<a><div class="room-name">Dice</div></a>
			</li>
		</ul>
	</div>
	<div class="top">
		<table>
			<thead>
				<tr>
					<th>Место</th>
					<th>Пользователь</th>
					<th>Профит</th>
				</tr>
			</thead>
			<tbody id="top_jackpot"></tbody>
		</table>
	</div>
	<div class="top">
		<table>
			<thead>
				<tr>
					<th>Место</th>
					<th>Пользователь</th>
					<th>Профит</th>
				</tr>
			</thead>
			<tbody id="top_double"></tbody>
		</table>
	</div>
	<div class="top">
		<table>
			<thead>
				<tr>
					<th>Место</th>
					<th>Пользователь</th>
					<th>Профит</th>
				</tr>
			</thead>
			<tbody id="top_pvp"></tbody>
		</table>
	</div>
	<div class="top">
		<table>
			<thead>
				<tr>
					<th>Место</th>
					<th>Пользователь</th>
					<th>Профит</th>
				</tr>
			</thead>
			<tbody id="top_battle"></tbody>
		</table>
	</div>
	<div class="top">
		<table>
			<thead>
				<tr>
					<th>Место</th>
					<th>Пользователь</th>
					<th>Профит</th>
				</tr>
			</thead>
			<tbody id="top_dice"></tbody>
		</table>
	</div>
</div>
<script>
$(document).ready(function() {
	$.ajax({
		url : '/topAjax',
		type : 'get',
		success : function(data) {
			var jackpot = '';
			var double = '';
			var pvp = '';
			var battle = '';
			var dice = '';
			var j = 1, s = 1, d = 1, b = 1, c = 1;
			data.jackpot.forEach(function (top) {
				jackpot += '<tr>';
				jackpot += '<td>' + j++ + '</td>';
				jackpot += '<td>' + top.username + '</td>';
				jackpot += '<td>' + top.total + ' <i class="fas fa-coins"></i></td>';
				jackpot += '</tr>';
			});
			data.double.forEach(function (top) {
				double += '<tr>';
				double += '<td>' + s++ + '</td>';
				double += '<td>' + top.username + '</td>';
				double += '<td>' + top.total + ' <i class="fas fa-coins"></i></td>';
				double += '</tr>';
			});
			data.flip.forEach(function (top) {
				pvp += '<tr>';
				pvp += '<td>' + d++ + '</td>';
				pvp += '<td>' + top.username + '</td>';
				pvp += '<td>' + top.total + ' <i class="fas fa-coins"></i></td>';
				pvp += '</tr>';
			});
			data.battle.forEach(function (top) {
				battle += '<tr>';
				battle += '<td>' + b++ + '</td>';
				battle += '<td>' + top.username + '</td>';
				battle += '<td>' + top.total + ' <i class="fas fa-coins"></i></td>';
				battle += '</tr>';
			});
			data.dice.forEach(function (top) {
				dice += '<tr>';
				dice += '<td>' + c++ + '</td>';
				dice += '<td>' + top.username + '</td>';
				dice += '<td>' + top.total + ' <i class="fas fa-coins"></i></td>';
				dice += '</tr>';
			});
			$('#top_jackpot').html(jackpot);
			$('#top_double').html(double);
			$('#top_pvp').html(pvp);
			$('#top_battle').html(battle);
			$('#top_dice').html(dice);
		},
		error : function(data) {
			console.log(data.responseText);
		}
	});
});
</script>
@endsection