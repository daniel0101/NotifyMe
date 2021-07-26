var app = require('express')();
var server = require('http').Server(app);
var io = require('socket.io')(server, {
  allowEIO3: true // false by default
});
require('dotenv').config();
const ejs = require('ejs');

// Set the view engine to ejs
app.set('view engine', 'ejs');

var redisPort = process.env.REDIS_PORT;
var redisHost = process.env.REDIS_HOST;
var ioRedis = require('ioredis');
var redis = new ioRedis(redisPort, redisHost);
var redisDB = new ioRedis(redisPort, redisHost);


// //show client to this publish server
app.get("/:state", async (req, res) => {
  // get url subscribed topic from redis
  var fullUrl = req.protocol + '://' + req.get('host') + req.originalUrl;

  topic = await redisDB.get(`laravel_database_${Buffer.from(fullUrl).toString('base64')}`);

  return res.render("front",{
    channel: `laravel_database_${topic}`,
    topic
  });
});

//subscribe to every event so we can emit a message to any channel needed
redis.psubscribe('*');
redis.on('pmessage', function (pattern,channel, message) {
  message  = JSON.parse(message);
  // emit message on this channel
  io.emit(channel, message.data);
});

var broadcastPort = process.env.BROADCAST_PORT;
server.listen(broadcastPort, function () {
  console.log(`Socket server is running on port ${broadcastPort}`);
});
