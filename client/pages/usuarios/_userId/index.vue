<template>
    <PageWithHeader>
        <template v-if="! $fetchState.pending" v-slot:header>{{ user.name }}</template>
        <div class="grid place-items-center h-80" v-if="$fetchState.pending">
            <svg class="animate-spin -ml-1 mr-3 h-1/2 text-gray-900" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
        <p v-else-if="$fetchState.error">Acontenceu algum erro :C</p>
        <div v-else>
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">

                <div class="border-t border-gray-200">
                <dl>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Email
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ user.email }}
                    </dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Permissões:
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <span v-if="userPermissions().can('*')">
                                Esse usuário tem permissão para executar tudo no sistema.
                            </span>
                            <ul v-else>
                                <li v-if="userPermissions().can('*', 'App\\Models\\User')">Gerenciar Usuários</li>
                                <li v-if="userPermissions().can('managePermissions')">Gerenciar Permissionamento de Usuários</li>
                            </ul>
                        </dd>
                    </div>
                </dl>
                </div>
            </div>
        </div>
    </PageWithHeader>
</template>

<script>
import Bouncer from '~/assets/js/Bouncer';

export default {
    layout: 'dashboard',
    data: () => ({
        user: {},
    }),
    watch: {
        '$route.query': '$fetch'
    },
    methods: {
        userPermissions(){
            return new Bouncer(this.user);
        }
    },
    async fetch() {
        this.user = await this.$axios.get(`/api/users/${this.$route.params.userId}`).then(res => res.data.data).catch(err => {
            this.$swal({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                icon: 'error',
                title: 'Falha ao buscar usuário!',
            });
            this.$router.push('/usuarios');
        });
    }
}
</script>