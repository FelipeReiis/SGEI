<script setup>
defineProps({ isOpen: Boolean });
</script>

<template>
  <nav class="menu-nav">
    <ul>
      <li class="active">
        <a href="#">
          <span class="icon">🏠</span>
          <span class="text" v-show="isOpen">Dashboard</span>
        </a>
      </li>
      <li>
        <a href="#">
          <span class="icon">👥</span>
          <span class="text" v-show="isOpen">Usuários</span>
        </a>
      </li>
      <li>
        <a href="#">
          <span class="icon">📊</span>
          <span class="text" v-show="isOpen">Relatórios</span>
        </a>
      </li>
      <li>
        <a href="#">
          <span class="icon">⚙️</span>
          <span class="text" v-show="isOpen">Configurações</span>
        </a>
      </li>
    </ul>
  </nav>
</template>

<style scoped>
.menu-nav { padding: 20px 10px; }
ul { list-style: none; padding: 0; margin: 0; }

li { margin-bottom: 8px; }

a {
  display: flex;
  align-items: center;
  padding: 12px 15px;
  border-radius: 12px; /* Borda bem arredondada */
  color: #b0a0b0;
  text-decoration: none;
  transition: all 0.3s;
  white-space: nowrap; /* Impede quebra de texto ao fechar */
}

a:hover {
  background-color: rgba(255, 0, 85, 0.1);
  color: white;
}

/* Item Ativo (Estilo Rosa Cheio) */
li.active a {
  background-color: #ff0055; /* Rosa Neon */
  color: white;
  box-shadow: 0 4px 15px rgba(255, 0, 85, 0.4);
  font-weight: 600;
}

.icon { margin-right: 15px; font-size: 1.2rem; min-width: 24px;}
</style>
