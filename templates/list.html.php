<h2>List zaměstnanců</h2>
<div>
    <form action="" method="GET">
        <div class="options">
            <div>
                <p>Vyhledat zaměstance podle nadřízeného</p>
                <select name="supervisor" id="supervisor">
                    <option></option>
                    <?php foreach ($supervisors as $supervisor) : ?>
                        <option value="<?= $supervisor['jmeno'] ?>"><?= $supervisor['jmeno'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <p>Vyhledat zaměstance podle pozice</p>
                <select name="position" id="position">
                    <option></option>
                    <?php foreach ($positions as $position) : ?>
                        <option value="<?= $position['nazev_pozice'] ?>"><?= $position['nazev_pozice'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <p>Řazení zaměsntanců podle:</p>
                <select name="sort" id="sort">
                    <option></option>
                    <option value="jmeno">Jméno</option>
                    <option value="prijmeni">Příjmení</option>
                    <option value="email">E-Mail</option>
                </select>
            </div>
        </div>
        <input type="submit" value="Vyhledat">
    </form>
</div>
<table>
    <tr>
        <th>Jméno</th>
        <th>Příjmení</th>
        <th>pohlavi</th>
        <th>Ulice</th>
        <th>Obec</th>
        <th>PSČ</th>
        <th>Telefon</th>
        <th>Email</th>
        <th>Pozice</th>
        <th>Nadřízený</th>
    </tr>
    <?php foreach ($employees as $employee) : ?>
        <tr>
            <td><?= $employee["jmeno"] ?></td>
            <td><?= $employee["prijmeni"] ?></td>
            <td><?= $employee["pohlavi"] ?></td>
            <td><?= $employee["ulice"] ?></td>
            <td><?= $employee["obec"] ?></td>
            <td><?= $employee["psc"] ?></td>
            <td><?= $employee["telefon"] ?></td>
            <td><?= $employee["email"] ?></td>
            <td><?= $employee["pozice"] ?></td>
            <td><?= $employee["nadrizeny"] ?></td>
            <td><a href="index.php?route=employee/edit&id=<?= $employee['id'] ?>">Editovat</a></td>

        </tr>
    <?php endforeach; ?>

</table>
<div class="buttons">
    <?php if ($page > 1) : ?>
        <?php
        $query = $_GET;
        // replace parameter(s)
        $query['page'] = $page - 1;
        // rebuild url
        $query_result = http_build_query($query);
        // new link
        ?>
        <a href="<?php echo $_SERVER['PHP_SELF']; ?>?<?php echo $query_result; ?>">Předchozí</a>
    <?php endif; ?>
    <?php if ($page < $maxPage) : ?>
        <?php
        $query = $_GET;
        // replace parameter(s)
        $query['page'] = $page + 1;
        // rebuild url
        $query_result = http_build_query($query);
        // new link
        ?>
        <a href="<?php echo $_SERVER['PHP_SELF']; ?>?<?php echo $query_result; ?>">Další</a>
    <?php endif; ?>
</div>