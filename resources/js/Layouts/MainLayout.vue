<script setup>
import { ref } from 'vue';
import Topo from '@/Components/Topo.vue';
import MenuLateral from '@/Components/MenuLateral.vue';
import Rodape from '@/Components/Rodape.vue';

// Estado para controlar se o menu está aberto ou fechado
const isSidebarOpen = ref(true);

const toggleSidebar = () => {
  isSidebarOpen.value = !isSidebarOpen.value;
};
</script>

<template>
  <div class="layout-container" :class="{ 'menu-closed': !isSidebarOpen }">

    <header class="area-header">
      <button @click="toggleSidebar">☰</button> <Topo/>
    </header>

    <aside class="area-sidebar">
      <MenuLateral v-show="isSidebarOpen" />
    </aside>

    <main class="area-content">
      <slot />
    </main>

    <footer class="area-footer">
      <Rodape/>
    </footer>

  </div>
</template>

<style scoped>
/* CONFIGURAÇÃO DO GRID */
.layout-container {
  display: grid;
  height: 100vh; /* Ocupa a tela toda */

  /* Definindo as colunas: Menu (250px) e Conteúdo (Resto) */
  grid-template-columns: 250px 1fr;

  /* Definindo as linhas: Header, Conteúdo, Footer */
  grid-template-rows: 60px 1fr auto;

  /* O Mapa do Layout */
  grid-template-areas:
    "header header"
    "sidebar content"
    "sidebar footer";
    /* Nota: coloquei o footer abaixo do conteúdo, mas ao lado do sidebar.
       Se quiser o footer na largura total, mude a ultima linha para "footer footer" */

  transition: grid-template-columns 0.3s ease;
}

/* Quando o menu estiver fechado, mudamos a largura da primeira coluna para 0 */
.layout-container.menu-closed {
  grid-template-columns: 0px 1fr;
}

/* Conectando as classes às áreas do Grid */
.area-header  { grid-area: header; z-index: 10; }
.area-sidebar { grid-area: sidebar; overflow-y: auto; background: #2d3748; }
.area-content { grid-area: content; overflow-y: auto; padding: 20px; background: #f8f9fa;}
.area-footer  { grid-area: footer; }

button { margin-right: 10px; cursor: pointer; }
</style>
