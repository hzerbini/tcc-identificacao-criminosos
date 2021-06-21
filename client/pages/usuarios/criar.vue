<template>
    <PageWithHeader>
        <template v-slot:header>Criação de Usuário</template>
        <template v-slot:header-right>OPtions</template>
        <div class="mt-10 sm:mt-0">
            <div class="md:grid md:grid-cols-3 md:gap-6">
                <div class="md:col-span-1">
                    <div class="px-4 sm:px-0">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Informações do Novo Usuário</h3>
                        <p class="mt-1 text-sm text-gray-600">
                            Use um endereço de email válido aonde você possa receber emails.
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
                                        <label class="block text-sm font-medium text-gray-700" :class="(errors.email)?'text-red-700':''">Email</label>
                                        <input type="text" v-model="email" autocomplete="email" class="mt-1 focus:outline-none focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" />
                                        <p v-if="errors.email" class="text-red-700"> {{ errors.email.join('\n') }}</p>
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <label class="block text-sm font-medium text-gray-700">Confirmação Email</label>
                                        <input v-model="emailConfirmation" type="text" class="mt-1 focus:outline-none focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" />
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
                                    Criar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </PageWithHeader>
</template>

<script>
export default {
    layout: 'dashboard',
    data: () => ({
        name: '',
        email: '',
        emailConfirmation: '',
        password: '',
        passwordConfirmation: '',
        errors: {}
    }),
    methods: {
        submit: function(){
            this.$axios.post('/api/users', {
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
                    title: 'Usuário criado com sucesso!',
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
                    title: 'Falha ao cadastrar usuário!',
                });

                if(resp.status == 422){
                    this.errors = resp.data.errors;
                }
            });
        }
    }
}
</script>