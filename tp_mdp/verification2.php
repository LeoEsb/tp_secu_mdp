<?php 
// Vérification de la validité des informations

// Hachage du mot de passe
//$pass_crypt = password_hash($_POST['password'], PASSWORD_DEFAULT);
//$_SESSION['username'] = $pseudo;
// Insertion
//$bdd = new PDO( 'mysql:host=localhost;dbname=motdepasse','root','');
//$req = $bdd->prepare('INSERT INTO users(username, password ) VALUES(:username, :password)');
//$req->execute(array(
    //'username' => $pseudo,
    //'password' => $pass_crypt,))
?>

<?php
/* Indique le bon format des entêtes (par défaut apache risque de les envoyer au standard ISO-8859-1)*/
header('Content-type: text/html; charset=UTF-8');

/* Initialisation de la variable du message de réponse*/
$message = null;

/* Récupération des variables issues du formulaire par la méthode post*/
$pseudo = filter_input(INPUT_POST, 'username');
$pass = filter_input(INPUT_POST, 'password');

/* Si le formulaire est envoyé */
if (isset($pseudo,$pass)) 
{   

    /* Teste que les valeurs ne sont pas vides ou composées uniquement d'espaces  */ 
    $pseudo = trim($pseudo) != '' ? $pseudo : null;
    $pass = trim($pass) != '' ? $pass : null;
   

    /* Si $pseudo et $pass différents de null */
    if(isset($pseudo,$pass)) 
    {
    /* Connexion au serveur : dans cet exemple, en local sur le serveur d'évaluation
    A MODIFIER avec vos valeurs */
    $hostname = "localhost";
    $database = "motdepasse";
    $username = "root";
    $password = "";
    
    /* Configuration des options de connexion */
    
    /* Désactive l'éumlateur de requêtes préparées (hautement recommandé)  */
    $pdo_options[PDO::ATTR_EMULATE_PREPARES] = false;
    
    /* Active le mode exception */
    $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
    
    /* Indique le charset */
    $pdo_options[PDO::MYSQL_ATTR_INIT_COMMAND] = "SET NAMES utf8";
    
    /* Connexion */
    try
    {
      $connect = new PDO('mysql:host='.$hostname.';dbname='.$database, $username, $password, $pdo_options);
    }
    catch (PDOException $e)
    {
      exit('problème de connexion à la base');
    }
        
    
    /* Requête pour compter le nombre d'enregistrements répondant à la clause : champ du pseudo de la table = pseudo posté dans le formulaire */
    $requete = "SELECT * FROM users WHERE username = ?";
    
    try
    {
      /* préparation de la requête*/
      $req_prep = $connect->prepare($requete);
      
      /* Exécution de la requête en passant la position du marqueur et sa variable associée dans un tableau*/
      $req_prep->execute(array(0=>$pseudo));
      
      /* Récupération du résultat */
      $resultat = $req_prep->fetchColumn();
      
      if ($resultat == 0) 
      /* Résultat du comptage = 0 pour ce pseudo, on peut donc l'enregistrer */
      {
        /* Pour enregistrer la date actuelle (date/heure/minutes/secondes) on peut utiliser directement la fonction mysql : NOW()*/
        $insertion = "INSERT INTO users(username,password) VALUES(:username, :password)";
        
        /* préparation de l'insertion */
        $insert_prep = $connect->prepare($insertion);
        
        /* Exécution de la requête en passant les marqueurs et leur variables associées dans un tableau*/
        $inser_exec = $insert_prep->execute(array(':username'=>$pseudo,':password'=>$pass));
        
        /* Si l'insertion s'est faite correctement...*/
        if ($inser_exec === true) 
        {
          /* Démarre une session si aucune n'est déjà existante et enregistre le pseudo dans la variable de session $_SESSION['login'] qui donne au visiteur la possibilité de se connecter.  */
          if (!session_id()) session_start();
          $_SESSION['login'] = $pseudo;
          header('Location: index.php');
          
          /* A MODIFIER Remplacer le '#' par l'adresse de votre page de destination, sinon ce lien indique la page actuelle.*/
          $message = 'Votre inscription est enregistrée.';
          /*ou redirection vers une page en cas de succès ex : menu.php*/
          /*header("Location: menu.php");
            exit();  */
        }   
      }
      else
      {   /* Le pseudo est déjà utilisé */
        $message = 'Ce pseudo est déjà utilisé, changez-le.';
      }
    }
    catch (PDOException $e)
    {
      $message = 'Problème dans la requête d\'insertion';
    }	
  }
  else 
  {    /* Au moins un des deux champs "pseudo" ou "mot de passe" n'a pas été rempli*/
    $message = 'Les champs Pseudo et Mot de passe doivent être remplis.';
  }
}
?>