<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth.user);

// Função para pegar as iniciais do nome (Ex: João Silva -> JS)
const getInitials = (name) => {
  return name
    .split(' ')
    .map(n => n[0])
    .join('')
    .toUpperCase()
    .substring(0, 2);
};
</script>

<template>
  <div class="header-content">
    <div class="search-bar">
      <span>🔍</span>
      <input type="text" placeholder="Buscar no sistema..." />
    </div>

    <div class="user-info">
      <div class="notif-dot" title="Notificações">🔔</div>

      <div class="profile">
        <div class="avatar">{{ getInitials(user.name) }}</div>

        <div class="details">
          <span class="name">{{ user.name }}</span>
          <span class="role text-uppercase">Admin</span>
        </div>
      </div>

      <Link
        :href="route('logout')"
        method="post"
        as="button"
        class="btn-logout"
      >
        <span>Sair</span>
        <i class="fas fa-sign-out-alt ml-2"></i>
      </Link>
    </div>
  </div>
</template>

<style scoped>
.header-content {
  width: 100%;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 10px 0;
}

/* Barra de Busca estilo "Pílula" */
.search-bar {
  background: white;
  border: 1px solid #ebd0de;
  padding: 8px 15px;
  border-radius: 20px;
  display: flex;
  align-items: center;
  width: 300px;
  transition: all 0.3s;
}

.search-bar:focus-within {
  border-color: #ff0055;
  box-shadow: 0 0 0 3px rgba(255, 0, 85, 0.1);
}

.search-bar input {
  border: none;
  outline: none;
  margin-left: 10px;
  width: 100%;
  color: #555;
  font-size: 0.9rem;
}

/* Área do Usuário */
.user-info {
  display: flex;
  align-items: center;
  gap: 25px;
}

.notif-dot {
  cursor: pointer;
  color: #666;
  font-size: 1.2rem;
  transition: transform 0.2s;
}
.notif-dot:hover { transform: scale(1.1); }

.profile {
  display: flex;
  align-items: center;
  gap: 12px;
  padding-right: 20px;
  border-right: 1px solid #eee; /* Linha sutil separando o perfil do botão sair */
}

.avatar {
  width: 42px;
  height: 42px;
  background-color: #ff0055;
  color: white;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  font-size: 0.9rem;
  box-shadow: 0 4px 8px rgba(255, 0, 85, 0.2);
}

.details { display: flex; flex-direction: column; }
.name { font-weight: bold; font-size: 0.85rem; color: #333; }
.role { font-size: 0.7rem; color: #ff0055; font-weight: 600; letter-spacing: 0.5px; }

/* Estilização do Botão Sair */
.btn-logout {
  background: transparent;
  border: 1px solid #ff0055;
  color: #ff0055;
  padding: 6px 15px;
  border-radius: 8px;
  font-size: 0.8rem;
  font-weight: bold;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  gap: 8px;
}

.btn-logout:hover {
  background: #ff0055;
  color: white;
  box-shadow: 0 4px 12px rgba(255, 0, 85, 0.3);
}
</style>
