<!-- resources/views/app.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Laravel React App</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>
<body>
<!-- This div is where React will mount the app -->
<div id="app"></div>

<script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
