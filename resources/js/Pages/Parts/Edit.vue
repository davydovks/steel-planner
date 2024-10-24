<template>
  <div>
    <Head :title="`Деталь ${form.position}`" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/parts">Детали</Link>
      <icon name="cheveron-right" class="inline w-6 h-6 ml-1 fill-gray-400" />
      <span>{{ form.position }} (редактирование)</span>
    </h1>
    <trashed-message v-if="part.deleted_at" class="mb-6" @restore="restore">Эта деталь удалена.</trashed-message>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-div v-model="form.order_name" class="pb-8 pr-6 w-full lg:w-1/2" label="Заказ" :link="form.order_link" />
          <text-input v-model="form.position" :error="form.errors.position" class="pb-8 pr-6 w-full lg:w-1/2" label="Позиция" />
          <text-input v-model="form.profile" :error="form.errors.profile" class="pb-8 pr-6 w-full lg:w-1/2" label="Профиль" />
          <text-input v-model="form.weight" v-maska="options" :error="form.errors.weight" class="pb-8 pr-6 w-full lg:w-1/2" label="Вес детали, кг" />
        </div>
        <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
          <Link class="btn-default" :href="`/parts/${part.id}`">Отмена</Link>
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
import LoadingButton from '@/Shared/LoadingButton.vue'
import TrashedMessage from '@/Shared/TrashedMessage.vue'

export default {
  components: {
    Head,
    Icon,
    Link,
    LoadingButton,
    TextDiv,
    TextInput,
    TrashedMessage,
  },
  directives: { maska: vMaska },
  layout: Layout,
  props: {
    part: Object,
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        order_link: `/orders/${this.part.order.id}`,
        order_name: this.part.order.name,
        position: String(this.part.position),
        profile: this.part.profile,
        weight: (this.part.weight / 1000).toFixed(1),
        order_id: this.part.order?.id,
      }),
      options: {
        mask: "#*.?#*",
        eager: true,
        number: {
          fraction: 3,
          locale: 'ru'
        }
      }
    }
  },
  methods: {
    update() {
      this.form.put(`/parts/${this.part.id}`)
    },
    restore() {
      if (confirm('Вы уверены, что хотите восстановить эту деталь?')) {
        this.$inertia.put(`/parts/${this.part.id}/restore`)
      }
    },
  },
}
</script>
