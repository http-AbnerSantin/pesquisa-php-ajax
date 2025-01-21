<?php
include('./Php/connect.php');
try {
// Parâmetros da consulta SQL
$update_table  = 'itens'; // Nome da tabela
$update_where  = "e1 != '0'"; // Cláusula WHERE
$update_fields = array( // Lista de campos a serem atualizados
    "e1 = '0'"
);

// Construção da consulta SQL
$update_sql = 'UPDATE ' . $update_table
    . ' SET ' . implode(', ', $update_fields)
    . ' WHERE ' . $update_where;

// Execução da consulta
$stmt = $pdo->prepare($update_sql);
$stmt->execute();

echo "Atualização realizada com sucesso!";
} catch (PDOException $e) {
// Tratamento de erro
echo "Erro ao atualizar os registros: " . $e->getMessage();
}