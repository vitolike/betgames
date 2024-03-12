@extends('layout')

@section('content')
<div class="head-game">
	<span class="game-name">Топ</span>
</div>
<div class="cont-b">
	<div class="second-title"><span>Топ партнеров</span></div>
	<div class="top">
		<table>
			<thead>
				<tr>
					<th>Место</th>
					<th>Пользователь</th>
					<th>Заработано</th>
				</tr>
			</thead>
			<tbody id="top"></tbody>
		</table>
	</div>
</div>
<script>
$(document).ready(function() {
	$.ajax({
		url : '/topPartnersAjax',
		type : 'get',
		success : function(data) {
			var html = '';
			var i = 1;
			data.forEach(function (top) {
				html += '<tr>';
				html += '<td>' + i++ + '</td>';
				html += '<td>' + top.username + '</td>';
				html += '<td>' + Math.floor(top.ref_money_history) + ' <i class="fas fa-coins"></i></td>';
				html += '</tr>';
			});
			$('#top').html(html);
		},
		error : function(data) {
			console.log(data.responseText);
		}
	});
});
</script>
@endsection