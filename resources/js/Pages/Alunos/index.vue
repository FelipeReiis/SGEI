<script setup>
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';
import DataTable from '@/Components/DataTable.vue'; // <--- Importamos o componente
import debounce from 'lodash/debounce'; // npm install lodash
import { Link } from '@inertiajs/vue3';

const props = defineProps({ alunos: Object, busca: Object });
defineOptions({ layout: MainLayout });

// Configuração das Colunas
const colunas = [
    { label: 'Aluno', key: 'aluno_nome', sortable: true }, // Slot personalizado
    { label: 'Resp. Financeiro', key: 'fin_nome', sortable: true },
    { label: 'Resp. Pedagógico', key: 'pedag_nome', sortable: true },
    { label: 'Status', key: 'status', sortable: false }, // Slot personalizado
    { label: 'Ações', key: 'actions', sortable: false }, // Slot personalizado
];

// Estado da Busca
const busca = ref(props.busca || '');

// Função de Busca (Observa o input e recarrega a página via Inertia)
watch(busca, debounce((value) => {
  router.get('/alunos', { busca: value }, { preserveState: true, replace: true });
}, 500));

// Função de Ordenação (O componente emite 'sort')
const handleSort = (key) => {
    // Aqui você chamaria o backend passando ?sort=key
    console.log("Ordenar por:", key);
};
</script>

<template>
  <div class="container-fluid">
    <h2 class="text-pink mb-4">Gerenciar Alunos</h2>

    <DataTable :colunas="colunas" :linhas="alunos" @sort="handleSort">

      <template #header>
        <input
            v-model="busca"
            type="text"
            class="form-control w-25 rounded-pill"
            placeholder="Buscar..."
        >
        <Link href="/alunos/create"><button class="btn btn-pink rounded-pill" >+ Novo Usuário</button></Link>
      </template>

      <template #name="{ linha }">
        <div class="d-flex align-items-center">
            <div class="avatar-circle me-2">{{ linha.name.charAt(0) }}</div>
            <span class="fw-bold">{{ linha.name }}</span>
        </div>
      </template>

      <template #status="{ linha }">
        <span class="badge rounded-pill bg-success" v-if="linha.is_active">Ativo</span>
        <span class="badge rounded-pill bg-secondary" v-else>Inativo</span>
      </template>

      <!-- <template #actions="{ linha }">
        <button class="btn btn-sm btn-outline-secondary me-1">✏️</button>
        <button class="btn btn-sm btn-outline-danger">🗑️</button>
      </template> -->

       <template #actions="{ linha }">
            <div class="d-flex gap-2">
                <Link
                    :href="route('alunos.edit', linha.aluno_id)"
                    class="btn btn-sm btn-outline-pink"
                    title="Editar Aluno">
                    <i class="bi bi-pencil"></i> ✏️
                </Link>

                <!-- <button
                    @click="confirmarExclusao(linha.id, linha.nome)"
                    class="btn btn-sm btn-outline-danger"
                    title="Excluir Aluno"
                >
                    <i class="bi bi-trash"></i> 🗑️
                </button> -->

            </div>
        </template>

    </DataTable>
  </div>
</template>

<style scoped>
.text-pink { color: #ff0055; font-weight: 700; }
.btn-pink { background-color: #ff0055; color: white; border: none; padding: 8px 20px;}
.avatar-circle {
    width: 30px; height: 30px; background: #ff0055; color: white;
    border-radius: 50%; display: flex; align-items: center; justify-content: center;
}
</style>
