<template>
  <div class="header-content">
    <div class="search-bar">
      <span>🔍</span>
      <input type="text" placeholder="Buscar..." />
    </div>

    <div class="user-info">
      <div class="notif-dot">🔔</div>
      <div class="profile">
        <div class="avatar">JS</div>
        <div class="details">
          <span class="name">João Silva</span>
          <span class="role">Admin</span>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.header-content {
  width: 100%;
  display: flex;
  justify-content: space-between;
  align-items: center;
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
  color: #999;
}
.search-bar input {
  border: none;
  outline: none;
  margin-left: 10px;
  width: 100%;
  color: #555;
}

/* Área do Usuário */
.user-info { display: flex; align-items: center; gap: 20px; }
.notif-dot { cursor: pointer; color: #666; }

.profile { display: flex; align-items: center; gap: 10px; }
.avatar {
  width: 40px; height: 40px;
  background-color: #ff0055;
  color: white;
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  font-weight: bold;
}
.details { display: flex; flex-direction: column; }
.name { font-weight: bold; font-size: 0.9rem; color: #333; }
.role { font-size: 0.75rem; color: #888; }
</style>
