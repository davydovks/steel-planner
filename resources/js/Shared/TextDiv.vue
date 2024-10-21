<template>
  <div :class="$attrs.class">
    <label v-if="label" class="form-label" :for="id">{{ label }}:</label>
    <div :id="id" ref="input" v-bind="{ ...$attrs, class: 'border-transparent' }" class="form-input" :value="modelValue">
      <a v-if="link" :href="link" class="text-indigo-400 hover:text-indigo-600 underline">{{ modelValue }}</a>
      <span v-else>{{ modelValue }}</span>
    </div>
  </div>
</template>

<script>
import { v4 as uuid } from 'uuid'

export default {
  inheritAttrs: false,
  props: {
    id: {
      type: String,
      default() {
        return `text-input-${uuid()}`
      },
    },
    label: String,
    link: String,
    modelValue: String,
  },
  methods: {
    focus() {
      this.$refs.input.focus()
    },
    select() {
      this.$refs.input.select()
    },
    setSelectionRange(start, end) {
      this.$refs.input.setSelectionRange(start, end)
    },
  },
}
</script>
