jQuery(function($){
    $(document).ready(function() {
        $(document).on('click', '.confirm-bet', function() {
            $.ajax({
                url: '/double/newBet',
                type: 'post',
                data: {
                    balance: localStorage.getItem('balance') || 'balance',
                    color: $('.color-selector .isActive').data('color'),
                    sum: $('#sum').val()
                },
                success: function(data) {
                    $.notify({
                        type: data.type,
                        message: data.msg
                    });
                }
            })
        });

        $(document).on('click', '.color-selector .label', function() {
            if(!$(this).hasClass('isActive')) {
                $('.color-selector .label').removeClass('isActive');
            
                $(this).addClass('isActive');

                if($(this).hasClass('red')) {
                    $(this).css('outline-color', '#01ff5f');
                    $('.black').css('outline-color', 'black');
                    $('.white').css('outline-color', '#fff');
                }

                
                if($(this).hasClass('black')) {
                    $(this).css('outline-color', '#01ff5f');
                    $('.white').css('outline-color', 'white');
                    $('.red').css('outline-color', '#f12c4c');
                }

                if($(this).hasClass('white')) {
                    $(this).css('outline-color', '#01ff5f');
                    $('.black').css('outline-color', '#000');
                    $('.red').css('outline-color', '#f12c4c');
                }
            }
        });

        socket.on('double', function(data) {
            if(data.type == 'bets') parseBets(data.bets);
            if(data.type == 'timer') {

                $('.progress p').html('RODANDO EM ' + (parseFloat(data.time) * 10).toFixed(2) + 's');
                $('.progress .scroll').css('width', (((data.time*10)/15) * 100) + '%');
            }
            if(data.type == 'slider') {

                $('.picker').css('transition-duration', '5000ms');
                $('.picker').css('transform', 'translateX(-' + ((2178 + ((104 * 15) * 4)) + data.slider.rotate ) + 'px)');

                $('.progress .scroll').css('width', '10000px');
                $('.progress p').html('GIRANDO');

                setTimeout(() => {
                    $('.picker').css('transition-duration', '10ms');
                    $('.picker').css('transform', 'translateX(-' + ((2178 + ((104 * 15) * 4)) + (data.slider.rotate - data.slider.random) ) + 'px)');

                    $('.progress p').html('ESPERANDO PRÓXIMA RODADA');
                }, 7000);
                
            }
            if(data.type == 'newGame') {
                
                $('.picker').css('transition-duration', '10ms');
                $('.picker').css('transform', 'translateX(-2178px)');

                $('.white-history .history-users').html('');
                $('.red-history .history-users').html('');
                $('.black-history .history-users').html('');

                $('.white-history .history-status .amount').html('R$ 0');
                $('.black-history .history-status .amount').html('R$ 0');
                $('.red-history .history-status .amount').html('R$ 0');

                $('.white-history .history-status .apostas').html('0 Apostas Totais');
                $('.black-history .history-status .apostas').html('0 Apostas Totais');
                $('.red-history .history-status .apostas').html('0 Apostas Totais');
                
                $('.history-items').prepend(`<div class="item ${ data.history.color }"> <span class="inside">${ data.history.number }</span> </div>`);
            }
        });
    });

    function parseBets(bets) {
        var list_white = [];
        var list_red = [];
        var list_black = [];

        var amount_white = 0;
        var amount_red = 0;
        var amount_black = 0;

        var bets_white = 0;
        var bets_red = 0;
        var bets_black = 0;

        for(var i in bets) {
            let bet = bets[i];

            if(bet.color == 'white') {
                list_white += `
                    <div class="history-user" data-userid="${ bet.user_id }">
                        <div class="rank-name">
                            <div class="avatar"><img src="${ bet.avatar }" alt=""></div>
                            <div class="name">${ bet.username }</div>
                        </div>
                        <div>R$ ${bet.sum}</div>
                    </div>
                `;
            };

            if(bet.color == 'red') {
                list_red += `
                    <div class="history-user" data-userid="${ bet.user_id }">
                        <div class="rank-name">
                            <div class="avatar"><img src="${ bet.avatar }" alt=""></div>
                            <div class="name">${ bet.username }</div>
                        </div>
                        <div>R$ ${bet.sum}</div>
                    </div>
                `;
            };

            if(bet.color == 'black') {
                list_black += `
                    <div class="history-user" data-userid="${ bet.user_id }">
                        <div class="rank-name">
                            <div class="avatar"><img src="${ bet.avatar }" alt=""></div>
                            <div class="name">${ bet.username }</div>
                        </div>
                        <div>R$ ${bet.sum}</div>
                    </div>
                `;
            };

            if(bet.color == 'white') {
                $('.white-history .history-users').html(list_white);
                amount_white += bet.sum;
                bets_white ++;
            }

            if(bet.color == 'red') {
                $('.red-history .history-users').html(list_red);
                amount_red += bet.sum;
                bets_red ++;
            
            }

            if(bet.color == 'black') {
                $('.black-history .history-users').html(list_black);
                amount_black += bet.sum;
                bets_black ++;
            }


        }

        $('.white-history .history-status .amount').html('R$ ' + amount_white.toFixed (2));
        $('.black-history .history-status .amount').html('R$ ' + amount_black.toFixed (2));
        $('.red-history .history-status .amount').html('R$ ' + amount_red.toFixed (2));

        $('.white-history .history-status .apostas').html(bets_white + ' Apostas Totais');
        $('.black-history .history-status .apostas').html(bets_black + ' Apostas Totais');
        $('.red-history .history-status .apostas').html(bets_red + ' Apostas Totais');

    }
});