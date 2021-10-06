<template>
    <PageWithHeader>
        <template v-slot:header>Usuários</template>
        <template v-slot:header-right>
            <NuxtLink to="/usuarios/criar" class="block bg-gray-900 p-2 rounded-full hover:bg-gray-700 focus:outline-none focus:ring">
                <UserAddIcon class="h-8 w-8 text-gray-100"/>
            </NuxtLink>
        </template>
        <div>
            <div class="grid place-items-center h-80" v-if="$fetchState.pending">
                <svg class="animate-spin -ml-1 mr-3 h-1/2 text-gray-900" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
            <p v-else-if="$fetchState.error">Acontenceu algum erro :C</p>
            <div v-else class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Foto
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nome
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Email
                                    </th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="user in data.data" :key="user.email">
                                    <td class="py-4 px-6 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            <div class="w-12 h-12 rounded-full overflow-hidden">
                                                <img v-if="user.photos.length > 0" :src="user.photos[0].path" class="w-full h-full object-cover"></img>
                                                <div v-else class="w-full h-full bg-gray-600"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{user.name}}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{user.email}}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <NuxtLink :to="`/usuarios/${user.id}`" class="text-indigo-600 hover:text-indigo-900 mx-2">Visualizar</NuxtLink>
                                        <NuxtLink :to="`/usuarios/${user.id}/editar`" href="#" class="text-indigo-600 hover:text-indigo-900 mx-2">Editar</NuxtLink>
                                        <NuxtLink :to="`/usuarios/${user.id}/alertas`" class="text-indigo-600 hover:text-indigo-900 mx-2">Alertas</NuxtLink>
                                        <button href="#" class="text-indigo-600 hover:text-indigo-900 mx-2" @click="deleteUser(user)">Deletar</button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <Paginator :meta="data.meta"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </PageWithHeader>
</template>

<script>
export default {
    layout: 'dashboard',
    data:() => ({
        data: null,
    }),
    watch: {
        '$route.query': '$fetch'
    },
    methods: {
        deleteUser(user){
            this.$swal({
                title: 'Tem certeza que deseja remover o usuário?',
                icon: 'warning',
                showCloseButton: true,
                showCancelButton: true,
                focusConfirm: false,
                confirmButtonText:'Sim',
                cancelButtonText:'Não',
            }).then(result => {
                if(result.isConfirmed){
                    this.$axios.delete(`/api/users/${user.id}`).then(()=>{
                        this.$swal({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            icon: 'success',
                            title: 'Usuário deletado com sucesso!',
                        });
                    }).catch(() => {
                        this.$swal({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            icon: 'error',
                            title: 'Falha ao deletar usuário!',
                        });
                    }).finally(() => this.$fetch());
                }
            })
        }
    },
    async fetch() {
        this.data = await this.$axios.get('/api/users', {
            params: this.$route.query
        }).then(res => res.data).catch(err => {
            this.$swal({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                icon: 'error',
                title: 'Falha ao buscar usuários!',
            });

            this.$router.push('/');
        });
    }
}
</script>