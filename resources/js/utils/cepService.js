export const buscarCep = async (cep) => {
    // Limpa o CEP para garantir que só existam números
    const cepLimpo = cep.replace(/\D/g, '');

    if (cepLimpo.length !== 8) return null;

    try {
        const response = await fetch(`https://viacep.com.br/ws/${cepLimpo}/json/`);
        const dados = await response.json();

        if (dados.erro) {
            alert("CEP não encontrado!");
            return null;
        }

        return {
            logradouro: dados.logradouro,
            bairro: dados.bairro,
            complemento: dados.complemento,
        };
    } catch (error) {
        console.error("Erro ao buscar CEP:", error);
        return null;
    }
};
