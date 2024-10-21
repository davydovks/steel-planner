<template>
  <div>
    <Head :title="`${form.name}`" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/orders">Заказы</Link>
      <icon name="cheveron-right" class="inline w-6 h-6 ml-1 fill-gray-400" />
      <span>{{ form.name }} (редактирование)</span>
    </h1>
    <trashed-message v-if="order.deleted_at" class="mb-6" @restore="restore">Этот заказ удален.</trashed-message>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input v-model="form.name" :error="form.errors.name" class="pb-8 pr-6 w-full lg:w-1/2" label="Название" />
          <select-input v-model="form.customer_id" :error="form.errors.customer_id" class="pb-8 pr-6 w-full lg:w-1/2" label="Заказчик">
            <option v-for="customer in customers" :key="customer.id" :value="customer.id">{{ customer.name }}</option>
          </select-input>
          <text-input v-model="form.due_date" v-maska="'####-##-##'" :error="form.errors.due_date" class="pb-8 pr-6 w-full lg:w-1/2" label="Крайняя дата" placeholder="ГГГГ-ММ-ДД" />
        </div>
        <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
          <Link class="btn-default" :href="`/orders/${order.id}`">Отмена</Link>
          <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">Сохранить</loading-button>
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
import TrashedMessage from '@/Shared/TrashedMessage.vue'

export default {
  components: {
    Head,
    Icon,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
    TrashedMessage,
  },
  directives: { maska: vMaska },
  layout: Layout,
  props: {
    order: Object,
    customers: Array,
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        name: this.order.name,
        customer_id: this.order.customer_id,
        due_date: this.order.due_date,
        percent: "0",
      }),
    }
  },
  methods: {
    update() {
      this.form.put(`/orders/${this.order.id}`)
    },
    restore() {
      if (confirm('Вы уверены, что хотите восстановить этот заказ?')) {
        this.$inertia.put(`/orders/${this.order.id}/restore`)
      }
    },
  },
}
</script>
