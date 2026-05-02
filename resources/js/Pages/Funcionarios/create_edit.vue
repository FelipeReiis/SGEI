<script setup>
    import { ref, watch } from 'vue';
    import { useForm } from '@inertiajs/vue3';
    import MainLayout from '@/Layouts/MainLayout.vue';
    import FormSecaoPessoa from '@/Components/FormSecaoPessoa.vue';

    defineOptions({ layout: MainLayout });
    const props = defineProps({ funcionario: Object, profissoes: Array, especialidades: Array, especialidades_salvas: Array });

    const formulario = useForm({
        funcionario:{
            id: props.funcionario?.id ?? '',
            nome: props.funcionario?.nome ?? '',
            email: props.funcionario?.email ?? '',
            telefone: props.funcionario?.telefone ?? '',
            rg: props.funcionario?.rg ?? '',
            cpf: props.funcionario?.cpf ?? '',
            tipo_servico:props.funcionario?.id_profissao ?? '',
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
        },
    })

    const enviar = () => {
        const acao = props.funcionario ? 'put' : 'post';
        const rota = props.funcionario ? route('funcionarios.update', props.funcionario.id) : route('funcionarios.store');

        formulario[acao](rota);
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

                            <div class="col-md-4" v-if="formulario.funcionario.tipo_servico == 5">
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
                                        <label class="form-check-label">{{ esp.nome }}</label>
                                    </div>
                                </div>

                                <div v-if="formulario.errors['funcionario.especialidades']" class="text-danger small mt-1">
                                    {{ formulario.errors['funcionario.especialidades'] }}
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
