<?php

session_start();

function showError($input)
{
    if (isset($_SESSION['errors'][$input . '_err'])) echo str_replace('_', ' ', $_SESSION['errors'][$input . '_err']);
}

function showData($input)
{
    if (isset($_SESSION[$input])) echo htmlspecialchars($_SESSION[$input]);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MakeWish</title>
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
</head>

<body>

    <div class="grid grid-cols-2 gap-40 h-auto h-screen">
        <div class="bg-red-500 h-full  w-full flex flex-col items-center justify-center">
            <h1 class="text-6xl text-white mb-6">MakeWish CORP</h1>
            <h2 class="text-2xl text-white">Where all your wishes come true!</h2>
        </div>
        <form action="sacuvaj_zelju.php" method="POST" class="max-w-md grid grid-cols-1 gap-5 my-14">
            <label class="block">
                <span class="text-gray-700">First name</span>
                <span class="text-sm text-white bg-black px-1 float-right"><?php showError('first_name'); ?></span>
                <input value="<?php showData('first_name'); ?>" type=" text" name="first_name" class="mt-1 block w-full border p-2 border-gray-700 outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500" />
            </label>

            <label class="block">
                <span class="text-gray-700">Last name</span>
                <span class="text-sm text-white bg-black px-1 float-right"><?php showError('last_name'); ?></span>
                <input value="<?php showData('last_name'); ?>" type="text" name="last_name" class="mt-1 block w-full border p-2 border-gray-700 outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500" />
            </label>
            <fieldset>
                <span class="mb-1">Did you behave this year?</span>
                <span class="text-sm text-white bg-black px-1 float-right"><?php showError('behaviour'); ?></span>
                <label class="flex items-center">
                    <input checked name="behaviour" type="radio" value="Yes" class="text-red-500 border p-2 border-gray-700 outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500">
                    <span class="ml-2 text-gray-700">Yes</span>
                </label>
                <label class="flex items-center">
                    <input name="behaviour" type="radio" value="No" class="text-red-500 focus:ring-2 focus:ring-red-500">
                    <span class="ml-2 text-gray-700">No</span>
                </label>
            </fieldset>
            <label class="block">
                <span class="text-gray-700">City</span>
                <span class="text-sm text-white bg-black px-1 float-right"><?php showError('city'); ?></span>
                <select name="city" class="block w-full mt-1 border p-2 border-gray-700 outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500">
                    <option value="London">London</option>
                    <option value="Paris">Paris</option>
                    <option value="Rome">Rome</option>
                    <option value="Madrid">Madrid</option>
                    <option value="Berlin">Berlin</option>
                    <option value="Berlin">Podgorica</option>
                </select>
            </label>
            <label class="block">
                <span class="text-gray-700">Wish</span>
                <span class="text-sm text-white bg-black px-1 float-right"><?php showError('wish'); ?></span>
                <textarea name="wish" class="mt-1 block w-full resize-none border p-2 border-gray-700 outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500" rows="3"><?php showData('wish'); ?></textarea>
            </label>
            <button type="submit" name="submit" class="bg-red-500 p-3 text-white font-semibold text-lg hover:bg-red-600">Make a wish!</button>
        </form>
    </div>

    <script>
        let radio = document.getElementsByName('behaviour');
        if ("<?php showData('behaviour') ?>" === 'No') radio[1].checked = true;

        let city = "<?php showData('city'); ?>";
        if (city) document.querySelector('select').value = city;
    </script>

</body>

</html>