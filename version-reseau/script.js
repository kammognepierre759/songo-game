let partieId = null;
let monJoueur = null;

function nouvellePartie() {
    fetch('game.php?action=nouvelle_partie', { method: 'POST', body: new URLSearchParams({ action: 'nouvelle_partie' }) })
    .then(r => r.json())
    .then(data => {
        partieId = data.id;
        monJoueur = 1;
        document.getElementById('affiche-id').textContent = partieId;
        document.getElementById('connexion').style.display = 'none';
        document.getElementById('jeu').style.display = 'block';
        afficherMessage("Partie créée ! ID: " + partieId + " — Vous êtes Joueur 1 (Sud)");
        setInterval(actualiser, 2000);
    });
}

function rejoindrePartie() {
    partieId = document.getElementById('input-id').value;
    monJoueur = 2;
    document.getElementById('affiche-id').textContent = partieId;
    document.getElementById('connexion').style.display = 'none';
    document.getElementById('jeu').style.display = 'block';
    afficherMessage("Vous avez rejoint la partie ! Vous êtes Joueur 2 (Nord)");
    setInterval(actualiser, 2000);
}

function jouer(caseId) {
    if (!partieId) return;

    const formData = new URLSearchParams();
    formData.append('action', 'jouer');
    formData.append('id', partieId);
    formData.append('case', caseId);

    fetch('game.php', { method: 'POST', body: formData })
    .then(r => r.json())
    .then(data => {
        if (data.erreur) {
            afficherMessage("⚠️ " + data.erreur);
            return;
        }
        mettreAJourPlateau(data);
    });
}

function actualiser() {
    if (!partieId) return;
    fetch('game.php?action=get_partie&id=' + partieId)
    .then(r => r.json())
    .then(data => {
        mettreAJourPlateau(data);
    });
}

function mettreAJourPlateau(data) {
    const plateau = data.plateau;
    for (let id in plateau) {
        const el = document.getElementById(id);
        if (el) el.querySelector('.nb').textContent = plateau[id];
    }
    document.getElementById('score-j1').textContent = data.score_j1;
    document.getElementById('score-j2').textContent = data.score_j2;

    const joueur = parseInt(data.joueur_actuel);
    afficherMessage("Tour du Joueur " + joueur);

    if (data.score_j1 >= 40) afficherMessage("🏆 Joueur 1 a gagné !");
    if (data.score_j2 >= 40) afficherMessage("🏆 Joueur 2 a gagné !");
}

function afficherMessage(msg) {
    document.getElementById('message').textContent = msg;
}
