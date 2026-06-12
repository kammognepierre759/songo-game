// État du jeu
let plateau = {
    N1: 5, N2: 5, N3: 5, N4: 5, N5: 5, N6: 5, N7: 5,
    S1: 5, S2: 5, S3: 5, S4: 5, S5: 5, S6: 5, S7: 5
};

let scores = { J1: 0, J2: 0 };
let joueurActuel = 1;

// Ordre de distribution
const ordreSud = ['S1','S2','S3','S4','S5','S6','S7','N7','N6','N5','N4','N3','N2','N1'];
const ordreNord = ['N7','N6','N5','N4','N3','N2','N1','S1','S2','S3','S4','S5','S6','S7'];

function jouer(caseId) {
    const estSud = caseId.startsWith('S');
    const estNord = caseId.startsWith('N');

    if (joueurActuel === 1 && estNord) {
        afficherMessage("Joueur 1 : jouez dans votre camp (Sud) !");
        return;
    }
    if (joueurActuel === 2 && estSud) {
        afficherMessage("Joueur 2 : jouez dans votre camp (Nord) !");
        return;
    }

    let graines = plateau[caseId];
    if (graines === 0) {
        afficherMessage("Cette case est vide !");
        return;
    }

    plateau[caseId] = 0;
    const ordre = joueurActuel === 1 ? ordreSud : ordreNord;
    const startIndex = ordre.indexOf(caseId);
    let index = (startIndex + 1) % ordre.length;
    let dernierCase = null;

    while (graines > 0) {
        if (ordre[index] === caseId) {
            index = (index + 1) % ordre.length;
            continue;
        }
        plateau[ordre[index]]++;
        dernierCase = ordre[index];
        graines--;
        index = (index + 1) % ordre.length;
    }

    // Capture
    if (dernierCase) {
        capturer(dernierCase);
    }

    afficherPlateau();
    verifierFin();

    joueurActuel = joueurActuel === 1 ? 2 : 1;
    afficherMessage(`Tour du Joueur ${joueurActuel}`);
}

function capturer(dernierCase) {
    const estDansCampAdverse = joueurActuel === 1
        ? dernierCase.startsWith('N')
        : dernierCase.startsWith('S');

    if (!estDansCampAdverse) return;
    if (dernierCase.endsWith('1')) return; // case N1 ou S1 interdite

    let val = plateau[dernierCase];
    if (val === 2 || val === 3 || val === 4) {
        if (joueurActuel === 1) scores.J1 += val;
        else scores.J2 += val;
        plateau[dernierCase] = 0;

        // Capture en chaîne
        const ordre = joueurActuel === 1 ? ordreNord : ordreSud;
        const idx = ordre.indexOf(dernierCase);
        let i = idx - 1;
        while (i >= 1) {
            let v = plateau[ordre[i]];
            if (v === 2 || v === 3 || v === 4) {
                if (joueurActuel === 1) scores.J1 += v;
                else scores.J2 += v;
                plateau[ordre[i]] = 0;
                i--;
            } else break;
        }
    }
    document.getElementById('score-j1').textContent = scores.J1;
    document.getElementById('score-j2').textContent = scores.J2;
}

function afficherPlateau() {
    for (let id in plateau) {
        document.getElementById(id).querySelector('.nb').textContent = plateau[id];
    }
}

function afficherMessage(msg) {
    document.getElementById('message').textContent = msg;
}

function verifierFin() {
    if (scores.J1 >= 40) {
        afficherMessage("🏆 Joueur 1 (Sud) a gagné avec " + scores.J1 + " graines !");
    } else if (scores.J2 >= 40) {
        afficherMessage("🏆 Joueur 2 (Nord) a gagné avec " + scores.J2 + " graines !");
    }
}

function reinitialiser() {
    for (let id in plateau) plateau[id] = 5;
    scores = { J1: 0, J2: 0 };
    joueurActuel = 1;
    afficherPlateau();
    document.getElementById('score-j1').textContent = 0;
    document.getElementById('score-j2').textContent = 0;
    afficherMessage("Nouvelle partie ! Tour du Joueur 1");
}

afficherMessage("Tour du Joueur 1 (Sud)");
