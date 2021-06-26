<template>
    <PageWithHeader>
        <template v-slot:header>Edição de Usuário</template>
        <template v-slot:header-right>
            <NuxtLink :to="`/usuarios/${user.id}`" class="text-indigo-600 hover:text-indigo-900 mx-2">Visualizar</NuxtLink>
            <button href="#" class="text-indigo-600 hover:text-indigo-900 mx-2" @click="deleteUser(user)">Deletar</button>
        </template>
        <div class="grid place-items-center h-80" v-if="$fetchState.pending">
            <svg class="animate-spin -ml-1 mr-3 h-1/2 text-gray-900" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
        <p v-else-if="$fetchState.error">Acontenceu algum erro :C</p>
        <template v-else>
            <div class="mt-10 sm:mt-0">
                <div class="md:grid md:grid-cols-3 md:gap-6">
                    <div class="md:col-span-1">
                        <div class="px-4 sm:px-0">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">Formulário de Edição do Usuário:</h3>
                            <p class="mt-1 text-sm text-gray-600">
                                Tome cuidado ao atualizar essas informações.
                            </p>
                        </div>
                    </div>
                    <div class="mt-5 md:mt-0 md:col-span-2">
                        <form @submit.prevent="submit">
                            <div class="shadow overflow-hidden sm:rounded-md">
                                <div class="px-4 py-5 bg-white sm:p-6">
                                    <div class="grid grid-cols-6 gap-6">
                                        <div class="col-span-12 sm:col-span-6">
                                            <label class="block text-sm font-medium text-gray-700" :class="(errors.name)?'text-red-700':''">Nome</label>
                                            <input v-model="name" type="text" autocomplete="given-name" 
                                                class="mt-1 focus:outline-none focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" />
                                            <p v-if="errors.name" class="text-red-700"> {{ errors.name.join('\n') }}</p>
                                        </div>

                                        <div class="col-span-6 sm:col-span-3">
                                            <label class="block text-sm font-medium text-gray-700" :class="(errors.password)?'text-red-700':''">Senha</label>
                                            <input v-model="password" type="password" class="mt-1 focus:outline-none focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" />
                                            <p v-if="errors.password" class="text-red-700"> {{ errors.password.join('\n') }}</p>
                                        </div>

                                        <div class="col-span-6 sm:col-span-3">
                                            <label class="block text-sm font-medium text-gray-700">Confirmação Senha</label>
                                            <input v-model="passwordConfirmation" type="password" class="mt-1 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" />
                                        </div>

                                    </div>
                                </div>
                                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Atualizar
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <hr class="my-8"/>
                <div class="mt-10 sm:mt-0" v-if="bouncer().can('managePermissions')">
                    <div class="md:grid md:grid-cols-3 md:gap-6">
                        <div class="md:col-span-1">
                            <div class="px-4 sm:px-0">
                                <h3 class="text-lg font-medium leading-6 text-gray-900">Atualização de Permissionamento do Usuário:</h3>
                                <p class="mt-1 text-sm text-gray-600">
                                    Tome cuidado ao atualizar essas informações.
                                </p>
                            </div>
                        </div>
                        <div class="mt-5 md:mt-0 md:col-span-2">
                            <form>
                                <div class="shadow overflow-hidden sm:rounded-md">
                                    <div class="px-4 py-5 bg-white sm:p-6">
                                        <div class="grid grid-cols-6 gap-6">
                                            <div class="col-span-12 sm:col-span-6 flex">
                                                <label class="block text-sm font-medium text-gray-700 mr-4">Gerenciar Usuários:</label>
                                                <input type="checkbox" v-model="manageUsers" @change="() => changePermission(manageUsers, 'manageUsers')">
                                            </div>
                                            <div class="col-span-12 sm:col-span-6 flex">
                                                <label class="block text-sm font-medium text-gray-700 mr-4">Gerenciar Permissionamento de usuários:</label>
                                                <input type="checkbox" v-model="managePermissions"  @change="() => changePermission(managePermissions, 'managePermissions')">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </PageWithHeader>
</template>

<script>
import Bouncer from '~/assets/js/Bouncer';

export default {
    layout: 'dashboard',
    data: () => ({
        user: {},
        name: '',
        email: '',
        emailConfirmation: '',
        password: '',
        passwordConfirmation: '',
        errors: {},
        manageUsers: false,
        managePermissions: false,
    }),
    watch: {
        '$route.query': '$fetch'
    },
    async fetch() {
        const data = await this.$axios.get(`/api/users/${this.$route.params.userId}`).then(res => res.data.data).catch(err => {
            this.$swal({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                icon: 'error',
                title: 'Falha ao buscar informações do usuário!',
            });
            this.$router.push('/usuarios');
        });

        this.user = data;
        this.name = data.name;

        //permissions
        this.updatePermissions(data);
    },
    methods: {
        bouncer: function(){
            return new Bouncer(this.$auth.user);
        },
        updatePermissions: function (user){
            const bouncer = new Bouncer(user);
            this.manageUsers = bouncer.can('*', 'App\\Models\\User');
            this.managePermissions = bouncer.can('managePermissions');
        },
        changePermission: async function(operation, permission){
            try{
                let response;
                if(operation){
                    response = await this.$axios.post(`/api/users/${this.$route.params.userId}/allow`, {
                        permission: permission
                    });
                }else{
                    response = await this.$axios.delete(`/api/users/${this.$route.params.userId}/disallow`, {
                        data: {
                            permission: permission
                        }
                    });
                }

                this.$swal({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    icon: 'success',
                    title: 'Permissões do usuário atualizadas com sucesso!',
                });

                this.updatePermissions(response.data.data);
            }catch(err){

                this.$swal({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    icon: 'error',
                    title: 'Falha ao atualizar permissões do usuário!',
                });

                this.updatePermissions(response.data.data);
            }
        },
        deleteUser(){
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
                    this.$axios.delete(`/api/users/${this.user.id}`).then(()=>{
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
        },
        submit: function(){
            this.$axios.patch(`/api/users/${this.$route.params.userId}`, {
                name: this.name,
                email: this.email, 
                email_confirmation: this.emailConfirmation, 
                password: this.password,
                password_confirmation: this.passwordConfirmation,
            }).then(() => {
                this.$router.push('/usuarios');
                this.$swal({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    icon: 'success',
                    title: 'Usuário atualizado com sucesso!',
                });
            }).catch((err) => {
                const resp = err.response;

                this.$swal({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    icon: 'error',
                    title: 'Falha ao atualizar usuário!',
                });

                if(resp.status == 422){
                    this.errors = resp.data.errors;
                }
            });
        }
    }
}
</script>