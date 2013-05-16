<?php
$message="IKcr9Df3RYuoBaysfvs+DVUyjR6kA/a97Ce8zLJRGQmOuq/aY/SXijQjGfWSuMQohNIOCTBd/cV86GY3iiG8vVJIExToXp/+dBFFXEN+ZIucB6Zu3jAiZO/S0/ivuNRM553xQexuuQFP9c5zweB7E/MvxzBxIwD5MyAD9ltws9pOBMSMvZQAzBwuNd66JdVd7VvQmgdQTbYDM0KyltziGzxublLVojcYyzfjbzo1YE1B2Y0hNuhmn33F96VSC6Xi0Trnk1hOzWWa/sU2UuB7/OgUTGnOPzfKn/koxbxO1I5busw/YwYRQSp6dyk2jaRUkp9fUJmfsIczsENmdqulfzmqra1Sj8701/dOwsbgra4PBUiExptgScy80vbsNI7K22l2xWrbbG6TjLvtHRJqrNWN1l+9h0WPYPNk1yfZcw5/xg8Pw8l+7HqXMMNcORV+YhfkqB/e9xAK+yxe+L/5/ULKYM/Tp2KA0uH3Vo+Yuon12OAz1pX8PEcaZQ65p72mbVR/aCYUlHZXjHe46u4hPk89VtW5F5FCe1Yp/yTxH6ZdvomQzwcFg3IHs7835eL7FERNAsd7ymTiO2eD/ewTN23mErTslzgdsz1a3ab/UknDDYemG6N3Xpr10c/ELTBp733Uvp/xHnPtN6le0RY4uNJzizXRY6ZD7B/7OYeKd3w=,";
require_once("../../library/authentication/rsa.php");

$rsm=new rsa_key_manager();
//$rsm->debug_keys();
$rsm->initialize();
$rsm->decrypt($message);
?>
<html>
    <body>
        <span><?php echo $message;?></span>
    </body>
</html>