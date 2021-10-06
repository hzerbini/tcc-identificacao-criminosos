import Echo from 'laravel-echo';

window.Pusher = require('pusher-js')

export default ({$axios}, inject) => {
    const echo = new Echo(
        {
            broadcaster: 'pusher',
            key: process.env.MIX_PUSHER_APP_KEY,
            cluster: process.env.MIX_PUSHER_APP_CLUSTER,
            forceTLS: true,
            authorizer: (channel, options) => {
                return {
                    authorize: (socketId, callback) => {
                        $axios.post('/api/broadcasting/auth', {
                            socket_id: socketId,
                            channel_name: channel.name
                        })
                            .then(response => {
                                callback(false, response.data);
                            })
                            .catch(error => {
                                callback(true, error);
                            });
                    }
                };
            },
        }
    )

    inject('echo', echo)
}