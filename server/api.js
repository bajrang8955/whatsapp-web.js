const express = require('express');
const { Client, LocalAuth } = require('../');
const app = express();
app.use(express.json());

const clients = new Map();

function getClient(userId) {
    if (clients.has(userId)) {
        return clients.get(userId);
    }
    const client = new Client({
        authStrategy: new LocalAuth({ clientId: userId })
    });
    client.on('ready', () => {
        console.log(`Client ${userId} is ready`);
    });
    client.on('disconnected', () => {
        clients.delete(userId);
        console.log(`Client ${userId} disconnected`);
    });
    client.initialize();
    clients.set(userId, client);
    return client;
}

app.post('/login', (req, res) => {
    const { userId } = req.body;
    const client = getClient(userId);
    if (client.info && client.info.wid) {
        return res.json({ status: 'authenticated' });
    }
    client.once('qr', qr => {
        res.json({ qr });
    });
});

app.get('/status/:userId', (req, res) => {
    const userId = req.params.userId;
    const client = clients.get(userId);
    const loggedIn = !!(client && client.info && client.info.wid);
    res.json({ loggedIn });
});

app.post('/send', (req, res) => {
    const { userId, to, message } = req.body;
    const client = clients.get(userId);
    if (!client || !(client.info && client.info.wid)) {
        return res.status(400).json({ error: 'User not authenticated' });
    }
    client.sendMessage(to, message)
        .then(() => res.json({ status: 'sent' }))
        .catch(err => res.status(500).json({ error: err.message }));
});

const port = process.env.PORT || 3000;
app.listen(port, () => console.log(`API running on port ${port}`));
