<template>
  <div>
    <Head :title="`${form.name}`" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/structures">Отправочные марки</Link>
      <icon name="cheveron-right" class="inline w-6 h-6 ml-1 fill-gray-400" />
      <span>{{ form.name }} (редактирование)</span>
    </h1>
    <trashed-message v-if="structure.deleted_at" class="mb-6" @restore="restore">Эта отправочная марка удалена.</trashed-message>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input v-model="form.name" :error="form.errors.name" class="pb-8 pr-6 w-full lg:w-1/2" label="Название" />
          <text-input v-model="form.quantity" v-maska="options" data-maska-number data-maska-number-locale="ru" :error="form.errors.quantity" class="pb-8 pr-6 w-full lg:w-1/2" label="Количество" />
          <!-- <select-input v-model="form.order_id" :error="form.errors.order_id" class="pb-8 pr-6 w-full lg:w-1/2" label="Заказ">
            <option v-for="order in orders" :key="order.id" :value="order.id">{{ order.name }}</option>
          </select-input> -->
          <text-div v-model="form.order_name" class="pb-8 pr-6 w-full" label="Заказ" :link="form.order_link" />
        </div>
        <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
          <Link class="btn-default" :href="`/structures/${structure.id}`">Отмена</Link>
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
import TextDiv from '@/Shared/TextDiv.vue'
import TextInput from '@/Shared/TextInput.vue'
// import SelectInput from '@/Shared/SelectInput.vue'
import LoadingButton from '@/Shared/LoadingButton.vue'
import TrashedMessage from '@/Shared/TrashedMessage.vue'

export default {
  components: {
    Head,
    Icon,
    Link,
    LoadingButton,
    // SelectInput,
    TextDiv,
    TextInput,
    TrashedMessage,
  },
  directives: { maska: vMaska },
  layout: Layout,
  props: {
    structure: Object,
    // orders: Array,
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        name: this.structure.name,
        quantity: String(this.structure.quantity),
        // order_id: this.structure.order.id,
        order_link: `/orders/${this.structure.order.id}`,
        order_name: this.structure.order.name,
        percent: "0",
      }),
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
    update() {
      this.form.put(`/structures/${this.structure.id}`)
    },
    restore() {
      if (confirm('Вы уверены, что хотите восстановить этот заказ?')) {
        this.$inertia.put(`/structures/${this.structure.id}/restore`)
      }
    },
  },
}
</script>
