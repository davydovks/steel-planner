<template>
  <div>
    <Head title="Создать клиента" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/customers">Клиенты</Link>
      <icon name="cheveron-right" class="inline w-6 h-6 ml-1 fill-gray-400" />
      <span>Создать</span>
    </h1>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input v-model="form.name" :error="form.errors.name" class="pb-8 pr-6 w-full" label="Имя" />
          <text-input v-model="form.address" :error="form.errors.address" class="pb-8 pr-6 w-full" label="Адрес" />
          <select-input v-model="form.customer_type_id" :error="form.errors.customer_type_id" class="pb-8 pr-6 w-full lg:w-1/2" label="Тип">
            <!-- <option :value="null" /> -->
            <option v-for="type in customer_types" :key="type.id" :value="type.id">{{ type.name }}</option>
          </select-input>
          <text-input v-model="form.TIN" :error="form.errors.TIN" class="pb-8 pr-6 w-full lg:w-1/2" label="ИНН" />
        </div>
        <div class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
          <Link class="btn-default mr-auto" href="/customers/">Отмена</Link>
          <loading-button :loading="form.processing" class="btn-indigo" type="submit">Сохранить</loading-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
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
  layout: Layout,
  props: {
    customer_types: Array,
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        name: '',
        address: '',
        customer_type_id: null,
        TIN: '',
      }),
    }
  },
  methods: {
    store() {
      this.form.post('/customers')
    },
  },
}
</script>
