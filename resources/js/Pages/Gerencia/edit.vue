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
    // Estado para controlar qual aluno está selecionado no momento (Checkbox ativo)
    const alunoSelecionadoId = ref(null);
    const buscaAluno = ref(props.busca || '');
    const arquivosAnexados = ref([]);
    // Formulário do Inertia
    const formulario = useForm({
        evento_id: props.evento?.id ?? '',
        aluno_id: '',
        aluno_nome: '',
        aluno_cpf: '',
        fin_nome: '',
        fin_cpf: '',
        comprovantes: [],
        forma_pagamento: '',
        qtd_parcelas: ''
    });

    // Monitora o filtro de busca de alunos
    watch(buscaAluno, debounce((value) => {
        router.get(route('gerencia.edit', props.evento.id), { busca: value }, { preserveState: true, replace: true });
    }, 500));

    const handleSort = (key) => {
         router.get(route('gerencia.edit', props.evento.id), { sort: key }, { preserveState: true, replace: true })
    };

    // Mágica do Checkbox: Ao mudar o aluno selecionado, preenche ou limpa o formulário de cima
    watch(alunoSelecionadoId, (novoId) => {
        if (novoId) {
            // Encontra o objeto do aluno clicado dentro da lista de alunos
            const aluno = props.alunos.data.find(a => a.aluno_id === novoId);
            if (aluno) {
                formulario.aluno_id = aluno.aluno_id;
                formulario.aluno_nome = aluno.aluno_nome;
                formulario.aluno_cpf = aluno.aluno_cpf;
                formulario.fin_nome = aluno.fin_nome;
                formulario.forma_pagamento = aluno.forma_pagamento;
                formulario.qtd_parcelas = aluno.qtd_parcela;
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
        formulario.forma_pagamento = '';
        formulario.qtd_parcelas = '';
        formulario.comprovantes = [];
        alunoSelecionadoId.value = null;
        arquivosAnexados.value = [];
    };

    // Captura o arquivo de imagem do comprovante
    const uploadComprovante = (event) => {
        const files = event.target.files;
        if (!files.length) return;

        // Convertemos a FileList do HTML em um Array do JS e jogamos no nosso acumulador
        Array.from(files).forEach(file => {
            // Opcional: Evitar arquivos duplicados com o mesmo nome e tamanho
            const jaExiste = arquivosAnexados.value.some(f => f.name === file.name && f.size === file.size);

            if (!jaExiste) {
                arquivosAnexados.value.push(file);
            }
        });

        // Sincroniza o array local com o formulário do Inertia que será enviado ao backend
        formulario.comprovantes = arquivosAnexados.value;

        // Reseta o input de arquivo para permitir selecionar o mesmo arquivo novamente se o usuário quiser
        event.target.value = '';
    };

    const removerArquivo = (index) => {
        arquivosAnexados.value.splice(index, 1);
        formulario.comprovantes = arquivosAnexados.value;
    };

    const formatarTamanho = (bytes) => {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
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

    const forcarDownload = () => {

        window.location.href = `/export/evento_aluno/${formulario.evento_id}`;
    };
</script>

<template>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-pink mb-0">Inscrições: {{ props.evento?.nome }}</h2>
            <Link :href="route('gerencia.index')" class="btn btn-outline-secondary rounded-pill">
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
                    <div class="col-md-6 mt-3">
                        <label class="form-label text-muted small">Tipo de Pagamento*</label>
                        <select v-model="formulario.forma_pagamento" class="form-select bg-light" :disabled="!formulario.aluno_id" readonly required>
                            <option value="" disabled selected>Selecione a forma de pagamento...</option>
                            <option value="Pix">Pix</option>
                            <option value="Cartão de Crédito">Cartão de Crédito</option>
                            <option value="Cartão de Débito">Cartão de Débito</option>
                            <option value="Boleto">Boleto</option>
                            <option value="Dinheiro">Dinheiro</option>
                        </select>
                        <div v-if="formulario.errors.forma_pagamento" class="text-danger small mt-1">
                            {{ formulario.errors.forma_pagamento }}
                        </div>
                    </div>
                    <div class="col-md-6 mt-3">
                        <label class="form-label text-muted small">Quantidade de Parcelas*</label>
                        <input type="number" v-model="formulario.qtd_parcelas" class="form-control bg-light" min="1" step="1" placeholder="Ex: 1" :disabled="!formulario.aluno_id" readonly required>
                        <div v-if="formulario.errors.qtd_parcelas" class="text-danger small mt-1">
                            {{ formulario.errors.qtd_parcelas }}
                        </div>
                    </div>

                    <div class="col-md-12 mt-4">
                        <label class="form-label fw-bold text-pink">Anexar Imagem do Comprovante* (Permite múltiplos)</label>

                        <div class="d-flex gap-3 align-items-start flex-column flex-md-row">
                            <!-- Input de Arquivo (Repare o atributo 'multiple') -->
                            <input
                                type="file"
                                @change="uploadComprovante"
                                class="form-control custom-input w-100 w-md-50"
                                :class="{'is-invalid': formulario.errors.comprovantes}"
                                accept="image/*"
                                :disabled="!formulario.aluno_id"
                                multiple
                            >

                            <button
                                type="submit"
                                class="btn btn-pink px-5 fw-bold w-100 w-md-auto ms-md-auto align-self-stretch align-self-md-start"
                                :disabled="formulario.processing || !formulario.aluno_id || arquivosAnexados.length === 0"
                            >
                                {{ formulario.processing ? 'Processando...' : 'Confirmar Inscrição' }}
                            </button>
                        </div>

                        <!-- 👇 LISTAGEM DOS ARQUIVOS SELECIONADOS 👇 -->
                        <div v-if="arquivosAnexados.length > 0" class="mt-3 p-3 bg-light rounded-3 border">
                            <span class="text-muted small fw-bold d-block mb-2">Arquivos selecionados para envio:</span>

                            <div class="d-flex flex-column gap-2">
                                <div
                                    v-for="(arquivo, index) in arquivosAnexados"
                                    :key="index"
                                    class="d-flex align-items-center justify-content-between bg-white p-2 rounded-2 shadow-sm border-start border-pink border-3"
                                >
                                    <div class="d-flex align-items-center gap-2 overflow-hidden me-2">
                                        <i class="bi bi-image text-pink fs-5 flex-shrink-0"></i>
                                        <span class="text-truncate small fw-secondary" :title="arquivo.name">
                                            {{ arquivo.name }}
                                        </span>
                                        <span class="badge bg-light text-dark border small flex-shrink-0">
                                            {{ formatarTamanho(arquivo.size) }}
                                        </span>
                                    </div>

                                    <!-- Botão para remover da lista individualmente -->
                                    <button
                                        type="button"
                                        @click="removerArquivo(index)"
                                        class="btn btn-sm btn-outline-danger border-0 p-1"
                                        title="Remover anexo"
                                    >
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- 👆 FIM DA LISTAGEM 👆 -->

                        <div v-if="formulario.errors.comprovantes" class="text-danger small mt-1">
                            {{ formulario.errors.comprovantes }}
                        </div>
                    </div>

                </div>
            </form>
        </div>

        <div class="card-custom shadow-sm p-4 bg-white rounded-4">
            <h5 class="fw-bold mb-3 text-secondary">👥 Listagem de Alunos</h5>

            <DataTable :colunas="colunas" :linhas="props.alunos" @sort="handleSort">

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

                   <button
                        @click="forcarDownload"
                        class="btn btn-sm btn-outline-pink"
                        title="Exportar">
                        <i class="bi bi-file-earmark-excel"></i> 📊 Exportar Excel
                    </button>
                </template>

               <template #selecao="{ linha }">
                    <div class="form-check d-flex justify-content-center">
                        <input
                            type="checkbox"
                            class="form-check-input"
                            :id="'aluno-' + linha.aluno_id"
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
