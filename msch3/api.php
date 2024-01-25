<?php
// api.php

// Connexion à la base de données avec PDO
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "schtf";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Ajout d'un événement
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $eventName = $_POST["eventName"];
    $formateurId = $_POST["formateurId"];
    $themeId = $_POST["themeId"];
    $startDate = $_POST["startDate"];
    $endDate = $_POST["endDate"];

    // Utilisation de requêtes préparées pour éviter l'injection SQL
    $sql = "INSERT INTO event (nom, id_formateurs, id_themes, start_date, end_date) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);

    // Vérifier si la préparation de la requête a réussi
    if ($stmt) {
        // Liaison des paramètres et exécution de la requête
        $stmt->bindParam(1, $eventName, PDO::PARAM_STR);
        $stmt->bindParam(2, $formateurId, PDO::PARAM_INT);
        $stmt->bindParam(3, $themeId, PDO::PARAM_INT);
        $stmt->bindParam(4, $startDate, PDO::PARAM_STR);
        $stmt->bindParam(5, $endDate, PDO::PARAM_STR);

        $stmt->execute();

        // Vérification de l'exécution de la requête
        if ($stmt->rowCount() > 0) {
            echo "Événement ajouté avec succès.";
        } else {
            echo "Erreur lors de l'ajout de l'événement : " . implode(", ", $stmt->errorInfo());
        }

        // Fermer la requête préparée
        $stmt = null;
    } else {
        echo "Erreur lors de la préparation de la requête : " . implode(", ", $pdo->errorInfo());
    }
}

// Fermer la connexion à la base de données
$pdo = null;
?>
