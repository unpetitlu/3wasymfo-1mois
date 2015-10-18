// création du serveur
var http = require('http');

// req est la requête et res est la réponse
var httpServer = http.createServer(function(req, res){
    console.log('un utilisateur se connecte');
    //res.end('hello world');
});

//listen 3000 port
httpServer.listen(3000, function(){});

// Ecouter les connexion au serveur
var io = require('socket.io').listen(httpServer);

// Dès qu'il y a une connection (event natif)
io.sockets.on('connection', function(socket)
{
    console.log('Nouvel utilisateur');
    socket.on('comment', function(data)
    {
        // socket.emit alert uniquement l'utilisateur courant
        //socket.emit('newmessage');

        // alert tous les utilisateur sauf celui courant
        socket.broadcast.emit('newmessage');

        // alert tous les utilisateur ainsi que celui en court
        //io.socket.broadcast.emit('newmessage');
    });

    // Dès qu'il y a une déconnection (event natif)
    socket.on('disconnect', function(){

    });
});