<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Guessing Number</title>
        <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico" />
        <!-- Font Awesome icons (free version)-->
        <script src="https://use.fontawesome.com/releases/v5.13.0/js/all.js" crossorigin="anonymous"></script>
        <!-- Google fonts-->
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
        <link href="https://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic" rel="stylesheet" type="text/css" />
        <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="css/styles.css" rel="stylesheet" />
    </head>

    <body id="page-top">
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav">
            <div class="container">
                <a class="navbar-brand js-scroll-trigger" href="#page-top"><img src="assets/img/navbar-logo.svg" alt="" /></a>
                <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                    Menu
                    <i class="fas fa-bars ml-1"></i>
                </button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav text-uppercase ml-auto">
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="#services">About</a></li>
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="#portfolio">Play</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- Masthead-->
        <header class="masthead">
            <div class="container">
                <div class="masthead-subheading">Welcome To myGuessing Game!</div>
                <div class="masthead-heading text-uppercase">Let's Get Started!</div>
                <a class="btn btn-primary btn-xl text-uppercase js-scroll-trigger" href="#services">Tell Me More</a>
            </div>
        </header>
        <!-- Services-->
        <section class="page-section" id="services">
            <div class="container">
                <div class="text-center">
                    <h2 class="section-heading text-uppercase">ABOUT</h2>
                    <h3 class="section-subheading text-muted">SIMPLE GUESSING GAME USING PHP</h3>
                </div>
               <center> <div style = "align-content: center" class="rowtext-center">
                    <div class="col-md-4">
                        
                        <h4 class="my-3">Guessing Game</h4>
                        <p class="text-muted">You will keep guessing numbers <strong>between 1 to 10</strong>. The computer will tell you each time if your guess was too high or too low.   Let's play!</p>
                    </div>
                </div> 
                </center>
            </div>
        </section>
        <!-- Portfolio Grid-->
        <section class="page-section bg-light" id="portfolio">
            <div class="container text-center">
                <div class="text-center">
                    <h2 class="section-heading text-uppercase">GUESSING GAME</h2>
                    <h3 class="section-subheading text-muted">SIMPLE GUESSING GAME USING PHP</h3>
                </div>
                      <?php

                        if ($_SERVER["REQUEST_METHOD"] == "POST")
                            {
                            $randNum = rand(1,10);
                            if(isset($_POST['num']))
                            {
                        if ($randNum > $_POST["num"])
                            {
                            echo "<p align=center>Your guess is <strong>too low</strong>.</p>";
                            echo "<div align=center> The number was $randNum. </div>";
                            }
                            else if ($randNum < $_POST["num"])
                            {
                            echo "<p align=center>Your guess is <strong> too high</strong>.</p>";
                            echo "<div align=center> The number was $randNum. </div>";
                            }
                            else if ($randNum == $_POST["num"])
                            {
                            echo "<p align=center> The number was $randNum. </p>";
                            echo "<div align=center>You got it! Wanna guess again? Just retype the number. </div>";

                            }     
                            }
                            }
                    ?>
                
            
<div class ="text-center">
  <center> <form method="post" action="index.php">
      <h3 class="my-3 text-center ">Enter a number <strong>between 1 and 10 </strong>.</h3>
    <h3 class="my-3">Your guess </h3>
      <input type="number" name="num" min="1" max="10" autofocus>
    <div class="my-3"><input type="submit" name = "submit" value="Submit"></div>
  </form></center>
                </div>
            </div>
        </section>

        <!-- Clients-->
        <div class="py-5">
            <div class="container text-center">
                <h2>Thanks for playing the game! Have a good day! </h2>
            </div>
        </div>
        <!-- Footer-->
        <footer class="footer py-4">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-4 text-lg-left">Copyright © Your Website 2020</div>
                    <div class="col-lg-4 my-3 my-lg-0">
                        <a class="btn btn-dark btn-social mx-2" href="#!"><i class="fab fa-twitter"></i></a>
                        <a class="btn btn-dark btn-social mx-2" href="#!"><i class="fab fa-facebook-f"></i></a>
                        <a class="btn btn-dark btn-social mx-2" href="#!"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                    <div class="col-lg-4 text-lg-right">
                        <a class="mr-3" href="#!">Privacy Policy</a>
                        <a href="#!">Terms of Use</a>
                    </div>
                </div>
            </div>
        </footer>
        
        <!-- Bootstrap core JS-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>
        <!-- Third party plugin JS-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
        <!-- Contact form JS-->
        <script src="assets/mail/jqBootstrapValidation.js"></script>
        <script src="assets/mail/contact_me.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>
    </body>
</html>
