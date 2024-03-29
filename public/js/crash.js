jQuery(function($){
$(document).ready(function() {
	let canvas = document.getElementById('crashChart'),
		ctx    = canvas.getContext('2d')

    this.socket = socket;

	/*Chart.pluginService.register({
		afterDraw: function(chart) {
			var ctx2 = chart,
				max = ctx2.chartArea.left-5,
				width = ctx2.width,
				height = ctx2.height - 10;
			ctx.save(),
			ctx.globalCompositeOperation = "destination-over";
			var lr = Math.round((width - 6) / 83.5) + 1,
				td = Math.round((height - 1) / 82.5) + 1;
			ctx.lineWidth = .5,
			ctx.strokeStyle = "#465166";
			for (var s = 0; s < lr; s++) {
				var c = max + 6 + 83 * s;
				ctx.beginPath(),
				ctx.setLineDash([4, 3]),
				0 === s && ctx.setLineDash([]),
				ctx.moveTo(c, 0),
				ctx.lineTo(c, height),
				ctx.stroke(),
				ctx.closePath()
			}
			for (var u = 0; u < td; u++) {
				var h = height - (88.8 * u + (u + 1 === td ? 1 : 0)),
					l = width - 6 - .5 - 9;
				ctx.beginPath(),
				ctx.setLineDash([4, 3]),
				0 === u && ctx.setLineDash([]),
				ctx.moveTo(max + 6, h),
				ctx.lineTo(l + max, h),
				ctx.stroke(),
				ctx.closePath()
			}
			ctx.globalCompositeOperation = "source-over",
			ctx.restore()
		}
	});*/

	let shadowLine = Chart.controllers.line.extend({
		initialize: function () {
			Chart.controllers.line.prototype.initialize.apply(this, arguments)

			var ctx = this.chart.ctx
			var originalStroke = ctx.stroke
			ctx.stroke = function () {
				ctx.save()
				//ctx.shadowColor = 'rgba(0,0,0,0.3)'
				ctx.shadowOffsetX = 0
				ctx.shadowOffsetY = 0
				ctx.shadowBlur = 0
				originalStroke.apply(this, arguments)
				ctx.restore()
			}
		}
	})
	Chart.controllers.shadowLine = shadowLine

	let chart = new Chart(ctx, {
		type: 'shadowLine',
		data: {
			labels: [0],
			datasets: [{
				label: '',
				backgroundColor: 'rgba(204,255,209, 0.0)', /// BACKGROUND LINHA
				borderColor: '#00000000', /// BORDA DA LINHA
				borderWidth: 0,
                pointRadius: 0,
				data: [0],
			}]
		},
		options: {
			animation: false,
			title: {
				display: false
			},
			legend: {
				display: false,
			},
			layout: {
				padding: {
					left: 7
				}
			},
			scales: {
				xAxes: [{
					gridLines: {
						display: false,
					},
					ticks: {
						min: 1,
						stepSize: 1,
						display: false,
					}
				}],
				yAxes: [{
					gridLines: {
						display: false,
					},
					ticks: {
						beginAtZero:true,
						//padding: 10,
						min: 1,
						max: 2,
						stepSize: 0.3,
						fontSize: 15,
						fontStyle: 600,
						fontFamily: "'Open Sans', sans-serif",
						fontColor: '#828f9a',
						callback: function(value, index, values) {
							if(value != '' && value.toFixed(1) == 1) return 0;
							if(!(index % parseInt(values.length / 5))) {
								return 'x' + value.toFixed(1);
						  	}
						}
					}
				}]
			}
		}
	})


    this.resetPlot = () => {
		chart.data.labels = [0];
		chart.data.datasets[0].data = [0];
		chart.options.scales.yAxes[0].ticks.max = 2;
		//chart.data.datasets[0].backgroundColor = 'rgba(204,255,209, 0.65)';
		chart.update();
        var div = document.getElementById("crashChart");
        div.style.backgroundImage = `url(#)`
    }

    this.socket.on('crash', async res => {
        if(res.type == 'bet') this.publishBet(res);
        if(res.type == 'timer') this.publishTime(res);
        if(res.type == 'slider') this.parseSlider(res);
        if(res.type == 'game') this.reset(res);
    });

    var email = $('meta[name="email"]').attr('content');

    this.socket.on('connect', async res => {
        sockets.to('crash').emit('init', {
            game: 'crash',
            email: email
          });
    });

    let color = "#4a1179"; //// cor do res.value
    this.publishTime = (res) => {
        var img = $('<img>').attr('src', "/img/loading11.png")/////// IMAGEM JOGO INICIANDO
                                .css('height', "100px")
                                .css('width', "100px")
                                .css({
                                    "display":"block",
                                    "margin-bottom":"1px",
                                });
        $('#chartInfo').html(img);
        $('#chartInfo').css('color', '#646565').append('Iniciando em  <span style="color:'+ color +'">' + res.value + '</span> segundos.');


        var div = document.getElementById("crashChart");
        div.style.backgroundImage = `url(#)`/////////////// RESET GAME INICIANDO..

    }

    this.publishBet = (res) => {
        let html = '';
        for(var i in res.bets)
        {
            let bet = res.bets[i];
            //html += '<tr><td class="username"><button type="button" class="btn btn-link" data-id="'+ bet.user.unique_id +'"><span class="sanitize-user"><div class="sanitize-avatar"><img src="'+ bet.user.avatar +'" alt=""></div><span class="sanitize-name">'+ bet.user.username +'</span></span></button></td><td><div class="bet-number">R$ <span class="bet-wrap"><span>' + bet.price +'</span></div></td><td>'+ bet.withdraw+'x</td><td>';
            if(bet.status == 0) html += '<tr><td class="username"><button type="button" class="btn btn-link" data-id="'+ bet.user.unique_id +'"><span class="sanitize-user"><div class="sanitize-avatar"><img src="'+ bet.user.avatar +'" alt=""></div><span class="sanitize-name">'+ bet.user.username +'</span></span></button></td><td><div class="bet-number">R$ <span class="bet-wrap"><span>' + bet.price +'</span></div></td><td>~</td><td>';
            if(bet.status == 1) html += '<tr><td class="username"><button type="button" class="btn btn-link" data-id="'+ bet.user.unique_id +'"><span class="sanitize-user"><div class="sanitize-avatar"><img src="'+ bet.user.avatar +'" alt=""></div><span class="sanitize-name">'+ bet.user.username +'</span></span></button></td><td><div class="bet-number">R$ <span class="bet-wrap"><span>' + bet.price +'</span></div></td><td>'+ bet.withdraw+'x</td><td>';

            if(bet.status == 1) html += '<span class="bet-wrap win">R$ <span>' + bet.won +'</span></span>';
            if(bet.status == 0) html += '<span class="bet-wrap wait"><svg class="icon"><use xlink:href="/img/symbols.svg#icon-time"></use></svg></span>';
            html += '</td></tr>';
        }
        $('#bets').html(html);
    }

    this.reset = (res) => {
        $('#bets').html('');
        $('#chartInfo').css('color', '#646565').text('Conectando...');
        this.resetButton(false);
        this.resetPlot();
		$('#gameId').text(res.id);
        $('.hash .text').text(res.hash);
		$('.game-history .item').addClass('isAnimate');
        let html = '';
        for(var i in res.history) html += '<div class="item isAnimate checkGame" data-hash="'+res.history[i].hash+'"><div class="item-bet" style="color: '+res.history[i].color+';">x'+res.history[i].multiplier+'</div></div>';
		if($('.game-history .item').length >= 10) $('.game-history .item:nth-child(10)').remove();
        $('.game-history').html(html);
		setTimeout(function() {
			$('.game-history .item').removeClass('isAnimate');
		}, 1000);
    }




    function randomUrl(urlValues) {
        var url = urlValues[Math.floor(Math.random()*urlValues.length)];
        return url;
    }

    var urlOptions = ["/img/crashou2.svg", "/img/crashou3.svg", ];
    console.log(randomUrl(urlOptions));



        this.parseSlider = (res) => {
        chart.data.labels = res.label;
        chart.data.datasets[0].data = res.data;
        chart.options.scales.yAxes[0].ticks.max = Math.max.apply(2, res.data) + 1;
        chart.update();


        var div = document.getElementById("crashChart");
        div.style.backgroundImage = `url(/img/fundocrash.svg)`;/////////////////JOGO RODANDO
        var img = $('<img>').attr('src', "/img/foguetesubindo.gif")/////////////////JOGO RODANDO
                                .css('height', "156px")
                                .css('width', "156px")
                                .css({
                                    "display":"block",
                                    "margin-bottom":"1px",
                                });
                                $(img).on('load', function(){
                            $('.foguete').css({'margin-top': -150 + 'px', 'margin-left': 150 + 'px'});
                        });
        $('#chartInfo').html(img);
        $('#chartInfo').append(((res.crashed) ? 'Explodiu ' : '') + 'x' + res.float.toFixed(2));////MULTIPLICADOR DO JOGO ATUAL SUBINDO




        if(res.crashed)
        {
            var div = document.getElementById("crashChart");
            div.style.backgroundImage = `url(${randomUrl(urlOptions)})`;/////////////////JOGO CRASHOU

            var img = $('<img>').attr('src', "/img/explosion.gif")/////////////////JOGO CRASHOU
                                .css('height', "156px")
                                .css('width', "156px")
                                .css({
                                    "display":"block",
                                    "margin-bottom":"1px",
                                    "margin-left":"80px"
                                });
        $('#chartInfo').html(img);
        $('#chartInfo').append(((res.crashed) ? 'Explodiu ' : '') + 'x' + res.float.toFixed(2));//// CRASHOU > FRASE CRASHOU E MULTIPLICADOR
		//chart.data.datasets[0].backgroundColor = 'rgba(204,255,209, 0.65)';
			chart.update();
            /*audio.play();*/
            $('#chartInfo').css({
                'transition' : 'color 200ms ease',
                'color' : '#4a1179'
            });
            $('.btn-withdraw span').text('Retirar R$ ' + parseFloat(window.bet*parseFloat(res.float.toFixed(2))).toFixed(2));
        } else {
            if(!window.isCashout && window.withdraw > 0) $('.btn-withdraw span').text('Retirar R$ ' + parseFloat(window.bet*parseFloat(res.float.toFixed(2))).toFixed(2));
            if(res.float >= window.withdraw && !window.isCashout)
            {
                window.isCashout = true;
                $('.btn-withdraw').click();
            }
            $('#chartInfo').css({
                'transition' : 'color 200ms ease',
                'color' : res.color
            });
        }

    }

    this.notify = (r) => {
        $.notify({
            position : 'bottom-left',
            type: (r.success) ? 'success' : 'error',
            message: r.msg
        });
    }

    this.resetButton = result => {
        if(result) {
            $('.btn-play').hide();
            $('.btn-withdraw').show();
        } else {
            $('.btn-withdraw').hide();
            $('.btn-play').show();
        }
    }

    $('.btn-play').click(() => {
        let betoutValue = parseFloat($('#betout').val()) || 0;
        console.log(betoutValue);

        $.ajax({
            url : '/crash/newBet',
            type : 'post',
            data : {
                bet : parseFloat($('#sum').val()) || 0,
                withdraw : betoutValue,
                balType: localStorage.getItem('balance')
            },
            success : res => {
                this.notify(res);
                if(res.success)
                {
                    window.bet = res.bet;
                    $('.btn-withdraw span').text('Retirar ' + (window.bet).toFixed(2));
                    this.resetButton(true);
                    window.withdraw = parseFloat($('#betout').val()) || 9999;
                    window.isCashout = false;
                }
            },
            error : err => {
                console.log('New error');
                console.log(err.responseText);
            }
        });
    });

    $('.btn-withdraw').click(() => {
        // window.isCashout = true;
        $.ajax({
            url : '/crash/cashout',
            type : 'post',
            success : res => {
                this.notify(res);
                if(res.success)
                {
                    this.resetButton(false);
                    $('.btn-withdraw span').text('Retirar');
                }
            },
            error : err => {
                this.notify({
                    success : false,
                    msg : 'Algo deu errado...'
                });
                console.log(err.responseText);
            }
        })
    });

    $('a[data-method="last"]').click(() => {
        $.ajax({
            url : '/crash/last',
            type : 'post',
            success : b => $('#sum').val(b),
            error : e => $('#sum').val(0)
        });
    });

	$('#betout').on('input change', function() {
		$('.input-suffix span').text($(this).val());
	});
});

function getXAxisLabel(value) {
    try {
        var xMin = chart.options.scales.yAxes[0].ticks.min;
    } catch(e) {
        var xMin = undefined;
    }
    if (xMin === value) {
        return '';
    } else {
		chart.data.labels = value;
		chart.update();
    }
}
});