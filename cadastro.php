<?php
include 'dados.php';

$opcoesSexo = ['Feminino', 'Masculino', 'Não Informar'];

// Nome de cada campo do formulário => texto usado nas mensagens de erro.
$campos = [
    'nome' => 'Nome',
    'ra' => 'RA',
    'sexo' => 'Sexo',
    'idade' => 'Idade',
    'endereco' => 'Endereço',
    'telefone' => 'Telefone',
    'email' => 'E-mail',
];

$erros = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $alunos = lerAlunos();
    $aluno = [];
    foreach ($campos as $campo => $rotulo) {
        // trim tira os espaços das pontas, então um campo só com espaços conta como vazio.
        $aluno[$campo] = trim($_POST[$campo] ?? '');
        if ($aluno[$campo] === '') {
            $erros[] = "O campo $rotulo é obrigatório.";
        }
    }

    // Só confere o formato depois que todos os campos foram preenchidos.
    if (count($erros) === 0) {
        if (!ctype_digit($aluno['ra'])) {
            $erros[] = 'O RA deve conter apenas números.';
        }
        if (!ctype_digit($aluno['idade']) || (int) $aluno['idade'] < 1 || (int) $aluno['idade'] > 120) {
            $erros[] = 'A idade deve ser um número inteiro entre 1 e 120.';
        }
        if (filter_var($aluno['email'], FILTER_VALIDATE_EMAIL) === false) {
            $erros[] = 'O e-mail informado não é válido.';
        }
        if (!in_array($aluno['sexo'], $opcoesSexo)) {
            $erros[] = 'Escolha uma das opções de sexo.';
        }
        foreach ($alunos as $cadastrado) {
            if ($cadastrado['ra'] === $aluno['ra']) {
                $erros[] = 'Já existe um aluno cadastrado com este RA.';
            }
        }
    }

    if (count($erros) === 0) {
        $alunos[] = $aluno;
        salvarAlunos($alunos);
        // Redireciona para que atualizar a página (F5) não envie o formulário de novo.
        header('Location: cadastro.php?sucesso=1');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cadastro de Alunos</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <nav>
        <ul>
            <li><a href="cadastro.php">Cadastrar aluno</a></li>
            <li><a href="alunos.php">Lista de alunos</a></li>
        </ul>
    </nav>
    <main>
        <h1>Cadastro de aluno</h1>

        <?php if (isset($_GET['sucesso'])) { ?>
            <p class="sucesso">Aluno cadastrado com sucesso!</p>
        <?php } ?>

        <?php if (count($erros) > 0) { ?>
            <ul class="erros">
                <?php foreach ($erros as $erro) { ?>
                    <li><?php echo $erro; ?></li>
                <?php } ?>
            </ul>
        <?php } ?>

        <form action="cadastro.php" method="post">
            <p>
                <label for="nome">Nome</label>
                <input type="text" id="nome" name="nome" required>
            </p>
            <p>
                <label for="ra">RA</label>
                <input type="text" id="ra" name="ra" pattern="[0-9]+" title="Apenas números" required>
            </p>
            <p>
                <label for="sexo">Sexo</label>
                <select id="sexo" name="sexo" required>
                    <option value="">Selecione</option>
                    <?php foreach ($opcoesSexo as $opcao) { ?>
                        <option value="<?php echo $opcao; ?>"><?php echo $opcao; ?></option>
                    <?php } ?>
                </select>
            </p>
            <p>
                <label for="idade">Idade</label>
                <input type="number" id="idade" name="idade" min="1" max="120" required>
            </p>
            <p>
                <label for="endereco">Endereço</label>
                <input type="text" id="endereco" name="endereco" required>
            </p>
            <p>
                <label for="telefone">Telefone</label>
                <input type="tel" id="telefone" name="telefone" required>
            </p>
            <p>
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" required>
            </p>
            <button type="submit">Cadastrar</button>
        </form>
    </main>
</body>
</html>
