<script setup>
    import { ref, watch } from 'vue';
    import { useForm, router, Link } from '@inertiajs/vue3';
    import MainLayout from '@/Layouts/MainLayout.vue';
    import DataTable from '@/Components/DataTable.vue';
    import debounce from 'lodash/debounce';

    defineOptions({ layout: MainLayout });

    const props = defineProps({
        evento: Object,       // Dados do evento atual
        alunos: Object,        // Lista de todos os alunos para a DataTable
        inscritosIds: Array,  // Array simples com IDs dos alunos já inscritos, ex: [1, 3, 5]
        busca: String
    });

    // Colunas para a tabela de alunos na parte inferior
    const colunas = [
        { label: 'Selecionar', key: 'selecao', sortable: false },
        { label: 'Aluno', key: 'aluno_nome', sortable: true },
        { label: 'CPF', key: 'aluno_cpf', sortable: true },
        { label: 'Status no Evento', key: 'status_inscricao', sortable: false },
    ];
    console.log(props.inscritosIds);
    // Estado para controlar qual aluno está selecionado no momento (Checkbox ativo)
    const alunoSelecionadoId = ref(null);
    const buscaAluno = ref(props.busca || '');

    // Formulário do Inertia
    const formulario = useForm({
        evento_id: props.evento?.id ?? '',
        aluno_id: '',
        aluno_nome: '',
        aluno_cpf: '',
        fin_nome: '',
        fin_cpf: '',
        comprovante: null,
    });

    // Monitora o filtro de busca de alunos
    watch(buscaAluno, debounce((value) => {
        router.get(route('gerencia.edit', props.evento.id), { busca: value }, { preserveState: true, replace: true });
    }, 500));

    // Mágica do Checkbox: Ao mudar o aluno selecionado, preenche ou limpa o formulário de cima
    watch(alunoSelecionadoId, (novoId) => {
        if (novoId) {
            // Encontra o objeto do aluno clicado dentro da lista de alunos
            const aluno = props.alunos.data.find(a => a.aluno_id === novoId);
            if (aluno) {
                console.log(aluno.aluno_id)
                formulario.aluno_id = aluno.aluno_id;
                formulario.aluno_nome = aluno.aluno_nome;
                formulario.aluno_cpf = aluno.aluno_cpf;
                formulario.fin_nome = aluno.fin_nome;
                formulario.fin_cpf = aluno.fin_cpf; // Ou o campo correto de CPF financeiro se houver
            }
        } else {
            // Se desmarcar, limpa os campos do aluno no formulário
            limparCamposAluno();
        }
    });

    const limparCamposAluno = () => {
        formulario.aluno_id = '';
        formulario.aluno_nome = '';
        formulario.aluno_cpf = '';
        formulario.fin_nome = '';
        formulario.fin_cpf = '';
        formulario.comprovante = null;
        alunoSelecionadoId.value = null;
    };

    // Captura o arquivo de imagem do comprovante
    const uploadComprovante = (e) => {
        formulario.comprovante = e.target.files[0];
    };

    // Executa a inscrição do aluno no evento
    const salvarInscricao = () => {
        if (!formulario.aluno_id) {
            alert('Por favor, selecione um aluno na tabela abaixo.');
            return;
        }

        // Como enviamos arquivo, usamos POST para a rota que vai salvar na tabela pivô
        formulario.post(route('gerencia.store'), {
            forceFormData: true,
            preserveScroll: true,
            onSuccess: () => {
                // Limpa o formulário superior para a próxima inscrição
                limparCamposAluno();
            }
        });
    };

    // Formata o valor do evento para exibição amigável
    const formatarMoeda = (valor) => {
        return Number(valor).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
    };
    const alternarSelecao = (id) => {
        // Se o usuário clicar no que já estava selecionado, ele desmarca (volta para null)
        if (alunoSelecionadoId.value === id) {
            alunoSelecionadoId.value = null;
        } else {
            // Se clicou em um novo, define o ID dele como o único ativo
            alunoSelecionadoId.value = id;
        }
    };
</script>

<template>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-pink mb-0">Inscrições: {{ props.evento?.nome }}</h2>
            <Link :href="route('eventos.index')" class="btn btn-outline-secondary rounded-pill">
                Voltar para Eventos
            </Link>
        </div>

        <div class="card-custom shadow-sm p-4 mb-4 bg-white rounded-4">
            <h5 class="fw-bold mb-3 text-secondary">📍 Painel de Vinculação</h5>
            <form @submit.prevent="salvarInscricao">
                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label text-muted small">Nome do Evento</label>
                        <input type="text" :value="props.evento?.nome" class="form-control bg-light" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small">Valor do Evento</label>
                        <input type="text" :value="formatarMoeda(props.evento?.valor)" class="form-control bg-light" readonly>
                    </div>

                    <hr class="my-3 text-muted opacity-25">

                    <div class="col-md-4">
                        <label class="form-label text-muted small">Nome do Aluno</label>
                        <input type="text" v-model="formulario.aluno_nome" class="form-control bg-light" placeholder="Selecione na tabela abaixo..." readonly required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label text-muted small">CPF do Aluno</label>
                        <input type="text" v-model="formulario.aluno_cpf" class="form-control bg-light" readonly required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted small">Responsável Financeiro</label>
                        <input type="text" v-model="formulario.fin_nome" class="form-control bg-light" readonly required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label text-muted small">CPF Financeiro</label>
                        <input type="text" v-model="formulario.fin_cpf" class="form-control bg-light" readonly required>
                    </div>

                    <div class="col-md-12 mt-3">
                        <label class="form-label fw-bold text-pink">Anexar Imagem do Comprovante*</label>
                        <div class="d-flex gap-3 align-items-center">
                            <input
                                type="file"
                                @change="uploadComprovante"
                                class="form-control custom-input w-50"
                                :class="{'is-invalid': formulario.errors.comprovante}"
                                accept="image/*"
                                :disabled="!formulario.aluno_id"
                                required
                            >
                            <button
                                type="submit"
                                class="btn btn-pink px-5 fw-bold"
                                :disabled="formulario.processing || !formulario.aluno_id"
                            >
                                {{ formulario.processing ? 'Processando...' : 'Confirmar Inscrição' }}
                            </button>
                        </div>
                        <div v-if="formulario.errors.comprovante" class="text-danger small mt-1">
                            {{ formulario.errors.comprovante }}
                        </div>
                    </div>

                </div>
            </form>
        </div>

        <div class="card-custom shadow-sm p-4 bg-white rounded-4">
            <h5 class="fw-bold mb-3 text-secondary">👥 Listagem de Alunos</h5>

            <DataTable :colunas="colunas" :linhas="props.alunos">

                <div class="d-flex justify-content-center mt-3">
                    <nav>
                        <ul class="pagination">
                            <li v-for="(link, k) in props.alunos.links" :key="k" class="page-item" :class="{ 'active': link.active, 'disabled': !link.url }">
                                <Link
                                    :href="link.url || '#'"
                                    v-html="link.label"
                                    class="page-link text-pink-pagination"
                                    preserve-scroll
                                    preserve-state
                                />
                            </li>
                        </ul>
                    </nav>
                </div>

                <template #header>
                    <input
                        v-model="buscaAluno"
                        type="text"
                        class="form-control w-25 rounded-pill"
                        placeholder="Buscar aluno por nome ou CPF..."
                    >
                </template>

               <template #selecao="{ linha }">
                    <div class="form-check d-flex justify-content-center">
                        <input
                            type="checkbox"
                            class="form-check-input"
                            :id="'aluno-' + linha.aluno_id"
                            :checked="alunoSelecionadoId === linha.aluno_id || props.inscritosIds.includes(linha.aluno_id)"
                            :disabled="props.inscritosIds.includes(linha.aluno_id)"
                            @change="alternarSelecao(linha.aluno_id)"
                        >
                    </div>
                </template>
                <template #aluno_nome="{ linha }">
                    <span :class="{'text-muted text-decoration-line-through': props.inscritosIds.includes(linha.aluno_id)}">
                        {{ linha.aluno_nome }}
                    </span>
                </template>

                <template #status_inscricao="{ linha }">
                    <span v-if="props.inscritosIds.includes(linha.aluno_id)" class="badge bg-success rounded-pill px-3">
                        ✓ Já Inscrito
                    </span>
                    <span v-else-if="alunoSelecionadoId === linha.aluno_id" class="badge bg-pink rounded-pill px-3">
                        ✍️ Selecionado no Painel
                    </span>
                    <span v-else class="badge bg-light text-muted border rounded-pill px-3">
                        Não Vinculado
                    </span>
                </template>
            </DataTable>
        </div>
    </div>
</template>

<style scoped>
.text-pink { color: #ff0055; font-weight: 700; }
.btn-pink { background-color: #ff0055; color: white; border: none; }
.btn-pink:hover:not(:disabled) { background-color: #d90047; color: white; }
.btn-pink:disabled { background-color: #ff80aa; opacity: 0.7; }
.btn-outline-pink { border-color: #ff0055; color: #ff0055; }
.btn-outline-pink:hover { background-color: #ff0055; color: white; }
.form-check-input:checked { background-color: #ff0055; border-color: #ff0055; }
.bg-pink { background-color: #ff0055; color: white; }

.page-item.active .page-link { background-color: #ff0055; border-color: #ff0055; color: white; }
.text-pink-pagination { color: #ff0055; }
</style>
