<template>
  <div>
    <Head title="Показатели" />
    <h1 class="mb-8 text-3xl font-bold">Показатели</h1>
    <p class="mb-8 leading-normal">На эту страницу будут добавлены ключевые показатели в виде графиков и таблиц.</p>
  </div>
</template>

<script>
import { Head } from '@inertiajs/vue3'
import Layout from '@/Shared/Layout.vue'

export default {
  components: {
    Head,
  },
  layout: Layout,
}
</script>
