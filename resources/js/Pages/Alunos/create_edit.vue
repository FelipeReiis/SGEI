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
            id: props.aluno?.aluno_id ?? '',
            nome: props.aluno?.aluno_nome ?? '',
            email: props.aluno?.aluno_email ?? '',
            telefone: props.aluno?.aluno_telefone ?? '',
            rg: props.aluno?.aluno_rg ?? '',
            cpf: props.aluno?.aluno_cpf ?? '',
            data_nascimento: props.aluno?.aluno_data_nascimento ?? '',
            cep: props.aluno?.aluno_cep ?? '',
            logradouro: props.aluno?.aluno_logradouro ?? '',
            bairro: props.aluno?.aluno_bairro ?? '',
            complemento: props.aluno?.aluno_complemento ?? '',
            numero: props.aluno?.aluno_numero ?? '',
        },

        pedagogico: props.aluno?.pedagogico ?? { nome: props.aluno?.pedag_nome ?? '', cpf:  props.aluno?.pedag_cpf ?? '', email:  props.aluno?.pedag_email ?? '', telefone: props.aluno?.pedag_telefone ?? '', rg:  props.aluno?.pedag_rg ?? '', data_nascimento:  props.aluno?.pedag_data_nascimento ?? '', cep: props.aluno?.pedag_cep ?? '', logradouro:  props.aluno?.pedag_logradouro ?? '', bairro: props.aluno?.pedag_bairro ?? '', complemento:  props.aluno?.pedag_complemento ?? '', id:props.aluno?.pedag_id ?? '', numero:props.aluno?.pedag_numero ?? ''},

        financeiro: props.aluno?.financeiro ??  { nome: props.aluno?.fin_nome ?? '', cpf:  props.aluno?.fin_cpf ?? '', email:  props.aluno?.fin_email ?? '', telefone: props.aluno?.fin_telefone ?? '', rg: props.aluno?.fin_rg ?? '', data_nascimento:  props.aluno?.fin_data_nascimento ?? '', cep: props.aluno?.fin_cep ?? '', logradouro: props.aluno?.fin_logradouro ?? '', bairro: props.aluno?.fin_bairro ?? '', complemento:props.aluno?.fin_complemento ?? '', id:props.aluno?.fin_id ?? '', numero:props.aluno?.fin_numero ?? ''},

        bancario: props.aluno?.bancario ?? {agencia: props.aluno?.agencia ?? '', conta: props.aluno?.conta ?? '', pix: props.aluno?.pix ?? '', banco: props.aluno?.banco ?? ''},

        mesmo_responsavel: props.aluno?.fin_id && props.aluno?.fin_id == props.aluno?.pedag_id ? true : false
    });
    // Lógica do Checkbox: Se marcar "mesmo responsável", copia o pedagógico para o financeiro
    watch(() => formulario.mesmo_responsavel, (valor) => {
        if (valor) {
            formulario.financeiro = { ...formulario.pedagogico };
        }
    });

    const enviar = () => {
        const acao = props.aluno ? 'put' : 'post';
        const rota = props.aluno ? route('alunos.update', props.aluno.aluno_id) : route('alunos.store');

        formulario[acao](rota);
    };
    const abertoPedagogico = ref(false);
    const abertoFinanceiro = ref(false);
    const abertoBanco = ref(false);
</script>

<template>
  <div class="card-custom shadow-sm p-4">
    <form @submit.prevent="enviar">

      <FormSecaoPessoa
        v-model="formulario.aluno"
        titulo="Dados do Aluno"
        icon="👤"
        :mostrarPessoa="true"
        :mostrarEndereco="true"
        :erros="formulario.errors"
        prefixo="aluno"
      >
      </FormSecaoPessoa>

      <div class="accordion mt-4" id="accordionResponsaveis">

        <div class="accordion-item border-0 mb-3 shadow-sm rounded-4 overflow-hidden">
            <div class="p-3 bg-light d-flex justify-content-between align-items-center cursor-pointer accordion-header"@click="abertoPedagogico = !abertoPedagogico">
                <h6 class="mb-0 fw-bold">📚 Responsável Pedagógico</h6>
                <span>{{ abertoPedagogico ? '▲' : '▼' }}</span>
            </div>

            <div v-show="abertoPedagogico" class="p-4 border-top">
                <FormSecaoPessoa v-model="formulario.pedagogico" titulo="Informações" icon="🛡️" :mostrarPessoa="true" :mostrarEndereco="true" prefixo="financeiro" :erros="formulario.errors"/>
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
                <FormSecaoPessoa v-model="formulario.financeiro" titulo="Informações" icon="🛡️" :mostrarPessoa="true" :mostrarEndereco="true" prefixo="financeiro" :erros="formulario.errors"/>
                <FormSecaoPessoa v-model="formulario.bancario" titulo="Dados Bancários" :mostrarBanco="true" :mostrarEndereco="false" :mostrarPessoa="false"  prefixo="bancario" :erros="formulario.errors"/>
            </div>

            <div v-show="abertoBanco" class="p-4 border-top">

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
