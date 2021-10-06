require('dotenv').config()
export default {
    ssr: false,
    // Global page headers: https://go.nuxtjs.dev/config-head
    head: {
        title: 'tcc-identificacao-criminosos',
        htmlAttrs: {
            lang: 'pt-br'
        },
        meta: [
            { charset: 'utf-8' },
            { name: 'viewport', content: 'width=device-width, initial-scale=1' },
            { hid: 'description', name: 'description', content: '' }
        ],
        link: [
            { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' }
        ]
    },

    router: {
        middleware: ['auth']
    },

    // Global CSS: https://go.nuxtjs.dev/config-css
    css: [
    ],

    // Plugins to run before rendering page: https://go.nuxtjs.dev/config-plugins
    plugins: [
        { src: './plugins/echo', mode: 'client' },
        '~plugins/vue-js-modal.js',
    ],

    // Auto import components: https://go.nuxtjs.dev/config-components
    components: true,

    // Modules for dev and build (recommended): https://go.nuxtjs.dev/config-modules
    buildModules: [
        // https://go.nuxtjs.dev/tailwindcss
        '@nuxtjs/tailwindcss',
    ],

    // Modules: https://go.nuxtjs.dev/config-modules
    axios: {
        credentials: true
    },

    modules: [
        '@nuxtjs/dotenv',
        '@nuxtjs/axios',
        '@nuxtjs/auth-next',
        'vue-sweetalert2/nuxt',
        'portal-vue/nuxt'
    ],
    auth: {
        plugins: [ '~/plugins/bouncer.js' ],
        watchLoggedIn: true,
        redirect: {
            login: '/login',
            logout: '/login',
            callback: '/login',
            home: '/'
        },
        strategies: {
            'laravelSanctum': {
                provider: 'laravel/sanctum',
                url: `${process.env.API_URL_BROWSER}/api`,
                endpoints: {
                    login: { url: '/login', method: 'post' },
                    logout: { url: '/logout', method: 'post' },
                    user: { url: '/user', method: 'get' }
                },
                user: {
                    property: 'data',
                }
            },
        },
    },

    // Build Configuration: https://go.nuxtjs.dev/config-build
    build: {
    }
}
