<template>
  <div>
    <Head :title="`${form.name}`" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/structures">Отправочные марки</Link>
      <icon name="cheveron-right" class="inline w-6 h-6 ml-1 fill-gray-400" />
      <span>{{ form.name }}</span>
    </h1>
    <trashed-message v-if="structure.deleted_at" class="mb-6" @restore="restore">Эта отправочная марка удалена.</trashed-message>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-div v-model="form.name" class="pb-8 pr-6 w-full lg:w-1/2" label="Название" />
          <text-div v-model="form.quantity" class="pb-8 pr-6 w-full lg:w-1/2" label="Количество в заказе" />
          <text-div v-model="form.order_name" class="pb-8 pr-6 w-full lg:w-1/2" label="Заказ" :link="form.order_link" />
          <text-div v-model="form.weight" class="pb-8 pr-6 w-full lg:w-1/2" label="Вес ОМ, т" />
        </div>
        <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
          <Link class="btn-default mr-auto" href="/structures/">Назад</Link>
          <button v-if="!structure.deleted_at" class="text-red-600 hover:underline mr-6" tabindex="-1" type="button" @click="destroy">Удалить</button>
          <Link class="btn-indigo" :href="`/structures/${structure.id}/edit`">Изменить</Link>
        </div>
    </div>
    <h2 class="mt-12 mb-4 text-2xl font-bold">Детали</h2>
    <div class="flex items-center justify-between mb-6">
      <search-filter v-model="table.search" class="mr-4 w-full max-w-md" @reset="reset">
        <label class="block text-gray-700">Показывать:</label>
        <select v-model="table.trashed" class="form-select mt-1 w-full">
          <option :value="null">По умолчанию</option>
          <option value="with">С удаленными</option>
          <option value="only">Только удаленные</option>
        </select>
      </search-filter>
      <Link class="btn-indigo" :href="`/parts/create/${structure.order.id}`">
        <span>Добавить</span>
        <span class="hidden md:inline">&nbsp;деталь</span>
      </Link>
    </div>
    <div class="mt-6 bg-white rounded shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold bg-indigo-100">
          <th class="px-6 py-4">Позиция</th>
          <th class="px-6 py-4">Количество</th>
          <th class="px-6 py-4">Вес одной детали, кг</th>
          <th class="px-6 py-4" colspan="2">Профиль</th>
        </tr>
        <tr v-for="part in structure.parts.data" :key="part.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
          <td class="border-t">
            <Link class="flex items-center px-6 py-4 focus:text-indigo-500" :href="`/parts/${part.id}`">
              {{ part.position }}
              <icon v-if="part.deleted_at" name="trash" class="shrink-0 ml-2 w-3 h-3 fill-gray-400" />
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/parts/${part.id}`" tabindex="-1">
              {{ part.pivot.quantity }}
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/parts/${part.id}`" tabindex="-1">
              {{ part.weight / 1000 }}
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/parts/${part.id}`" tabindex="-1">
              {{ part.profile }}
            </Link>
          </td>
          <td class="w-px border-t">
            <Link class="flex items-center px-4" :href="`/parts/${part.id}`" tabindex="-1">
              <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
            </Link>
          </td>
        </tr>
        <tr v-if="structure.parts.data != null && structure.parts.data.length === 0">
          <td class="px-6 py-4 border-t" colspan="4">Отправочные марки не найдены.</td>
        </tr>
      </table>
    </div>
    <pagination class="mt-6" :links="structure.parts.links" />
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import mapValues from 'lodash/mapValues'
import pickBy from 'lodash/pickBy'
import throttle from 'lodash/throttle'
import Icon from '@/Shared/Icon.vue'
import Layout from '@/Shared/Layout.vue'
import TextDiv from '@/Shared/TextDiv.vue'
import SelectInput from '@/Shared/SelectInput.vue'
import LoadingButton from '@/Shared/LoadingButton.vue'
import Pagination from '@/Shared/Pagination.vue'
import SearchFilter from '@/Shared/SearchFilter.vue'
import TrashedMessage from '@/Shared/TrashedMessage.vue'

export default {
  components: {
    Head,
    Icon,
    Link,
    LoadingButton,
    Pagination,
    SearchFilter,
    SelectInput,
    TextDiv,
    TrashedMessage,
  },
  layout: Layout,
  props: {
    filters: Object,
    structure: Object,
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        name: this.structure.name,
        order_link: `/orders/${this.structure.order.id}`,
        order_name: this.structure.order.name,
        quantity: String(this.structure.quantity),
        weight: (this.structure.weight / 1000000).toFixed(3),
      }),
      table: {
        search: this.filters.search,
        trashed: this.filters.trashed,
      },
    }
  },
  methods: {
    update() {
      this.form.put(`/structures/${this.structure.id}`)
    },
    destroy() {
      if (confirm('Вы уверены, что хотите удалить эту отправочную марку?')) {
        this.$inertia.delete(`/structures/${this.structure.id}`)
      }
    },
    restore() {
      if (confirm('Вы уверены, что хотите восстановить эту отправочную марку?')) {
        this.$inertia.put(`/structures/${this.structure.id}/restore`)
      }
    },
    reset() {
      this.table = mapValues(this.table, () => null)
    },
  },
  watch: {
    table: {
      deep: true,
      handler: throttle(function () {
        this.$inertia.get(`/parts/${this.structure.order.id}`, pickBy(this.table), { preserveState: true })
      }, 150),
    },
  },
}
</script>
