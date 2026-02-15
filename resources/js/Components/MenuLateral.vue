<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3'; // Importante para navegação SPA

const props = defineProps({ isOpen: Boolean });

// Estado para controlar qual menu está expandido
const expandedItem = ref(null);

const toggleSubmenu = (label) => {
  // Se já estiver aberto, fecha. Se não, abre.
  expandedItem.value = expandedItem.value === label ? null : label;
};

// Dados do Menu (Simulando o que viria do Backend ou Config)
const menuItems = [
  { label: 'Dashboard', icon: '🏠', route: '/dashboard' },
  {
    label: 'Secretaria',
    icon: '📂',
    children: [
      { label: 'Alunos', route: '/alunos' },
      { label: 'Fornecedores', route: '/fornecedores' },
      { label: 'Produtos', route: '/produtos' }
    ]
  },
  {
    label: 'Financeiro',
    icon: '💲',
    children: [
      { label: 'Contas a Pagar', route: '/contas-pagar' },
      { label: 'Contas a Receber', route: '/contas-receber' }
    ]
  },
  { label: 'Configurações', icon: '⚙️', route: '/config' },
];
</script>

<template>
  <nav class="menu-nav">
    <ul>
      <li v-for="item in menuItems" :key="item.label" :class="{ 'active': route().current() === item.route }">

        <div v-if="item.children">
          <div class="menu-item-parent" @click="toggleSubmenu(item.label)">
            <div class="d-flex align-items-center">
              <span class="icon">{{ item.icon }}</span>
              <span class="text" v-show="isOpen">{{ item.label }}</span>
            </div>
            <span v-show="isOpen" class="arrow" :class="{ 'rotated': expandedItem === item.label }">▼</span>
          </div>

          <ul v-show="isOpen && expandedItem === item.label" class="submenu">
            <li v-for="sub in item.children" :key="sub.label">
              <Link :href="sub.route" class="submenu-link">
                {{ sub.label }}
              </Link>
            </li>
          </ul>
        </div>

        <Link v-else :href="item.route || '#'" class="menu-link">
          <span class="icon">{{ item.icon }}</span>
          <span class="text" v-show="isOpen">{{ item.label }}</span>
        </Link>
      </li>
    </ul>
  </nav>
</template>

<style scoped>
.menu-nav { padding: 20px 10px; }
ul { list-style: none; padding: 0; margin: 0; }
li { margin-bottom: 5px; }

/* Estilo Base dos Links e Botões do Menu */
.menu-link, .menu-item-parent {
  display: flex;
  align-items: center;
  justify-content: space-between; /* Para jogar a seta p/ direita */
  padding: 12px 15px;
  border-radius: 12px;
  color: #b0a0b0;
  text-decoration: none;
  cursor: pointer;
  transition: all 0.3s;
  white-space: nowrap;
}

.menu-link:hover, .menu-item-parent:hover {
  background-color: rgba(255, 0, 85, 0.1);
  color: white;
}

.icon { margin-right: 15px; font-size: 1.2rem; min-width: 24px; }

/* Submenu Styles */
.submenu {
  background-color: rgba(0, 0, 0, 0.2); /* Fundo mais escuro */
  border-radius: 8px;
  margin-top: 5px;
  overflow: hidden;
}

.submenu-link {
  display: block;
  padding: 10px 10px 10px 54px; /* Indentação maior para alinhar texto */
  color: #a090a0;
  text-decoration: none;
  font-size: 0.9rem;
}

.submenu-link:hover { color: #ff0055; }

/* Animação da Seta */
.arrow { font-size: 0.7rem; transition: transform 0.3s; }
.rotated { transform: rotate(180deg); }
</style>
