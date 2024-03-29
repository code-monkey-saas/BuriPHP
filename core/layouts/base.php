<?php defined('_EXEC') or die; ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="{$_lang}">

<head>
    <meta charset="UTF-8" />
    <meta content="text/html; charset=iso-8859-1" http-equiv="Content-Type" />
    {$dependencies.meta}

    <base href="{$_base}">

    <title>{$_title}</title>

    <!--Adaptive Responsive-->
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="author" content="codemonkey.com.mx" />
    <meta name="description" content="" />

    <link rel="stylesheet" href="{$path.css}valkyrie.css?v=1.0" type="text/css" media="all" />
    <link rel="stylesheet" href="{$path.css}icons.css?v=1.0" type="text/css" media="all" />
    <link rel="stylesheet" href="{$path.css}styles.css?v=1.0" type="text/css" media="all" />
    {$dependencies.css}
</head>

<body>
    {{renderView}}

    <script src="{$path.js}jquery-3.4.1.min.js"></script>
    <script src="{$path.js}valkyrie.js?v=1.0"></script>
    <script src="{$path.js}codemonkey-1.3.0.js?v=1.0"></script>
    <script src="{$path.js}scripts.js?v=1.0"></script>
    {$dependencies.js}

    {$dependencies.other}
</body>

</html>