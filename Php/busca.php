<?php
include("connect.php");

$response = '';
$limit = 1000;  // Número de registros por "página"
$offset = 0;    // Inicializamos o offset

// Verifica se estamos passando um parâmetro de página
if (isset($_POST['offset'])) {
    $offset = $_POST['offset'];
}

// Verifica se o filtro e a busca foram enviados
if (isset($_POST['filtro']) && isset($_POST['buscar']) && !empty($_POST['buscar'])) {
    $filtro = $_POST['filtro'];  // Tipo de filtro (codigo_barras, descricao, etc.)
    $buscar = $_POST['buscar'];  // Termo de busca

    // Definindo filtros exatos e parciais
    $valid_filters_exact = ['codigo_barras', 'fornecedor_id'];
    $valid_filters_like = ['descricao', 'marca_descricao']; // Corrigido: removido filtro 'descricao' duplicado
    $valid_filters_like_2 = ['descricao_moto'];

    //WHERE descricao LIKE letra%
    // WHERE descricao_moto LIKE %letra%

    if (in_array($filtro, $valid_filters_exact)) {
        // Para filtros exatos, usamos WHERE e = (igualdade exata)
        $sql = "SELECT * FROM itens_view WHERE $filtro = :buscar LIMIT $limit OFFSET $offset";
    } elseif (in_array($filtro, $valid_filters_like)) {
        // Para filtros LIKE, usamos WHERE e LIKE (busca parcial)
        $sql = "SELECT * FROM itens_view WHERE $filtro LIKE :buscar LIMIT $limit OFFSET $offset";
        
    } elseif(in_array($filtro, $valid_filters_like_2)) {
        $sql = "SELECT * FROM itens_view WHERE $filtro LIKE :buscar LIMIT $limit OFFSET $offset";

    }
     else {
        $response .= '<tr><td colspan="33">Filtro inválido</td></tr>';
        echo $response;
        exit;
    }

    $cmd = $pdo->prepare($sql);
    // Adiciona % para LIKE se necessário

    if (in_array($filtro, $valid_filters_like)) {
        $cmd->bindValue(':buscar', $buscar . '%');
    }elseif( in_array($filtro, $valid_filters_like_2)) {
        $cmd->bindValue(':buscar','%' . $buscar . '%');
    } else {
        $cmd->bindValue(':buscar', $buscar);
    }
    
    $cmd->execute();

    $dados = $cmd->fetchAll(PDO::FETCH_CLASS);

    if ($cmd->rowCount() > 0) {
        foreach ($dados as $dado) {
            $response .= '
            <tr>
                <td>' . htmlspecialchars($dado->codigo_barras) . '</td>
                <td>' . htmlspecialchars($dado->descricao) . '</td>
                <td>' . htmlspecialchars($dado->marca_descricao) . '</td>

                <td>' . htmlspecialchars($dado->fornecedor_id) . '</td>
                <td>' . htmlspecialchars($dado->estoque_pdv3) . '</td>
                <td>' . htmlspecialchars($dado->estoque_pdv6) . '</td>
                <td>' . htmlspecialchars($dado->estoque_pdv5) . '</td>
                <td>' . htmlspecialchars($dado->estoque_pdv7) . '</td>
                <td>' . htmlspecialchars($dado->estoque_atual) . '</td>
                <td>' . htmlspecialchars($dado->estoque_pdv1) . '</td>
                <td>' . htmlspecialchars($dado->estoque_pdv2) . '</td>
                <td>' . htmlspecialchars($dado->estoque_pdv4) . '</td>
                <td>' . htmlspecialchars($dado->estoque_pdv9) . '</td>
                <td>' . htmlspecialchars($dado->estoque_pdv12) . '</td>
                <td>' . htmlspecialchars($dado->estoque_pdv8) . '</td>
                <td>' . htmlspecialchars($dado->estoque_pdv10) . '</td>
                <td>' . htmlspecialchars($dado->estoque_pdv11) . '</td>
                <td>' . htmlspecialchars($dado->estoque_pdv13) . '</td>
                <td>' . htmlspecialchars($dado->estoque_apartado) . '</td>
                <td>' . htmlspecialchars($dado->media_venda) . '</td>
                <td>' . htmlspecialchars($dado->custo_venda) . '</td>
                <td>' . htmlspecialchars($dado->ativo) . '</td>
                <td>' . htmlspecialchars($dado->valor_venda4) . '</td>
                <td>' . htmlspecialchars($dado->valor_venda3) . '</td>
                <td>' . htmlspecialchars($dado->valor_venda2) . '</td>
                <td>' . htmlspecialchars($dado->valor_venda1) . '</td>
                <td>' . htmlspecialchars($dado->minimo_p) . '</td>
                <td>' . htmlspecialchars($dado->minimo_d) . '</td>
                <td>' . htmlspecialchars($dado->e1) . '</td>
                <td>' . htmlspecialchars($dado->e2) . '</td>
                <td>' . htmlspecialchars($dado->e3) . '</td>
                <td>' . htmlspecialchars($dado->e4) . '</td>
                <td>' . htmlspecialchars($dado->item_id) . '</td>
            </tr>';
        }


    } else {
        $response .= '<tr><td colspan="33">Nenhum item encontrado</td></tr>';
    }
} else {
    $response .= '<tr><td colspan="33">Nenhum dado encontrado</td></tr>';
}

echo $response;
