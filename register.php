
<?php session_start(); ?>
<?php require_once('inc/functions.php'); ?>

<?php
    
    if(!empty($_POST)){


        $errors = [];
        require_once('inc/db.php');
        // vérification du pseudo
        if(empty($_POST['username']) || !preg_match('/^[a-zA-Z0-9_]+$/', $_POST['username'])) {
            $errors['username'] = "Votre pseudo n'est pas valide (alphanumérique)";
        }else {
            $req = $pdo->prepare("SELECT id FROM users WHERE username = ?");
            $req->execute([$_POST['username']]);
            $user = $req->fetch();
            if($user) {
                $errors['username'] = "Ce pseudo est déja pris";
            }
        }
        // vérification de l'email
        if(empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "votre email n'est pas valide";
        }else {
            $req = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $req->execute([$_POST['email']]);
            $user = $req->fetch();
            if($user) {
                $errors['email'] = "Cet email est déja pris";
            }
        }

        // vérification de password
        if(empty($_POST['password']) || $_POST['password'] != $_POST['password_confirm']) {
            $errors['password'] = "vous devez rentrer un mot de passe valide";
        }

        

        if(empty($errors)) {

                    

                    $req = $pdo->prepare("INSERT INTO users SET username = ?, password = ?, email = ?, confirmation_token = ?");
                    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
                    $token =str_random(60);
                    $req->execute([$_POST['username'], $password, $_POST['email'], $token]);
                    
                    $user_id = $pdo->lastInsertId();

                    mail($_POST['email'], 'confirmation de votre compte', "Afin de valider votre compte merci de cliquer sur ce lien\n\nhttp://localhost/PHP/comptes/confirm.php?id=$user_id&token=$token");
                    $_SESSION['flash']['success'] = "un email de confirmation vous a été envoyer pour valider votre compte";
                    header('location: login.php');
                    exit();
        }
        // debug($errors);

    }
?>
<?php require('inc/header.php'); ?>
<h1>S'inscrire</h1>

    <?php if(!empty($errors)): ?>
        <div class="alert alert-danger" role="alert">
        <p>
            Vous n'avez pas rempli le formulaire correctement
        </p>
        <ul>
            <?php foreach ($errors as $error): ?>

                <li><?= $error; ?></li>
            <?php endforeach; ?>
        </ul>
        
        </div>
    <?php endif; ?>

<form method="POST" action="">


<!-- pseudo field  -->
    <div class="form-group">
      <label for="username">Pseudo :</label>
      <input type="text"
        class="form-control" name="username" id="username" placeholder="" >
    </div>

    <!-- email field  -->
    <div class="form-group">
      <label for="email">Email :</label>
      <input type="text"
        class="form-control" name="email" id="email" placeholder="" >
    </div>

    <!-- pseudo field  -->
    <div class="form-group">
      <label for="password">Mot de passe :</label>
      <input type="password"
        class="form-control" name="password" id="password" placeholder="" required>
    </div>

    <!-- pseudo field  -->
    <div class="form-group">
      <label for="password_confirm">Confirmez votre mot de passe :</label>
      <input type="password"
        class="form-control" name="password_confirm" id="password_confirm" placeholder="" required>
    </div>

    <button class="btn btn-primary btn-lg" type="submit">M'inscrire</button>
</form>


<?php require('inc/footer.php'); ?>