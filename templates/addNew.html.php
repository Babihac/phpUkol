<?php
if (!empty($errors)) :
?>
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
    <label for="firstname">Jméno</label>
    <input type="text" name="emp[firstname]" id="firstname" value="<?= $employee['firstname'] ?? '' ?>">

    <label for="lastname">Příjmení</label>
    <input type="text" name="emp[lastname]" id="lastname" value="<?= $employee['lastname'] ?? '' ?>">


    <div>
        <label for="gender">Pohlaví</label>
        <select name="emp[gender]" id="gender">
            <option value="Muž">Muž</option>
            <option value="Žena">Žena</option>
            <option value="Agender">Agender</option>
            <option value="Transgender">Transgender</option>
            <option value="Vrtulník Apache">Vrtulník Apache</option>
        </select>
    </div>


    <label for="street">Ulice</label>
    <input type="text" name="emp[street]" id="street" value="<?= $employee['street'] ?? '' ?>">

    <label for="city">Obec</label>
    <input type="text" name="emp[city]" id="city" value="<?= $employee['city'] ?? '' ?>">

    <label for="zipCode">PSČ</label>
    <input type="text" name="emp[zipCode]" id="zipCode" value="<?= $employee['zipCode'] ?? '' ?>">


    <label for="telefon">Telefon</label>
    <input type="text" name="emp[phone]" id="phone" value="<?= $employee['phone'] ?? '' ?>">


    <label for="mail">E-Mail</label>
    <input type="text" name="emp[mail]" id="mail" value="<?= $employee['mail'] ?? '' ?>">

    <label for="position">Pozice</label>
    <input type="text" name="emp[position]" id="position" value="<?= $employee['position'] ?? '' ?>">

    <label for="supervisor">Nadřízený</label>
    <select name="emp[supervisor]" id="supervisor">
        <option></option>
        <option value="Mistr">Mistr</option>
        <option value="Putin">Putin</option>
        <option value="Mao Ce-tung z hradu">Mao Ce-tung z hradu</option>
    </select>
    <input type="submit" value="odeslat">
</form>
<a href="index.php">Zpět</a>