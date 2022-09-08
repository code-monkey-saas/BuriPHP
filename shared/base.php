<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="">

<head>
    <meta charset="UTF-8" />
    <meta content="text/html; charset=iso-8859-1" http-equiv="Content-Type" />
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="author" content="codemonkey.com.mx" />
    <meta name="description" content="" />

    <base href="{$pageBase}">

    <title>{$pageTitle}</title>

    <link rel="stylesheet" href="{{path|css}}styles.css" type="text/css" media="all" />
    {$cssDependencies}
</head>

<body>
    {{renderView}}

    <script src="{{path|js}}scripts.js"></script>
    {$jsDependencies}
</body>

</html>