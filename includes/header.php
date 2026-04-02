<?php
$title = isset($title) ? $title : 'Algoma University';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="img/algomau.png" type="image/png">
    <title><?php echo $title; ?></title>
</head>

<body>

    <nav class="navbar navbar-expand-lg bg-secondary mb-5 custom-padding">

        <div class="container-fluid">

            <a class="navbar-brand text-white" href="algomau.php">Algoma University</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">

                <span class="navbar-toggler-icon"></span>

            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">

                <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                    <li class="nav-item">

                        <a class="nav-link  text-info" href="index.php">Home</a>

                    </li>

                    <li class="nav-item">

                        <a class="nav-link  text-info" href="labsolutions.php">Lab Solutions</a>

                    </li>

                </ul>

                <a href="signupform.php" class="btn btn-info btn-sm w-20 me-2">Sign Up</a>

                <a href="loginform.php" class="btn btn-info btn-sm w-20 me-2">Login</a>

                <a href="logout.php" class="btn btn-info btn-sm w-20">Logout</a>

            </div>

        </div>

    </nav>