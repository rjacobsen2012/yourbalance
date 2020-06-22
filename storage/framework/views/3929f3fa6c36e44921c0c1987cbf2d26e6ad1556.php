<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

        <title>Budget App</title>

        <link rel="stylesheet" type="text/css" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="<?php echo e(mix('css/app.css')); ?>">


        <script defer src="<?php echo e(mix('js/app.js')); ?>"></script>
    </head>
    <body>
        <div id="app" data-page="<?php echo e(json_encode($page)); ?>"></div>
        <script data-ad-client="ca-pub-2577859903263502" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    </body>
</html>
<?php /**PATH /Users/richardjacobsen/development/rj-app/resources/views/app.blade.php ENDPATH**/ ?>