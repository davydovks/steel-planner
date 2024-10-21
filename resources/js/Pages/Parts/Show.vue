<template>
  <div>
    <Head :title="`Деталь ${form.position}`" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/parts">Детали</Link>
      <icon name="cheveron-right" class="inline w-6 h-6 ml-1 fill-gray-400" />
      <span>{{ form.position }}</span>
    </h1>
    <trashed-message v-if="part.deleted_at" class="mb-6" @restore="restore">Эта деталь удалена.</trashed-message>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-div v-model="form.order_name" class="pb-8 pr-6 w-full lg:w-1/2" label="Заказ" :link="form.order_link" />
          <text-div v-model="form.position" class="pb-8 pr-6 w-full lg:w-1/2" label="Позиция" />
          <text-div v-model="form.profile" class="pb-8 pr-6 w-full lg:w-1/2" label="Профиль"/>
          <text-div v-model="form.weight" class="pb-8 pr-6 w-full lg:w-1/2" label="Вес детали, кг"/>
          <text-div v-model="form.quantity" class="pb-8 pr-6 w-full lg:w-1/2" label="Количество в заказе" />
          <text-div v-model="form.total_weight" class="pb-8 pr-6 w-full lg:w-1/2" label="Общий вес в заказе, кг" />
        </div>
        <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
          <Link class="btn-default mr-auto" href="/parts/">Назад</Link>
          <button v-if="!part.deleted_at" class="text-red-600 hover:underline mr-6" tabindex="-1" type="button" @click="destroy">Удалить</button>
          <Link class="btn-indigo" :href="`/parts/${part.id}/edit`">Изменить</Link>
        </div>
    </div>
    <h2 class="mt-12 text-2xl font-bold">Отправочные марки</h2>
    <div class="mt-6 bg-white rounded shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold bg-indigo-100">
          <th class="px-6 py-4">Название</th>
          <th class="px-6 py-4">Количество ОМ, шт</th>
          <th class="px-6 py-4">Деталей в одной ОМ, шт</th>
          <th class="px-6 py-4" colspan="2">Деталей во всех ОМ, шт</th>
        </tr>
        <tr v-for="structure in part.structures.data" :key="structure.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
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
              {{ structure.pivot.quantity }}
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/structures/${structure.id}`" tabindex="-1">
              {{ structure.pivot.quantity * structure.quantity }}
            </Link>
          </td>
          <td class="w-px border-t">
            <Link class="flex items-center px-4" :href="`/structures/${structure.id}`" tabindex="-1">
              <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
            </Link>
          </td>
        </tr>
        <tr v-if="part.structures != null && part.structures.length === 0">
          <td class="px-6 py-4 border-t" colspan="4">Отправочные марки не найдены.</td>
        </tr>
      </table>
    </div>
    <pagination class="mt-6" :links="part.structures.links" />
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Icon from '@/Shared/Icon.vue'
import Layout from '@/Shared/Layout.vue'
import TextDiv from '@/Shared/TextDiv.vue'
import SelectInput from '@/Shared/SelectInput.vue'
import LoadingButton from '@/Shared/LoadingButton.vue'
import Pagination from '@/Shared/Pagination.vue'
import TrashedMessage from '@/Shared/TrashedMessage.vue'

export default {
  components: {
    Head,
    Icon,
    Link,
    LoadingButton,
    Pagination,
    SelectInput,
    TextDiv,
    TrashedMessage,
  },
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
        profile: this.part.profile,
        position: String(this.part.position),
        quantity: String(this.part.quantity),
        total_weight: (this.part.weight * this.part.quantity / 1000).toFixed(1),
        weight: (this.part.weight / 1000).toFixed(1),
      }),
    }
  },
  methods: {
    update() {
      this.form.put(`/parts/${this.part.id}`)
    },
    destroy() {
      if (confirm('Вы уверены, что хотите удалить эту деталь?')) {
        this.$inertia.delete(`/parts/${this.part.id}`)
      }
    },
    restore() {
      if (confirm('Вы уверены, что хотите восстановить эту деталь?')) {
        this.$inertia.put(`/parts/${this.part.id}/restore`)
      }
    },
  },
}
</script>
