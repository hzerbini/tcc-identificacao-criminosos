<template>
    <modal :name="name" height="auto"  :scrollable="true" :adaptative="true" @opened="getAlert" @closed="checkIfUpdates">
        <div class="grid place-items-center h-80" v-if="loadingStatus == 'loading'">
            <svg class="animate-spin -ml-1 mr-3 h-1/2 text-gray-900" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
        <p class="py-20 px-4 text-center"v-else-if="loadingStatus == 'error'">Acontenceu algum erro :C</p>
        <div v-else class="pt-8 pb-16 px-16 whitespace-normal">
            <template v-if="editing">
                <div class="text-right mb-8">
                    <button class="text-indigo-600 hover:text-indigo-900 mx-2 font-semibold" @click="() => editing = false">Visualizar</button>
                    <button v-if="fetchedAlert.read_at != null" class="text-indigo-600 hover:text-indigo-900 mx-2 font-semibold" @click="markAsNotRead">Marcar como não lido</button>
                </div>
                <form @submit.prevent="updateAlert">
                    <label class="block text-sm font-medium text-gray-700" :class="(errors.title)?'text-red-700':''">Titulo</label>
                    <input v-model="newTitle" type="text"
                        class="mt-1 py-2 px-1 focus:outline-none focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" />

                    <label class="block text-sm font-medium text-gray-700 mt-8" :class="(errors.message)?'text-red-700':''">Mensagem</label>
                    <textarea name="" id="" cols="30" rows="10"
                        class="mt-1 p-4 focus:outline-none focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                         v-model="newMessage"></textarea>
                    <button class="px-2 py-1 bg-blue-500 rounded-lg text-white font-bold mt-8" type="submit">Atualizar</button>
                </form>
            </template>
            <template v-if="!editing">
                <div class="text-right mb-8">
                    <button v-if="$bouncer.can('update', fetchedAlert)" class="text-indigo-600 hover:text-indigo-900 mx-2 font-semibold" @click="() => editing = true">Editar</button>
                    <button v-if="fetchedAlert.read_at != null" class="text-indigo-600 hover:text-indigo-900 mx-2 font-semibold" @click="markAsNotRead">Marcar como não lido</button>
                </div>
                <h3 class="mb-2 text-2xl text-center text-gray-900">{{fetchedAlert.title}}</h3>
                <hr/>
                <p class="mx-8 text-center text-gray-600 text-lg mt-8">{{fetchedAlert.message}}</p>
            </template>

        </div>
    </modal>
</template>

<script>
export default{
    props: ['name', 'alert'],
    data:() => ({
        fetchedAlert: null,
        loadingStatus: 'loading',
        editing: false,
        newMessage: null,
        newTitle: null,
        errors: {}
    }),
    methods: {
        getAlert() {
            if(this.loadingStatus == 'loading'){
                this.$axios.get(`/api/alerts/${this.alert.id}`, {
                    params: this.$route.query
                }).then(res => {
                    this.fetchedAlert = res.data.data;
                    this.loadingStatus = 'success';
                    this.newMessage = this.fetchedAlert.message;
                    this.newTitle = this.fetchedAlert.title;
                }).catch(err => {
                    this.$swal({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        icon: 'error',
                        title: 'Falha ao buscar alerta!',
                    });

                    this.loadingStatus = 'error';
                });
            }
        },
        markAsNotRead(){
            this.$axios.patch(`/api/alerts/${this.alert.id}`, {
                isnt_read: true,
            }).then(res => {
                this.fetchedAlert = res.data.data;

                this.$swal({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    icon: 'success',
                    title: 'Alerta marcado como não lido com sucesso!',
                });
                
                this.$emit('update-alert');

            }).catch(err => {
                const resp = err.response;
                this.$swal({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    icon: 'error',
                    title: 'Falha ao marcar como não lido!',
                });
            });
        },
        updateAlert() {
            console.log('entrei');
            this.$axios.patch(`/api/alerts/${this.alert.id}`, {
                title: this.newTitle,
                message: this.newMessage,
            }).then(res => {
                this.fetchedAlert = res.data.data;
                this.newMessage = this.fetchedAlert.message;
                this.newTitle = this.fetchedAlert.title;

                this.$swal({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    icon: 'success',
                    title: 'Alerta atualizado com sucesso!',
                });
                
            }).catch(err => {
                const resp = err.response;
                this.$swal({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    icon: 'error',
                    title: 'Falha ao atualizar alerta!',
                });

                if(resp.status == 422){
                    this.errors = resp.data.errors;
                }
            });
        },
        checkIfUpdates(){
            if(
                this.alert.read_at != this.fetchedAlert.read_at ||
                this.alert.title != this.fetchedAlert.title ||
                this.alert.message != this.fetchedAlert.message
            ){
                this.$emit('update-alert');
            }

            this.$emit('closed');
        }
    },
}
</script>