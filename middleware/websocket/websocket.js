const express = require("express");
const { createServer } = require("http");
const { Server } = require("socket.io");
require('dotenv').config({ path: '../../.env' });
var cors = require("cors");
const app = express();
const httpServer = createServer(app);
const io = new Server(httpServer, {
});

const HOST = process.env.WEBSOCKET_URL || "localhost";
const PORT = process.env.WEBSOCKET_PORT || 3030;
// get data from database

app.use(cors());
app.all("*", function (req, res, next) {
    let origin = req.headers.origin;
    res.header(
        "Access-Control-Allow-Headers",
        "Origin, X-Requested-With, Content-Type, Accept"
    );
    next();
});



io.on("connection", (socket) => {
    console.log("Connected from : " + socket.id);
    socket.on("realtime", (data) => {
        console.log("Realtime data received: ", data);
        
        io.emit("realtime_data", data);
    });

});


httpServer.listen(PORT, () => {
  console.log("Server berhasil berjalan di "+ HOST);
  console.log("Menunggu koneksi WebSocket...");
});

