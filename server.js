import express from "express";
import http from "http";
import { Server } from "socket.io";
import cors from "cors";

const app = express();

app.use(cors());

const server = http.createServer(app);

const io = new Server(server, {
    cors: {
        origin: "*",
    },
});

io.on("connection", (socket) => {

    console.log("User Connected");

    socket.on("send_message", (data) => {

        console.log(data);

        io.emit("receive_message", data);

    });

});

server.listen(3000, () => {
    console.log("Server running on port 3000");
});