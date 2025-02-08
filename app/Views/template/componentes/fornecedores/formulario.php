<form method="post" id="form_fornecedor" name="form_fornecedor" action="<?= site_url('fornecedores/salvar') ?>">
    <input type="hidden" name="id_fornecedor" value="<?= $fornecedor['id_fornecedor'] ?? '' ?>">

    <div class="row mb-3">
        <div class="form-group col">
            <label for="tipo_pessoa">Tipo de Pessoa</label>
            <select class="form-control" name="tipo_pessoa" id="tipo_pessoa">
                <option value="F" <?= isset($fornecedor['tipo_pessoa']) && $fornecedor['tipo_pessoa'] == 'F' ? 'selected' : '' ?>>Física</option>
                <option value="J" <?= isset($fornecedor['tipo_pessoa']) && $fornecedor['tipo_pessoa'] == 'J' ? 'selected' : '' ?>>Jurídica</option>
            </select>
        </div>
    </div>

    <div class="row mb-3">
        <div class="form-group col">
            <label for="nome">Nome</label>
            <input type="text" class="form-control" name="nome" id="nome" value="<?= $fornecedor['nome'] ?? '' ?>" required>
        </div>
    </div>

    <div class="row mb-3">
        <div class="form-group col">
            <label for="documento">Documento (CPF/CNPJ)</label>
            <input type="text" class="form-control" name="documento" id="documento" value="<?= $fornecedor['documento'] ?? '' ?>" required>
        </div>
    </div>

    <div class="row mb-3">
        <div class="form-group col">
            <label for="email">Email</label>
            <input type="email" class="form-control" name="email" id="email" value="<?= $fornecedor['email'] ?? '' ?>">
        </div>
        <div class="form-group col">
            <label for="telefone">Telefone</label>
            <input type="text" class="form-control" name="telefone" id="telefone" value="<?= $fornecedor['telefone'] ?? '' ?>">
        </div>
    </div>

    <div class="row mb-3">
        <div class="form-group col">
            <label for="endereco">Endereço</label>
            <input type="text" class="form-control" name="endereco" id="endereco" value="<?= $fornecedor['endereco'] ?? '' ?>">
        </div>
        <div class="form-group col">
            <label for="complemento">Complemento</label>
            <input type="text" class="form-control" name="complemento" id="complemento" value="<?= $fornecedor['complemento'] ?? '' ?>">
        </div>
    </div>

    <div class="row mb-3">
        <div class="form-group col">
            <label for="cep">CEP</label>
            <input type="text" class="form-control" name="cep" id="cep" value="<?= $fornecedor['cep'] ?? '' ?>">
        </div>
        <div class="form-group col">
            <label for="cidade">Cidade</label>
            <input type="text" class="form-control" name="cidade" id="cidade" value="<?= $fornecedor['cidade'] ?? '' ?>">
        </div>
        <div class="form-group col">
            <label for="uf">UF</label>
            <input class="form-control" list="ufs" name="uf" id="uf" value="<?= $fornecedor['uf'] ?? '' ?>" maxlength="2">
            <datalist id="ufs">
                <option value="AC">Acre</option>
                <option value="AL">Alagoas</option>
                <option value="AP">Amapá</option>
                <option value="AM">Amazonas</option>
                <option value="BA">Bahia</option>
                <option value="CE">Ceará</option>
                <option value="DF">Distrito Federal</option>
                <option value="ES">Espírito Santo</option>
                <option value="GO">Goiás</option>
                <option value="MA">Maranhão</option>
                <option value="MT">Mato Grosso</option>
                <option value="MS">Mato Grosso do Sul</option>
                <option value="MG">Minas Gerais</option>
                <option value="PA">Pará</option>
                <option value="PB">Paraíba</option>
                <option value="PR">Paraná</option>
                <option value="PE">Pernambuco</option>
                <option value="PI">Piauí</option>
                <option value="RJ">Rio de Janeiro</option>
                <option value="RN">Rio Grande do Norte</option>
                <option value="RS">Rio Grande do Sul</option>
                <option value="RO">Rondônia</option>
                <option value="RR">Roraima</option>
                <option value="SC">Santa Catarina</option>
                <option value="SP">São Paulo</option>
                <option value="SE">Sergipe</option>
                <option value="TO">Tocantins</option>
            </datalist>
        </div>
    </div>

    <div class="row mb-3" id="razao_social_div" style="display: none;">
        <div class="form-group col">
            <label for="razao_social">Razão Social</label>
            <input type="text" class="form-control" name="razao_social" id="razao_social" value="<?= $fornecedor['razao_social'] ?? '' ?>">
        </div>
    </div>

    <div class="row mb-3">
        <div class="form-group col">
            <label for="ativo">Ativo</label>
            <select class="form-control" name="ativo" id="ativo">
                <option value="1" <?= !isset($fornecedor['ativo']) || $fornecedor['ativo'] == 1 ? 'selected' : '' ?>>Sim</option>
                <option value="0" <?= isset($fornecedor['ativo']) && $fornecedor['ativo'] == 0 ? 'selected' : '' ?>>Não</option>
            </select>
        </div>
    </div>

    <div class="mt-3">
        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="<?= site_url('/fornecedores/') ?>" class="btn btn-outline-secondary">Cancelar</a>
    </div>
</form>

<script>
    const tipoPessoaSelect = document.getElementById('tipo_pessoa');
    const razaoSocialDiv = document.getElementById('razao_social_div');

    function toggleRazaoSocial() {
        if (tipoPessoaSelect.value === 'J') {
            razaoSocialDiv.style.display = 'block';
        } else {
            razaoSocialDiv.style.display = 'none';
        }
    }

    tipoPessoaSelect.addEventListener('change', toggleRazaoSocial);
    // Chamada inicial para definir o estado correto ao carregar a página
    toggleRazaoSocial();
</script>