<template>
    <PageWithHeader>
        <template v-slot:header>Edição de Suspeito</template>
        <template v-slot:header-right>
            <NuxtLink :to="`/suspeitos/${suspect.id}`" class="text-indigo-600 hover:text-indigo-900 mx-2">Visualizar</NuxtLink>
            <button v-if="$bouncer.can('delete', 'App\\Models\\Suspect', search)" href="#" class="text-indigo-600 hover:text-indigo-900 mx-2" @click="deleteSuspect(suspect)">Deletar</button>
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
                            <h3 class="text-lg font-medium leading-6 text-gray-900">Formulário de Edição do Suspeito:</h3>
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
                                            <input v-model="name" type="text"
                                                class="mt-1 py-1 focus:outline-none focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" />
                                            <p v-if="errors.name" class="text-red-700"> {{ errors.name.join('\n') }}</p>
                                        </div>

                                        <div class="col-span-8 sm:col-span-4">
                                            <label class="block text-sm font-medium text-gray-700" :class="(errors.cpf)?'text-red-700':''">CPF</label>
                                            <input type="text" v-model="cpf" class="mt-1 py-1 focus:outline-none focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" />
                                            <p v-if="errors.cpf" class="text-red-700"> {{ errors.cpf.join('\n') }}</p>
                                        </div>

                                        <div class="col-span-4 sm:col-span-2">
                                            <label class="block text-sm font-medium text-gray-700" :class="(errors.birth_date)?'text-red-700':''">Data de Nascimento</label>
                                            <date-picker v-model="date" format="DD/MM/YYYY"></date-picker>
                                            <p v-if="errors.birth_date" class="text-red-700"> {{ errors.birth_date.join('\n') }}</p>
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
                <hr class="my-8" v-if="suspect.photos.length > 0"/>
                <div class="md:grid md:grid-cols-3 md:gap-6" v-if="suspect.photos.length > 0">
                    <div class="md:col-span-1">
                        <div class="px-4 sm:px-0">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">Remoção Fotos do Suspeito:</h3>
                        </div>
                    </div>
                    <div class="mt-5 md:mt-0 md:col-span-2">
                        <div class="shadow overflow-hidden sm:rounded-md">
                            <div class="px-4 py-5 bg-white sm:p-6 flex justify-center">
                                <carousel :key="suspect.photos.length" :nav="false" :items="1" class="w-64">                                
                                    <div v-for="photo in suspect.photos" class="relative">
                                        <button class="absolute top-0 right-0 px-2 py-1 bg-red-500 rounded-full focus:outline-none focus:ring" @click="deletePhoto(photo)">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                        <img :src="photo.path" class="h-full w-full object-cover">
                                    </div>
                                </carousel>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="my-8"/>
                <div class="mt-10 sm:mt-0">
                    <div class="md:grid md:grid-cols-3 md:gap-6">
                        <div class="md:col-span-1">
                            <div class="px-4 sm:px-0">
                                <h3 class="text-lg font-medium leading-6 text-gray-900">Adição de Fotos do Suspeito:</h3>
                            </div>
                        </div>
                        <div class="mt-5 md:mt-0 md:col-span-2">
                            <form @submit.prevent="submitPhotos" class="shadow overflow-hidden sm:rounded-md">
                                <div class="px-4 py-5 bg-white sm:p-6">
                                    <file-pond ref="pond" :key="suspect.photos.length" allow-multiple="true" accepted-file-types="image/*" server="/api/filepond" />
                                </div>
                                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Atualizar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <hr class="my-8" v-if="suspect.tattoos.length > 0"/>
                <div class="md:grid md:grid-cols-3 md:gap-6" v-if="suspect.tattoos.length > 0">
                    <div class="md:col-span-1">
                        <div class="px-4 sm:px-0">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">Tatuagens do Suspeito:</h3>
                        </div>
                    </div>
                    <div class="mt-5 md:mt-0 md:col-span-2">
                        <div class="shadow overflow-hidden sm:rounded-md">
                            <div class="px-4 py-5 bg-white sm:p-6 flex justify-center">
                                <carousel :key="suspect.tattoos.length" :nav="false" :items="1" class="w-64">                                
                                    <div v-for="tattoo in suspect.tattoos" class="relative">
                                        <div class="absolute top-0 right-0">
                                            <button class="px-2 py-1 bg-blue-500 rounded-full focus:outline-none focus:ring" @click="openUpdateTattooModal(tattoo)">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                    <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                            <button class="px-2 py-1 bg-red-500 rounded-full focus:outline-none focus:ring" @click="deleteTattoo(tattoo)">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                        <img :src="tattoo.path" class="h-full w-full object-cover">
                                        <div class="absolute flex bottom-0 left-0 flex flex-wrap w-64">
                                            <p class="my-2 mx-2 bg-gray-200 rounded-sm px-2 py-1" v-for="feature in tattoo.features">{{ feature.name }}</p>
                                        </div>
                                        <portal to="modals">                                        
                                            <modal :name="`editTattoo${tattoo.id}`" height="auto">
                                                <h3 class="text-gray-700 text-2xl m-2">Atualizar Caracteristicas da Tatuagem:</h3>
                                                <div class="flex justify-center">
                                                    <div class="w-64 h-64">
                                                        <img class="w-full h-full object-cover" :src="tattoo.path" alt="">
                                                    </div>
                                                </div>
                                                <div class="flex align-items-center">
                                                    <input class="ml-3 py-2 px-1 focus:outline-none focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-l -md" type="text" v-model="feature">
                                                    <button class="bg-green-300 mr-3 rounded-r-md text-white font-bold" @click="addFeature()">Adicionar</button>
                                                </div>
                                                <div class="flex flex-wrap">
                                                    <button class="bg-gray-400 p-2 m-2 rounded-md" v-for="feature in features" @click="removeFeature(feature)">{{ feature }}</button>
                                                </div>
                                                <hr>
                                                <div class="p-2 flex justify-end bg-gray-100">
                                                    <button class="px-2 py-1 bg-blue-500 rounded-lg text-white font-bold" @click="updateTattoo(tattoo)">Atualiazar</button>
                                                </div>
                                            </modal>
                                        </portal>
                                    </div>
                                </carousel>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="my-8"/>
                <div class="mt-10 sm:mt-0">
                    <div class="md:grid md:grid-cols-3 md:gap-6">
                        <div class="md:col-span-1">
                            <div class="px-4 sm:px-0">
                                <h3 class="text-lg font-medium leading-6 text-gray-900">Adição de Tatuagens do Suspeito:</h3>
                            </div>
                        </div>
                        <div class="mt-5 md:mt-0 md:col-span-2">
                            <form @submit.prevent="submitTattoos" class="shadow overflow-hidden sm:rounded-md">
                                <div class="px-4 py-5 bg-white sm:p-6">
                                    <file-pond ref="pondTattoo" :key="suspect.tattoos.length" accepted-file-types="image/*" server="/api/filepond" />
                                </div>
                                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Atualizar
                                    </button>
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
// Import DatePicker
import DatePicker from 'vue2-datepicker';
import 'vue2-datepicker/locale/pt-br';
import 'vue2-datepicker/index.css';


import Bouncer from '~/assets/js/Bouncer';
import carousel from 'vue-owl-carousel';
import vueFilePond, {setOptions} from 'vue-filepond';
import FilepondTranslation from '~/assets/js/FilepoundPtBr';
// // Import plugins
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.esm.js';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.esm.js';

// Import styles
import 'filepond/dist/filepond.min.css';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css';

// Create FilePond component
const FilePond = vueFilePond(FilePondPluginFileValidateType, FilePondPluginImagePreview);

setOptions({ ...FilepondTranslation});

export default {
    layout: 'dashboard',
    data: () => ({
        suspect: {},
        name: '',
        cpf: '',
        date: '',
        feature: "",
        features: [],
        errors: {}
    }),
    watch: {
        '$route.query': '$fetch'
    },
    async fetch() {
        const data = await this.$axios.get(`/api/suspects/${this.$route.params.suspectId}`).then(res => res.data.data).catch(err => {
            this.$swal({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                icon: 'error',
                title: 'Falha ao buscar informações do suspeito!',
            });
            this.$router.push('/usuarios');
        });

        this.suspect = data;
        this.name = data.name;
        this.cpf = data.cpf;
        this.date = new Date(data.birth_date);

    },
    methods: {
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
        submitPhotos: function(){
            this.$axios.post(`/api/suspects/${this.suspect.id}/photos`, {
                photos: this.$refs.pond.getFiles().map(file => file.serverId)
            }).then((response) => {
                this.$swal({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    icon: 'success',
                    title: 'Fotos adicionadas com sucesso!',
                });

                this.suspect = response.data.data;
            }).catch((err) => {
                const resp = err.response;

                this.$swal({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    icon: 'error',
                    title: 'Falha ao adicionar fotos!',
                });

                if(resp.status == 422){
                    this.errors = resp.data.errors;
                }
            });
        },
        deletePhoto: function(photo){
            this.$swal({
                title: 'Tem certeza que deseja remover essa foto do suspeito?',
                icon: 'warning',
                showCloseButton: true,
                showCancelButton: true,
                focusConfirm: false,
                confirmButtonText:'Sim',
                cancelButtonText:'Não',
            }).then(result => {
                if(result.isConfirmed){
                    this.$axios.delete(`/api/suspects/${this.suspect.id}/photos/${photo.id}`).then((response)=>{
                        this.$swal({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            icon: 'success',
                            title: 'Foto deletada com sucesso!',
                        });
                        this.suspect = response.data.data;
                    }).catch(() => {
                        this.$swal({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            icon: 'error',
                            title: 'Falha ao deletar foto!',
                        });
                    });
                }
            })
        },
        submitTattoos: function(){
            this.$axios.post(`/api/suspects/${this.suspect.id}/tattoos`, {
                tattoos: this.$refs.pondTattoo.getFiles().map(file => file.serverId)
            }).then((response) => {
                this.$swal({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    icon: 'success',
                    title: 'Tatuagens adicionadas com sucesso!',
                });
                this.suspect = response.data.data;
            }).catch((err) => {
                const resp = err.response;

                this.$swal({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    icon: 'error',
                    title: 'Falha ao adicionar tatuagens!',
                });

                if(resp.status == 422){
                    this.errors = resp.data.errors;
                }
            });
        },
        openUpdateTattooModal: function(tattoo){
            this.features = tattoo.features.map(feature => feature.name);
            this.$modal.show(`editTattoo${tattoo.id}`);
        },
        removeFeature: function(newFeature){
            this.features = this.features.filter(feature => newFeature !== feature);
        },
        addFeature: function(){
            if(this.feature){
                this.features.push(this.feature);
                this.feature = "";
            }
        },
        updateTattoo: function(tattoo){
            this.$swal({
                title: 'Tem certeza que deseja atualizar essa tatuagem do suspeito?',
                icon: 'warning',
                showCloseButton: true,
                showCancelButton: true,
                focusConfirm: false,
                confirmButtonText:'Sim',
                cancelButtonText:'Não',
            }).then(result => {
                if(result.isConfirmed){
                    this.$axios.patch(`/api/suspects/${this.suspect.id}/tattoos/${tattoo.id}`, {
                        "tattoo_features": this.features
                    }).then((response)=>{
                        this.$swal({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            icon: 'success',
                            title: 'Tatuagem foi atualizada com sucesso!',
                        });
                        this.$modal.hide(`editTattoo${tattoo.id}`);
                        this.$fetch();
                        // this.suspect = response.data.data;
                    }).catch(() => {
                        this.$swal({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            icon: 'error',
                            title: 'Falha ao atualizar tatuagem!',
                        });
                    });
                }
            })
        },
        deleteTattoo: function(tattoo){
            this.$swal({
                title: 'Tem certeza que deseja remover essa tatuagem do suspeito?',
                icon: 'warning',
                showCloseButton: true,
                showCancelButton: true,
                focusConfirm: false,
                confirmButtonText:'Sim',
                cancelButtonText:'Não',
            }).then(result => {
                if(result.isConfirmed){
                    this.$axios.delete(`/api/suspects/${this.suspect.id}/tattoos/${tattoo.id}`).then((response)=>{
                        this.$swal({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            icon: 'success',
                            title: 'Tatuagem foi deletada com sucesso!',
                        });
                        console.log(response.data.data);
                        this.suspect = response.data.data;
                    }).catch(() => {
                        this.$swal({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            icon: 'error',
                            title: 'Falha ao deletar tatuagem!',
                        });
                    });
                }
            })
        },
        submit: function(){
            this.$axios.patch(`/api/suspects/${this.$route.params.suspectId}`, {
                name: this.name,
                cpf: this.cpf,
                birth_date: this.date
            }).then(() => {
                this.$router.push('/suspeitos');
                this.$swal({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    icon: 'success',
                    title: 'Suspeito atualizado com sucesso!',
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
                    title: 'Falha ao atualizar suspeito!',
                });

                if(resp.status == 422){
                    this.errors = resp.data.errors;
                }
            });
        }
    },
    components: { carousel, FilePond, DatePicker }
}
</script>