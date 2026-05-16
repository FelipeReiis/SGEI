<script setup>
    import { useForm } from '@inertiajs/vue3';
    import MainLayout from '@/Layouts/MainLayout.vue';
    import FormSectionLayout from '@/Components/FormSectionLayout.vue';

    defineOptions({ layout: MainLayout });
    const props = defineProps({
        evento: Object,
    });

    const formulario = useForm({
        id: props.evento?.id ?? '',
        evento: props.evento?.nome ?? '',
        data_evento: props.evento?.data ?? '',
        preco: props.evento?.valor ?? '',
        ativo: props.evento?.status ?? '',
        img: null, // Para upload de arquivo, iniciamos como null
        obs: props.evento?.observacao ?? '',
    });

    // Máscara Monetária em tempo real (R$ 1.234,56)
    const maskDinheiro = (v) => {
        v = v.replace(/\D/g, "");
        v = (v / 100).toFixed(2) + "";
        v = v.replace(".", ",");
        v = v.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
        return "R$ " + v;
    };

    // Captura o arquivo de imagem selecionado
    const uploadImagem = (e) => {
        formulario.img = e.target.files[0];
    };

    const enviar = () => {
        // Se for edição com arquivo, usamos POST com _method para enganar o Laravel e aceitar o arquivo
        if (props.evento) {
            formulario.transform((data) => ({
                ...data,
                _method: 'put'
            })).post(route('eventos.update', props.evento.id), {
                forceFormData: true // Garante o envio do arquivo
            });
        } else {
            formulario.post(route('eventos.store'));
        }
    }
</script>

<template>
    <div class="card-custom shadow-sm p-4">
        <form @submit.prevent="enviar">

            <FormSectionLayout titulo="Configuração do Evento" icon="🎉">
                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Nome do Evento*</label>
                        <input
                            type="text"
                            v-model="formulario.evento"
                            class="form-control custom-input"
                            :class="{'is-invalid' : formulario.errors?.evento}"
                            placeholder="Ex: Workshop de Dança"
                            required
                        >
                        <div v-if="formulario.errors?.evento" class="invalid-feedback">
                            {{ formulario.errors.evento }}
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Data*</label>
                        <input
                            type="date"
                            v-model="formulario.data_evento"
                            class="form-control custom-input"
                            :class="{'is-invalid' : formulario.errors?.data_evento}"
                            required
                        >
                        <div v-if="formulario.errors?.data_evento" class="invalid-feedback">
                            {{ formulario.errors.data_evento }}
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Valor*</label>
                        <input
                            type="text"
                            :value="formulario.preco"
                            @input="formulario.preco = maskDinheiro($event.target.value)"
                            class="form-control custom-input"
                            :class="{'is-invalid' : formulario.errors?.preco}"
                            placeholder="R$ 0,00"
                            required
                        >
                        <div v-if="formulario.errors?.preco" class="invalid-feedback">
                            {{ formulario.errors.preco }}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Status*</label>
                        <select
                            v-model="formulario.ativo"
                            class="form-select custom-input"
                            :class="{'is-invalid' : formulario.errors?.ativo}"
                            required
                        >
                            <option value="">Selecione o status...</option>
                            <option value="nao_iniciado">Não Iniciado</option>
                            <option value="em_andamento">Em Andamento</option>
                            <option value="finalizado">Finalizado</option>
                        </select>
                        <div v-if="formulario.errors?.ativo" class="invalid-feedback">
                            {{ formulario.errors.ativo }}
                        </div>
                    </div>

                    <div class="col-md-8">
                        <label class="form-label">Imagem do Evento</label>
                        <div class="input-group">
                            <input
                                type="file"
                                @change="uploadImagem"
                                class="form-control custom-input"
                                :class="{'is-invalid' : formulario.errors?.img}"
                                accept="image/*"
                            >
                        </div>
                        <div v-if="formulario.errors?.img" class="text-danger small mt-1">
                            {{ formulario.errors.img }}
                        </div>
                        <small v-if="props.evento?.imagem && !formulario.img" class="text-muted d-block mt-1">
                            Imagem atual: <a :href="props.evento.imagem" target="_blank" class="text-pink">Visualizar arquivo</a>
                        </small>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Observações</label>
                        <textarea
                            v-model="formulario.obs"
                            class="form-control custom-input"
                            :class="{'is-invalid' : formulario.errors?.obs}"
                            rows="3"
                            placeholder="Detalhes adicionais sobre o evento..."
                            maxlength="500"
                        ></textarea>
                        <div v-if="formulario.errors?.obs" class="invalid-feedback">
                            {{ formulario.errors.obs }}
                        </div>
                    </div>

                </div>
            </FormSectionLayout>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <button
                    type="submit"
                    class="btn btn-pink px-5 fw-bold"
                    :disabled="formulario.processing"
                >
                    {{ formulario.processing ? 'Processando...' : (props.evento ? 'Atualizar Evento' : 'Salvar Evento') }}
                </button>
            </div>

        </form>
    </div>
</template>

<style scoped>
/* Mantendo a consistência do seu padrão de cores */
.form-check-input:checked { background-color: #ff0055; border-color: #ff0055; }
.btn-pink { background-color: #ff0055; color: white; }
.btn-pink:hover { background-color: #d90047; color: white; }
.text-pink { color: #ff0055; }
</style>
