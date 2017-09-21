<?php 
session_start();
require_once('inc/functions.php');

if(!empty($_POST) && !empty($_POST['username']) && !empty($_POST['password'])) {
    require_once('inc/db.php');
    require_once('inc/functions.php');
    
    $req = $pdo->prepare("SELECT * FROM users WHERE (username = :username OR email = :username) AND confirmed_at IS NOT NULL");
    $req->execute([
        'username' => $_POST['username']
    ]);
    $user = $req->fetch();
    
    if(password_verify($_POST['password'], $user->password)){
        
        $_SESSION['auth'] = $user;
        $_SESSION['flash']['success'] = "vous êtes maintenant bien connecté";
        
        header('location: account.php');
        exit();
    }else {
        $_SESSION['flash']['danger'] = "Identifiant ou mot de passe incorrect";
    }
}


?>





<?php require_once('inc/header.php'); ?>
<h1>Se connecter</h1>

<form method="POST" action="">


<!-- pseudo field  -->
    <div class="form-group">
      <label for="username">Pseudo ou l'email:</label>
      <input type="text"
        class="form-control" name="username" id="username" placeholder="" >
    </div>

    

    <!-- pseudo field  -->
    <div class="form-group">
      <label for="password">Mot de passe :</label>
      <input type="password"
        class="form-control" name="password" id="password" placeholder="" required>
    </div>

    
    <button class="btn btn-primary btn-lg" type="submit">Se connecter</button>
</form>


<?php require_once('inc/footer.php'); ?> 