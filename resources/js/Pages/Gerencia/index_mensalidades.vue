<script setup>
    import { ref, watch } from 'vue';
    import { router } from '@inertiajs/vue3';
    import MainLayout from '@/Layouts/MainLayout.vue';
    import DataTable from '@/Components/DataTable.vue';
    import debounce from 'lodash/debounce';
    import { Link } from '@inertiajs/vue3';

    const props = defineProps({ mensalidades: Object, busca: Object });
    defineOptions({ layout: MainLayout });

    const colunas = [
        { label: 'Mensalidade', key: 'mes', sortable: true },
        { label: 'Ano', key: 'ano', sortable: true },
        { label: 'Valor', key: 'valor', sortable: true },
        { label: 'Data de Vencimento', key: 'data_vencimento', sortable: true },
        { label: 'Ações', key: 'actions', sortable: false },
    ];

    const busca = ref(props.busca || '');

    watch(busca, debounce((value) => {
        router.get('/gerencia/mensalidades', { busca: value }, { preserveState: true, replace: true });
    }, 500));

    const handleSort = (key) => {
        router.get('/gerencia/mensalidades', { sort: key }, { preserveState: true, replace: true });
    };
</script>

<template>
  <div class="container-fluid">
    <h2 class="text-pink mb-4">Gerenciar Mensalidades</h2>

    <DataTable :colunas="colunas" :linhas="mensalidades" @sort="handleSort">

      <template #header>
        <input
            v-model="busca"
            type="text"
            class="form-control w-25 rounded-pill"
            placeholder="Buscar..."
        >
      </template>

      <template #name="{ linha }">
        <div class="d-flex align-items-center">
            <div class="avatar-circle me-2">{{ linha.name.charAt(0) }}</div>
            <span class="fw-bold">{{ linha.name }}</span>
        </div>
      </template>

       <template #actions="{ linha }">
            <div class="d-flex gap-2">
                <Link
                    :href="route('mensalidades.edit', linha.id)"
                    class="btn btn-sm btn-outline-pink"
                    title="Detalhes">
                    <i class="bi bi-pencil"></i> ✏️
                </Link>
            </div>
        </template>

        <template #valor="{ linha }">
            <span>
                {{ Number(linha.valor).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }) }}
            </span>
        </template>

          <template #data="{ linha }">
            <span>
                {{new Date(linha.data_vencimento).toLocaleDateString('pt-BR', { timeZone: 'UTC' })}}
            </span>
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

