<?php
include("connect.php");
$response = '';



if(isset($_POST['buscar']) && !empty($_POST['buscar'])){
    $buscar = $_POST['buscar'];
    $cmd = $pdo->prepare("SELECT * FROM cidadao where nome LIKE '%$buscar%'");
    $cmd->execute();
    $dados = $cmd->fetchAll(PDO::FETCH_CLASS);

    if ($cmd->rowCount() > 0) {
        foreach ($dados as $dado) {
            $response .= '

            <tr>
                <td>'.$dado->id.'</td>
                <td>'.$dado->nome.'</td>
                <td>'.$dado->email.'</td>
                <td>Maputo</td>
                <td>2342342342B</td>
                <td>
                    <button class="btn-sm btn-outline-primary"><i class="fa-solid fa-edit"></i></button>
                    <button class="btn-sm btn-outline-danger"><i class="fa-solid fa-trash-can"></i></button>
                </td>
            </tr>

            ';
        }
    }else{
        $response .= '

        <tr>
        
            <td colspan="6">Nenhum Usuario foi achado</td>
        
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
