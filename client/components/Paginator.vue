<template>
  <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
    <div class="flex-1 flex justify-between sm:hidden">
      <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
        Anterior
      </a>
      <a href="#" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
        Próximo
      </a>
    </div>
    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
      <div>
        <p class="text-sm text-gray-700">
          Mostrando
          {{ ' ' }}
          <span class="font-medium">{{meta.from}}</span>
          {{ ' ' }}
          até
          {{ ' ' }}
          <span class="font-medium">{{ meta.to }}</span>
          {{ ' ' }}
          de
          {{ ' ' }}
          <span class="font-medium">{{ meta.total }}</span>
          {{ ' ' }}
          resultados
        </p>
      </div>
      <div>
        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
          <NuxtLink :to="{path: $route.path, query: {...$route.query, ...{page: (meta.current_page > 1)?meta.current_page - 1:meta.current_page }}}" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
            <span class="sr-only">Anterior</span>
            <ChevronLeftIcon class="h-5 w-5" aria-hidden="true" />
          </NuxtLink>
          <!-- Current: "z-10 bg-indigo-50 border-indigo-500 text-indigo-600", Default: "bg-white border-gray-300 text-gray-500 hover:bg-gray-50" -->
          <template v-for="link in meta.links">
            <NuxtLink v-if="(!isNaN(link.label))" :to="{path: $route.path, query: {...$route.query, ...{page: link.label}}}" class="relative inline-flex items-center px-4 py-2 border text-sm font-medium" :class="(link.active)?'z-10 bg-indigo-50 border-indigo-500 text-indigo-600': 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'">
                {{ link.label }}
            </NuxtLink>
            <span v-else-if="(link.label === '...')" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">
                ...
            </span>
          </template>
          <NuxtLink :to="{path: $route.path, query: {...$route.query, ...{page: (meta.current_page < meta.last_page)?meta.current_page + 1:meta.current_page }}}" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
            <span class="sr-only">Próximo</span>
            <ChevronRightIcon class="h-5 w-5" aria-hidden="true" />
          </NuxtLink>
        </nav>
      </div>
    </div>
  </div>
</template>

<script>
export default{
    props: ['meta'],
}
</script>