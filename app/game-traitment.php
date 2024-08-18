<?php

function getCurrentStepData($gameId, $stepOrder) {
    try {
        $db = connectDb();
        $query = "SELECT * FROM game_step WHERE Id_game = :gameId AND step_order = :stepOrder";
        $stmt = $db->prepare($query);
        $stmt->execute(['gameId' => $gameId, 'stepOrder' => $stepOrder]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$result) {
            error_log("Aucune donnée trouvée pour gameId=$gameId et stepOrder=$stepOrder");
            return null;
        }
        
        return $result;
    } catch (PDOException $e) {
        error_log("Erreur de base de données : " . $e->getMessage());
        return null;
    }
}

function getCorrectAnswer($stepId) {
    try {
        $db = connectDb();
        $query = "SELECT answer FROM answer WHERE Id_game_step = :stepId AND GoodFalse = true";
        $stmt = $db->prepare($query);
        $stmt->execute(['stepId' => $stepId]);
        return $stmt->fetchColumn();
    } catch (PDOException $e) {
        error_log("Erreur de base de données : " . $e->getMessage());
        return null;
    }
}

function handleGameStep($requestData) {
    // Initialiser les variables
    $message = '';

    // Récupérer les données de l'étape actuelle
    $stepData = getCurrentStepData($_SESSION['gameId'], $_SESSION['currentStep']);

    // Traiter la soumission de réponse
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($requestData['answer'])) {
        $userAnswer = $requestData['answer'];
        if ($stepData) {
            $correctAnswer = getCorrectAnswer($stepData['Id_game_step']);
            if ($correctAnswer !== null) {
                if (strtolower($userAnswer) === strtolower($correctAnswer)) {
                    $message = "Bonne réponse !";
                    $_SESSION['currentStep']++;
                    header("Location: middlestep.php");
                    exit();
                } else {
                    $message = "Presque ! N'abandonnez pas !";
                }
            } else {
                $message = "Erreur : impossible de vérifier la réponse.";
            }
        } else {
            $message = "Erreur : données de l'étape non disponibles.";
        }
    }

    return [
        'stepData' => $stepData,
        'message' => $message
    ];
}

