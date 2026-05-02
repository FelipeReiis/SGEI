<script setup>
defineProps({
    titulo: String,
    icon: String
});
</script>

<template>
  <div class="form-section mb-4">
    <h5 class="section-title text-pink fw-bold">
        <span class="icon">{{ icon }}</span> {{ titulo }}
    </h5>
    <hr class="mb-4">

    <slot></slot>
  </div>
</template>
