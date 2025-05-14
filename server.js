import express from 'express';
import { createServer } from 'http';
import { Server } from 'socket.io';

const app = express();
const server = createServer(app);
const io = new Server(server, {
    cors: {
        origin: '*',
    },
});

const users = {};

io.on('connection', (socket) => {
    console.log(`User connected: ${socket.id}`);

    socket.on('login', ({id}) => {
        users[id] = socket.id
        console.log(`User logged in: ${id}`);
        io.emit("login", socket.id)
        console.log("users : ",users)
    });

    socket.on('message', ({id , message}) => {
        console.log(`Sending private message to User ${id} to ${message}`);
        if (users[id]) {

            console.log("users : ",users)
            io.to(users[id]).emit('message', {
                senderId: id,
                message
            });
            io.to(socket.id).emit('message', {
                senderId: id,
                message
            });
            console.log(users[id])
        } else {
            console.log(`User ${id} not found or offline.`);
        }
    });

    // Handle user disconnection and clean up
    socket.on('disconnect', () => {
        console.log(`User disconnected: ${socket.id}`);
        for (const [id] of Object.entries(users)) {
            console.log("des : user id ", id)
            if (id) {
                delete users[id];
                break;
            }
        }

        console.log(users)
    });
});
const PORT = 3000;
server.listen(PORT, () => {
    console.log(`Socket.IO server running at http://192.168.0.10:${PORT}`);
});
