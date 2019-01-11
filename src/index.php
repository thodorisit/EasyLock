<?php
    require 'easylock.class.php';
    (new EasyLock)->setPasswords(array('password1','password2'))->lock();
?>
<html>
    <head>
        <style>
            body {
                backround:#EDEDED;
                text-align:center;
                font-family:Arial;
                color:#0A0A0A;
            }
            .page-content {
                margin: 0;
                position: absolute;
                top: 50%;
                left: 50%;
                -ms-transform: translate(-50%, -50%);
                transform: translate(-50%, -50%);
            }
        </style>
    </head>
    <body>
        <div class="page-content">
            Your page content...
        </div>
    </body>
</html>