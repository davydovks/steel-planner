<template>
  <div>
    <Head title="Создать деталь" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/parts">Детали</Link>
      <icon name="cheveron-right" class="inline w-6 h-6 ml-1 fill-gray-400" />
      <span>Создать</span>
    </h1>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <select-input v-model="form.order_id" :error="form.errors.order_id" class="pb-8 pr-6 w-full lg:w-1/2" label="Заказ">
            <option :value="null" />
            <option v-for="order in orderList" :key="order.id" :value="order.id">{{ order.name }}</option>
          </select-input>
          <text-input v-model="form.position" v-maska="options_int" :error="form.errors.position" class="pb-8 pr-6 w-full lg:w-1/2" label="Позиция" />
          <text-input v-model="form.profile" :error="form.errors.profile" class="pb-8 pr-6 w-full lg:w-1/2" label="Профиль" />
          <text-input v-model="form.weight" v-maska="options_float" :error="form.errors.weight" class="pb-8 pr-6 w-full lg:w-1/2" label="Вес детали, кг" />
        </div>
        <div class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
          <Link class="btn-default mr-auto" href="/parts/">Отмена</Link>
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
    orders: Array,
    order: Object,
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        position: '',
        profile: '',
        weight: '',
        order_id: this.order?.id,
      }),
      orderList: this.orders,
      options_float: {
        mask: "#*.?#*",
        eager: true,
        number: {
          fraction: 3,
          locale: 'ru'
        }
      },
      options_int: {
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
      this.form.post('/parts')
    },
  },
}
</script>
