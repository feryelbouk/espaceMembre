<?php 
session_start();


$user_id = $_GET['id'];
$token = $_GET['token'];

require('inc/db.php');

$req = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$req->execute([$user_id]);
$user = $req->fetch();

if($user && $user->confirmation_token == $token) {
    

    
    $req = $pdo->prepare("UPDATE users SET confirmation_token = NULL, confirmed_at = NOW() WHERE id = ?");
    $req->execute([$user_id]);
    $_SESSION['flash']['success'] = "Votre compte a bien été validé";
    $_SESSION['auth'] = $user;
    header('location:account.php');
} else {
    $_SESSION['flash']['danger'] = "ce token n'est plus valide";
    header('location: login.php');
}