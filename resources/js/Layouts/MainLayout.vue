<script setup>
    import { ref } from 'vue';
    import Topo from '@/Components/Topo.vue';
    import MenuLateral from '@/Components/MenuLateral.vue';
    import Rodape from '@/Components/Rodape.vue';
    import FlashMessage from '@/Components/FlashMessage.vue';

    const isSidebarOpen = ref(true);
</script>

<template>


    <div class="layout-grid" :class="{ 'menu-closed': !isSidebarOpen }">
        <aside class="area-sidebar">
        <div class="logo-area">
            <h1 v-show="isSidebarOpen">Sistema</h1>
            <button class="toggle-btn" @click="isSidebarOpen = !isSidebarOpen">
            <span v-if="isSidebarOpen">«</span>
            <span v-else>»</span>
            </button>
        </div>
        <MenuLateral :isOpen="isSidebarOpen" />
        </aside>

        <header class="area-header">
        <Topo />
        </header>

        <main class="area-content">
            <FlashMessage />
            <slot />
        </main>

        <footer class="area-footer">
        <Rodape />
        </footer>

    </div>
</template>

<style scoped>
.layout-grid {
  display: grid;
  height: 100vh;
  width: 100vw;

  /* Colunas: Menu (260px) e Conteúdo (Resto) */
  grid-template-columns: 260px 1fr;

  /* Linhas: Header (70px), Conteúdo (Resto), Footer (Auto) */
  grid-template-rows: 70px 1fr auto;

  /* MAPA DO LAYOUT (Estilo Dashboard Moderno) */
  grid-template-areas:
    "sidebar header"
    "sidebar content"
    "sidebar footer";

  transition: grid-template-columns 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
}

/* Estado Fechado */
.layout-grid.menu-closed {
  grid-template-columns: 60px 1fr; /* Recolhe para 60px apenas */
}

/* Áreas */
.area-sidebar {
  grid-area: sidebar;
  background-color: #280a1b; /* Roxo bem escuro */
  color: white;
  display: flex;
  flex-direction: column;
  overflow: hidden;
  box-shadow: 4px 0 10px rgba(0,0,0,0.2);
  z-index: 20;
}

.area-header {
  grid-area: header;
  background-color: #fcecf4; /* Rosa bem clarinho */
  display: flex;
  align-items: center;
  padding: 0 30px;
  box-shadow: 0 2px 5px rgba(0,0,0,0.05);
  z-index: 10;
}

.area-content {
  grid-area: content;
  background-color: #f8f9fa; /* Cinza gelo */
  padding: 30px;
  overflow-y: auto;
}

.area-footer {
  grid-area: footer;
  background-color: #1a1a1a;
  color: #666;
}

/* Logo Area e Botão */
.logo-area {
  height: 70px; /* Mesma altura do header */
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 20px;
  border-bottom: 1px solid rgba(255,255,255,0.1);
}

.logo-area h1 {
  color: #ff0055; /* Rosa Neon */
  font-weight: 800;
  font-size: 1.5rem;
  margin: 0;
}

.toggle-btn {
  background: none;
  border: none;
  color: white;
  font-size: 1.5rem;
  cursor: pointer;
}
</style>
