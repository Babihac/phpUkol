<?php if ($user["id"] == $employee["id"] || $user["pozice"] == "admin") : ?>
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
      <input type="hidden" name="emp[id]" value="<?= $employee['id'] ?? '' ?>">
      <label for="firstname">Jméno</label>
      <input type="text" name="emp[jmeno]" id="firstname" value="<?= $employee['jmeno'] ?? '' ?>">

      <label for="lastname">Příjmení</label>
      <input type="text" name="emp[prijmeni]" id="lastname" value="<?= $employee['prijmeni'] ?? '' ?>">


      <div>
         <label for="gender">Pohlaví</label>
         <select name="emp[pohlavi]" id="gender">
            <option value="Muž">Muž</option>
            <option value="Žena">Žena</option>
            <option value="Agender">Agender</option>
            <option value="Transgender">Transgender</option>
            <option value="Vrtulník Apache">Vrtulník Apache</option>
         </select>
      </div>


      <label for="street">Ulice</label>
      <input type="text" name="emp[ulice]" id="street" value="<?= $employee['ulice'] ?? '' ?>">

      <label for="city">Obec</label>
      <input type="text" name="emp[obec]" id="city" value="<?= $employee['obec'] ?? '' ?>">

      <label for="zipCode">PSČ</label>
      <input type="text" name="emp[psc]" id="zipCode" value="<?= $employee['psc'] ?? '' ?>">


      <label for="telefon">Telefon</label>
      <input readonly type="text" name="emp[telefon]" id="phone" value="<?= $employee['telefon'] ?? '' ?>">


      <label for="mail">E-Mail</label>
      <input type="text" name="emp[email]" id="mail" value="<?= $employee['email'] ?? '' ?>">

      <!-- <label for="position">Pozice</label>
    <input type="text" name="emp[pozice]" id="position" value="<?= $employee['pozice'] ?? '' ?>"> -->


      <label for="position">Pozice</label>
      <?php if ($user["pozice"] == "dělník") : ?>
         <select disabled name="emp[pozice]" id="supervisor">
            <option></option>
            <?php foreach ($positions as $position) : ?>
               <?php if ($employee['pozice'] == $position['nazev_pozice']) : ?>
                  <option selected value="<?= $position['nazev_pozice'] ?>"><?= $position['nazev_pozice'] ?></option>
               <?php else : ?>
                  <option value="<?= $position['nazev_pozice'] ?>"><?= $position['nazev_pozice'] ?></option>
               <?php endif; ?>
            <?php endforeach; ?>
         </select>
         <input type="hidden" name="emp[pozice]" value="<?= $employee['pozice'] ?>">

      <?php else : ?>
         <select name="emp[pozice]" id="supervisor">
            <option></option>
            <?php foreach ($positions as $position) : ?>
               <?php if ($employee['pozice'] == $position['nazev_pozice']) : ?>
                  <option selected value="<?= $position['nazev_pozice'] ?>"><?= $position['nazev_pozice'] ?></option>
               <?php else : ?>
                  <option value="<?= $position['nazev_pozice'] ?>"><?= $position['nazev_pozice'] ?></option>
               <?php endif; ?>
            <?php endforeach; ?>
         </select>
      <?php endif; ?>

      <label for="supervisor">Nadřízený</label>
      <?php if ($user["pozice"] == "dělník") : ?>
         <select disabled name="emp[nadrizeny]" id="supervisor">
            <option></option>
            <?php foreach ($supervisors as $supervisor) : ?>
               <?php if ($employee['nadrizeny'] == $supervisor['jmeno']) : ?>
                  <option selected value="<?= $supervisor['jmeno'] ?>"><?= $supervisor['jmeno'] ?></option>
               <?php else : ?>
                  <option value="<?= $supervisor['jmeno'] ?>"><?= $supervisor['jmeno'] ?></option>
               <?php endif; ?>
            <?php endforeach; ?>
         </select>
         <input type="hidden" name="emp[nadrizeny]" value="<?= $employee['nadrizeny'] ?>">

      <?php else : ?>
         <select name="emp[nadrizeny]" id="supervisor">
            <option></option>
            <?php foreach ($supervisors as $supervisor) : ?>
               <?php if ($employee['nadrizeny'] == $supervisor['jmeno']) : ?>
                  <option selected value="<?= $supervisor['jmeno'] ?>"><?= $supervisor['jmeno'] ?></option>
               <?php else : ?>
                  <option value="<?= $supervisor['jmeno'] ?>"><?= $supervisor['jmeno'] ?></option>
               <?php endif; ?>
            <?php endforeach; ?>
         </select>
      <?php endif ?>
      <input type="submit" value="odeslat">
   </form>

<?php else : ?>
   <span>Můžete editovat pouze Vaše údaje</span>
<?php endif; ?>

<a href="index.php">Zpět</a>