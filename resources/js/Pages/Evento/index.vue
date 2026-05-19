<script setup>
    import { ref, watch } from 'vue';
    import { router } from '@inertiajs/vue3';
    import MainLayout from '@/Layouts/MainLayout.vue';
    import DataTable from '@/Components/DataTable.vue';
    import debounce from 'lodash/debounce';
    import { Link } from '@inertiajs/vue3';
    import Swal from 'sweetalert2';

    const props = defineProps({ eventos: Object, busca: Object });
    defineOptions({ layout: MainLayout });

    const colunas = [
        { label: 'Evento', key: 'nome', sortable: true },
        { label: 'Data', key: 'data', sortable: true },
        { label: 'Valor', key: 'valor', sortable: true },
        { label: 'Status', key: 'status', sortable: true },
        { label: 'Ações', key: 'actions', sortable: false },
    ];

    const busca = ref(props.busca || '');

    watch(busca, debounce((value) => {
        router.get('/eventos', { busca: value }, { preserveState: true, replace: true });
    }, 500));

    const handleSort = (key) => {
        router.get('/eventos', { sort: key }, { preserveState: true, replace: true })
        console.log("Ordenar por:", key);
    };

    const confirmarExclusao = (id, nome) => {
        Swal.fire({
            title: 'Tem certeza?',
            text: `Você deseja excluir o evento "${nome}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ff0055',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sim, excluir!',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                router.delete(route('eventos.destroy', id), {
                    onSuccess: () => {
                        Swal.fire({
                            title: 'Excluído!',
                            text: 'O evento foi removido com sucesso.',
                            icon: 'success',
                            confirmButtonColor: '#ff0055'
                        });
                    },
                    onError: () => {
                        Swal.fire({
                            title: 'Erro!',
                            text: 'Não foi possível excluir o evento.',
                            icon: 'error',
                            confirmButtonColor: '#ff0055'
                        });
                    }
                });
            }
        });
    };

</script>

<template>
  <div class="container-fluid">
    <h2 class="text-pink mb-4">Gerenciar Eventos</h2>

    <DataTable :colunas="colunas" :linhas="eventos" @sort="handleSort">

      <template #header>
        <input
            v-model="busca"
            type="text"
            class="form-control w-25 rounded-pill"
            placeholder="Buscar..."
        >
        <Link href="/eventos/create"><button class="btn btn-pink rounded-pill" >+ Novo Evento</button></Link>
      </template>

      <template #name="{ linha }">
        <div class="d-flex align-items-center">
            <div class="avatar-circle me-2">{{ linha.name.charAt(0) }}</div>
            <span class="fw-bold">{{ linha.name }}</span>
        </div>
      </template>

      <!-- <template #actions="{ linha }">
        <button class="btn btn-sm btn-outline-secondary me-1">✏️</button>
        <button class="btn btn-sm btn-outline-danger">🗑️</button>
      </template> -->

       <template #actions="{ linha }">
            <div class="d-flex gap-2">
                <Link
                    :href="route('eventos.edit', linha.id)"
                    class="btn btn-sm btn-outline-pink"
                    title="Editar Evento">
                    <i class="bi bi-pencil"></i> ✏️
                </Link>

                <button
                    @click="confirmarExclusao(linha.id, linha.nome)"
                    class="btn btn-sm btn-outline-danger"
                    title="Excluir Aluno"
                >
                    <i class="bi bi-trash"></i> 🗑️
                </button>

            </div>
        </template>

        <template #valor="{ linha }">
            <span>
                {{ Number(linha.valor).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }) }}
            </span>
        </template>

          <template #data="{ linha }">
            <span>
                {{new Date(linha.data).toLocaleDateString('pt-BR', { timeZone: 'UTC' })}}
            </span>
        </template>

    <template #status="{ linha }">
        <span class="badge" :class="{
            'bg-warning text-dark': linha.status === 'nao_iniciado',
            'bg-info text-white': linha.status === 'em_andamento',
            'bg-success': linha.status === 'finalizado'
        }">
            {{
                linha.status === 'nao_iniciado' ? 'Não Iniciado' :
                linha.status === 'em_andamento' ? 'Em Andamento' : 'Finalizado'
            }}
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
