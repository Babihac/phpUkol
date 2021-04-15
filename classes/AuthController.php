<?php

class AuthController
{
   private Authentication $authentication;

   public  function __construct(Authentication $authentication)
   {
      $this->authentication = $authentication;
   }

   public function showLoginForm()
   {
      return ["title" => "přihlášení", "template" => 'loginForm.html.php'];
   }

   public function login()
   {
      if (!empty($_POST['email']) && !empty($_POST["password"]) &&  $this->authentication->login($_POST['email'], $_POST['password'])) {
         header("location: index.php?");
      } else {
         return ["title" => "přihlášení", "template" => 'loginForm.html.php', "vars" => ["error" => "chybně zadané údaje"]];
      }
   }

   public function logout()
   {
      session_destroy();
      header("location: index.php?");
   }
}
