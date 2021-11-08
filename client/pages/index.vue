<template>
  <div>
    <div class="bg-indigo-600">
      <div class="p-6 flex justify-center">
            <div class="relative ml-7 mr-12 w-full">
              <input type="text" v-model="search" @focus="openFeatures" @blur="closeFeatures" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full h-full sm:text-sm border-gray-300 rounded-md" />
                <div v-show="isOpen">
                    <ul v-if="tattoo_features.length == 0 && search.length > 3" class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-56 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm">
                        <li class="text-gray-900 cursor-default select-none relative" role="option">
                            {{ (loadingFeatures)?"carregando...":"Nenhum filtro corresponde a sua pesquisa." }}
                        </li>
                    </ul>
                    <ul v-else-if="tattoo_features. length > 0" class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-56 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm" tabindex="-1" role="listbox" aria-labelledby="listbox-label" aria-activedescendant="listbox-option-3">
                    <!--
                        Select option, manage highlight styles based on mouseenter/mouseleave and keyboard navigation.

                        Highlighted: "text-white bg-indigo-600", Not Highlighted: "text-gray-900"
                    -->
                        <li v-for="feature in tattoo_features" class="text-gray-900 cursor-default select-none relative" id="listbox-option-0" role="option">
                        <button class="w-full h-full py-2 pl-3 pr-9 hover:bg-gray-200 focus:outline-none" @click="addFeature(feature)">
                            <div class="flex items-center">
                            <!-- Selected: "font-semibold", Not Selected: "font-normal" -->
                            <span class="font-normal ml-3 block truncate hover:font-semibold">
                                {{feature}}
                            </span>
                            </div>
                        </button>
                        </li>

                    <!-- More items... -->
                    </ul>
                </div>
            </div>
            <button class="bg-indigo-800 p-2 px-4 font-semibold text-lg text-white ml-4 rounded-md" @click="saveSearch">+</button>
      </div>
      <div class="flex flex-wrap gap-4 px-2 py-1 text-white">
          <button v-for="feature in features" class="focus:outline-none"  @click="() => {features = features.filter(f => f !== feature)}"> {{ feature }} x</button>
      </div>
    </div>
    <main>
      <div class="grid place-items-center h-80" v-if="$fetchState.pending">
        <svg class="animate-spin -ml-1 mr-3 h-1/2 text-gray-900" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
      </div>
      <div v-else>
        <div class="grid place-items-center my-12">
        
          <carousel  :nav="false" :items="1" class="w-64 h-64 md:w-96 md:h-96">
            <div v-for="tattoo in tattoos" class="relative">
              <NuxtLink class="absolute top-0 right-0 bg-red-500 rounded-md" :to="`/suspeitos/${tattoo.suspect_id}`">Suspeito</NuxtLink>
              <img :src="tattoo.path" class="h-full w-full object-cover">
              <div class="absolute flex bottom-0 left-0 flex flex-wrap w-64">
                  <p class="my-2 mx-2 bg-gray-200 rounded-sm px-2 py-1" v-for="feature in tattoo.features">{{ feature.name }}</p>
              </div>
            </div>
          </carousel>
        </div>
        <Paginator class="absolute bottom-0 w-full z-20" :meta="meta"/>
      </div>
    </main>

  </div>
</template>

<script>
import carousel from 'vue-owl-carousel'

export default {
  layout: 'dashboard',
  data: () => ({
    'search': '',
    'features': [],
    'tattoo_features': [],
    'isOpen': false,
    'tattoos': [],
    'meta': null,
    'loadingFeatures' : false,
  }),
  watch: {
    '$route.query': '$fetch',
    features: '$fetch',
    search: function (search){
      if (search.length >= 3) {
        this.loadingFeatures = true;
        if (this.timerId) {
            clearTimeout(this.timerId);
        }

        this.timerId = setTimeout(() => {
          this.searchTattooFeatures();
        }, 500)
      } else {
        this.tattoo_features = [];
      }
    }
  },
  async fetch() {
      const data = await this.$axios.get(`/api/tattoos`, {
        params: {
          ...this.$route.query,
          ...{features: this.features}
        }
      }).then(res => res.data).catch(err => {
          this.$swal({
              toast: true,
              position: 'top-end',
              showConfirmButton: false,
              timer: 3000,
              timerProgressBar: true,
              icon: 'error',
              title: 'Falha ao buscar tatuagens!',
          });
          this.$router.push('/');
      });
      this.tattoos = data.data;
      this.meta = data.meta;
  },
  methods: {
    openFeatures(){
      if(this.timerDropdown){
        clearTimeout(this.timerDropdown);
      }
      this.isOpen = true
    },
    closeFeatures(){
      this.timerDropdown = setTimeout(() => {this.isOpen = false;}, 1000);
    },
    saveSearch(){
        this.$swal({
            title: 'Salvar Pesquisa',
            html: `<input type="text" id="saveSearchNameInput" class="swal2-input" placeholder="Nome">
                <input type="text" id="saveSearchDescriptionInput" class="swal2-input" placeholder="Descrição">`,
            confirmButtonText: 'Salvar',
            focusConfirm: false,
            preConfirm: () => {
                const name = this.$swal.getPopup().querySelector('#saveSearchNameInput').value
                const description = this.$swal.getPopup().querySelector('#saveSearchDescriptionInput').value
                if (!description || !name) {
                    this.$swal.showValidationMessage(`Por favor, insira nome e descrição para pesquisa.`);
                }
                if(this.features.length <= 0){
                    this.$swal.showValidationMessage(`Você não pode salvar uma pesquisa sem nenhum parâmetro.`);
                }
                return { name, description}
            }
        }).then((result) => {
            this.$axios.post(`api/users/${this.$auth.user.id}/saved-suspect-searches`, {
                name: result.value.name,
                description: result.value.description,
                tattooFeatures: this.features
            }).then(response => {
                this.$swal({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    icon: 'success',
                    title: 'Pesquisa salva com sucesso!',
                });
            }).catch(err => {
                const response = err.response;
                if(response.status == 422){
                    this.$swal({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        icon: 'error',
                        title: 'Erro ao salvar pesquisa!',
                    });
                }
            });
        });
    },
    searchTattooFeatures(){
        this.loadingFeatures = true;
      this.$axios.get('api/tattoo-features', {
        params: {
          search: this.search
        }
      }).then(response => {
        this.tattoo_features = response.data.data.map(feature => feature.name);
        this.loadingFeatures = false;
      });
    },
    addFeature(feature){
      this.features = this.features.filter(f => f != feature);
      this.features.push(feature);
    }
  },
  components: { carousel }
}
</script>
