<?php
ob_start();
require('../config.php');
include('../includes/verificacao.php');

$page_title = "Perfil";
$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

if(isset($_GET["u"]) && $_GET["u"] == $_SESSION["id"]){

    $stmt = $pdo->prepare("SELECT * FROM users WHERE id=:id");
    $stmt->bindParam(":id", $param_id, PDO::PARAM_INT);
    $param_id = $_GET["u"];
    $stmt->execute();
    $dados = $stmt->fetch(PDO::FETCH_ASSOC);
    $id = $dados["id"];
    $name = $dados["name"];
    $username = $dados["username"];
    $passport = $dados["passport"];
    $role = $dados["role"];
    $image = $dados["image"];
    $rg = $dados["rg"];

}  else {
    header("Location: ../admin/index.php");
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<?php include("partials/head.php"); ?>
<body>
  <div class="container-scroller">
    <!-- partial:../../partials/_navbar.html -->
    <?php include("partials/navbar.php"); ?>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:../../partials/_settings-panel.html -->
      <?php include("partials/settings-panel.php"); ?>
      <!-- partial -->
      <!-- partial:../../partials/_sidebar.html -->
      <?php include("partials/sidebar.php"); ?>
      <!-- partial -->
      <div class="main-panel">        
        <div class="content-wrapper">
          <div class="row">

        <?php 
                  //Receber dados do formulário
      if($_SERVER["REQUEST_METHOD"] == "POST"){

        $arquivo = $_FILES['image'];
$pasta = "images/faces/";
$nomeDoArquivo = $arquivo['name'];
$novoNomeDoArquivo = uniqid();
$extensao = strtolower(pathinfo($nomeDoArquivo, PATHINFO_EXTENSION));
$path = $pasta . $novoNomeDoArquivo . "." . $extensao;
$deu_certo = move_uploaded_file($arquivo["tmp_name"], $path); 

            $query = "UPDATE users SET name=:name, image=:image, passport=:passport, rg=:rg WHERE id=:id";
            $edit = $pdo->prepare($query);
            $edit->bindParam(':name', $_POST['name'], PDO::PARAM_STR);
            $edit->bindParam(':image', $path);
            $edit->bindParam(':passport', $_POST['passport'], PDO::PARAM_INT);
            $edit->bindParam(':rg', $_POST['rg'], PDO::PARAM_STR);
            $edit->bindParam(':id', $id, PDO::PARAM_INT);

            if($edit->execute()){
              $usuario = $_SESSION['username'];
              $mensagem = "O usuário ".$_SESSION['username']." editou seu perfil.";
              $sql = "INSERT INTO logs(data, mensagem, usuario) VALUES(NOW(), :mensagem, :usuario)";
              $stmt = $pdo->prepare($sql);
              $stmt->bindParam(":mensagem", $mensagem, PDO::PARAM_STR);
              $stmt->bindParam(":usuario", $usuario, PDO::PARAM_STR);
              $stmt->execute();
              echo '<div class="alert alert-success md" role="alert">
             Perfil editado com sucesso! Recarregando a página em 5 segundos...
            </div>';
              header("Refresh: 5");
            } else{
              echo '<div class="alert alert-danger md" role="alert">
             ERRO: Usuário não editado.
            </div>';
            }
          }
        ?>
            <div class="col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Edite seu perfil</h4>
                  <p class="card-description">
                    Todos os campos são obrigatórios.
                  </p>
                  <form class="forms-sample" enctype="multipart/form-data" id="edit-usuario" action="" method="POST">
                  <div class="col-sm-3">
          <img src="<?php echo $uss_image; ?>" width="150" height="150" class="rounded-circle me-2">
    </div>  
                  <div class="form-group">
                      <label for="exampleInputName1">Nome</label>
                      <input type="text" id="name" name="name" class="form-control" value="<?php echo $uss_name; ?>" placeholder="<?php echo $uss_name; ?>">
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail3">Usuário</label>
                      <input type="text" class="form-control" id="exampleInputEmail3" placeholder="<?php echo $uss_username; ?>" disabled>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputPassword4">Passaporte</label>
                      <input type="text" id="passport" name="passport" class="form-control" value="<?php echo $uss_passport; ?>" placeholder="<?php echo $uss_passport; ?>">
                    </div>
                    <div class="form-group">
                      <label for="exampleInputPassword4">REGISTRO</label>
                      <input type="text" id="rg" name="rg" class="form-control" value="<?php echo $uss_rg; ?>" placeholder="<?php echo $uss_rg; ?>">
                    </div>
                    <div class="form-group">
                      <label>Imagem do perfil</label>
                      <input type="file" name="image" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary me-2">Atualizar</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:../../partials/_footer.html -->
        <footer class="footer">
          <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Premium <a href="https://www.bootstrapdash.com/" target="_blank">Bootstrap admin template</a> from BootstrapDash.</span>
            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Copyright © 2021. All rights reserved.</span>
          </div>
        </footer>
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  <!-- plugins:js -->
  <script src="../admin/vendors/js/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <!-- Plugin js for this page -->
  <script src="../admin/vendors/typeahead.js/typeahead.bundle.min.js"></script>
  <script src="../admin/vendors/select2/select2.min.js"></script>
  <script src="../admin/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="../admin/js/off-canvas.js"></script>
  <script src="../admin/js/hoverable-collapse.js"></script>
  <script src="../admin/js/template.js"></script>
  <script src="../admin/js/settings.js"></script>
  <script src="../admin/js/todolist.js"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <script src="../admin/js/file-upload.js"></script>
  <script src="../admin/js/typeahead.js"></script>
  <script src="../admin/js/select2.js"></script>
  <!-- End custom js for this page-->
</body>

</html>
