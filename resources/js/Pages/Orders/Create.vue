<template>
  <div>
    <Head title="Создать клиента" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/orders">Заказы</Link>
      <icon name="cheveron-right" class="inline w-6 h-6 ml-1 fill-gray-400" />
      <span>Создать</span>
    </h1>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input v-model="form.name" :error="form.errors.name" class="pb-8 pr-6 w-full" label="Название" />
          <select-input v-model="form.customer_id" :error="form.errors.customer_id" class="pb-8 pr-6 w-full lg:w-1/2" label="Заказчик">
            <!-- <option :value="null" /> -->
            <option v-for="customer in customers" :key="customer.id" :value="customer.id">{{ customer.name }}</option>
          </select-input>
          <text-input v-model="form.due_date" v-maska="'####-##-##'" :error="form.errors.due_date" class="pb-8 pr-6 w-full lg:w-1/2" label="Крайняя дата" placeholder="ГГГГ-ММ-ДД" />
          


        </div>
        <div class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
          <Link class="btn-default mr-auto" href="/orders/">Отмена</Link>
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
    customer: Object,
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        name: '',
        customer_id: this.customer?.id,
        due_date: null,
      }),
    }
  },
  methods: {
    store() {
      this.form.post('/orders')
    },
  },
}
</script>
