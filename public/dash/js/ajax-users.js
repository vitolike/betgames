"use strict";
var KTDatatablesDataSourceAjaxServer = function () {

    var initTable1 = function () {
        var table = $('#ajax-users');

        // begin first table
        table.DataTable({
            responsive: true,
            searchDelay: 500,
            processing: true,
            serverSide: true,
            ajax: {
                url: "/admin/usersAjax",
                type: "POST"
            },
            columns: [
                {data: "id", searchable: true},
                {data: "username", visible: false, searchable: true},
                {
                    data: "username", searchable: false,
                    render: function (data, type, row) {
                        return '<img src="' + row.avatar + '" style="width:26px;border-radius:50%;margin-right:10px;vertical-align:middle;">' + data;
                    }

                },
                {
                    data: "balance", searchable: false,
                    render: function (data, type, row) {
                        return 'R$ ' + data;
                    }

                },
                {
                    data: "bonus", searchable: false,
                    render: function (data, type, row) {
                        return 'R$ ' + data;
                    }

                },
                /*
                {
                    data: null, searchable: false, orderable: false,
                    render: function (data, type, row) {
                        return '<a href="https://vk.com/id' + row.user_id + '" target="_blank">* REDIRECIONAR AO SITE</a>';
                    }

                },*/
                {
                    data: null, searchable: false, orderable: false,
                    render: function (data, type, row) {
                        if (row.is_admin) return '<span class="kt-font-bold kt-font-danger">Administrador</span>';
                        if (row.is_moder) return '<span class="kt-font-bold kt-font-success">Moderador</span>';
                        if (row.is_youtuber) return '<span class="kt-font-bold kt-font-primary">YouTuber</span>';
                        return 'Usuario';
                    }

                },
                {
                    data: "ip", searchable: true, orderable: false,
                    render: function (data, type, row) {
                        return data;
                    }

                },
                {
                    data: "ban", searchable: false, orderable: true,
                    render: function (data, type, row) {
                        if (data) return '<span class="kt-badge kt-badge--danger kt-badge--inline kt-badge--pill">SIM</span>';
                        return '<span class="kt-badge kt-badge--success kt-badge--inline kt-badge--pill">Não</span>';
                    }

                },
                {
                    data: null, searchable: false, orderable: false,
                    render: function (data, type, row) {
                        return '<a href="/admin/user/' + row.id + '" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Editar"><i class="la la-edit"></i></a>';
                    }
                }
            ],
            "language": {
                "processing": "Carregando...",
                "search": "Procurar:",
                "lengthMenu": "Mostrar _MENU_ registros",
                "info": "Mostrando _START_ de _END_ no total de _TOTAL_",
                "infoEmpty": "Mostrando 0 a 0 de 0 no total.",
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
        init: function () {
            initTable1();
        },

    };

}();

jQuery(document).ready(function () {
    KTDatatablesDataSourceAjaxServer.init();
});
