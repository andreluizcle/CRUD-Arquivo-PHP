<?php
// Arquivo onde a lista de alunos fica guardada.
// __DIR__ é a pasta deste arquivo, então o caminho funciona de qualquer página.
const ARQUIVO_ALUNOS = __DIR__ . '/alunos.dat';

// Lê os alunos salvos no arquivo. Se ele ainda não existe, não há alunos.
function lerAlunos() {
    if (!file_exists(ARQUIVO_ALUNOS)) {
        return [];
    }
    return unserialize(file_get_contents(ARQUIVO_ALUNOS), ['allowed_classes' => false]);
}

// Transforma a lista em texto com serialize() e grava no arquivo.
// LOCK_EX impede que dois cadastros escrevam no arquivo ao mesmo tempo.
function salvarAlunos($alunos) {
    file_put_contents(ARQUIVO_ALUNOS, serialize($alunos), LOCK_EX);
}
