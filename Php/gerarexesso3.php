<?php

include('connect.php');
// Nome do arquivo que será gerado
$nome_arquivo = "Excesso3.txt";

// Caminho onde o arquivo será salvo temporariamente (ajustado para Windows ou qualquer sistema)
// $caminho_arquivo = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $nome_arquivo;
$caminho_arquivo = "C:\\xampp\\tmp\\" . $nome_arquivo;


// Abrindo o arquivo para escrita
$handle = fopen($caminho_arquivo, "w");

if (!$handle) {
    echo "Erro ao criar o arquivo para exportação.";
    return;
}

// Consulta dos dados do banco
$query = "
    SELECT codigo_barras, codigo_fornecedor, descricao, marca_id, e3, unidade_id, custo_venda, (custo_venda * e3) as total, codigo_fabricante
    FROM 
        itens
    WHERE e3 > 0";

// Executando a consulta
$sql = $pdo->prepare($query);
$sql->execute();

if ($sql->rowCount() === 0) {
    echo "Nenhum dado encontrado para exportação.";
    fclose($handle);
    return;
}

// Loop para montar o conteúdo do arquivo
$primeira_linha = true;
while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
    if ($primeira_linha) {
        // Cabeçalho (mestre)
        $linha = str_pad("EXCESSO3", 4, " ", STR_PAD_LEFT) . "\r\n";
        fwrite($handle, $linha);
        $primeira_linha = false;
    }

    // Detalhes (itens)
    $linha = 
        str_pad($row['codigo_barras'], 6, " ", STR_PAD_LEFT) . // Codigo
        str_pad($row['codigo_fornecedor'], 8, " ", STR_PAD_LEFT) . // Cod Fornecedor
        str_pad($row['descricao'], 10, " ", STR_PAD_LEFT) . // Descrição
        str_pad($row['marca_id'], 10, " ", STR_PAD_LEFT) . // Marca
        str_pad((int)$row['e3'], 8, " ", STR_PAD_LEFT) . // Quantidade
        str_pad($row['unidade_id'], 12, " ", STR_PAD_LEFT) . // Unidade
        str_pad(number_format($row['custo_venda'], 2, ',', '.'), 12, " ", STR_PAD_LEFT) . // Custo
        str_pad(number_format($row['total'], 2, ',', '.'), 12, " ", STR_PAD_LEFT) . // Total
        str_pad($row['codigo_fabricante'], 12, " ", STR_PAD_LEFT) . "\r\n"; // Cod Fabricante
    fwrite($handle, $linha);
}

// Fechando o arquivo
fclose($handle);

// Forçando o download do arquivo gerado
header("Content-Description: File Transfer");
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename={$nome_arquivo}");
header("Expires: 0");
header("Cache-Control: must-revalidate");
header("Pragma: public");
header("Content-Length: " . filesize($caminho_arquivo));

// Limpando o buffer para evitar erros
ob_clean();
flush();

// Lendo o arquivo para download
readfile($caminho_arquivo);

// Excluindo o arquivo temporário
unlink($caminho_arquivo);

// Finalizando a execução
exit();

?>
