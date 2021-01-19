<?php require_once('padrao_restrito.php'); ?>
<?php 
// equipe_membros_tabela
$query_equipe_membros_tabela = sprintf("
SELECT 
	equipe_membros.*
FROM 
	equipe_membros 
WHERE 
    equipe_membros.IdEquipeMembros=%s AND
    equipe_membros.IdEquipe=%s
",
GetSQLValueString(@$url_parametro, "int"),
GetSQLValueString(@$_SESSION[$url . '_equipe_IdEquipe'], "int"));
$equipe_membros_tabela = mysqli_query($conexao, $query_equipe_membros_tabela) or die(mysqli_error($conexao));
$row_equipe_membros_tabela = mysqli_fetch_assoc($equipe_membros_tabela);
$totalRows_equipe_membros_tabela = mysqli_num_rows($equipe_membros_tabela);
// fim - equipe_membros_tabela

// equipe_membros_consulta
$query_equipe_membros_consulta = sprintf("
SELECT 
    COUNT(equipe_membros.IdEquipeMembros) AS retorno,
    (SELECT COUNT(equipe_membros2.tipo) FROM equipe_membros AS equipe_membros2 WHERE tipo=1) as tipo1,
    (SELECT COUNT(equipe_membros2.tipo) FROM equipe_membros AS equipe_membros2 WHERE tipo=2) as tipo2,
    (SELECT COUNT(equipe_membros2.tipo) FROM equipe_membros AS equipe_membros2 WHERE tipo=3) as tipo3
FROM 
	equipe_membros 
WHERE 
	equipe_membros.IdEquipe=%s
",
GetSQLValueString(@$_SESSION[$url . '_equipe_IdEquipe'], "int"));
$equipe_membros_consulta = mysqli_query($conexao, $query_equipe_membros_consulta) or die(mysqli_error($conexao));
$row_equipe_membros_consulta = mysqli_fetch_assoc($equipe_membros_consulta);
$totalRows_equipe_membros_consulta = mysqli_num_rows($equipe_membros_consulta);
if(
    $totalRows_equipe_membros_tabela == 0 and // inserir
    ($row_parametro['cadastro_status'] == 0 or $row_equipe_membros_consulta['retorno'] >= 7)
){

    header('location: '.$url.'/membros'); 
    exit;

}
// fim - equipe_membros_consulta

$listar = $url."/membros";

//region - delete --------------------------------------------------------------------------------------------------------------------------
if ((isset($row_equipe_membros_tabela['IdEquipeMembros'])) && ($row_equipe_membros_tabela['IdEquipeMembros'] != "") && (isset($url_parametro2)) && ($url_parametro2 == "deletar")) {

	// equipe_membros
	$delete_SQL_equipe_membros = sprintf("
	DELETE FROM 
		equipe_membros 
	WHERE 
		IdEquipeMembros=%s
	",
	GetSQLValueString($row_equipe_membros_tabela['IdEquipeMembros'], "int"));
	$Result_equipe_membros = mysqli_query($conexao, $delete_SQL_equipe_membros) or die(mysqli_error($conexao));
	// fim - equipe_membros

	$deleteGoTo = $listar;
	header(sprintf("Location: %s", $deleteGoTo));
	exit;

}
//endregion - fim - delete --------------------------------------------------------------------------------------------------------------------

//region - insert --------------------------------------------------------------------------------------------------------------------------
if (
	(isset($_POST["MM_insert"]) and $_POST["MM_insert"] == "form")
) {

	$insert_SQL_equipe_membros = sprintf("
	INSERT INTO equipe_membros (status, data_criacao, IdEquipe, nome, telefone, nick, Id_Nick, descricao, tipo, senha) 
	VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
	",
	GetSQLValueString(1, "int"),
    GetSQLValueString(date('Y-m-d H:i:s'), "date"),
    GetSQLValueString($_SESSION[$url . '_equipe_IdEquipe'], "int"),
    GetSQLValueString($_POST['nome'], "text"),
    GetSQLValueString($_POST['telefone'], "text"),
    GetSQLValueString($_POST['nick'], "text"),
    GetSQLValueString($_POST['Id_Nick'], "text"),
    GetSQLValueString($_POST['descricao'], "text"),
    GetSQLValueString($_POST['tipo'], "text"),
    GetSQLValueString($_POST['senha'], "text"));

	$Result_insert_equipe_membros = mysqli_query($conexao, $insert_SQL_equipe_membros) or die(mysqli_error($conexao));
	$ultimo_id = mysqli_insert_id($conexao);

	$insertGoTo = $listar;
	header(sprintf("Location: %s", $insertGoTo));
	exit;
}
//endregion - fim - insert --------------------------------------------------------------------------------------------------------------------

//region - update --------------------------------------------------------------------------------------------------------------------------
if ((isset($row_equipe_membros_tabela['IdEquipeMembros'])) && ($row_equipe_membros_tabela['IdEquipeMembros'] != "") && (isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form")) {

	$update_SQL_equipe_membros = sprintf("
	UPDATE equipe_membros 
	SET status=%s, data_edicao=%s, nome=%s, telefone=%s, nick=%s, Id_Nick=%s, descricao=%s, tipo=%s, senha=%s
	WHERE IdEquipeMembros=%s and 
    equipe_membros.IdEquipe=%s",
	GetSQLValueString(1, "int"),
	GetSQLValueString(date('Y-m-d H:i:s'), "date"),
	GetSQLValueString($_POST['nome'], "text"),
    GetSQLValueString($_POST['telefone'], "text"),
    GetSQLValueString($_POST['nick'], "text"),
    GetSQLValueString($_POST['Id_Nick'], "text"),
    GetSQLValueString($_POST['descricao'], "text"),
    GetSQLValueString($_POST['tipo'], "text"),
    GetSQLValueString($_POST['senha'], "text"),

    GetSQLValueString($row_equipe_membros_tabela['IdEquipeMembros'], "int"),
    GetSQLValueString(@$_SESSION[$url . '_equipe_IdEquipe'], "int"));
	
	$Result_update_equipe_membros = mysqli_query($conexao, $update_SQL_equipe_membros) or die(mysqli_error($conexao));

	$updateGoTo = $listar;
	header(sprintf("Location: %s", $updateGoTo));
	exit;
}
//endregion - fim - update --------------------------------------------------------------------------------------------------------------------

?>
<main>
    <!-- area-bg-one -->
    <div class="area-bg-one">
        <!-- about-us-area -->
        <section class="about-us-area mb-50 mt-60">
            <div class="container">
                
                <div class="section-title text-center title-style-three">
                    <h2><?php if($totalRows_equipe_membros_tabela == 0) {?>Cadastrar<?php }else{?>EDITAR<?php } ?> <span>Membro</span></h2>
                </div>

                <div class="contact-info-list mt-5">
                    <ul>
                        <li>
                            <a href="<?php echo $url; ?>/"><i class="fas fa-angle-double-left"></i><span class="text-light">Voltar Ínicio  / </span></a>
                            <a href="<?php echo $url; ?>/membros"><i class="fas fa-angle-double-left"></i><span class="text-light">Membros / </span></a>
                            <a href="<?php echo $url; ?>/sair"><i class="fas fa-sign-out-alt"></i><span class="text-light">Sair</span></a>
                        </li>
                    </ul>
                </div>

                <div class="contact-form mt-40">
                    <form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="form" id="form">

                        <div class="row">
                            <div class="col-md-4 footer-newsletter">
                                <label>Nome</label>
                                <input type="text" name="nome" id="nome" value="<?php echo $row_equipe_membros_tabela['nome'];?>" maxlength="150">
                            </div>
                            <div class="col-md-4 footer-newsletter">
                                <label>Nick</label>
                                <input type="text" name="nick" id="nick" value="<?php echo $row_equipe_membros_tabela['nick'];?>" maxlength="150">
                            </div>
                            <div class="col-md-4 footer-newsletter">
                                <label>ID</label>
                                <input type="text" name="Id_Nick" id="Id_Nick" value="<?php echo $row_equipe_membros_tabela['Id_Nick'];?>" maxlength="10">
                            </div>

                            <div class="col-md-6 footer-newsletter">
                                <label>Senha</label>
                                <input type="password" name="senha" id="senha" value="<?php echo $row_equipe_membros_tabela['senha'];?>" maxlength="50">
                            </div>
                            <div class="col-md-6 footer-newsletter">
                                <label>Confirme a Senha</label>
                                <input type="password" name="senha2" id="senha2" value="<?php echo $row_equipe_membros_tabela['senha'];?>" maxlength="50">
                            </div>

                            <div class="col-md-6 footer-newsletter">
                                <label>Tipo</label>
                                <select id="tipo" name="tipo" >
                                    <option value="1" <?php if (!(strcmp($row_equipe_membros_tabela['tipo'], 1))) {echo "selected=\"selected\"";} ?>>Jogador</option>
                                    <option value="2" <?php if (!(strcmp($row_equipe_membros_tabela['tipo'], 2))) {echo "selected=\"selected\"";} ?>>Reserva</option>
                                    <option value="3" <?php if (!(strcmp($row_equipe_membros_tabela['tipo'], 3))) {echo "selected=\"selected\"";} ?>>Treinador</option>
                                </select>
                            </div>
                            <div class="col-md-6 footer-newsletter">
                                <label>Telefone</label>
                                <input type="text" name="telefone" id="telefone" value="<?php echo $row_equipe_membros_tabela['telefone'];?>" maxlength="150">
                            </div>
                        </div>

                        <div class="footer-newsletter">
                            <label>Descrição de apresentação</label>
                            <textarea name="descricao" id="descricao" class="input_contato" maxlength="500"><?php echo $row_equipe_membros_tabela['descricao'];?></textarea>
                        </div>

                        <input type="hidden" name="<?php if($totalRows_equipe_membros_tabela == 0) {?>MM_insert<?php }else{?>MM_update<?php } ?>"  value="form" />
                        <button type="submit"><?php if($totalRows_equipe_membros_tabela == 0) {?>Cadastrar<?php }else{?>EDITAR<?php } ?></button>

                    </form>
                </div>

            </div>
        </section>
        <!-- about-us-area-end -->
    </div>
    <!-- area-bg-one-end -->

</main>
<?php mysqli_free_result($equipe_membros_consulta);?>
<?php mysqli_free_result($equipe_membros_tabela);?>