<script setup>
    const anoAtual = new Date().getFullYear()
</script>

<template>
  <div class="footer-inner">
    &copy; {{ anoAtual }} Minha Empresa - Todos os direitos reservados.
  </div>
</template>

<style scoped>
.footer-inner {
  background-color: #0a0005;
  padding: 10px;
  text-align: center;
  font-size: 0.8rem;
  color: white;
}
</style>
