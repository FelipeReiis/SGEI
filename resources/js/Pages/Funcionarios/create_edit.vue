<script setup>
    import { ref, watch } from 'vue';
    import { useForm } from '@inertiajs/vue3';
    import MainLayout from '@/Layouts/MainLayout.vue';
    import FormSecaoPessoa from '@/Components/FormSecaoPessoa.vue';

    defineOptions({ layout: MainLayout });
    const props = defineProps({ funcionario: Object });

    const formulario = useForm({
        funcionario:{
            id: props.funcionario?.id ?? '',
            nome: props.funcionario?.nome ?? '',
            email: props.funcionario?.email ?? '',
            telefone: props.funcionario?.telefone ?? '',
            rg: props.funcionario?.rg ?? '',
            cpf: props.funcionario?.cpf ?? '',
            data_nascimento: props.funcionario?.data_nascimento ?? '',
            cep: props.funcionario?.cep ?? '',
            logradouro: props.funcionario?.logradouro ?? '',
            bairro: props.funcionario?.bairro ?? '',
            complemento: props.funcionario?.complemento ?? '',
            numero: props.funcionario?.numero ?? '',
            agencia: props.funcionario?.agencia ?? '',
            conta: props.funcionario?.conta ?? '',
            pix: props.funcionario?.pix ?? '',
            banco: props.funcionario?.banco ?? '',
        },
    })

    const enviar = () => {
        console.log(formulario.errors)
        if (props.funcionario) {
            formulario.post(route('funcionarios.update', props.funcionario.id), {
                _method: 'put',
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
