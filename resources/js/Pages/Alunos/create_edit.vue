<script setup>
    import { ref, watch } from 'vue';
    import { useForm } from '@inertiajs/vue3';
    import MainLayout from '@/Layouts/MainLayout.vue';
    import FormSecaoPessoa from '@/Components/FormSecaoPessoa.vue';

    defineOptions({ layout: MainLayout });
    const props = defineProps({ aluno: Object });

    const formulario = useForm({
        // Estrutura organizada por objetos
        aluno: {
            id: props.aluno?.id ?? '',
            nome: props.aluno?.nome ?? '',
            email: props.aluno?.email ?? '',
            telefone: props.aluno?.telefone ?? '',
            rg: props.aluno?.rg ?? '',
            cpf: props.aluno?.cpf ?? '',
            data_nascimento: props.aluno?.data_nascimento ?? '',
            cep: props.aluno?.cep ?? '',
            logradouro: props.aluno?.logradouro ?? '',
            bairro: props.aluno?.bairro ?? '',
            complemento: props.aluno?.complemento ?? '',
            numero: props.aluno?.numero ?? '',
            turma_id: props.aluno?.turma_id ?? '',
        },
        pedagogico: props.aluno?.pedagogico ?? { nome: '', cpf: '', email: '', telefone: '', rg: '', data_nascimento: '', cep:'', logradouro: '', bairro:'', complemento:'', numero:''},
        financeiro: props.aluno?.financeiro ?? { nome: '', cpf: '', email: '', telefone: '', rg: '', data_nascimento: '', cep:'', logradouro: '', bairro:'', complemento:'', numero:'' },
        mesmo_responsavel: false
    });

    // Lógica do Checkbox: Se marcar "mesmo responsável", copia o pedagógico para o financeiro
    watch(() => formulario.mesmo_responsavel, (valor) => {
        if (valor) {
            formulario.financeiro = { ...formulario.pedagogico };
        }
    });

    const enviar = () => {
        const acao = props.aluno ? 'put' : 'post';
        const rota = props.aluno ? route('alunos.update', props.aluno.id) : route('alunos.store');

        formulario[acao](rota);
    };
    const abertoPedagogico = ref(false);
    const abertoFinanceiro = ref(false);
</script>

<template>
  <div class="card-custom shadow-sm p-4">
    <form @submit.prevent="enviar">

      <FormSecaoPessoa
        v-model="formulario.aluno"
        titulo="Dados do Aluno"
        icon="👤"
        :mostrarEndereco="true"
        :erros="formulario.errors"
      >
        <template #extra-field>
            <label class="form-label">Turma</label>
            <select v-model="formulario.aluno.turma_id" class="form-select custom-input">
                <option value="">Selecione...</option>
                <option value="1">9º Ano A</option>
            </select>
        </template>
      </FormSecaoPessoa>

      <div class="accordion mt-4" id="accordionResponsaveis">

        <div class="accordion-item border-0 mb-3 shadow-sm rounded-4 overflow-hidden">
            <div class="p-3 bg-light d-flex justify-content-between align-items-center cursor-pointer accordion-header"@click="abertoPedagogico = !abertoPedagogico">
                <h6 class="mb-0 fw-bold">📚 Responsável Pedagógico</h6>
                <span>{{ abertoPedagogico ? '▲' : '▼' }}</span>
            </div>

            <div v-show="abertoPedagogico" class="p-4 border-top">
                <FormSecaoPessoa v-model="formulario.pedagogico" titulo="Informações" icon="🛡️" :mostrarEndereco="true" :erros="formulario.errors"/>
            </div>
        </div>

        <div class="accordion-item border-0 mb-3 shadow-sm rounded-4 overflow-hidden">
            <div class="p-3 bg-light d-flex justify-content-between align-items-center cursor-pointer accordion-header"@click="abertoFinanceiro = !abertoFinanceiro">
                <h6 class="mb-0 fw-bold">📚 Responsável Financeiro</h6>
                <span>{{ abertoFinanceiro ? '▲' : '▼' }}</span>
            </div>

            <div class="form-check form-switch ms-auto me-3">
                <input class="form-check-input" type="checkbox" v-model="formulario.mesmo_responsavel" id="switchMesmo">
                <label class="form-check-label small" for="switchMesmo">Mesmo que Pedagógico?</label>
            </div>

            <div v-show="abertoFinanceiro" class="p-4 border-top">
                <FormSecaoPessoa v-model="formulario.financeiro" titulo="Informações" icon="🛡️" :mostrarEndereco="true" :erros="formulario.errors"/>
            </div>
        </div>

      </div>

      <div class="d-flex justify-content-end gap-2 mt-5">
        <button type="submit" class="btn btn-pink px-5 fw-bold" :disabled="formulario.processing">
           {{ formulario.processing ? 'Processando...' : (props.aluno ? 'Atualizar Tudo' : 'Salvar Cadastro Completo') }}
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
