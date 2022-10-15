<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Atividade</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' media='screen' href='main.css'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
    <script src='main.js'></script>
    <style>

        .tb{
            margin-top: 20px;
            text-align: center;
            width: 100%;
        }
        a{
            text-decoration: none;
        }

        thead, tbody{
            text-align: center;
        }

        tr:hover{
            background-color:#4682B4;
            color: #fff;
            transform: scale(1,1.2);
        }

        a:hover{
            color: red; 
        }

        div{
            float: left;
        }

        h2, h4{
            text-align: center;
            float: left;
            margin-left: 10px;
        }

        form{
            margin-top: 10px;
            text-align: center;
            padding: 20px;
        }

        input:hover{
            border-radius: 5px;
            transform: scale(1, 1.1);
        }

        .bt1:hover{
            background-color: #4682B4;
            color: #fff;
        }

        button{
            text-decoration: none;
            border: 1px solid;
        }
        button:hover{
            background-color: #4682B4;
            border-radius: 5px;
        }


    </style>
</head>
<body >
    <form action="index.php" method="POST" >
        <Input type="text" placeholder="Nome do produto: " name="nomeProduto">
        
        <?php
            include 'conn.php';
            $exibir = $conn -> prepare('SELECT * FROM `setores`');
            $exibir -> execute();  
            echo "<select name='id'";
            while($row=$exibir->fetch()){
                //echo "<a href='index.php?puxarSetor&id= ".$row['id_setor']."'><option>".$row['nome_set']."</option></a>";
                echo "<option value='".$row['id_setor']."'>".$row['nome_set']."</option>";
            }
            echo "</select>";
        ?>
        
        <Input type="text" placeholder="Preço de Custo: " name="precoCusto">
        <Input type="text" placeholder="Preço de Venda: " name="precoVenda">
        <Input type="number" placeholder="Estoque: " name="estoque">
        <Input type="submit" value="Enviar" name="gravar" class="bt1">
        <button><a href="index.php">Recarregar</a></button>
        

    </form>
    </hr>

    <?php  
    //inclusão dos dados no banco de dados
        include 'conn.php';
        if(isset($_POST['gravar'])){
            $nome = $_POST['nomeProduto'];
            $set = $_POST['id'];
            $custo = floatval($_POST['precoCusto']);
            $venda = floatval($_POST['precoVenda']);
            $estoque = intval($_POST['estoque']);
            $sit = 1;
            //echo $nome.'</br>'.$set.'</br>'.$custo.'</br>'.$venda.'</br>'.$estoque;
            $grava = $conn -> prepare('INSERT INTO `produtos` (`id_prod`, `nome_prod`, `setor_prod`, `custo_prod`, `venda_prod`, `estoque_prod`, `situacao_prod`) VALUES (NULL, :pnome, :pid, :pcusto, :pvenda, :pestoque, :psit);');
            $grava -> bindValue(':pnome',$nome);
            $grava -> bindValue(':pid',$set);
            $grava -> bindValue(':pcusto',$custo);
            $grava -> bindValue(':pvenda',$venda);
            $grava -> bindValue(':pestoque', $estoque);
            $grava -> bindValue(':psit', $sit);
            $grava -> execute();
        }
    ?>
    <hr>
    
    <table class="table"> 
        <thead>
            <tr>
           <th scope="col">Código</th> 
            <th scope="col">Nome do Produto</th>
            <th scope="col">Setor</th>
            <th scope="col">Custo de Produção</th>
            <th scope="col">Custo de Venda</th>
            <th scope="col">Estoque</th>
            <th scope="col">Situação</th>
            <th scope="col"></th>
            <th scope="col"></th>
            </tr>
        </thead>

        <?php
        //Exibir tabela
        $exibir = $conn -> prepare('SELECT * FROM `produtos`');
        $exibir -> execute();   
        if($exibir -> rowCount()==0){
            echo "Não há registros";
        }else{
            echo '<tbody>';
            while($row=$exibir->fetch()){
                echo "<tr>";
                echo "<td>".$row['id_prod']."</td>";
                echo "<td>".$row['nome_prod']."</td>";
                switch($row['setor_prod']){
                    case 3:
                        echo "<td>RH</td>";
                        break;
                    case 2:
                        echo "<td>Engenharia</td>";
                        break;
                    case 6:
                        echo "<td>Comercial</td>";
                        break;
                    case 5:
                        echo "<td>Fabrica</td>";
                        break;
                    case 4:
                        echo "<td>Expedição</td>";
                        break;
                }
                //echo "<td>".$row['setor_prod']."</td>";
                echo "<td>".$row['custo_prod']."</td>";  
                echo "<td>".$row['venda_prod']."</td>";
                echo "<td>".$row['estoque_prod']."</td>";
                if($row['situacao_prod']==1){
                    echo "<td>Disponivel</td>";
                }else{
                    echo "<td>Indisponivel</td>";
                }
                //echo "<td>".$row['situacao_prod']."</td>";
                //echo "<td><a href='index.php?aviso&id= ".base64_encode($row['id_cad'])."&nome=".base64_encode($row['nome_cad'])."'>Excluir</a></td>";
                echo "<td><a href='index.php?aviso&id= ".$row['id_prod']."&nome=".$row['nome_prod']."'>Excluir</a></td>";
                echo "<td><a href='index.php?altera&id= ".$row['id_prod']."'>Alterar</a></td>";

                echo "<tr>";
            }
            echo '</tbody>';
        }

        //Alteração
        if(isset($_GET['altera'])){
            $id = $_GET['id'];
            $alterar = $conn -> prepare('SELECT * FROM `produtos` WHERE `id_prod`= ?;');
            $alterar -> bindParam(1, $id);
            $alterar -> execute();
            $row = $alterar -> fetch();

            ?>
       <center>     
        <form action="index.php" method="POST">
            <Input type="hidden" name="id" placeholder="id: " value="<?php echo base64_encode($row['id_prod']) ?>">
            <Input type="text" placeholder="Nome do produto: " name="nomeProduto" value="<?php echo $row['nome_prod'] ?>"> 
            <?php
            include 'conn.php';
            $exibir = $conn -> prepare('SELECT * FROM `setores`');
            $exibir -> execute();  
            echo "<select name='setorProd'>";
            while($row2=$exibir->fetch()){
                //echo "<a href='index.php?puxarSetor&id= ".$row['id_setor']."'><option>".$row['nome_set']."</option></a>";
               echo "<option value='".$row2['id_setor']."'";
                 if($row2['id_setor']==$row['setor_prod']){
                      echo "selected";
                  }
                echo ">".$row2['nome_set']."</option>";
            }
            echo "</select>";
        ?>
            <Input type="text" placeholder="Preço de Custo: " name="precoCusto" value="<?php echo $row['custo_prod'] ?>">
            <Input type="text" placeholder="Preço de Venda: " name="precoVenda" value="<?php echo $row['custo_prod'] ?>">
            <Input type="number" placeholder="Estoque: " name="estoque" value="<?php echo $row['estoque_prod'] ?>">
            <Input type="submit" name= "alterar" value="alterar" class="bt1">
        </form>
        </center>    
    </br>
        </br>
      
            <?php
        }
        if(isset($_POST['alterar'])){
            $id = base64_decode($_POST['id']);
            $nome = $_POST['nomeProduto'];
            $setor = $_POST['setorProd'];
            $precoCusto = $_POST['precoCusto'];
            $precoVenda = $_POST['precoVenda'];
            $estoque = $_POST['estoque'];
            $alterar = $conn -> prepare('UPDATE `produtos` SET `nome_prod` = ? , `setor_prod` = ?, `custo_prod` = ?, `venda_prod` = ?, `estoque_prod` = ? WHERE `produtos`.`id_prod` = ?;');
            $alterar -> bindValue(1, $nome);
            $alterar -> bindValue(2, $setor);
            $alterar -> bindValue(3, $precoCusto);
            $alterar -> bindValue(4, $precoVenda);
            $alterar -> bindValue(5, $estoque);
            $alterar -> bindValue(6, $id);
            $alterar -> execute();

        }



        //excluir arquivos
        if(isset($_GET['excluir'])){
            $id = $_GET['id'];
            $nome = $_GET['nome'];
            $excluir = $conn->prepare('DELETE FROM produtos WHERE `produtos`.`id_prod` = :pid');
            $excluir -> bindValue(':pid', $id);
            $excluir -> execute();
            echo "<h4>".$nome." excluido com sucesso!</h4>";
        }
    
        if(isset($_GET['aviso'])){
            $id=$_GET['id'];
            $nome= $_GET['nome'];
            echo "<h4>Deseja excluir ".$nome."?</br>";
            echo "<h4><a href='index.php?excluir&id=$id&nome=$nome'>SIM</a></h4>"."&nbsp &nbsp"."<h4><a href='index.php'>NÃO</a></h4>";
        }



    ?>
    </table>




</body>
</html>