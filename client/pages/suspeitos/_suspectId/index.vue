<template>
    <PageWithHeader>
        <template v-if="! $fetchState.pending" v-slot:header>{{ suspect.name }}</template>
        <template v-slot:header-right>
            <NuxtLink :to="`/suspeitos/${suspect.id}/editar`" href="#" class="text-indigo-600 hover:text-indigo-900 mx-2">Editar</NuxtLink>
            <button href="#" class="text-indigo-600 hover:text-indigo-900 mx-2" @click="deleteSuspect(suspect)">Deletar</button>
        </template>
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
                            CPF
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ suspect.cpf }}
                        </dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Data de Nascimento
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ getSuspectDate(suspect) }}
                        </dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Fotos
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 flex justify-center">
                            <carousel :nav="false" :items="1" class="w-64">
                                <img v-for="photo in suspect.photos" :src="photo.path" alt=""/>
                            </carousel>
                        </dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Tatuagens
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 flex justify-center">
                            <carousel :nav="false" :items="1" class="w-64">
                                    <div class="relative w-64 h-64" v-for="tattoo in suspect.tattoos">
                                        <img :src="tattoo.path" class="h-full w-full object-cover">
                                        <div class="absolute flex bottom-0 left-0 flex flex-wrap w-64">
                                            <p class="my-2 mx-2 bg-gray-200 rounded-sm px-2 py-1" v-for="feature in tattoo.features">{{ feature.name }}</p>
                                        </div>
                                    </div>
                            </carousel>
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
import carousel from 'vue-owl-carousel'

export default {
    layout: 'dashboard',
    data: () => ({
        suspect: {},
    }),
    watch: {
        '$route.query': '$fetch'
    },
    methods: {
        getSuspectDate(suspect){
            const data = new Date(suspect.birth_date);
            return [data.getDate(),data.getMonth() + 1, data.getFullYear()].join('/');
        },
        deleteSuspect(suspect){
            this.$swal({
                title: 'Tem certeza que deseja remover o suspeito?',
                icon: 'warning',
                showCloseButton: true,
                showCancelButton: true,
                focusConfirm: false,
                confirmButtonText:'Sim',
                cancelButtonText:'Não',
            }).then(result => {
                if(result.isConfirmed){
                    this.$axios.delete(`/api/suspects/${suspect.id}`).then(()=>{
                        this.$swal({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            icon: 'success',
                            title: 'Suspeito deletado com sucesso!',
                        });
                        this.$router.push('/suspeitos');
                    }).catch(() => {
                        this.$swal({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            icon: 'error',
                            title: 'Falha ao deletar suspeito!',
                        });
                    }).finally(() => this.$fetch());
                }
            })
        },
    },
    async fetch() {
        this.suspect = await this.$axios.get(`/api/suspects/${this.$route.params.suspectId}`).then(res => res.data.data).catch(err => {
            this.$swal({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                icon: 'error',
                title: 'Falha ao buscar suspeito!',
            });
            this.$router.push('/suspeitos');
        });
    },
    components: { carousel }
}
</script>