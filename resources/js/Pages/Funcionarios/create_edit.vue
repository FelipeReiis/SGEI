<script setup>
    import { ref } from 'vue';
    import { useForm } from '@inertiajs/vue3';
    import MainLayout from '@/Layouts/MainLayout.vue';
    import FormSecaoPessoa from '@/Components/FormSecaoPessoa.vue';

    defineOptions({ layout: MainLayout });
    const props = defineProps({ funcionario: Object, profissoes: Array, especialidades: Array, especialidades_salvas: Array });

    const arquivosAnexados = ref([]);

    const formulario = useForm({
        funcionario: {
            id: props.funcionario?.id ?? '',
            nome: props.funcionario?.nome ?? '',
            email: props.funcionario?.email ?? '',
            telefone: props.funcionario?.telefone ?? '',
            rg: props.funcionario?.rg ?? '',
            cpf: props.funcionario?.cpf ?? '',
            tipo_servico: props.funcionario?.id_profissao ?? '',
            especialidades: props.especialidades_salvas ?? [],
            data_nascimento: props.funcionario?.data_nascimento ?? '',
            cep: props.funcionario?.endereco?.cep ?? '',
            logradouro: props.funcionario?.endereco?.logradouro ?? '',
            bairro: props.funcionario?.endereco?.bairro ?? '',
            complemento: props.funcionario?.endereco?.complemento ?? '',
            numero: props.funcionario?.endereco?.numero ?? '',
            agencia: props.funcionario?.bancario?.agencia ?? '',
            conta: props.funcionario?.bancario?.conta ?? '',
            pix: props.funcionario?.bancario?.pix ?? '',
            banco: props.funcionario?.bancario?.banco ?? '',
            documentos: [] // Array de arquivos que será enviado ao backend
        },
    });
    const TAMANHO_MAXIMO_MB = 5;
    const TAMANHO_MAXIMO_BYTES = TAMANHO_MAXIMO_MB * 1024 * 1024;
    // Função de upload de múltiplos arquivos
    const uploadDocumento = (event) => {
        const files = event.target.files;
        if (!files.length) return;

        Array.from(files).forEach(file => {
            // 💡 1. VALIDAÇÃO DE TAMANHO
            if (file.size > TAMANHO_MAXIMO_BYTES) {
                alert(`O arquivo "${file.name}" excede o limite máximo permitido de ${TAMANHO_MAXIMO_MB}MB.`);
                return; // Pulamos este arquivo
            }

            // 2. Verifica se já não foi adicionado
            const jaExiste = arquivosAnexados.value.some(f => f.file.name === file.name && f.file.size === file.size);

            if (!jaExiste) {
                arquivosAnexados.value.push({
                    file: file,
                    url: URL.createObjectURL(file)
                });
            }
        });

        // Atualiza a propriedade no formulário do Inertia (manda só os objetos File)
        formulario.funcionario.documentos = arquivosAnexados.value.map(item => item.file);

        event.target.value = ''; // Reseta o input file
    };

    // Remove arquivo individual da lista
    const removerArquivo = (index) => {
        URL.revokeObjectURL(arquivosAnexados.value[index].url);
        arquivosAnexados.value.splice(index, 1);
        formulario.funcionario.documentos = arquivosAnexados.value.map(item => item.file);
    };

    // Formata o tamanho do arquivo
    const formatarTamanho = (bytes) => {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    };

    const enviar = () => {
        // ATENÇÃO: No Inertia, para enviar arquivos em Edição (PUT), usaremos o método POST com _method: 'PUT'
        // para evitar bugs do PHP não ler multipart/form-data via PUT.
        if (props.funcionario) {
            formulario.post(route('funcionarios.update', props.funcionario.id), {
                headers: { 'X-HTTP-Method-Override': 'PUT' }
            });
        } else {
            formulario.post(route('funcionarios.store'));
        }
    };
</script>

<template>
  <div class="card-custom shadow-sm p-4">
    <form @submit.prevent="enviar">

      <div class="accordion mt-4" id="accordionFuncionario">

        <div class="accordion-item border-0 mb-3 shadow-sm rounded-4 overflow-hidden">

            <div class="p-4 border-top">
                <FormSecaoPessoa
                    novalidate
                    v-model="formulario.funcionario"
                    titulo="Dados do Funcionário"
                    icon="👤"
                    :mostrarPessoa="true"
                    :mostrarEndereco="true"
                    :mostrarBanco="true"
                    :erros="formulario.errors"
                    prefixo="funcionario"
                >
                    <template #extra-field>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Tipo de Serviço*</label>
                                <select
                                    v-model="formulario.funcionario.tipo_servico"
                                    class="form-select custom-input"
                                    :class="{'is-invalid': formulario.errors && formulario.errors['funcionario.tipo_servico']}"
                                >
                                    <option value="">Selecione...</option>
                                    <option v-for="profissao in profissoes" :key="profissao.id" :value="profissao.id">{{profissao.descricao}}</option>
                                </select>
                                <div v-if="formulario.errors['funcionario.tipo_servico']" class="invalid-feedback">
                                    {{ formulario.errors['funcionario.tipo_servico'] }}
                                </div>
                            </div>

                            <div class="col-md-8" v-if="formulario.funcionario.tipo_servico == 5">
                                <label class="form-label">Especialidade (Multiselect)*</label>

                                <div class="d-flex flex-wrap gap-3 p-3 border rounded bg-light">
                                    <div v-for="esp in especialidades" :key="esp.id" class="form-check">
                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            :id="esp.id"
                                            :value="esp.id"
                                            v-model="formulario.funcionario.especialidades"
                                        >
                                        <label class="form-check-label" :for="esp.id">{{ esp.nome }}</label>
                                    </div>
                                </div>

                                <div v-if="formulario.errors['funcionario.especialidades']" class="text-danger small mt-1">
                                    {{ formulario.errors['funcionario.especialidades'] }}
                                </div>
                            </div>
                        </div>

                        <!-- 💡 NOVA SEÇÃO: INPUT DE DOCUMENTOS MÚLTIPLOS 💡 -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <label class="form-label fw-bold text-secondary">📁 Documentos do Funcionário (RG, CNH, Contrato, etc.)</label>

                                <input
                                    type="file"
                                    class="form-control custom-input"
                                    multiple
                                    accept="image/*,application/pdf"
                                    @change="uploadDocumento"
                                >

                                <!-- Exibição dos arquivos anexados -->
                                <div v-if="arquivosAnexados.length > 0" class="mt-3 p-3 bg-light rounded-3 border">
                                    <span class="text-muted small fw-bold d-block mb-2">
                                        Documentos anexados para envio (clique para visualizar):
                                    </span>

                                    <div class="d-flex flex-column gap-2">
                                        <div
                                            v-for="(item, index) in arquivosAnexados"
                                            :key="index"
                                            class="d-flex align-items-center justify-content-between bg-white p-2 rounded-2 shadow-sm border-start border-pink border-3"
                                        >
                                            <div class="d-flex align-items-center gap-2 overflow-hidden me-2">
                                                <i class="bi bi-file-earmark-text text-pink fs-5 flex-shrink-0"></i>

                                                <a
                                                    :href="item.url"
                                                    target="_blank"
                                                    class="text-truncate small fw-semibold text-decoration-none text-dark link-primary"
                                                    :title="'Clique para visualizar ' + item.file.name"
                                                >
                                                    {{ item.file.name }}
                                                    <i class="bi bi-box-arrow-up-right ms-1 text-muted" style="font-size: 0.75rem;"></i>
                                                </a>

                                                <span class="badge bg-light text-dark border small flex-shrink-0">
                                                    {{ formatarTamanho(item.file.size) }}
                                                </span>
                                            </div>

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
                            </div>
                        </div>

                    </template>
                </FormSecaoPessoa>
            </div>

        </div>
      </div>

      <div class="d-flex justify-content-end gap-2 mt-5">
        <button type="submit" class="btn btn-pink px-5 fw-bold" :disabled="formulario.processing">
           {{ formulario.processing ? 'Processando...' : (props.funcionario ? 'Atualizar Tudo' : 'Salvar Cadastro Completo') }}
        </button>
      </div>
    </form>
  </div>
</template>

<style scoped>
/* Mantendo seu estilo Pink */
.accordion-button:not(.collapsed) {
    background-color: #fcecf4;
    color: #ff0055;
    box-shadow: none;
}
.accordion-button:focus { box-shadow: none; border-color: rgba(255, 0, 85, 0.1); }
.form-check-input:checked { background-color: #ff0055; border-color: #ff0055; }
</style>
