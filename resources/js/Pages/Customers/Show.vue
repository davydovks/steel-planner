<template>
  <div>
    <Head :title="`${form.name}`" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/customers">Клиенты</Link>
      <icon name="cheveron-right" class="inline w-6 h-6 ml-1 fill-gray-400" />
      <span>{{ form.name }}</span>
    </h1>
    <trashed-message v-if="customer.deleted_at" class="mb-6" @restore="restore">Этот клиент удален.</trashed-message>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-div v-model="form.name" class="pb-8 pr-6 w-full lg:w-1/2" label="Имя" />
          <text-div v-model="form.address" class="pb-8 pr-6 w-full lg:w-1/2" label="Адрес" />
          <text-div v-model="form.type" class="pb-8 pr-6 w-full lg:w-1/2" label="Тип" />
          <text-div v-model="form.TIN" class="pb-8 pr-6 w-full lg:w-1/2" label="ИНН" />
        </div>
        <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
          <Link class="btn-default mr-auto" href="/customers/">Назад</Link>
          <button v-if="!customer.deleted_at" class="text-red-600 hover:underline mr-6" tabindex="-1" type="button" @click="destroy">Удалить</button>
          <Link class="btn-indigo" :href="`/customers/${customer.id}/edit`">Изменить</Link>
        </div>
    </div>
    <h2 class="mt-12 mb-4 text-2xl font-bold">Заказы</h2>
    <div class="flex items-center justify-between mb-6">
      <search-filter v-model="table.search" class="mr-4 w-full max-w-md" @reset="reset">
        <label class="block text-gray-700">Показывать:</label>
        <select v-model="table.trashed" class="form-select mt-1 w-full">
          <option :value="null">По умолчанию</option>
          <option value="with">С удаленными</option>
          <option value="only">Только удаленные</option>
        </select>
      </search-filter>
      <Link class="btn-indigo" :href="`/orders/create/${customer.id}`">
        <span>Добавить</span>
        <span class="hidden md:inline">&nbsp;заказ</span>
      </Link>
    </div>
    <div class="mt-6 bg-white rounded shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold bg-indigo-100">
          <th class="px-6 py-4">Название</th>
          <th class="px-6 py-4">Вес заказа, т</th>
          <th class="px-6 py-4">Изготовлено, т</th>
          <th class="px-6 py-4" colspan="2">Процент готовности</th>
        </tr>
        <tr v-for="order in customer.orders.data" :key="order.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
          <td class="border-t">
            <Link class="flex items-center px-6 py-4 focus:text-indigo-500" :href="`/orders/${order.id}`">
              {{ order.name }}
              <icon v-if="order.deleted_at" name="trash" class="shrink-0 ml-2 w-3 h-3 fill-gray-400" />
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/orders/${order.id}`" tabindex="-1">
              {{ (order.weight / 1000000).toFixed(1) }}
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/orders/${order.id}`" tabindex="-1">
              {{ order.completed_weight }}
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/orders/${order.id}`" tabindex="-1">
              {{ order.percent + '%' }}
            </Link>
          </td>
          <td class="w-px border-t">
            <Link class="flex items-center px-4" :href="`/orders/${order.id}`" tabindex="-1">
              <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
            </Link>
          </td>
        </tr>
        <tr v-if="customer.orders?.data?.length === 0">
          <td class="px-6 py-4 border-t" colspan="4">Заказы не найдены.</td>
        </tr>
      </table>
    </div>
    <pagination class="mt-6" :links="customer.orders.links" />
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
import LoadingButton from '@/Shared/LoadingButton.vue'
import SelectInput from '@/Shared/SelectInput.vue'
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
    customer: Object,
    filters: Object,
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        name: this.customer.name,
        address: this.customer.address,
        type: this.customer.type,
        TIN: this.customer.TIN,
      }),
      table: {
        search: this.filters.search,
        trashed: this.filters.trashed,
      },
    }
  },
  methods: {
    update() {
      this.form.put(`/customers/${this.customer.id}`)
    },
    destroy() {
      if (confirm('Вы уверены, что хотите удалить этого клиента?')) {
        this.$inertia.delete(`/customers/${this.customer.id}`)
      }
    },
    restore() {
      if (confirm('Вы уверены, что хотите восстановить этого клиента?')) {
        this.$inertia.put(`/customers/${this.customer.id}/restore`)
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
        this.$inertia.get(`/customers/${this.customer.id}`, pickBy(this.table), { preserveState: true })
      }, 150),
    },
  },
}
</script>
