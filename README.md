# Cadastro de Alunos em PHP

Site de duas páginas que cadastra alunos e mostra a lista ordenada por RA. Os dados ficam guardados em um arquivo, sem banco de dados e sem sessão.

## Contexto

Atividade de Fixação das Unidades 13 a 17 da disciplina **SI401 – Programação para a Web** (2º semestre de 2026, Prof. Guilherme Palermo Coelho). A atividade é individual.

Aluno: _nome_ — RA: _RA_

O enunciado pede:

- duas páginas: uma com o formulário de cadastro e outra com todos os alunos ordenados por RA;
- formulário com os campos nome, RA, sexo, idade, endereço, telefone e e-mail, e um botão "Cadastrar";
- um menu à esquerda para navegar entre as duas páginas;
- HTML no padrão mais recente, validado no site do W3C;
- **proibido** usar SGBD ou sessões para guardar os dados. A dica do professor é usar manipulação de arquivos e serialização.

## O que o site faz

- **Cadastro** (`cadastro.php`): formulário com os 7 campos. Ao clicar em "Cadastrar", o aluno é validado e salvo. Se der certo, aparece "Aluno cadastrado com sucesso!". Se der errado, aparece uma lista com os erros acima do formulário.
- **Lista** (`alunos.php`): tabela com todos os alunos, do menor para o maior RA. Se ainda não houver alunos, aparece "Nenhum aluno cadastrado ainda.".

## Como os dados são guardados

A "base de dados" é o arquivo `alunos.dat`, criado automaticamente no primeiro cadastro.

- Cada aluno é um array associativo (`nome`, `ra`, `sexo`, `idade`, `endereco`, `telefone`, `email`).
- Todos os alunos ficam juntos em uma lista (um array de arrays).
- Para gravar, `serialize()` transforma a lista em texto e `file_put_contents()` escreve esse texto no arquivo. O `LOCK_EX` impede que dois cadastros escrevam no arquivo ao mesmo tempo.
- Para ler, `file_get_contents()` pega o texto do arquivo e `unserialize()` transforma de volta em array. A opção `allowed_classes => false` impede que o texto vire objetos.

Como o arquivo fica no disco, os dados continuam lá entre uma requisição e outra, mesmo depois de fechar o navegador. Não há banco de dados e não há `session_start()` nem `$_SESSION`.

## Estrutura de arquivos

```
CRUD-PHP/
├── cadastro.php   página 1: menu + formulário, validação e gravação do aluno
├── alunos.php     página 2: menu + tabela de alunos ordenada por RA
├── dados.php      nome do arquivo de dados + funções lerAlunos() e salvarAlunos()
├── estilo.css     menu à esquerda com flexbox e aparência básica
├── alunos.dat     criado no primeiro cadastro (não faz parte da entrega)
└── Enunciado/     enunciado da atividade
```

## Requisitos

- PHP 7.0 ou superior (testado no PHP 8.3.6).
- Nenhuma outra dependência: sem banco de dados, framework, biblioteca ou JavaScript.

## Como rodar

Na pasta do projeto, inicie o servidor embutido do PHP:

```
cd CRUD-PHP
php -S localhost:8000
```

Depois abra no navegador:

- Cadastro: http://localhost:8000/cadastro.php
- Lista: http://localhost:8000/alunos.php

O endereço `http://localhost:8000/` sozinho mostra "Not Found", porque não existe `index.php` (o enunciado pede exatamente duas páginas). Sempre use `/cadastro.php` ou `/alunos.php`.

Para parar o servidor, aperte `Ctrl+C` no terminal.

A pasta do projeto precisa permitir escrita, porque o PHP cria o `alunos.dat` dentro dela.

## Validações

O navegador já barra parte dos erros (`required`, `type="email"`, `pattern`). Mesmo assim, o PHP valida tudo de novo, porque a validação do navegador pode ser burlada (por exemplo, enviando o formulário pelo `curl`).

- Todos os campos são obrigatórios. O `trim()` faz um campo só com espaços contar como vazio.
- O RA tem só dígitos (`ctype_digit`) e não pode estar cadastrado ainda.
- A idade é um número inteiro entre 1 e 120.
- O e-mail precisa ser válido (`filter_var` com `FILTER_VALIDATE_EMAIL`).
- O sexo precisa ser uma das opções do formulário: Feminino, Masculino ou Não Informar.

## Fluxo: clicar em "Cadastrar" até aparecer na lista

1. O formulário envia os 7 campos por POST para o próprio `cadastro.php`.
2. No topo do arquivo, antes de qualquer HTML, o PHP carrega a lista atual com `lerAlunos()`. Se o `alunos.dat` ainda não existe, a lista começa vazia.
3. Cada campo passa por `trim()` e pelas validações. Os problemas encontrados vão para o array `$erros`.
4. Se houver erros, a página é exibida de novo com a lista de mensagens.
5. Se não houver erros, o aluno entra na lista com `$alunos[] = $aluno` e `salvarAlunos()` grava a lista no arquivo.
6. Em seguida, `header('Location: cadastro.php?sucesso=1')` redireciona o navegador. Assim, apertar F5 não cadastra o mesmo aluno de novo.
7. O `alunos.php` lê o mesmo arquivo, ordena com `usort` e o operador `<=>` (comparando o RA como número) e mostra uma linha da tabela por aluno. Cada valor passa por `htmlspecialchars()`, para que um texto como `<script>` apareça como texto e não seja executado.

## Validação do HTML (W3C)

O HTML gerado pelas páginas passou no Nu Html Checker do W3C sem erros: cadastro em branco, com erros e com sucesso, lista vazia e lista preenchida.

Para validar de novo:

1. Com o servidor rodando, abra a página no navegador.
2. Veja o código-fonte (`Ctrl+U`) e copie tudo.
3. Acesse https://validator.w3.org/nu/, escolha "Validate by direct input", cole o código e clique em "Check".

Valide o HTML que aparece no navegador, não os arquivos `.php`, porque o validador não entende o código PHP.