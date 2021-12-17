<template>
    <PageWithHeader>
        <template v-slot:header>Alertas</template>
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
                                    Usuário
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Titulo
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Mensagem
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Data
                                    </th>
                                    <td scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Lido em
                                    </td>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="alert in data.data" :key="alert.id">
                                    <th class="py-4 whitespace-nowrap">
                                        <div v-if="$bouncer.can('view','App\\Models\\User', alert.user)" class="text-sm font-medium text-blue-700 hover:text-blue-900">
                                            <NuxtLink  :to="`/usuarios/${alert.user_id}`">{{ alert.user.name }}</NuxtLink>
                                        </div>
                                        <span v-else>{{ alert.user.name }}</span>
                                    </th>
                                    <td class="py-4 px-6 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900" >
                                            {{ alert.title }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 w-32 truncate" v-html="alert.message"></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{(new Date(alert.created_at)).toLocaleString("pt-br")}}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                           {{ (alert.read_at != null)? (new Date(alert.read_at)).toLocaleString("pt-br") : 'Não lido.' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button v-if="$bouncer.can('view', 'App\\Models\\Alert', alert)" class="text-indigo-600 hover:text-indigo-900 mx-2 font-semibold"" @click="() => $modal.show(`view.${alert.id}`)">Visualizar</button>
                                        <button v-if="$bouncer.can('delete','App\\Models\\Alert', alert)" href="#" class="text-indigo-600 hover:text-indigo-900 mx-2 font-semibold" @click="deleteAlert(alert)">Deletar</button>
                                    </td>
                                    <portal to="modals">
                                        <AlertModal :name="`view.${alert.id}`" :alert='alert' @update-alert="$fetch"/>
                                    </portal>
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
        teste: '<span class="bg-red-500"> TESTE LOUCO</span>'
    }),
    watch: {
        '$route.query': '$fetch'
    },
    mounted (){
      this.$echo.private(`alerts`)
        .listen('NewAlertSent', (e) => {
            this.$fetch();
        });
    },
    beforeDestroy(){
        this.$echo.leave(`alerts`);
    },
    methods: {
        deleteAlert(alert){
            this.$swal({
                title: 'Tem certeza que deseja remover o alerta?',
                icon: 'warning',
                showCloseButton: true,
                showCancelButton: true,
                focusConfirm: false,
                confirmButtonText:'Sim',
                cancelButtonText:'Não',
            }).then(result => {
                if(result.isConfirmed){
                    this.$axios.delete(`/api/alerts/${alert.id}`).then(()=>{
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
        this.data = await this.$axios.get('/api/alerts', {
            params: this.$route.query
        }).then(res => res.data).catch(err => {
            this.$swal({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                icon: 'error',
                title: 'Falha ao buscar alertas!',
            });

            this.$router.push('/');
        });

        console.log(this.data);
    }
}
</script>