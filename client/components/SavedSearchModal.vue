<template>
    <modal :name="name" height="auto"  :scrollable="true" :adaptative="true">
        <div class="pt-8 pb-16 px-16 whitespace-normal">
                <h3 class="mb-2 text-2xl text-center text-gray-900">{{search.name}}</h3>
                <hr/>
                <p class="mx-8 text-gray-600 text-lg mt-8"><span class="text-gray-800">Descrição:</span> {{search.description}}</p>
                <p class="mx-8 text-gray-800 text-lg mt-8" >Parâmetros:</p>
                <ul class="mx-8 text-gray-600 text-lg list-disc ml-14" v-for="feature in search.tattoo_features">
                    <li >{{ feature.name }}</li>
                </ul>
        </div>
    </modal>
</template>

<script>
export default{
    props: ['name', 'search']
}
</script>