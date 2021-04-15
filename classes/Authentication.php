<?php
class Authentication
{
   private $users;

   function __construct(Database $users)
   {
      $this->users = $users;
   }

   public function login($email, $password)
   {
      $user = $this->users->findOne("email", $email);
      if (!empty($user)) {
         $user_pswd = $user["heslo"];
         echo $user_pswd;
         echo $password;
         echo $user["email"];
         if (password_verify($password, $user_pswd)) {
            echo "fdd";
            session_regenerate_id();
            $_SESSION['email'] = $email;
            $_SESSION['password'] = $user_pswd;
            return true;
         }
      }
      return false;
   }


   public function isLoggedIn()
   {
      if (empty($_SESSION['email'])) {
         return false;
      }
      return true;
   }

   public function getUser()
   {
      if ($this->isLoggedIn()) {
         return $this->users->findOne("email", strtolower($_SESSION['email']));
      } else {
         return false;
      }
   }
}
