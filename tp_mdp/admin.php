<html>
    <head>
        <meta charset="utf-8">
     <style>
#formulaire{
    text-align: center;
    padding-top:20px;
}

     </style>
    </head>
    <body>
        <div id="content">
                                
            <!-- tester si l'utilisateur est connecté -->
            <?php
               session_start();
                if(isset($_GET['deconnexion']))
                { 
                   if($_GET['deconnexion']==true)
                   {  
                      session_unset();
                      header("location:index.php");
                   }
                }
                else if($_SESSION['username'] !== ""){
                    $user = $_SESSION['username'];
                    // afficher un message
                    echo "<h1><center>Bonjour $user, vous êtes connectés</center></h1>";
                }
            ?>
 <a href='admin.php?deconnexion=true'><span><center><h2>Déconnexion</h2></center></span></a>
 <br>
<br>
<br>           
   
    </body>
</html>			