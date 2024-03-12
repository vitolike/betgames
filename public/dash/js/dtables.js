"use strict";
var KTDatatablesData = function() {

	var initTable1 = function() {
		var table = $('#dtable');

		// begin first table
		table.DataTable({
			responsive: true,
			searchDelay: 500,
			"language": {
				  "processing": "Carregando...",
				  "search": "Procurar:",
				  "lengthMenu": "Mostrar _MENU_ registros",
				  "info": "Mostrando _START_ a _END_ de _TOTAL_ no total.",
				  "infoEmpty": "Mostrando de 0 a 0 de 0 no total.",
				  "infoFiltered": "(отфильтровано de _MAX_ no total.)",
				  "infoPostFix": "",
				  "loadingRecords": "Conectando... no total....",
				  "zeroRecords": "Записи отсутствуют.",
				  "emptyTable": "Não há dados  nessa lista ainda.",
				  "paginate": {
					"first": "Первая",
					"previous": "Pagina Anterior",
					"next": "Proxima Página",
					"last": "Последняя"
				  },
				  "aria": {
					"sortAscending": ": RECEBER BÔNUS для сортировки столбца по возрастанию",
					"sortDescending": ": RECEBER BÔNUS для сортировки столбца по убыванию"
				  }
			}
		});
	};
	var initTable2 = function() {
		var table = $('#dtable2');

		// begin first table
		table.DataTable({
			responsive: true,
			searchDelay: 500,
			"language": {
				  "processing": "Carregando...",
				  "search": "Procurar:",
				  "lengthMenu": "Mostrando _MENU_ no total.",
				  "info": "Mostrando _START_ a _END_ de _TOTAL_ no total.",
				  "infoEmpty": "Mostrando de 0 a 0 de 0 no total.",
				  "infoFiltered": "(отфильтровано de _MAX_ no total.)",
				  "infoPostFix": "",
				  "loadingRecords": "Conectando... no total....",
				  "zeroRecords": "Записи отсутствуют.",
				  "emptyTable": "Não há dados  nessa lista ainda.",
				  "paginate": {
					"first": "Первая",
					"previous": "Pagina Anterior",
					"next": "Proxima Página",
					"last": "Последняя"
				  },
				  "aria": {
					"sortAscending": ": RECEBER BÔNUS для сортировки столбца по возрастанию",
					"sortDescending": ": RECEBER BÔNUS для сортировки столбца по убыванию"
				  }
			}
		});
	};

	return {

		//main function to initiate the module
		init: function() {
			initTable1();
			initTable2();
		},

	};

}();

jQuery(document).ready(function() {
	KTDatatablesData.init();
});
