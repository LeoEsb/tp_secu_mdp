<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <link type="text/css" rel="stylesheet" href="style.css">
</head>

<body>
    <div id="container">
        <!-- zone d'inscription -->
        
        <form action="verification2.php" method="POST">
            <h1>Inscription</h1>
            
            <label for="username"><b>Pseudo :</b></label>
            <input type="text" placeholder="Entrer le nom d'utilisateur" name="username" id="username" required>

            <label for="password" ><b>Mot de passe :</b></label>
            <input type="password" placeholder="Entrer le mot de passe" name="password" id="password" required>

            <input type="submit" id='submit' value='Inscription' >
            <a href="index.php"> <input type="submit" value="Déjà un compte ?"> </a>
            <?php
            if(isset($_GET['erreur'])){
                $err = $_GET['erreur'];
                if($err==1 || $err==2)
                   echo "<p style='color:red'>Utilisateur ou mot de passe incorrect</p>";
            }
            ?>
        </form>
    </div>
</body>
</html>

</body>
</html>