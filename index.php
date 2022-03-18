<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="http://localhost:8000/assets/css/styles.css" >
    <title>Injection SQL</title>
</head>
<body>
<?php

// Connexion à la base de données

$host ="localhost";
    $user_mysql ="root";
    $password_mysql ="";
    $database = "injection_sql";

    $conn = new mysqli($host, $user_mysql, $password_mysql, $database);
    $conn->query("SET NAMES utf8");

    if(!$conn)
    {
        echo "La connexion ne passe pas!";
        exit();
    }

?>

<h1>Injection SQL</h1>

<?php

// Vérification 

if ((isset($_POST['prenom'])) && (isset($_POST['nom'])) && (isset($_POST['message'])) && (isset($_POST['tracker']))) {

    if (($_POST['prenom'] == "") || ($_POST['nom'] == "") || ($_POST['message'] == "") || ($_POST['tracker'] == "")) {
        $error = "<p style='color: red; text-align: center;'>Vous n'avez pas rempli tous les champs !</p>";
    }
    else {

        $prenom_post = htmlspecialchars($_POST['prenom'], ENT_QUOTES);
        $nom_post = htmlspecialchars($_POST['nom'], ENT_QUOTES);
        $message_post = htmlspecialchars($_POST['message'], ENT_QUOTES);
        $tracker_post = password_hash($_POST['tracker'], PASSWORD_BCRYPT);
        $prenom = $conn->real_escape_string($prenom_post);
        $nom = $conn->real_escape_string($nom_post);
        $message = $conn->real_escape_string($message_post);
        $tracker = $conn->real_escape_string($tracker_post);

        $query = $conn->query("SELECT * FROM utilisateur WHERE prenom='$prenom' AND nom='$nom' AND message='$message'");
        $ligne = $query->fetch_object();

        if ($ligne != null) {
            $error = "<p style='color: red; text-align: center;'>Vous avez déjà posté ce message !</p>";
            }

        if (!isset($error)) {
            $conn->query("INSERT INTO utilisateur (prenom, nom, message, tracker) VALUES ('$prenom', '$nom', '$message', '$tracker')");
            $succes = "<p style='color: green; text-align: center;'>Votre message a bien été posté !</p>";
            }
        }
} ?>

<div class="registration-form">
        <form method="POST" action="">
            <div class="form-icon">
            </div>
            <?php if (isset($succes)) { echo $succes; }
                if (isset($error)) { echo $error; } ?>
            <div class="form-group">
                <input class="form-control item" type="text" placeholder="Votre Prénom" name="prenom" id="prenom" value="<?php if (isset($_POST['prenom'])) { echo $_POST['prenom'];} ?>"">
            </div>
            <div class="form-group">
                <input class="form-control item" type="text" placeholder="Votre Nom" name="nom" id="nom" value="<?php if (isset($_POST['nom'])) { echo $_POST['nom'];} ?>"">
            </div>
            <div class="form-group">
                <textarea class="form-control item" placeholder="Votre message" name="message" id="message" rows="5" cols="40"><?php if (isset($_POST['message'])) { echo $_POST['message'];} ?></textarea>
            </div>
            <div class="form-group">
                <input class="form-control item" type="text" placeholder="Tracker" name="tracker" id="tracker" value=""">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-block create-account" style="margin-left: 180px;">Envoyer</button>
            </div>
        </form>
    </div>
</body>
</html>



