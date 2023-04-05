<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Ingresos</title>
</head>
<body>
    <h1>Lista de ingresos</h1>

    <ul>
        <?php foreach($results as $result): ?>

            <li>Gastaste <?= $result["amount"] ?> USD en <?= $result["description"] ?> </li>

        <?php endforeach; ?>

    </ul>

</body>
</html>