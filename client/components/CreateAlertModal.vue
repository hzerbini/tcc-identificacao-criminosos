<template>
    <modal :name="name" height="auto"  :scrollable="true" :adaptative="true">
        <div class="p-16 whitespace-normal">
            <form @submit.prevent="createAlert">
                <label class="block text-sm font-medium text-gray-700" :class="(errors.title)?'text-red-700':''">Titulo</label>
                <input v-model="title" type="text"
                    class="mt-1 py-2 px-1 focus:outline-none focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" />

                <label class="block text-sm font-medium text-gray-700 mt-8" :class="(errors.message)?'text-red-700':''">Mensagem</label>
                <textarea name="" id="" cols="30" rows="10"
                    class="mt-1 p-4 focus:outline-none focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                        v-model="message"></textarea>
                <button class="px-2 py-1 bg-blue-500 rounded-lg text-white font-bold mt-8" type="submit">Criar</button>
            </form>
        </div>
    </modal>
</template>

<script>
export default{
    props: ['name'],
    data:() => ({
        message: '',
        title: '',
        errors: {}
    }),
    methods: {
        createAlert() {
            this.$axios.post(`/api/users/${this.$route.params.userId}/alerts`, {
                title: this.title,
                message: this.message,
            }).then(res => {

                this.$swal({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    icon: 'success',
                    title: 'Alerta criado com sucesso!',
                });

                this.title = '';
                this.message = '';
                this.errors = {};
                this.$modal.hide(this.name);

            }).catch(err => {
                const resp = err.response;
                this.$swal({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    icon: 'error',
                    title: 'Falha ao criar alerta!',
                });

                if(resp.status == 422){
                    this.errors = resp.data.errors;
                }
            });
        }
    },
}
</script>