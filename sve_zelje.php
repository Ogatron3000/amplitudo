<?php

if (file_exists('zelje_db')) {
    $tableRows = '<tr>';

    $wishFiles = array_slice(scandir('zelje_db'), 2);
    foreach ($wishFiles as $wishFile) {
        $handle = fopen("zelje_db/$wishFile", 'r');
        $wishData = json_decode(fread($handle, filesize("zelje_db/$wishFile")));

        foreach ($wishData as $value) {
            $tableRows .= "<td>$value</td>";
        }

        fclose($handle);

        $tableRows .= '</tr>';
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wish</title>
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">

    <style>
        th,
        td {
            border: 4px solid #EF4444;
            padding: 1rem;
            width: 8rem;
            max-width: 24rem;
            word-wrap: break-word;
        }
    </style>
</head>

<body>
    <div class="text-center text-gray-700">
        <h1 class="text-4xl text-red-500 my-12">Wishes</h1>
        <?php if (file_exists('zelje_db')) : ?>
            <table class="mx-auto">
                <thead>
                    <tr>
                        <th>First name</th>
                        <th>Last name</th>
                        <th>City</th>
                        <th>Behaviour</th>
                        <th>Wish</th>
                    </tr>
                </thead>
                <tbody>
                    <?php echo $tableRows; ?>
                </tbody>
            </table>
        <?php else : ?>
            <h2 class="text-2xl text-red-500">Nothing to show yet!</h2>
        <?php endif; ?>
    </div>

</body>

</html>