<template>
  <div>
    <nav class="bg-gray-800">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <img class="h-8 w-8" src="https://tailwindui.com/img/logos/workflow-mark-indigo-500.svg" alt="Workflow">
            </div>
            <div class="hidden md:block">
              <div class="ml-10 flex items-baseline space-x-4">
                <!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->
                <NuxtLink to="/" class="px-3 py-2 rounded-md text-sm font-medium" :class="(checkMenuOptionActive('index')? 'bg-gray-900 text-white': 'text-gray-300 hover:bg-gray-700 hover:text-white')">Dashboard</NuxtLink>

                <NuxtLink to="/usuarios" v-if="bouncer().can('viewAll', 'App\\Models\\User')" class="px-3 py-2 rounded-md text-sm font-medium" :class="(checkMenuOptionActive('usuarios')? 'bg-gray-900 text-white': 'text-gray-300 hover:bg-gray-700 hover:text-white')">Usuários</NuxtLink>

                <NuxtLink to="/suspeitos" v-if="bouncer().can('viewAll', 'App\\Models\\Suspect')" class="px-3 py-2 rounded-md text-sm font-medium" :class="(checkMenuOptionActive('suspeitos')? 'bg-gray-900 text-white': 'text-gray-300 hover:bg-gray-700 hover:text-white')">Suspeitos</NuxtLink>

                <NuxtLink to="/" class="px-3 py-2 rounded-md text-sm font-medium" :class="(checkMenuOptionActive('')? 'bg-gray-900 text-white': 'text-gray-300 hover:bg-gray-700 hover:text-white')">Projects</NuxtLink>

                <NuxtLink to="/" class="px-3 py-2 rounded-md text-sm font-medium" :class="(checkMenuOptionActive('')? 'bg-gray-900 text-white': 'text-gray-300 hover:bg-gray-700 hover:text-white')">Calendar</NuxtLink>

                <NuxtLink to="/" class="px-3 py-2 rounded-md text-sm font-medium" :class="(checkMenuOptionActive('')? 'bg-gray-900 text-white': 'text-gray-300 hover:bg-gray-700 hover:text-white')">Reports</NuxtLink>

              </div>
            </div>
          </div>
          <div class="hidden md:block">
            <div class="ml-4 flex items-center md:ml-6">
              <!-- Profile dropdown -->
              <div class="ml-3 relative">
                <div>
                  <button @click="isOpen = !isOpen" type="button" class="max-w-xs text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-900 hover:text-white focus:bg-gray-900 focus:text-white focus:outline-none" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                    <span class="sr-only">Open user menu</span>
                    <span>{{ $auth.user.email }}</span>
                  </button>
                </div>

                <!--
                  Dropdown menu, show/hide based on menu state.

                  Entering: "transition ease-out duration-100"
                    From: "transform opacity-0 scale-95"
                    To: "transform opacity-100 scale-100"
                  Leaving: "transition ease-in duration-75"
                    From: "transform opacity-100 scale-100"
                    To: "transform opacity-0 scale-95"
                -->
                <div v-if="isOpen" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">
                  <!-- Active: "bg-gray-100", Not Active: "" -->
                  <NuxtLink :to="`/usuarios/${this.$auth.user.id}`"><a class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="user-menu-item-0">Seu Perfil</a></NuxtLink>

                  <button @click="logout" class="block px-4 py-2 text-sm text-gray-700 focus:outline-none" role="menuitem" tabindex="-1" id="user-menu-item-2">Logout</button>
                </div>
              </div>
            </div>
          </div>
          <div class="-mr-2 flex md:hidden">
            <!-- Mobile menu button -->
            <button @click="isOpen = !isOpen" type="button" class="bg-gray-800 inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white" aria-controls="mobile-menu" aria-expanded="false">
              <span class="sr-only">Open main menu</span>
              <!--
                Heroicon name: outline/menu

                Menu open: "hidden", Menu closed: "block"
              -->
              <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
              </svg>
              <!--
                Heroicon name: outline/x

                Menu open: "block", Menu closed: "hidden"
              -->
              <svg class="hidden h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>
      </div>

      <!-- Mobile menu, show/hide based on menu state. -->
      <div v-if="isOpen" class="md:hidden" id="mobile-menu">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
          <!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->
          <NuxtLink to="/" class="block px-3 py-2 rounded-md text-base font-medium" :class="(checkMenuOptionActive('index')? 'bg-gray-900 text-white': 'text-gray-300 hover:bg-gray-700 hover:text-white')">Dashboard</NuxtLink>

          <NuxtLink to="/usuarios" v-if="bouncer().can('viewAll', 'App\\Models\\User')" class="block px-3 py-2 rounded-md text-base font-medium" :class="(checkMenuOptionActive('usuarios')? 'bg-gray-900 text-white': 'text-gray-300 hover:bg-gray-700 hover:text-white')"> Usuários </NuxtLink>

          <NuxtLink to="/suspeitos" v-if="bouncer().can('viewAll', 'App\\Models\\Suspect')" class="block px-3 py-2 rounded-md text-base font-medium" :class="(checkMenuOptionActive('suspeitos')? 'bg-gray-900 text-white': 'text-gray-300 hover:bg-gray-700 hover:text-white')"> Usuários </NuxtLink>

          <a href="#" class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Projects</a>

          <a href="#" class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Calendar</a>

          <a href="#" class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Reports</a>
        </div>
        <div class="pt-4 pb-3 border-t border-gray-700">
          <div class="flex items-center px-5">
            <div>
              <div class="text-base font-medium leading-none text-white">{{$auth.user.name}}</div>
              <div class="text-sm font-medium leading-none text-gray-400">{{$auth.user.email}}</div>
            </div>
            <NuxtLink :to="`/usuarios/${this.$auth.user.id}`"><a href="#" class="block px-3 py-2 rounded-md text-base font-medium text-gray-400 hover:text-white hover:bg-gray-700">Seu Perfil</a></NuxtLink>

            <button @click="logout" href="#" class="block px-3 py-2 rounded-md text-base font-medium text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none">Logout</button>
          </div>
        </div>
      </div>
    </nav>

    <Nuxt/>
  </div>
</template>

<script>
import Bouncer from '~/assets/js/Bouncer';

export default{
  data: () => ({
    isOpen: false,
  }),
  methods: {
    bouncer(){
      return new Bouncer(this.$auth.user);
    },
    checkMenuOptionActive(option){
      return (this.$route.name?.split('-')[0] == option);
    },
    logout(){
      this.$auth.logout()
      .then(() => {
          this.$router.push('/login');
          this.$swal({
              toast: true,
              position: 'top-end',
              showConfirmButton: false,
              timer: 3000,
              timerProgressBar: true,
              icon: 'success',
              title: 'Logout foi realizado com sucesso!',
          });
      }).catch(err => {
          this.$swal({
              toast: true,
              position: 'top-end',
              showConfirmButton: false,
              timer: 3000,
              timerProgressBar: true,
              icon: 'error',
              title: 'Aconteceu algum erro ao deslogar usuário!',
          });
      });
    }
  }
}
</script>