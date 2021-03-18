<h2>List zaměstnanců</h2>
<p>Řazení zaměsntanců podle:</p>
<form action="" method="GET">
    <select name="sort" id="supervisor">
        <option></option>
        <option value="Jméno">Jméno</option>
        <option value="Příjmení">Příjmení</option>
        <option value="E-mail">E-Mail</option>
    </select>
    <input type="submit" value="Seřadit">
</form>
<table>
    <tr>
        <?php foreach ($employees[0] as $key => $value) : ?>
            <th><?= $key ?></th>
        <?php endforeach; ?>
    </tr>
    <?php foreach ($employees as $employee) : ?>
        <tr>
            <td><?= $employee["Jméno"] ?></td>
            <td><?= $employee["Příjmení"] ?></td>
            <td><?= $employee["Pohlaví"] ?></td>
            <td><?= $employee["Ulice"] ?></td>
            <td><?= $employee["Obec"] ?></td>
            <td><?= $employee["PSČ"] ?></td>
            <td><?= $employee["Telefon"] ?></td>
            <td><?= $employee["E-mail"] ?></td>
            <td><?= $employee["Pozice"] ?></td>
            <td><?= $employee["Nadřízený"] ?></td>

        </tr>
    <?php endforeach; ?>


</table>