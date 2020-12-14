$('document').ready(function() {
    socket();
});


async function socket() {
   var socket = io('http://localhost:3001');

    socket.on('connect', function(){
       socket.emit('agentStatus',
       ['asdasd']);
   });
   
   socket.on('manager', 
   await function(data){
       console.log(data);

       data.forEach(element => {
           console.log(element);
       });
   });
   
   socket.on('disconnect', function(){});

   console.log('asdasdasd');
}