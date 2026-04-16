<script setup>
    import { ref, watch } from 'vue';
    import { router } from '@inertiajs/vue3';
    import MainLayout from '@/Layouts/MainLayout.vue';
    import DataTable from '@/Components/DataTable.vue';
    import debounce from 'lodash/debounce';
    import { Link } from '@inertiajs/vue3';

    const props = defineProps({ funcionarios: Object, busca: Object });
    defineOptions({ layout: MainLayout });

    const colunas = [
        { label: 'Funcionario', key: 'nome', sortable: true },
        { label: 'CPF', key: 'cpf', sortable: true },
        // { label: 'Função', key: 'pedag_nome', sortable: true },
        // { label: 'Status', key: 'status', sortable: false },
        { label: 'Ações', key: 'actions', sortable: false },
    ];

    const busca = ref(props.busca || '');

    watch(busca, debounce((value) => {
    router.get('/funcionarios', { busca: value }, { preserveState: true, replace: true });
    }, 500));

    const handleSort = (key) => {
        // Aqui você chamaria o backend passando ?sort=key
        console.log("Ordenar por:", key);
    };

</script>

<template>
  <div class="container-fluid">
    <h2 class="text-pink mb-4">Gerenciar Funcionarios</h2>

    <DataTable :colunas="colunas" :linhas="funcionarios" @sort="handleSort">

      <template #header>
        <input
            v-model="busca"
            type="text"
            class="form-control w-25 rounded-pill"
            placeholder="Buscar..."
        >
        <Link href="/funcionarios/create"><button class="btn btn-pink rounded-pill" >+ Novo Usuário</button></Link>
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
                    :href="route('funcionarios.edit', linha.id)"
                    class="btn btn-sm btn-outline-pink"
                    title="Editar Fcuncionário">
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
