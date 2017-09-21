<?php 
session_start();
require_once('inc/functions.php');
logged_only();

    if(!empty($_POST)) {
         if($_POST['password'] != $_POST['password_confirm']){
             $_SESSION['flash']['danger'] = "les mots de passe ne corresponds pas";
         }else {
            $user_id = $_SESSION['auth']->id;
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
            require_once('inc/db.php');
            $req = $pdo->prepare("UPDATE users SET password = ?");
            $req->execute([$password]);
         }
    }


require_once('inc/header.php');
?>


    <<h1>Bonjour <?= $_SESSION['auth']->username; ?></h1>

    <form action="" method="post">

        <div class="form-group">
          <label for="password"></label>
          <input type="password" name="password" id="password" class="form-control" placeholder="Changez votre mot de passe">
          
        </div>

        <div class="form-group">
          <label for="password_confirm"></label>
          <input type="password" name="password_confirm" id="password_confirm" class="form-control" placeholder="Confirmation du mot de passe">
          
        </div>

        <button class="btn btn-primary btn-lg" type="submit">Changer mon mot de passe</button>

    </form>

<?php require_once('inc/footer.php'); ?>