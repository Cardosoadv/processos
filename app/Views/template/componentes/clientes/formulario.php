<form method="post" id="form_cliente" name="form_cliente" action="<?= site_url('clientes/salvar') ?>">
    <input type="hidden" name="id_cliente" value="<?= $cliente['id_cliente'] ?? '' ?>">

    <div class="row mb-3">
        <div class="form-group col">
            <label for="tipo_cliente">Tipo de Cliente</label>
            <select class="form-control" name="tipo_cliente" id="tipo_cliente">
                <option value="F" <?= isset($cliente['tipo_cliente']) && $cliente['tipo_cliente'] == 'F' ? 'selected' : '' ?>>Física</option>
                <option value="J" <?= isset($cliente['tipo_cliente']) && $cliente['tipo_cliente'] == 'J' ? 'selected' : '' ?>>Jurídica</option>
            </select>
        </div>
    </div>

    <div class="row mb-3">
        <div class="form-group col">
            <label for="nome">Nome</label>
            <input type="text" class="form-control" name="nome" id="nome" value="<?= $cliente['nome'] ?? '' ?>" required>
        </div>
    </div>

    <div class="row mb-3">
        <div class="form-group col">
            <label for="documento">Documento (CPF/CNPJ)</label>
            <input type="text" class="form-control" name="documento" id="documento" value="<?= $cliente['documento'] ?? '' ?>" required>
        </div>
    </div>

    <div class="row mb-3">
        <div class="form-group col">
            <label for="email">Email</label>
            <input type="email" class="form-control" name="email" id="email" value="<?= $cliente['email'] ?? '' ?>">
        </div>
        <div class="form-group col">
            <label for="telefone">Telefone</label>
            <input type="text" class="form-control" name="telefone" id="telefone" value="<?= $cliente['telefone'] ?? '' ?>">
        </div>
    </div>

    <div class="row mb-3">
        <div class="form-group col">
            <label for="endereco">Endereço</label>
            <input type="text" class="form-control" name="endereco" id="endereco" value="<?= $cliente['endereco'] ?? '' ?>">
        </div>
        <div class="form-group col">
            <label for="complemento">Complemento</label>
            <input type="text" class="form-control" name="complemento" id="complemento" value="<?= $cliente['complemento'] ?? '' ?>">
        </div>
    </div>

    <div class="row mb-3">
        <div class="form-group col">
            <label for="cep">CEP</label>
            <input type="text" class="form-control" name="cep" id="cep" value="<?= $cliente['cep'] ?? '' ?>">
        </div>
        <div class="form-group col">
            <label for="cidade">Cidade</label>
            <input type="text" class="form-control" name="cidade" id="cidade" value="<?= $cliente['cidade'] ?? '' ?>">
        </div>
        <div class="form-group col">
    <label for="uf">UF</label>
    <input class="form-control" list="ufs" name="uf" id="uf" value="<?= $cliente['uf'] ?? '' ?>" maxlength="2">
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
            <input type="text" class="form-control" name="razao_social" id="razao_social" value="<?= $cliente['razao_social'] ?? '' ?>">
        </div>
    </div>

    <div class="row mb-3">
        <div class="form-group col">
            <label for="ativo">Ativo</label>
            <select class="form-control" name="ativo" id="ativo">
                <option value="1" <?= !isset($cliente['ativo']) || $cliente['ativo'] == 1 ? 'selected' : '' ?>>Sim</option>
                <option value="0" <?= isset($cliente['ativo']) && $cliente['ativo'] == 0 ? 'selected' : '' ?>>Não</option>
            </select>
        </div>
    </div>

    <div class="mt-3">
        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="<?= site_url('/clientes/') ?>" class="btn btn-outline-secondary">Cancelar</a>
    </div>
</form>

<script>
    const tipoClienteSelect = document.getElementById('tipo_cliente');
    const razaoSocialDiv = document.getElementById('razao_social_div');

    function toggleRazaoSocial() {
        if (tipoClienteSelect.value === 'J') {
            razaoSocialDiv.style.display = 'block';
        } else {
            razaoSocialDiv.style.display = 'none';
        }
    }

    tipoClienteSelect.addEventListener('change', toggleRazaoSocial);
    // Chamada inicial para definir o estado correto ao carregar a página
    toggleRazaoSocial();
</script>
