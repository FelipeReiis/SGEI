<script setup>
    import MainLayout from '@/Layouts/MainLayout.vue';
    import { useForm } from '@inertiajs/vue3';

    defineOptions({ layout: MainLayout });
    const props = defineProps({
        aluno: {
            type: Object,
            default: null // Se for null, estamos criando
        }
    });
   const formulario = useForm({
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
        turma_id: props.aluno?.turma_id ?? '',
    });

    const enviar = () => {
        if(props.aluno){

            formulario.post(route('alunos.store'), {
                onSuccess: () => {
                // O que fazer se salvar com sucesso (ex: fechar modal ou limpar)
                console.log('Aluno salvo com sucesso!');
                },
                onError: (erros) => {
                // O Inertia já preenche o formulario.errors automaticamente
                console.error('Existem erros no formulário', erros);
                }
            });
        }else {
             formulario.put(route('alunos.update', props.aluno.id), {
                onSuccess: () => {
                    // O que fazer se salvar com sucesso (ex: fechar modal ou limpar)
                    console.log('Aluno salvo com sucesso!');
                },
                onError: (erros) => {
                    // O Inertia já preenche o formulario.errors automaticamente
                    console.error('Existem erros no formulário', erros);
                }
            });
        }

    };
</script>
<template>
  <div class="card-custom shadow-sm border-0 p-4">
    <form @submit.prevent="enviar">

      <div class="form-section mb-5">
        <h5 class="section-title"><span class="icon">👤</span> Dados Pessoais</h5>
        <hr class="mb-4">

        <div class="row g-3">
          <div class="col-md-8">
            <label class="form-label">Nome Completo*</label>
            <input type="text" class="form-control custom-input" placeholder="Ex: Maria Oliveira" max="70" v-model="formulario.nome" required>
          </div>

          <div class="col-md-4">
            <label class="form-label">Turma</label>
            <select class="form-select custom-input" v-model="formulario.turma_id">
              <option value="">Selecione...</option>
              <option value="1">9º Ano A</option>
              <option value="2">1º Ano Ensino Médio</option>
            </select>
          </div>

          <div class="col-md-7">
            <label class="form-label">E-mail</label>
            <input type="email" class="form-control custom-input" placeholder="email@exemplo.com" max="50" v-model="formulario.email">
          </div>
          <div class="col-md-5">
            <label class="form-label">Telefone</label>
            <input type="text" class="form-control custom-input" placeholder="(00) 00000-0000" max="15" v-model="formulario.telefone">
          </div>

          <div class="col-md-4">
            <label class="form-label">RG*</label>
            <input type="text" class="form-control custom-input" placeholder="00.000.000-0" v-model="formulario.rg"required>
          </div>
          <div class="col-md-4">
            <label class="form-label">CPF*</label>
            <input type="text" class="form-control custom-input" placeholder="000.000.000-00" v-model="formulario.cpf" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Data de Nascimento*</label>
            <input type="date" class="form-control custom-input" v-model="formulario.data_nascimento" required>
          </div>
        </div>
      </div>

      <div class="form-section mb-4">
        <h5 class="section-title"><span class="icon">📍</span> Endereço</h5>
        <hr class="mb-4">

        <div class="row g-3">
          <div class="col-md-3">
            <label class="form-label">CEP</label>
            <input type="text" class="form-control custom-input" placeholder="00000-000" v-model="formulario.cep">
          </div>
          <div class="col-md-9">
            <label class="form-label">Logradouro (Rua/Avenida)</label>
            <input type="text" class="form-control custom-input" placeholder="Rua das Flores, nº 10" max="100" v-model="formulario.logradouro">
          </div>

          <div class="col-md-8">
            <label class="form-label">Bairro</label>
            <input type="text" class="form-control custom-input" placeholder="Centro" max="70" v-model="formulario.bairro">
          </div>
          <div class="col-md-4">
            <label class="form-label">Complemento</label>
            <input type="text" class="form-control custom-input" placeholder="Apto 101" max="70" v-model="formulario.complemento">
          </div>
        </div>
      </div>

      <div class="d-flex justify-content-end gap-2 mt-5">
        <button type="button" class="btn btn-outline-secondary px-4">Cancelar</button>
        <button
        type="submit"
        class="btn btn-pink px-5 fw-bold"
        :disabled="formulario.processing">
            <span v-if="formulario.processing" class="spinner-border spinner-border-sm me-2"></span>
            {{ formulario.processing ? 'Salvando...' : 'Salvar Aluno' }}
        </button>
      </div>

    </form>
  </div>
</template>

<style scoped>
/* Estilos herdados e refinados */
.section-title {
  color: #333;
  font-weight: 700;
  display: flex;
  align-items: center;
}
.section-title .icon { margin-right: 10px; }

.form-label {
  font-size: 0.85rem;
  font-weight: 600;
  color: #666;
  margin-bottom: 6px;
}

.custom-input {
  border-radius: 10px;
  border: 1px solid #e0e0e0;
  padding: 10px 15px;
  background-color: #f8f9fa;
  transition: all 0.3s;
}

.custom-input:focus {
  border-color: #ff0055;
  background-color: #fff;
  box-shadow: 0 0 0 4px rgba(255, 0, 85, 0.1);
  outline: none;
}

.btn-pink {
  background-color: #ff0055;
  color: white;
  border-radius: 10px;
  transition: all 0.3s;
}

.btn-pink:hover {
  background-color: #d90049;
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(255, 0, 85, 0.3);
}
</style>
