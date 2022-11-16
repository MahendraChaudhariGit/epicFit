import Echo from 'laravel-echo';
   
window.Echo = new Echo({
    broadcaster: 'socket.io',
    host: window.location.hostname + ":" + window.laravel_echo_port,
    //'localhost:6001',
    transports: ['polling', 'flashsocket'] // Fix CORS error!
});