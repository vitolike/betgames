process.env['NODE_TLS_REJECT_UNAUTHORIZED'] = 0;
let fs 				= require('fs'),
	config 			= require('./config.js'),
	app             = require('express')(),
	server,
	getProtocolOptions = () => config.https ? {
		protocol: 'https',
		protocolOptions: {
			key: fs.readFileSync(config.ssl.key),
			cert: fs.readFileSync(config.ssl.cert),
			requestCert: true,
   		    rejectUnauthorized: false
		}
	} : {
		protocol: 'http'
	},
	options 		= getProtocolOptions(),
	fakeStatus		= 0;
	

if(options.protocol == 'https') server = require('https').createServer(options.protocolOptions, app);
else server = require('http').createServer(app);

let	io              = require('socket.io')(server),
	redis 			= require('redis'),
        redisClient 	        = redis.createClient(),
	requestify 		= require('requestify'),
	acho 			= require('acho'),
	log 			= acho({
		upper: true
	}),
	online 			= 0,
	ipsConnected	= []

	redisClient.on("error", function(err) {
	assert(err instanceof Error);
	assert(err instanceof AbortError);
	assert(err instanceof AggregateError);
	
	// The set and get are aggregated in here
	assert.strictEqual(err.errors.length, 2);
	assert.strictEqual(err.code, "NR_CLOSED");
});
server.listen(config.port);
options.protocol = 'http';
log.info('Servidor ligado com sucesso '+ options.protocol + '://' + config.domain + ':' + config.port);

io.sockets.on('connection', function(socket) {
	var address = socket.handshake.address;
	if(!ipsConnected.hasOwnProperty(address)) {
		ipsConnected[address] = 1;
		online = online + 3;
	}
	updateOnline(online);
    socket.on('disconnect', function() {
		if(ipsConnected.hasOwnProperty(address)) {
			delete ipsConnected[address];
			online = online - 3;
		}
		updateOnline(online);
	});

	socket.on('init', function (init) {
		if (!init) return;
		//console.log(init)
		if (init.game === 'double') socket.join('double');
		if (init.game === 'wheel') socket.join('wheel');
		if (init.game === 'crash') socket.join('crash');
	});
});

let doubleRunning = false;

function updateOnline(online) {
    requestify.post(options.protocol + '://' + config.domain + '/api/getOnline')
    .then(function(response) {
            var res = JSON.parse(response.body);

            if(res && res > 0) io.sockets.emit('online', online+res);
    },function(response){
        log.error('Erro  [getOnline]');
    });
} 


redisClient.subscribe('message');
redisClient.subscribe('chat.clear');
redisClient.subscribe('new.msg');
redisClient.subscribe('del.msg');
redisClient.subscribe('ban.msg');
redisClient.subscribe('updateBalance');
redisClient.subscribe('updateBalanceAfter');
redisClient.subscribe('updateBonus');
redisClient.subscribe('updateBonusAfter');
redisClient.subscribe('wheel');
redisClient.subscribe('double');
//redisClient.subscribe('jackpot.timer');
//redisClient.subscribe('jackpot.slider');
//redisClient.subscribe('jackpot');
redisClient.subscribe('crash');
redisClient.subscribe('new.flip');
redisClient.subscribe('end.flip');
redisClient.subscribe('battle.newBet');
redisClient.subscribe('battle.startTime');
redisClient.subscribe('dice');
redisClient.subscribe('bonus');
redisClient.subscribe('giveaway');
redisClient.subscribe('giveaway.newGiveaway');
//redisClient.subscribe('hilo.newBet');
//redisClient.subscribe('hilo.timer');
redisClient.on('message', function(channel, message) {
	if(channel == 'chat.clear') io.sockets.emit('clear', JSON.parse(message));
	if(channel == 'new.msg') io.sockets.emit('chat', JSON.parse(message));
	if(channel == 'del.msg') io.sockets.emit('chatdel', JSON.parse(message));
	if(channel == 'ban.msg') io.sockets.emit('ban_message', JSON.parse(message));
	if(channel == 'updateBalanceAfter') {
		message = JSON.parse(message);
		setTimeout(function() {
			io.sockets.emit('updateBalance', message);
		}, message.timer*1000);
	}
	if(channel == 'updateBonusAfter') {
		message = JSON.parse(message);
		setTimeout(function() {
			io.sockets.emit('updateBonus', message);
		}, message.timer*1000);
	}
	/*if(channel == 'jackpot.timer') {
		message = JSON.parse(message);
		startJackpotTimer(message);
		return;
	}*/
	if(channel == 'giveaway' && JSON.parse(message).type == 'newGiveaway') {
		message = JSON.parse(message);
		io.sockets.emit('giveaway', {
			type: 'new',
			data: message.data
		});
		startGiveawayTimer(message.data);
		return;
	}
	if(channel == 'battle.startTime') {
		message = JSON.parse(message);
        startBattleTimer(message.time);
        return;
    }
	if(channel == 'wheel' && JSON.parse(message).type == 'wheel_timer') {
		message = JSON.parse(message);
        startWheelTimer(message.timer[2]);
		return;
    }

	if(channel == 'double' && JSON.parse(message).type == 'double_timer') {
		message = JSON.parse(message);
        if(!doubleRunning) {
			doubleRunning = true;
			startDoubleTimer(message.timer[2]);
		}
		return;
    }
	if(typeof message == 'string') return io.sockets.emit(channel, JSON.parse(message));
	io.sockets.emit(channel, message);
});

/* Jackpot */
/*
var currentTimers = [];
function startJackpotTimer(res) {
	if(typeof currentTimers[res.room] == 'undefined') currentTimers[res.room] = 0;
	if(currentTimers[res.room] != 0 && (currentTimers[res.room] - new Date().getTime()) < ((res.time+1)*1000)) return;
	currentTimers[res.room] = new Date().getTime();
	let time = res.time;
	let timer = setInterval(() => {
		if(time == 0) {
			clearInterval(timer);
			io.sockets.emit('jackpot', {
				type: 'timer',
				room: res.room,
				data: {
					min: Math.floor(time/60),
					sec: time-(Math.floor(time/60)*60)
				}
			});
			currentTimers[res.room] = 0;
			showJackpotSlider(res.room, res.game);
			return;
		}
		time--;
		io.sockets.emit('jackpot', {
			type: 'timer',
			room: res.room,
			data: {
				min: Math.floor(time/60),
				sec: time-(Math.floor(time/60)*60)
			}
		});
	}, 1*1000)
}*/

/*function showJackpotSlider(room, game) {
	requestify.post(options.protocol + '://' + config.domain + '/api/jackpot/slider', {
		room: room,
		game: game
	})
    .then(function(res) {
		let timeout = setTimeout(() => {
			clearInterval(timeout);
			newJackpotGame(room);
		}, 12*1000)
    }, function(res) {
		log.error('Erro na função slider');
    });
}*/

/*function newJackpotGame(room) {
	requestify.post(options.protocol + '://' + config.domain + '/api/jackpot/newGame', {
        room : room
    })
    .then(function(res) {
        res = JSON.parse(res.body);
		io.sockets.emit('jackpot', {
			type: 'newGame',
			room: room,
			data: res
		});
    }, function(res) {
		log.error('[ROOM '+room+'] Erro na função newGame');
    });
}*/

/*function getStatusJackpot(room) {
	requestify.post(options.protocol + '://' + config.domain + '/api/jackpot/getGame', {
        room : room
    })
	.then(function(res) {
		res = JSON.parse(res.body);
		if(res.status == 1) startJackpotTimer(res);
		if(res.status == 2) showJackpotSlider(res.room, res.game);
		if(res.status == 3) newJackpotGame(res.room);
	}, function(res) {
		log.error('[ROOM '+room+'] Erro na função getStatusJackpot');
	});
}*/

/*requestify.post(options.protocol + '://' + config.domain + '/api/jackpot/getRooms')
.then(function(res) {
	rooms = JSON.parse(res.body);
	rooms.forEach(function(room) {
		getStatusJackpot(room.name);
	});
}, function(res) {
	log.error(res.body);
	log.error('[APP] Erro na função getRooms');
});*/

/* Wheel */
function startWheelTimer(time) {
 	let timer = setInterval(() => {
 		if(time == 0) {
 			clearInterval(timer);
 			io.sockets.emit('wheel', {
 				type: 'timer',
 				min: Math.floor(time/60),
 				sec: time-(Math.floor(time/60)*60)
 			});
 			showWheelSlider();
 			return;
 		}
 		time--;
 		io.sockets.to('wheel').emit('wheel', {
			type: 'timer',
 			min: Math.floor(time/60),
 			sec: time-(Math.floor(time/60)*60)
 		});
 	}, 1*1000)
}

function startDoubleTimer(time) {
	time = time * 100;
	let timer = setInterval(() => {
		if(time <= 0) {
			clearInterval(timer);
			io.sockets.to('double').emit('double', {
				type: 'timer',
				time: time/1000
			});
			showDoubleSlider();
			doubleRunning = false;
			return;
		}
		time -= 5;
		io.sockets.to('double').emit('double', {
			type: 'timer',
			time: time/1000
		});
	}, 50)
}

function showWheelSlider() {
 	requestify.post(options.protocol + '://' + config.domain + '/api/wheel/slider')
     .then(function(res) {
         res = JSON.parse(res.body);
 		setTimeout(() => {
             newWheelGame();
         }, res.time);
     }, function(res) {
 		setTimeout(() => {
 			showWheelSlider()
 		}, 5000);
 		log.error('Erro na função wheelSlider 1',JSON.stringify(res));
		console.log( JSON.stringify(res) );
     });
}

function newWheelGame() {
	requestify.post(options.protocol + '://' + config.domain + '/api/wheel/newGame')
    .then(function(res) {
        res = JSON.parse(res.body);
    }, function(res) {
		log.error('Erro na função wheelNewGame');
    });
}

requestify.post(options.protocol + '://' + config.domain + '/api/wheel/getGame')
.then(function(res) {
	res = JSON.parse(res.body);
	if(res.status == 1) startWheelTimer(res.timer[2]);
	if(res.status == 2) startWheelTimer(res.timer[2]);
	if(res.status == 3) newWheelGame();
}, function(res) {
	log.error('Erro wheelGetGame');
});

function showDoubleSlider() {
	requestify.post(options.protocol + '://' + config.domain + '/api/double/slider')
    .then(function(res) {
        res = JSON.parse(res.body);
		setTimeout(() => {
            newDoubleGame();
        }, res.time);
    }, function(res) {
		setTimeout(() => {
			showDoubleSlider()
		}, 5000);
		log.error('Erro na função wheelSlider 2',JSON.stringify(res));
		console.log( JSON.stringify(res) );
    });
}

function newDoubleGame() {
	requestify.post(options.protocol + '://' + config.domain + '/api/double/newGame')
    .then(function(res) {
		//console.log(res)
        res = JSON.parse(res.body);
    }, function(res) {
		log.error('Erro na função wheelNewGame');
    });
}

requestify.post(options.protocol + '://' + config.domain + '/api/double/getGame')
.then(function(res) {
	res = JSON.parse(res.body);
	if(res.status == 1) startDoubleTimer(res.timer[2]);
	if(res.status == 2) newDoubleGame();
	if(res.status == 3) newDoubleGame();
}, function(res) {

	log.error('Erro wheelGetGame');
});

/*Battle*/
function startBattleTimer(time) {
	setBattleStatus(1);
	let timer = setInterval(() => {
		if(time == 0) {
			clearInterval(timer);
			io.sockets.emit('battle.timer', {
				min: Math.floor(time/60),
				sec: time-(Math.floor(time/60)*60)
			});
			setBattleStatus(2);
			showBattleWinners();
			return;
		}
		time--;
		io.sockets.emit('battle.timer', {
			min: Math.floor(time/60),
			sec: time-(Math.floor(time/60)*60)
		});
	}, 1*1000)
}

function showBattleWinners() {
    requestify.post(options.protocol + '://' + config.domain + '/api/battle/getSlider')
    .then(function(res) {
        res = JSON.parse(res.body);
        io.sockets.emit('battle.slider', res);
		setBattleStatus(3);
		ngTimerBattle();
    }, function(res) {
        log.error('[BATTLE] Erro  getSlider');
		setTimeout(BattleShowWinners, 1000);
    });
}

function ngTimerBattle() {
	var ngtime = 6;
	var battlengtimer = setInterval(function() {
		ngtime--;
		if(ngtime == 0) {
			clearInterval(battlengtimer);
			newBattleGame();
		}
	}, 1000);
}

function newBattleGame() {
    requestify.post(options.protocol + '://' + config.domain + '/api/battle/newGame')
    .then(function(res) {
        res = JSON.parse(res.body);
        io.sockets.emit('battle.newGame', res);
    }, function(res) {
        log.error('[BATTLE] Erro newGame');
		setTimeout(newBattleGame, 1000);
    });
}

function setBattleStatus(status) {
    requestify.post(options.protocol + '://' + config.domain + '/api/battle/setStatus', {
		status : status
    })
    .then(function(res) {
        status = JSON.parse(res.body);
    }, function(res) {
        log.error('[BATTLE] Erro setStatus');
		setTimeout(setBattleStatus, 1000);
    });
}

requestify.post(options.protocol + '://' + config.domain + '/api/battle/getStatus')
.then(function(res) {
	res = JSON.parse(res.body);
	if(res.status == 1) startBattleTimer(res.time);
	if(res.status == 2) startBattleTimer(res.time);
	if(res.status == 3) newBattleGame();
}, function(res) {
	log.error('[BATTLE] Erro  getStatus');
});

/*
function HiloNewGame() {
    requestify.post(options.protocol + '://' + config.domain + '/api/hilo/newGame')
    .then(function(res) {
        res = JSON.parse(res.body);
        io.sockets.emit('hilo.newGame', res);
		HiloStartTimer(res.time);
    }, function(res) {
        log.error('[HILO] Erro na função newGame');
		setTimeout(HiloNewGame, 1000);
    });
}

function HiloStartTimer(times) {
	var preFinish = false;
	var hiloTimer,
		time = times*100;
	HiloSetStatus(1);
	clearInterval(hiloTimer);
    hiloTimer = null;
    hiloTimer = setInterval(function() {
		time--;
		if(time <= 0) {
			if(!preFinish) {
				clearInterval(hiloTimer);
				hiloTimer = null;
				preFinish = true;
				HiloSetStatus(2);
				HiloGetFlip();
			}
		}
        io.sockets.emit('hilo.timer', {
            total : times,
            time : 100-(time/100)
        });
    }, 10);
}

function HiloGetFlip() {
    requestify.post(options.protocol + '://' + config.domain + '/api/hilo/getFlip')
    .then(function(res) {
        res = JSON.parse(res.body);
        io.sockets.emit('hilo.getFlip', res);
		HiloSetStatus(3);
		setTimeout(HiloNewGame, 4500);
    }, function(res) {
        log.error('[HILO] Erro na função getFlip');
    });
}

// FairPlay статусов
requestify.post(options.protocol + '://' + config.domain + '/api/hilo/getStatus')
.then(function(res) {
	res = JSON.parse(res.body);
	if(res.status <= 1) HiloStartTimer(res.time);
	if(res.status == 2) HiloStartTimer(res.time);
	if(res.status == 3) HiloNewGame();
}, function(res) {
	log.error('[HILO] Erro na função getStatus');
});

function HiloSetStatus(status) {
    requestify.post(options.protocol + '://' + config.domain + '/api/hilo/setStatus', {
		status : status
    })
    .then(function(res) {
        status = JSON.parse(res.body);
    }, function(res) {
        log.error('[HILO] Erro na função setStatus');
		setTimeout(HiloSetStatus, 1000);
    });
}*/

function unBan() {
    requestify.post(options.protocol + '://' + config.domain + '/api/unBan')
    .then(function(res) {
        var data = JSON.parse(res.body);
        setTimeout(unBan, 60000);
    },function(response){
        log.error('Erro na função [unBan]');
        setTimeout(unBan, 1000);
    });
}

/*function getMerchBalance() {
    requestify.post(options.protocol + '://' + config.domain + '/api/getMerchBalance')
    .then(function(response) {
        var balance = JSON.parse(response.body);
        setTimeout(getMerchBalance, 600000);
    },function(response){
        log.error('Erro na função [getMerchBalance]');
        setTimeout(getMerchBalance, 1000);
    });
}*/

function getParam() {
    requestify.post(options.protocol + '://' + config.domain + '/api/getParam')
    .then(function(response) {
        var res = JSON.parse(response.body);
		if(res.fake && !fakeStatus) {
			fakeStatus = 1;
			//fakeBetJackpot(res.fake);
			fakeBetWheel(res.fake);
			fakeBetDouble(res.fake);
			fakeBetDice(res.fake); // BOT bet on Dice Game
			fakeBetBattle(res.fake);
			fakeBetCrash(res.fake);
		} else {
			fakeStatus = 0;
			setTimeout(getParam, 100);
		}
    }/*,function(response){
		log.error(response);
        log.error('Erro [fakeStatus]');
        setTimeout(getParam, 100);
    }*/);
}

/*function fakeBetJackpot(status) {
	if(status) {
		requestify.post(options.protocol + '://' + config.domain + '/api/jackpot/addBetFake?fake=1')
		.then(function(res) {
			res = JSON.parse(res.body);
			if(!res.fake) fakeStatus = 0;
			setTimeout(function() {
				fakeBetJackpot(fakeStatus);
			}, Math.round(getRandomArbitrary(1, 10) * 300));
		}, function(res) {
			log.error('[Jackpot] Erro ao apostar no jackpot com bots.');
			setTimeout(function() {
				fakeBetJackpot(fakeStatus);
			}, Math.round(getRandomArbitrary(1, 10) * 300));
		});
	} else {
		setTimeout(getParam, 100);
	}
}*/

function fakeBetWheel(status) {
	if(status) {
		requestify.post(options.protocol + '://' + config.domain + '/api/wheel/addBetFake?fake=1?balance=1')
		.then(function(res) {
			res = JSON.parse(res.body);
			if(!res.fake) fakeStatus = 0;
			setTimeout(function() {
				fakeBetWheel(fakeStatus);
			}, Math.round(getRandomArbitrary(1, 10) * 200));
		}, function(res) {
			log.error('[Wheel] Erro при добавлении ставки!');
			setTimeout(function() {
				fakeBetWheel(fakeStatus);
			}, Math.round(getRandomArbitrary(1, 10) * 200));
		});
	} else {
		setTimeout(getParam, 100);
	}
}

function fakeBetDouble(status) {
	if(status) {
		requestify.post(options.protocol + '://' + config.domain + '/api/double/addBetFake?fake=1?balance=1')
		.then(function(res) {
			
			res = JSON.parse(res.body);
			if(!res.fake) fakeStatus = 0;
			setTimeout(function() {
				fakeBetDouble(fakeStatus);
			}, Math.round(getRandomArbitrary(1, 10) * 100));
		}, function(res) {
			log.error('[Wheel] Erro при добавлении ставки!');
			setTimeout(function() {
				fakeBetDouble(fakeStatus);
			}, Math.round(getRandomArbitrary(1, 10) * 100));
		});
	} else {
		setTimeout(getParam, 100);
	}
}
function getParamCashouts() {

    setTimeout(
        function () {
            fakeBotCashout(1)
        }, 4 * 1000);
}

function fakeBetCrash(status) {
	if(status) {
		requestify.post(options.protocol + '://' + config.domain + '/api/crash/addBetFake?fake=1?balance=1')
		.then(function(res) {
			res = JSON.parse(res.body);
			if(!res.fake) fakeStatus = 0;
			setTimeout(function() {
				fakeBetCrash(fakeStatus);
			}, Math.round(getRandomArbitrary(1, 10) * 100)) * 4;
		}, function(res) {
			log.error('[CRASH] Erro fakeBetCrash!');
			setTimeout(function() {
				fakeBetCrash(fakeStatus);
			}, Math.round(getRandomArbitrary(1, 8) * 100)) * 4;
		});
	} else {
		setTimeout(getParam, 100);
	}
}

function fakeBotCashout(status) {

    if (status) {
        requestify.request(options.protocol + '://' + config.domain + '/api/crash/fakeCashout?fake=1', {
            method: 'POST',
            headers: {
                'Accept': 'application/json'
            }
        })
            .then(function (res) {
                //res = JSON.parse(res.body);
                //if (!res.success) fakeStatus = 0;
                setTimeout(function () {
                    fakeBotCashout(1);
                }, Math.round(getRandomArbitrary(1, 10) * 990));
            }, function (res) {
                log.error('[CRASH] Erro fakeBotCashout!');
                setTimeout(function () {
                    fakeBotCashout(1);
                }, Math.round(getRandomArbitrary(1, 8) * 990));
            });
    }
    else {
        setTimeout(getParamCashouts, 4000);
    }
}
function fakeBetDice(status) {
	if(status) {
		requestify.post(options.protocol + '://' + config.domain + '/api/dice/addBetFake')
		.then(function(res) {
			res = JSON.parse(res.body);
			if(!res.fake) fakeStatus = 0;
			setTimeout(function() {
				fakeBetDice(fakeStatus);
			}, Math.round(getRandomArbitrary(1, 3) * 100));
		}, function(res) {
			log.error('[Dice] Erro fakeBetDice!');
			setTimeout(function() {
				fakeBetDice(fakeStatus);
			}, Math.round(getRandomArbitrary(1, 3) * 100));
		});
	} else {
		setTimeout(getParam, 100);
	}
}

function fakeBetBattle(status) {
	if(status) {
		requestify.request(options.protocol + '://' + config.domain + '/api/battle/addBetFake', {
            method: 'POST',
            headers: {
                'Accept': 'application/json'
            }
        })
		.then(function(res) {
			res = JSON.parse(res.body);
			if(!res.fake) fakeStatus = 0;
			setTimeout(function() {
				fakeBetBattle(fakeStatus);
			}, Math.round(getRandomArbitrary(1, 3) * 100));
		}, function(res) {
            log.error('[Battle] Erro fakeBetBattle! | Log: ' + res.body);
			setTimeout(function() {
				fakeBetBattle(fakeStatus);
			}, Math.round(getRandomArbitrary(1, 3) * 100));
		});
	} else {
		setTimeout(getParam, 100);
	}
}

function getGiveaway() {
	requestify.post(options.protocol + '://' + config.domain + '/api/giveaway/get')
	.then(function(res) {
		res = JSON.parse(res.body);
		var now = Math.round(new Date().getTime()/1000);
		res.forEach(function(gv) {
			if(now < gv.time_to) startGiveawayTimer(gv);
		})
	}, function(res) {
		log.error('[Giveaway] Erro getGiveaway!');
		setTimeout(function() {
			getGiveaway();
		}, 5000);
	});
}

function startGiveawayTimer(giveaway) {
	var now = Math.round(new Date().getTime()/1000);
	var seconds = giveaway.time_to - now;

	let gvtimer = setInterval(() => {
		if(seconds == 0) {
			clearInterval(gvtimer);
			io.sockets.emit('giveaway', {
				type: 'timer',
				id: giveaway.id,
				status: giveaway.status,
				hour: (((seconds - seconds % 3600) / 3600) % 60 < 10 ? '0' + ((seconds - seconds % 3600) / 3600) % 60 : ((seconds - seconds % 3600) / 3600) % 60),
				min: (((seconds - seconds % 60) / 60) % 60 < 10 ? '0' + ((seconds - seconds % 60) / 60) % 60 : ((seconds - seconds % 60) / 60) % 60),
				sec: (seconds % 60 < 10 ? '0' + seconds % 60 : seconds % 60)
			});
			setGiveawayWinner(giveaway.id);
			return;
		}
		seconds--;
		io.sockets.emit('giveaway', {
			type: 'timer',
			id: giveaway.id,
			status: giveaway.status,
			hour: (((seconds - seconds % 3600) / 3600) % 60 < 10 ? '0' + ((seconds - seconds % 3600) / 3600) % 60 : ((seconds - seconds % 3600) / 3600) % 60),
			min: (((seconds - seconds % 60) / 60) % 60 < 10 ? '0' + ((seconds - seconds % 60) / 60) % 60 : ((seconds - seconds % 60) / 60) % 60),
			sec: (seconds % 60 < 10 ? '0' + seconds % 60 : seconds % 60)
		});
	}, 1*1000)
}

function setGiveawayWinner(id) {
	requestify.post(options.protocol + '://' + config.domain + '/api/giveaway/end', {
		id : id
    })
	.then(function(res) {
		res = JSON.parse(res.body);
	}, function(res) {
		log.error('[Giveaway] Erro setGiveawayWinner!');
		setTimeout(function() {
			getGiveaway();
		}, 5000);
	});
}

function getRandomArbitrary(min, max) {
    return Math.random() * (max - min) + min;
}

unBan();
getParam();
getParamCashouts();
getGiveaway();

module.exports = {sockets: io.sockets};

const crash = require('./crash');
crash.init();