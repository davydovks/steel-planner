<template>
  <div>
    <Head title="Создать отправочную марку" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/structures">Отправочные марки</Link>
      <icon name="cheveron-right" class="inline w-6 h-6 ml-1 fill-gray-400" />
      <span>Создать</span>
    </h1>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input v-model="form.name" :error="form.errors.name" class="pb-8 pr-6 w-full lg:w-1/2" label="Название" />
          <text-input v-model="form.quantity" v-maska="options" data-maska-number data-maska-number-locale="ru" :error="form.errors.quantity" class="pb-8 pr-6 w-full lg:w-1/2" label="Количество" />
          <select-input v-model="form.customer_id" @change="selectCustomer" :error="form.errors.customer_id" class="pb-8 pr-6 w-full lg:w-1/2" label="Заказчик">
            <option :value="null" />
            <option v-for="customer in customerList" :key="customer.id" :value="customer.id">{{ customer.name }}</option>
          </select-input>
          <select-input v-model="form.order_id" @change="selectOrder" :error="form.errors.order_id" class="pb-8 pr-6 w-full lg:w-1/2" label="Заказ">
            <option :value="null" />
            <option v-for="order in orderList" :key="order.id" :value="order.id">{{ order.name }}</option>
          </select-input>
        </div>
        <div class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
          <Link class="btn-default mr-auto" href="/structures/">Отмена</Link>
          <loading-button :loading="form.processing" class="btn-indigo" type="submit">Сохранить</loading-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import { vMaska } from "maska/vue"
import Icon from '@/Shared/Icon.vue'
import Layout from '@/Shared/Layout.vue'
import TextInput from '@/Shared/TextInput.vue'
import SelectInput from '@/Shared/SelectInput.vue'
import LoadingButton from '@/Shared/LoadingButton.vue'

export default {
  components: {
    Head,
    Icon,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
  },
  directives: { maska: vMaska },
  layout: Layout,
  props: {
    customers: Array,
    orders: Array,
    order: Object,
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        name: '',
        quantity: '',
        order_id: this.order?.id,
        customer_id: this.orders.find((order) => order.id == this.order?.id)?.customer_id,
      }),
      customerList: this.customers,
      orderList: this.orders,
      options: {
        mask: "#*",
        eager: true,
        number: {
          locale: 'ru'
        }
      }
    }
  },
  methods: {
    store() {
      this.form.post('/structures')
    },
    selectCustomer() {
      this.orderList = this.form.customer_id
        ?  this.orders.filter((item) => item.customer_id == this.form.customer_id)
        : this.orders
    },
    selectOrder() {
      this.form.customer_id = this.orders.find((order) => order.id == this.form.order_id)?.customer_id
      this.customerList = this.form.customer_id
        ?  this.customers.filter((item) => item.id == this.form.customer_id)
        : this.customers
    },
  },
}
</script>
