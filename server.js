const app =require("express")();
const http = require("http").createServer(app);
const io =require("socket.io")(http)


io.on('connection',(socket) =>{
    socket.on("new_appointment_create", _=>{
        io.emit('admin_appointment_list')
    })
})

http.listen(3000, _=>{
    console.log("Server 3000 Portunu dinliyor...")
})