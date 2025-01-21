<?php
include('connect.php');
// Nome do arquivo que será gerado
$nome_arquivo = "Excesso4.txt";

// Caminho onde o arquivo será salvo temporariamente (compatível com Windows e Linux)
$caminho_arquivo = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $nome_arquivo;

// Abrindo o arquivo para escrita
$handle = fopen($caminho_arquivo, "w");

if (!$handle) {
    echo "Erro ao criar o arquivo para exportação.";
    return;
}

// Consulta dos dados do banco
$query = "
    SELECT codigo_barras, codigo_fornecedor, descricao, marca_id, e4, unidade_id, custo_venda, (custo_venda * e4) as total, codigo_fabricante
    FROM 
        itens
    WHERE e4 > 0";

// Executando a consulta
$sql = $pdo->prepare($query);
$sql->execute();

if ($sql->rowCount() === 0) {
    echo "Nenhum dado encontrado para exportação.";
    fclose($handle);
    return;
}

// Loop para montar o conteúdo do arquivo
$linha1 = "EXCESSO4\r\n"; 
fwrite($handle, $linha1);

// Inicialização das variáveis para total
$totalValor = 0.00;
$totalQtd = 0;

// Loop para montar o conteúdo do arquivo
while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {

    // Buscando descrição da marca
    $marcaDesc = "SELECT descricao FROM marcas WHERE marca_id = " . $row['marca_id'];
    $marcaResult = $pdo->query($marcaDesc);
    $marcaDescricao = $marcaResult->fetchColumn() ?: '';

    // Buscando unidade de medida
    $undDesc = "SELECT und_tributaria FROM und_medidas WHERE unidade_id = " . $row['unidade_id'];
    $undResult = $pdo->query($undDesc);
    $undDescricao = $undResult->fetchColumn() ?: '';

    // Atualizando totais
    $totalQtd += $row['e4'];
    $totalValor += $row['total'];

    // Detalhes (itens)
    $linha = 
        str_pad($row['codigo_barras'], 6, " ", STR_PAD_LEFT) . // Codigo
        " " .
        str_pad($row['codigo_fornecedor'], 11, " ", STR_PAD_RIGHT) . // Cod Fornecedor
        " " .
        str_pad(substr($row['descricao'], 0, 26), 24, " ", STR_PAD_LEFT) . // Descrição
        " " .
        str_pad(substr($marcaDescricao, 0, 4), 4, " ", STR_PAD_RIGHT) . // Marca
        " " .
        str_pad($row['e4'], 7, " ", STR_PAD_RIGHT) . // Quantidade
        " " .
        str_pad(substr($undDescricao, 0, 2), 2, " ", STR_PAD_RIGHT) . // Unidade
        " " .
        str_pad(number_format($row['custo_venda'], 2, ',', '.'), 7, " ", STR_PAD_LEFT) . // Custo
        " " .
        str_pad(number_format($row['total'], 2, ',', '.'), 10, " ", STR_PAD_LEFT) . // Total
        " " . $row['codigo_fabricante'] . "\r\n"; // Cod Fabricante
    
    fwrite($handle, $linha);
}

// Adicionando o total no final do arquivo
$linha = "SAIDA\r\n";
fwrite($handle, $linha);
$linha = "Total dos produtos.:     " . number_format($totalValor, 2, ',', '.') . "     Qtde de Itens.:    " . $totalQtd . "\r\n";
fwrite($handle, $linha);

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
