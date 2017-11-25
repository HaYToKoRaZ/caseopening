var app = require('express')();
var mysql = require('mysql');
var fs = require('fs');
var log4js = require('log4js');
var request = require('request');
var time = new Date();
log4js.configure({
  appenders: {
      console: {
          type: 'console'
      },
      default: {
          type: 'file',
          filename: 'logs/tuzik'+time+'.log'
      }
  },
  categories: {
      default: {
          appenders: ['default', 'console'],
          level: 'trace'
      }
  }
});
var options = {
  key: fs.readFileSync('/var/www/socket/certs/key.pem'),
  cert: fs.readFileSync('/var/www/socket/certs/cert.pem'),
};

var https = require('https').createServer(options, app);
var io = require('socket.io')(https);

var pool = mysql.createPool({
  connectionLimit : 10,
  host     : 'localhost',
  user     : 'root',
  password : 'JEZwqGJx',
  database : 'gambling',
  debug    : false,
});

var users = [];
var onlineUsers = 0;
var prevOnline = 0;
var liveFeed = '';

getLiveDrops(liveFeed);
getOnlineCount(prevOnline, onlineUsers);
getNotifications();

io.on('connection', function(socket) {
  onlineUsers++;
  socket.emit('onlinecount', onlineUsers);
  var header = [];
  if(socket.handshake.headers['cookie'] != undefined) {
    header = socket.handshake.headers['cookie'].split('; SESSID=');
  }
  if(header[1]) {
    var shash = header[1].split(';');
    var shash = shash[0];
    pool.getConnection(function(err,connection) {
      connection.query('SELECT * FROM `users` WHERE `shash` = ? AND `shash` != ""', [shash], function(err, results) {
        connection.release();
        if(results != '' && results[0].id != '') {
          users[socket.id] = results[0];
          var user = users[socket.id];
          console.log('User connected: '+user.steamid);
        } else {
          console.log('User connected with bad SESSID');
        }
      });
    });
  } else {
    console.log('Stranger connected');
  }
  socket.on('disconnect', function() {
    delete users[socket.id];
    onlineUsers--;
    console.log('Client disconnected');
  });
});

function getOnlineCount(prevOnline) {
  if(prevOnline != onlineUsers) {
    io.emit('onlinecount', onlineUsers);
    prevOnline = onlineUsers;
  }
  setTimeout(function() {
    getOnlineCount(prevOnline);
  }, 2000);
}

function getLiveDrops(liveFeed) {
  pool.getConnection(function(err,connection) {
    connection.query('SELECT `id` FROM `users_items` WHERE (TIMESTAMPDIFF(SECOND, `created`, CURRENT_TIMESTAMP()) > 9 AND `openid` != 0) OR (TIMESTAMPDIFF(SECOND,`created`, CURRENT_TIMESTAMP()) > 3 AND `tradeid` != 0) ORDER BY `id` DESC LIMIT 1', function(err, results) {
      connection.release();
      if(results != '' && results[0] != undefined && results[0].id != '') {
        if(liveFeed != results[0].id) {
          liveFeed = results[0].id;
          request.get('https://tuz1k.com/process/liveDrops.php', function(error, response, body) {
            if(!error && response.statusCode == 200) {
              io.emit('livedrops', body);
            }
            setTimeout(function() {
              getLiveDrops(liveFeed);
            }, 500);
          });
        } else {
          setTimeout(function() {
            getLiveDrops(liveFeed);
          }, 500);
        }
      } else {
        setTimeout(function() {
          getLiveDrops(liveFeed);
        }, 500);
      }
    });
  });
}

function getNotifications() {
  pool.getConnection(function(err,connection) {
    var steamIds = '';
    for(var i in users) {
      steamIds += "'" + users[i].steamid + "',";
    }
    steamIds = steamIds.slice(0, -1);
    if(onlineUsers > 35) {
      connection.query('SELECT * FROM `users_messages` WHERE `steamid` IN ('+steamIds+')', function(err, results) {
        connection.release();
        if(results != '' && results[0] != undefined && results[0].id != '') {
          for(var i in results) {
            var steamid = results[i]['steamid'];
            var msg = results[i]['message'];
            var type = results[i]['type'];
            var delay = results[i]['delay'];
            var id = results[i]['id'];
            if(msg == 'affiliate') {
              msg = 'Affiliate code used 1 time(s) and $0.02 has been added to your balance';
            } else if(msg == 'trade') {
              msg = 'Trade offer for withdraw has been sent with 1 item(s).<br /><a href=\"https://steamcommunity.com/my/tradeoffers\" target=\"_blank\">View Trade Offers</a>';
            }
            pool.getConnection(function(err,connection) {
              connection.query('DELETE FROM `users_messages` WHERE `id` = ?', [id], function(err, results) {
                connection.release();
                sendToSteam(steamid, msg, 'notification', type, delay);
              });
            });
          }
        }
        setTimeout(function() {
          getNotifications();
        }, 500);
      });
    } else {
      setTimeout(function() {
        getNotifications();
      }, 500);
    }
  });
}

function getSteamSockets(steamid) {
  var userSockets = [];
  for(var i in users) {
    if(users[i].steamid == steamid) {
      userSockets.push(i);
    }
  }
  return userSockets;
}

function sendToSteam(steamid, msg, type, info, delay) {
  var userSockets = getSteamSockets(steamid);
  if(type == 'notification') {
    msg = notification(info, delay, msg);
  }
  for(var i in userSockets) {
    if(io.sockets.connected[userSockets[i]] != undefined) {
      io.sockets.connected[userSockets[i]].emit(type, msg);
    }
  }
}

function notification(info, delay, message) {
  return {info: info, delay: delay, message: message};
}

log.warn('=================================');
log.warn('======= Server started! =========');
log.warn('=== Server created by TUZ1K ====');
log.warn('== STEAM: http://steamcommunity.com/id/tuz1k/ ==');
log.warn('=================================');

https.listen(8443, function() {
  console.log('listening on *:8443');
});
