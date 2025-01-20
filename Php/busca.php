<?php
include("connect.php");
$response = '';

if (isset($_POST['filtro'])) {
    $filtro = $_POST['filtro'];  // Atribui o valor do filtro à variável $filtro
    echo "Filtro selecionado: " . htmlspecialchars($filtro);  // Exibe o valor selecionado com segurança
    echo $filtro;
} else {
    echo '<script>alert("Nenhum filtro foi selecionado")</script>';
}

if(isset($_POST['buscar']) && !empty($_POST['buscar'])){

    $buscar = $_POST['buscar'];
    $cmd = $pdo->prepare("SELECT * FROM itens where descricao LIKE '$buscar%'");
    $cmd->execute();
    $dados = $cmd->fetchAll(PDO::FETCH_CLASS);

    if ($cmd->rowCount() > 0) {
        foreach ($dados as $dado) {
            $response .= '

            <tr>
                <td>'.$dado->codigo_barras.'</td>
                <td>'.$dado->descricao.'</td>
                <td>'.$dado->fornecedor_id.'</td>
                <td>'.$dado->estoque_pdv3.'</td>
                <td>'.$dado->estoque_pdv6.'</td>
                <td>'.$dado->estoque_pdv5.'</td>
                <td>'.$dado->estoque_pdv7.'</td>
                <td>'.$dado->estoque_atual.'</td>
                <td>'.$dado->estoque_pdv1.'</td>
                <td>'.$dado->estoque_pdv2.'</td>
                <td>'.$dado->estoque_pdv4.'</td>
                <td>'.$dado->estoque_pdv9.'</td>
                <td>'.$dado->estoque_pdv12.'</td>
                <td>'.$dado->estoque_pdv8.'</td>
                <td>'.$dado->estoque_pdv10.'</td>
                <td>'.$dado->estoque_pdv11.'</td>
                <td>'.$dado->estoque_pdv13.'</td>
                <td>'.$dado->estoque_apartado.'</td>
                <td>'.$dado->media_venda.'</td>
                <td>'.$dado->custo_venda.'</td>
                <td>'.$dado->ativo.'</td>
                <td>'.$dado->valor_venda4.'</td>
                <td>'.$dado->valor_venda3.'</td>
                <td>'.$dado->valor_venda2.'</td>
                <td>'.$dado->valor_venda1.'</td>
                <td>'.$dado->minimo_p.'</td>
                <td>'.$dado->minimo_d.'</td>
                <td>'.$dado->e1.'</td>
                <td>'.$dado->e2.'</td>
                <td>'.$dado->e3.'</td>
                <td>'.$dado->e4.'</td>
                <td>'.$dado->item_id.'</td>












            </tr>

            ';
        }
    }else{
        $response .= '

        <tr>
        
            <td colspan="6">Nenhum item foi achado</td>
        
        </tr>

            ';
    }

}else{
    $response .= '

    <tr>
      
        <td colspan="5">Nenhum dado Encontrado</td>
       
    </tr>

    ';
}

echo $response;
