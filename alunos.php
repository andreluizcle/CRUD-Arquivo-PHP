<?php
include 'dados.php';

$alunos = lerAlunos();

// Ordena do menor para o maior RA, comparando como número (assim 200 vem antes de 1000).
usort($alunos, function ($a, $b) {
    return (int) $a['ra'] <=> (int) $b['ra'];
});
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lista de Alunos</title>
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
        <h1>Alunos cadastrados</h1>

        <?php if (count($alunos) === 0) { ?>
            <p>Nenhum aluno cadastrado ainda.</p>
        <?php } else { ?>
            <table>
                <thead>
                    <tr>
                        <th scope="col">RA</th>
                        <th scope="col">Nome</th>
                        <th scope="col">Sexo</th>
                        <th scope="col">Idade</th>
                        <th scope="col">Endereço</th>
                        <th scope="col">Telefone</th>
                        <th scope="col">E-mail</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($alunos as $aluno) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($aluno['ra']); ?></td>
                            <td><?php echo htmlspecialchars($aluno['nome']); ?></td>
                            <td><?php echo htmlspecialchars($aluno['sexo']); ?></td>
                            <td><?php echo htmlspecialchars($aluno['idade']); ?></td>
                            <td><?php echo htmlspecialchars($aluno['endereco']); ?></td>
                            <td><?php echo htmlspecialchars($aluno['telefone']); ?></td>
                            <td><?php echo htmlspecialchars($aluno['email']); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } ?>
    </main>
</body>
</html>
