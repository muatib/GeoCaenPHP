<?php

/**
 * Retrieves the data for a specific step of a game from the database.
 *
 * @param int $gameId The ID of the game
 * @param int $stepOrder The order number of the step within the game
 *
 * @return array|null An associative array containing the step data if found, null otherwise
 
 */
function getCurrentStepData($gameId, $stepOrder) {
    try {
        $db = connectDb();
        $query = "SELECT * FROM game_step WHERE Id_game = :gameId AND step_order = :stepOrder";
        $stmt = $db->prepare($query);
        $stmt->execute(['gameId' => $gameId, 'stepOrder' => $stepOrder]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$result) {
            error_log("No data found for gameId=$gameId and stepOrder=$stepOrder");
            return null;
        }
        
        return $result;
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return null;
    }
}

/**
 * Retrieves the correct answer for a given game step.
 *
 * @param int $stepId The ID of the game step
 *
 * @return string|null The correct answer if found, otherwise null
 
 */
function getCorrectAnswer($stepId) {
    try {
        $db = connectDb(); // Establishes the database connection (function not shown here)
        $query = "SELECT answer FROM answer WHERE Id_game_step = :stepId AND GoodFalse = true"; 
        $stmt = $db->prepare($query); // Prepares the SQL query
        $stmt->execute(['stepId' => $stepId]); // Executes the query with the step ID
        return $stmt->fetchColumn(); // Retrieves the answer (first column of the first result)
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage()); // Logs the error
        return null; // Returns null in case of an error
    }
}

/**
 * Handles the logic for a game step, including processing answer submissions and providing feedback.
 *
 * @param array $requestData Data from the user's request, potentially containing an answer submission.
 *
 * @return array An associative array containing:
 *   - 'stepData': The data for the current game step.
 *   - 'message': A message to display to the user, providing feedback or error information.
 
 */
function handleGameStep($requestData) {
    // Initialize variables
    $message = '';

    // Retrieve the current step data
    $stepData = getCurrentStepData($_SESSION['gameId'], $_SESSION['currentStep']);

    // Process answer submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($requestData['answer'])) {
        $userAnswer = $requestData['answer'];
        if ($stepData) {
            $correctAnswer = getCorrectAnswer($stepData['Id_game_step']);
            if ($correctAnswer !== null) {
                if (strtolower($userAnswer) === strtolower($correctAnswer)) {
                    $message = "Correct answer!";
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

