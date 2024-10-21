<template>
  <div>
    <Head :title="`${form.name}`" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/orders">Заказы</Link>
      <icon name="cheveron-right" class="inline w-6 h-6 ml-1 fill-gray-400" />
      <span>{{ form.name }}</span>
    </h1>
    <trashed-message v-if="order.deleted_at" class="mb-6" @restore="restore">Этот заказ удален.</trashed-message>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-div v-model="form.name" class="pb-8 pr-6 w-full lg:w-1/2" label="Имя" />
          <text-div v-model="form.customer_name" class="pb-8 pr-6 w-full lg:w-1/2" label="Заказчик" :link="form.customer_link" />
          <text-div v-model="form.due_date" class="pb-8 pr-6 w-full lg:w-1/2" label="Крайняя дата" />
          <text-div v-model="form.weight" class="pb-8 pr-6 w-full lg:w-1/2" label="Общий вес, т" />
        </div>
        <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
          <Link class="btn-default mr-auto" href="/orders/">Назад</Link>
          <button v-if="!order.deleted_at" class="text-red-600 hover:underline mr-6" tabindex="-1" type="button" @click="destroy">Удалить</button>
          <Link class="btn-indigo" :href="`/orders/${order.id}/edit`">Изменить</Link>
        </div>
    </div>
    <h2 class="mt-12 mb-4 text-2xl font-bold">Отправочные марки</h2>
    <div class="flex items-center justify-between mb-6">
      <search-filter v-model="table.search" class="mr-4 w-full max-w-md" @reset="reset">
        <label class="block text-gray-700">Показывать:</label>
        <select v-model="table.trashed" class="form-select mt-1 w-full">
          <option :value="null">По умолчанию</option>
          <option value="with">С удаленными</option>
          <option value="only">Только удаленные</option>
        </select>
      </search-filter>
      <Link class="btn-indigo" :href="`/structures/create/${order.id}`">
        <span>Добавить</span>
        <span class="hidden md:inline">&nbsp;ОМ</span>
      </Link>
    </div>
    <div class="mt-6 bg-white rounded shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold bg-indigo-100">
          <th class="px-6 py-4">Название</th>
          <th class="px-6 py-4">Количество</th>
          <th class="px-6 py-4" colspan="2">Вес одной ОМ, т</th>
        </tr>
        <tr v-for="structure in order.structures.data" :key="structure.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
          <td class="border-t">
            <Link class="flex items-center px-6 py-4 focus:text-indigo-500" :href="`/structures/${structure.id}`">
              {{ structure.name }}
              <icon v-if="structure.deleted_at" name="trash" class="shrink-0 ml-2 w-3 h-3 fill-gray-400" />
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/structures/${structure.id}`" tabindex="-1">
              {{ structure.quantity }}
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/structures/${structure.id}`" tabindex="-1">
              {{ (structure.weight / 1000000).toFixed(3) }}
            </Link>
          </td>
          <td class="w-px border-t">
            <Link class="flex items-center px-4" :href="`/structures/${structure.id}`" tabindex="-1">
              <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
            </Link>
          </td>
        </tr>
        <tr v-if="order.structures?.data?.length === 0">
          <td class="px-6 py-4 border-t" colspan="4">Отправочные марки не найдены.</td>
        </tr>
      </table>
    </div>
    <pagination class="mt-6" :links="order.structures.links" />
    <h2 class="mt-12 mb-4 text-2xl font-bold">Детали</h2>
    <div class="flex items-center justify-between mb-6">
      <!-- <search-filter v-model="table.search" class="mr-4 w-full max-w-md" @reset="reset">
        <label class="block text-gray-700">Показывать:</label>
        <select v-model="table.trashed" class="form-select mt-1 w-full">
          <option :value="null">По умолчанию</option>
          <option value="with">С удаленными</option>
          <option value="only">Только удаленные</option>
        </select>
      </search-filter> -->
      <data value=""></data>
      <Link class="btn-indigo" :href="`/parts/create/${order.id}`">
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
        <tr v-for="part in order.parts.data" :key="part.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
          <td class="border-t">
            <Link class="flex items-center px-6 py-4 focus:text-indigo-500" :href="`/parts/${part.id}`">
              {{ part.position }}
              <icon v-if="part.deleted_at" name="trash" class="shrink-0 ml-2 w-3 h-3 fill-gray-400" />
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/parts/${part.id}`" tabindex="-1">
              {{ part.quantity }}
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/parts/${part.id}`" tabindex="-1">
              {{ (part.weight / 1000).toFixed(1) }}
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
        <tr v-if="order.parts?.data?.length === 0">
          <td class="px-6 py-4 border-t" colspan="4">Детали не найдены.</td>
        </tr>
      </table>
    </div>
    <pagination class="mt-6" :links="order.parts.links" />
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
    order: Object,
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        name: this.order.name,
        customer_link: "/customers/" + this.order.customer.id,
        customer_name: this.order.customer.name,
        due_date: this.order.due_date,
        weight: String((this.order.weight / 1000000).toFixed(3)),
      }),
      table: {
        search: this.filters.search,
        trashed: this.filters.trashed,
      },
    }
  },
  methods: {
    update() {
      this.form.put(`/orders/${this.order.id}`)
    },
    destroy() {
      if (confirm('Вы уверены, что хотите удалить этот заказ?')) {
        this.$inertia.delete(`/orders/${this.order.id}`)
      }
    },
    restore() {
      if (confirm('Вы уверены, что хотите восстановить этот заказ?')) {
        this.$inertia.put(`/orders/${this.order.id}/restore`)
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
        this.$inertia.get(`/orders/${this.order.id}`, pickBy(this.table), { preserveState: true })
      }, 150),
    },
  },
}
</script>
