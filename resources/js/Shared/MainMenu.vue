<template>
  <div>
    <div class="mb-4">
      <Link class="group flex items-center py-3" href="/">
        <icon name="dashboard" class="mr-2 w-4 h-4" :class="isUrl('') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
        <div :class="isUrl('') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Показатели</div>
      </Link>
    </div>
    <div class="mb-4">
      <Link class="group flex items-center py-3" href="/production">
        <icon name="office" class="mr-2 w-4 h-4" :class="isUrl('production_dashboard') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
        <div :class="isUrl('production_dashboard') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Производство</div>
      </Link>
      <Link class="item flex items-center py-1 ps-6 text-sm" href="/production" v-if="isProductionSection">
        <div :class="isUrl('production') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Производство ОМ</div>
      </Link>
      <Link class="item flex items-center py-1 ps-6 text-sm" href="/fabrication" v-if="isProductionSection">
        <div :class="isUrl('fabrication') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Заготовка</div>
      </Link>
      <Link class="item flex items-center py-1 ps-6 text-sm" href="/production_log" v-if="isProductionSection">
        <div :class="isUrl('production_log') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Журнал производства</div>
      </Link>
      <Link class="item flex items-center py-1 ps-6 text-sm" href="/fabrication_log" v-if="isProductionSection">
        <div :class="isUrl('fabrication_log') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Журнал заготовки</div>
      </Link>
      <Link class="item flex items-center py-1 ps-6 text-sm" href="/operators" v-if="isProductionSection">
        <div :class="isUrl('operators') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Исполнители</div>
      </Link>
    </div>
    <div class="mb-4">
      <Link class="group flex items-center py-3" href="/orders">
        <icon name="users" class="mr-2 w-4 h-4" :class="isUrl('data') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
        <div :class="isUrl('data') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Данные</div>
      </Link>
      <Link class="flex items-center py-1 ps-6 text-sm" href="/customers" v-if="isDataSection">
        <div :class="isUrl('customers') ? 'text-white' : 'text-indigo-300 hover:text-white'">Клиенты</div>
      </Link>
      <Link class="flex items-center py-1 ps-6 text-sm" href="/orders" v-if="isDataSection">
        <div :class="isUrl('orders') ? 'text-white' : 'text-indigo-300 hover:text-white'">Заказы</div>
      </Link>
      <Link class="flex items-center py-1 ps-6 text-sm" href="/structures" v-if="isDataSection">
        <div :class="isUrl('structures') ? 'text-white' : 'text-indigo-300 hover:text-white'">Отправочные марки</div>
      </Link>
      <Link class="flex items-center py-1 ps-6 text-sm" href="/parts" v-if="isDataSection">
        <div :class="isUrl('parts') ? 'text-white' : 'text-indigo-300 hover:text-white'">Детали</div>
      </Link>
      <!-- <Link class="flex items-center py-1 ps-6 text-sm" href="/profiles" v-if="isDataSection">
        <div :class="isUrl('profiles') ? 'text-white' : 'text-indigo-300 hover:text-white'">Профили</div>
      </Link> -->
    </div>
    <div class="mb-4">
      <Link class="group flex items-center py-3" href="/users">
        <icon name="printer" class="mr-2 w-4 h-4" :class="isUrl('settings') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
        <div :class="isUrl('settings') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Настройки</div>
      </Link>
      <Link class="item flex items-center py-1 ps-6 text-sm" href="/users" v-if="isAdminSection">
        <div :class="isUrl('users') ? 'text-white' : 'text-indigo-300 hover:text-white'">Пользователи</div>
      </Link>
      <Link class="item flex items-center py-1 ps-6 text-sm" href="/roles" v-if="isAdminSection">
        <div :class="isUrl('roles') ? 'text-white' : 'text-indigo-300 hover:text-white'">Роли</div>
      </Link>
      <Link class="item flex items-center py-1 ps-6 text-sm" href="/stages" v-if="isAdminSection">
        <div :class="isUrl('stages') ? 'text-white' : 'text-indigo-300 hover:text-white'">Этапы производства</div>
      </Link>
    </div>
  </div>
</template>

<script>
import { Link } from '@inertiajs/vue3'
import Icon from '@/Shared/Icon.vue'

export default {
  components: {
    Icon,
    Link,
  },
  data() {
    return {
      sections: {
        production: ['production', 'fabrication', 'production_log', 'fabrication_log', 'operators'],
        data: ['customers', 'orders', 'structures', 'parts'],
        admin: ['users', 'roles', 'stages'],
      }
    }
  },
  computed: {
    isDataSection() {
      return this.isUrl(...this.sections.data)
    },
    isProductionSection() {
      return this.isUrl(...this.sections.production)
    },
    isAdminSection() {
      return this.isUrl(...this.sections.admin)
    },
  },
  methods: {
    isUrl(...urls) {
      let currentUrl = this.$page.url.substring(1)
      if (urls[0] === '') {
        return currentUrl === ''
      }
      return urls.filter((url) => currentUrl.startsWith(url)).length
    },
  },
}
</script>
