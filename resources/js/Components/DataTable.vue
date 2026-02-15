<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  colunas: Array,   // [{ label: 'Nome', key: 'name', sortable: true }]
  linhas: Object,     // O objeto de paginação do Laravel (data, links, etc)
  filtros: Object   // Para manter o estado da busca
});

const emit = defineEmits(['sort']);

// Função para emitir evento de ordenação
const sortBy = (key) => {
  emit('sort', key);
};
</script>

<template>
  <div class="card-custom">

    <div class="d-flex justify-content-between align-items-center mb-4">
      <slot name="header"></slot> </div>

    <div class="table-responsive">
      <table class="table table-hover align-middle custom-table">
        <thead>
          <tr>
            <th
              v-for="col in colunas"
              :key="col.key"
              @click="col.sortable ? sortBy(col.key) : null"
              :class="{ 'cursor-pointer': col.sortable }"
            >
              {{ col.label }}
              <span v-if="col.sortable" class="sort-icon">⇅</span>
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="linha in linhas?.data" :key="linha.id">
            <td v-for="col in colunas" :key="col.key">

              <slot :name="col.key" :linha="linha">
                {{ linha[col.key] }}
              </slot>

            </td>
          </tr>

          <tr v-if="!linhas?.data || linhas.data.length === 0">
            <td :colspan="colunas.length" class="text-center py-5 text-muted">
              Nenhum registro encontrado.
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="d-flex justify-content-end mt-3" v-if="linhas?.data.length > 0">
      <nav>
        <ul class="pagination">
          <li
            v-for="(link, index) in linhas?.links"
            :key="index"
            class="page-item"
            :class="{ 'active': link.active, 'disabled': !link.url }"
          >
            <Link
                class="page-link"
                :href="link.url || '#'"
                v-html="link.label"
            />
          </li>
        </ul>
      </nav>
    </div>

  </div>
</template>

<style scoped>
/* Estilo Container */
.card-custom {
  background: white;
  border-radius: 16px;
  padding: 25px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.03);
}

/* Tabela Customizada */
.custom-table thead th {
  background-color: #fcecf4; /* Fundo Rosa Claro no Header */
  color: #ff0055;           /* Texto Rosa Neon */
  border: none;
  font-weight: 700;
  padding: 15px;
  font-size: 0.9rem;
}

.custom-table thead th:first-child { border-radius: 10px 0 0 10px; }
.custom-table thead th:last-child { border-radius: 0 10px 10px 0; }

.custom-table tbody td {
  padding: 15px;
  color: #555;
  border-bottom: 1px solid #f0f0f0;
  font-size: 0.95rem;
}

.cursor-pointer { cursor: pointer; user-select: none; }
.cursor-pointer:hover { background-color: #fadce9; }
.sort-icon { font-size: 0.7rem; margin-left: 5px; opacity: 0.5; }

/* Paginação Rosa */
:deep(.page-link) {
  color: #ff0055;
  border: none;
  margin: 0 2px;
  border-radius: 8px;
}
:deep(.page-item.active .page-link) {
  background-color: #ff0055;
  color: white;
}
:deep(.page-link:hover) {
  background-color: #fcecf4;
}
</style>
