   <?php if (!empty($errors)) : ?>
      <div class="errors">
         <p>Tyto pole obsahují chybu</p>
         <ul>
            <?php
            foreach ($errors as $error) :
            ?>
               <li><?= $error ?></li>
            <?php
            endforeach; ?>
         </ul>
      </div>
   <?php
   endif;
   ?>
   <form action="" method="POST">
      <label for="oldPassword">Původní heslo</label>
      <input id="oldPassword" type="password" name="oldPassword">

      <label for="newPassword">Nové heslo</label>
      <input id="newPassword" type="password" name="newPassword">

      <label for="passwordConfirm">potvrtďte heslo</label>
      <input id="passwordConfirm" type="password" name="passwordConfirm">

      <input type="submit" value="Potrzení">
   </form>



   <a href="index.php">Zpět</a>