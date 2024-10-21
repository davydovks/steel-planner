<template>
  <div>
    <Head title="Детали" />
    <h1 class="mb-8 text-3xl font-bold">Детали</h1>
    <div class="flex items-center justify-between mb-6">
      <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
        <label class="block text-gray-700">Показывать:</label>
        <select v-model="form.trashed" class="form-select mt-1 w-full">
          <option :value="null">По умолчанию</option>
          <option value="with">С удаленными</option>
          <option value="only">Только удаленные</option>
        </select>
      </search-filter>
      <Link class="btn-indigo" href="/parts/create">
        <span>Добавить</span>
        <span class="hidden md:inline">&nbsp;деталь</span>
      </Link>
    </div>
    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold bg-indigo-100">
          <th class="px-6 py-4">Заказ</th>
          <th class="px-6 py-4 text-center">Позиция</th>
          <th class="px-6 py-4 text-center">Штук в заказе</th>
          <th class="px-6 py-4 text-center">Вес детали, кг</th>
          <th class="px-6 py-4 text-center">Профиль</th>
          <th class="px-6 py-4" colspan="2">Изготовлено</th>
        </tr>
        <tr v-for="part in parts.data" :key="part.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
          <td class="border-t">
            <Link class="flex items-center px-6 py-4 focus:text-indigo-500" :href="`/parts/${part.id}`">
              {{ part.order.name }}
              <icon v-if="part.order.deleted_at" name="trash" class="shrink-0 ml-2 w-3 h-3 fill-gray-400" />
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4 justify-center" :href="`/parts/${part.id}`">
              {{ part.position }}
              <icon v-if="part.deleted_at" name="trash" class="shrink-0 ml-2 w-3 h-3 fill-gray-400" />
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4 justify-center" :href="`/parts/${part.id}`" tabindex="-1">
              {{ part.quantity }}
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4 justify-center" :href="`/parts/${part.id}`" tabindex="-1">
              {{ part.weight / 1000 }}
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4 justify-center" :href="`/parts/${part.id}`" tabindex="-1">
              {{ part.profile }}
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4 justify-center" :href="`/parts/${part.id}`" tabindex="-1">
              0
            </Link>
          </td>
          <td class="w-px border-t">
            <Link class="flex items-center px-4" :href="`/parts/${part.id}`" tabindex="-1">
              <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
            </Link>
          </td>
        </tr>
        <tr v-if="parts.data.length === 0">
          <td class="px-6 py-4 border-t" colspan="4">Детали не найдены</td>
        </tr>
      </table>
    </div>
    <pagination class="mt-6" :links="parts.links" />
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Icon from '@/Shared/Icon.vue'
import pickBy from 'lodash/pickBy'
import Layout from '@/Shared/Layout.vue'
import throttle from 'lodash/throttle'
import mapValues from 'lodash/mapValues'
import Pagination from '@/Shared/Pagination.vue'
import SearchFilter from '@/Shared/SearchFilter.vue'

export default {
  components: {
    Head,
    Icon,
    Link,
    Pagination,
    SearchFilter,
  },
  layout: Layout,
  props: {
    filters: Object,
    parts: Object,
  },
  data() {
    return {
      form: {
        search: this.filters.search,
        trashed: this.filters.trashed,
      },
    }
  },
  watch: {
    form: {
      deep: true,
      handler: throttle(function () {
        this.$inertia.get('/parts', pickBy(this.form), { preserveState: true })
      }, 150),
    },
  },
  methods: {
    reset() {
      this.form = mapValues(this.form, () => null)
    },
  },
}
</script>
