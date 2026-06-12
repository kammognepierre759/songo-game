<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$conn = new mysqli('localhost', 'root', '', 'songo');

if ($conn->connect_error) {
    die(json_encode(['erreur' => 'Connexion échouée']));
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'nouvelle_partie') {
    $plateau = json_encode([
        'N1'=>5,'N2'=>5,'N3'=>5,'N4'=>5,'N5'=>5,'N6'=>5,'N7'=>5,
        'S1'=>5,'S2'=>5,'S3'=>5,'S4'=>5,'S5'=>5,'S6'=>5,'S7'=>5
    ]);
    $sql = "INSERT INTO parties (plateau) VALUES ('$plateau')";
    $conn->query($sql);
    echo json_encode(['id' => $conn->insert_id, 'message' => 'Partie créée']);
}

elseif ($action === 'get_partie') {
    $id = intval($_GET['id']);
    $result = $conn->query("SELECT * FROM parties WHERE id=$id");
    $row = $result->fetch_assoc();
    $row['plateau'] = json_decode($row['plateau'], true);
    echo json_encode($row);
}

elseif ($action === 'jouer') {
    $id = intval($_POST['id']);
    $case = $_POST['case'];
    
    $result = $conn->query("SELECT * FROM parties WHERE id=$id");
    $row = $result->fetch_assoc();
    $plateau = json_decode($row['plateau'], true);
    $joueur = intval($row['joueur_actuel']);
    $score_j1 = intval($row['score_j1']);
    $score_j2 = intval($row['score_j2']);

    // Vérifications
    if ($joueur === 1 && str_starts_with($case, 'N')) {
        echo json_encode(['erreur' => 'Joueur 1 joue au Sud !']); exit;
    }
    if ($joueur === 2 && str_starts_with($case, 'S')) {
        echo json_encode(['erreur' => 'Joueur 2 joue au Nord !']); exit;
    }
    if ($plateau[$case] === 0) {
        echo json_encode(['erreur' => 'Case vide !']); exit;
    }

    // Distribution
    $ordreSud = ['S1','S2','S3','S4','S5','S6','S7','N7','N6','N5','N4','N3','N2','N1'];
    $ordreNord = ['N7','N6','N5','N4','N3','N2','N1','S1','S2','S3','S4','S5','S6','S7'];
    $ordre = $joueur === 1 ? $ordreSud : $ordreNord;

    $graines = $plateau[$case];
    $plateau[$case] = 0;
    $startIndex = array_search($case, $ordre);
    $index = ($startIndex + 1) % count($ordre);
    $dernierCase = null;

    while ($graines > 0) {
        if ($ordre[$index] === $case) {
            $index = ($index + 1) % count($ordre);
            continue;
        }
        $plateau[$ordre[$index]]++;
        $dernierCase = $ordre[$index];
        $graines--;
        $index = ($index + 1) % count($ordre);
    }

    // Capture
    if ($dernierCase) {
        $estAdverse = $joueur === 1
            ? str_starts_with($dernierCase, 'N')
            : str_starts_with($dernierCase, 'S');
        
        if ($estAdverse && !str_ends_with($dernierCase, '1')) {
            $val = $plateau[$dernierCase];
            if ($val >= 2 && $val <= 4) {
                if ($joueur === 1) $score_j1 += $val;
                else $score_j2 += $val;
                $plateau[$dernierCase] = 0;
            }
        }
    }

    $nouveauJoueur = $joueur === 1 ? 2 : 1;
    $plateauJson = $conn->real_escape_string(json_encode($plateau));
    $conn->query("UPDATE parties SET plateau='$plateauJson', joueur_actuel=$nouveauJoueur, score_j1=$score_j1, score_j2=$score_j2 WHERE id=$id");

    echo json_encode([
        'plateau' => $plateau,
        'joueur_actuel' => $nouveauJoueur,
        'score_j1' => $score_j1,
        'score_j2' => $score_j2
    ]);
}

$conn->close();
?>
