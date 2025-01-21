<?php

include("../connect.php");

try {
    // Parâmetros da consulta SQL
    $update_table  = 'itens'; // Nome da tabela
    $update_where  = "e2 != '0'"; // Cláusula WHERE
    $update_fields = array( // Lista de campos a serem atualizados
        "e2 = '0'"
    );

    // Construção da consulta SQL
    $update_sql = 'UPDATE ' . $update_table
        . ' SET ' . implode(', ', $update_fields)
        . ' WHERE ' . $update_where;

    // Execução da consulta
    $stmt = $pdo->prepare($update_sql);
    $stmt->execute();

    // Mensagem de sucesso (opcional, mas pode ser útil para debug)
    echo "Atualização realizada com sucesso!";

    // Redireciona para o index.html
    header("Location: /Busca-Instantanea-Ajax_BT5/index.html");
    exit; // Importante para interromper o script após o redirecionamento
} catch (PDOException $e) {
    // Tratamento de erro
    echo "Erro ao atualizar os registros: " . $e->getMessage();
}
