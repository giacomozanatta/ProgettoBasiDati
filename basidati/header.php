
<div class="header">
        <center>
                <h1>FORUM DI RECENSIONE DI LIBRI</h1>
                <a href="index.php">HOME</a>  
                <?php if (!isset($_SESSION['username'])) {?>
                        <a href="login.php">LOGIN</a>  
                        <a href="signup.php">SIGNUP</a><br>
                <?php } else {?>
                        Benvenuto, <a href="utente.php?id= <?php echo $_SESSION["id_user"].'">'.$_SESSION['username']; ?></a>! 
                        <a href="logout.php">LOGOUT</a><br>
                <?php } ?>
                <hr>
        </center>
</div>