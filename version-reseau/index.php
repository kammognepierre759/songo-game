<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Jeu de Songo - Réseau</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>🎮 Jeu de Songo - Version Réseau</h1>

    <div id="connexion">
        <button onclick="nouvellePartie()">🆕 Nouvelle Partie</button>
        <br><br>
        <input type="number" id="input-id" placeholder="Entrer ID de partie">
        <button onclick="rejoindrePartie()">🔗 Rejoindre</button>
    </div>

    <div id="jeu" style="display:none">
        <p>ID de partie : <strong id="affiche-id"></strong></p>

        <div id="info-joueurs">
            <div class="joueur" id="info-j1">
                <span>Joueur 1 (Sud)</span>
                <span>Graines : <strong id="score-j1">0</strong></span>
            </div>
            <div class="joueur" id="info-j2">
                <span>Joueur 2 (Nord)</span>
                <span>Graines : <strong id="score-j2">0</strong></span>
            </div>
        </div>

        <div id="plateau">
            <div id="rangee-nord" class="rangee">
                <div class="case" id="N7" onclick="jouer('N7')"><span class="nb">5</span></div>
                <div class="case" id="N6" onclick="jouer('N6')"><span class="nb">5</span></div>
                <div class="case" id="N5" onclick="jouer('N5')"><span class="nb">5</span></div>
                <div class="case" id="N4" onclick="jouer('N4')"><span class="nb">5</span></div>
                <div class="case" id="N3" onclick="jouer('N3')"><span class="nb">5</span></div>
                <div class="case" id="N2" onclick="jouer('N2')"><span class="nb">5</span></div>
                <div class="case" id="N1" onclick="jouer('N1')"><span class="nb">5</span></div>
            </div>
            <div id="rangee-sud" class="rangee">
                <div class="case" id="S1" onclick="jouer('S1')"><span class="nb">5</span></div>
                <div class="case" id="S2" onclick="jouer('S2')"><span class="nb">5</span></div>
                <div class="case" id="S3" onclick="jouer('S3')"><span class="nb">5</span></div>
                <div class="case" id="S4" onclick="jouer('S4')"><span class="nb">5</span></div>
                <div class="case" id="S5" onclick="jouer('S5')"><span class="nb">5</span></div>
                <div class="case" id="S6" onclick="jouer('S6')"><span class="nb">5</span></div>
                <div class="case" id="S7" onclick="jouer('S7')"><span class="nb">5</span></div>
            </div>
        </div>

        <div id="message"></div>
    </div>

    <script src="script.js"></script>
</body>
</html>
