<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta name="description" content="">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no">

    <title>{{ $config['title'] }}</title>
    <base href="{{ url('') }}" />
    <meta name="title" content="{{ $config['title'] }}" />
    <meta name="description" content="{{ $config['description'] }}" />
    <link rel="apple-touch-icon" sizes="57x57" href="images/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="images/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="images/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="images/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="images/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="images/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="images/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="images/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="images/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="images/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="images/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon/favicon-16x16.png">
    <link rel="manifest" href="images/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#3a6073">
    <meta name="msapplication-TileImage" content="images/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#3a6073">
    <!-- Place favicon.ico in the root directory -->
    <link rel="stylesheet" href="styles/landing-vendor.css">
    <link rel="stylesheet" href="styles/landing.css">
    <script src="scripts/modernizr.js"></script>
</head>

<body>
    <main>
        <section class="body">
            <div class="logo">
                <div class="image">
                    <img src="{{ $config['first_logo'] }}" alt="Logo One">
                    @if (!empty($config['second_logo']) && $config['second_logo'] != 'images/logomsonly.png')
                        <img src="{{ $config['second_logo'] }}" class="ms-1" alt="Logo One">
                    @endif
                </div>
                <div class="text">
                    <h1>{{ $config['first_title'] }}</h1>
                    <h1>{{ $config['second_title'] }}</h1>
                </div>
            </div>
        </section>
    </main>
</body>

</html>
