<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Nouvel exercice - {{ $ch->titre }}</title>
<link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
<style>:root{--bg:#f1f5f9;--surface:#fff;--text:#1e293b;--muted:#64748b;--line:#e2e8f0;--blue:#2563eb}*{box-sizing:border-box}body{margin:0;font-family:Inter,system-ui,sans-serif;background:var(--bg);color:var(--text)}.wrap{max-width:800px;margin:0 auto;padding:24px 16px}.back{color:var(--muted);text-decoration:none;font-size:.9rem;display:inline-flex;align-items:center;gap:6px;margin-bottom:16px}.back:hover{color:var(--text)}h1{font-size:1.3rem;margin:0 0 20px}.card{background:var(--surface);border:1px solid var(--line);border-radius:12px;padding:24px;margin-bottom:16px}label{display:block;font-size:.85rem;font-weight:600;margin-bottom:4px;color:var(--text)}input,select,textarea{width:100%;margin-bottom:14px;border:1px solid var(--line);border-radius:8px;padding:10px 12px;font:inherit;outline:none}.question-card{background:#f8fafc;border:1px solid var(--line);border-radius:10px;padding:16px;margin-bottom:12px}.question-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:8px}.btn{display:inline-flex;align-items:center;gap:6px;padding:8px 14px;border-radius:8px;font-weight:600;font-size:.85rem;border:0;cursor:pointer;text-decoration:none}.btn-primary{background:var(--blue);color:#fff}.btn-outline{background:transparent;border:1px solid var(--line);color:var(--text)}.btn-success{background:#16a34a;color:#fff}.btn-danger{color:#dc2626}.btn-sm{padding:5px 10px;font-size:.78rem}.actions{display:flex;gap:8px;margin-top:16px}</style>
</head>
<body>
<div class="wrap">
    <a href="{{ route('teacher.courses.show', $course->id) }}" class="back"><i class="fa fa-arrow-left"></i> {{ $course->titre }} / {{ $ch->titre }}</a>
    <h1><i class="fa fa-plus-circle" style="color:var(--blue)"></i> Nouvel exercice</h1>

    <form method="POST" action="{{ route('teacher.exercices.store') }}" id="exerciseForm">
        @csrf
        <input type="hidden" name="chapitre_id" value="{{ $ch->id }}">

        <div class="card">
            <label>Titre de l'exercice *</label>
            <input name="titre" required maxlength="200" placeholder="ex: Evaluation fractions">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                <div>
                    <label>Type</label>
                    <select name="type" id="exType">
                        <option value="qcm">QCM</option>
                        <option value="vrai_faux">Vrai / Faux</option>
                        <option value="texte">Reponse texte</option>
                        <option value="appariement">Appariement</option>
                    </select>
                </div>
                <div>
                    <label>Temps limite (secondes, optionnel)</label>
                    <input name="temps_limite" type="number" min="0" max="3600" placeholder="ex: 600">
                </div>
            </div>
            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;margin-top:8px">
                <input type="checkbox" name="publie" value="1" style="width:auto;margin:0"> Publier immediatement
            </label>
        </div>

        <div class="card">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
                <h2 style="margin:0;font-size:1rem">Questions</h2>
                <button type="button" class="btn btn-success btn-sm" onclick="addQuestion()"><i class="fa fa-plus"></i> Ajouter une question</button>
            </div>
            <div id="questions">
                <div class="question-card" data-index="0">
                    <div class="question-header">
                        <strong>Question 1</strong>
                        <button type="button" class="btn btn-outline btn-sm btn-danger" onclick="this.closest('.question-card').remove()"><i class="fa fa-times"></i></button>
                    </div>
                    <label>Question</label>
                    <textarea name="questions[0][question]" rows="2" required placeholder="Saisissez la question..."></textarea>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                        <div>
                            <label>Points</label>
                            <input name="questions[0][points]" type="number" value="1" min="1" max="100">
                        </div>
                        <div>
                            <label>Reponse correcte</label>
                            <input name="questions[0][reponse_correcte]" required placeholder="La bonne reponse">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="actions">
            <a href="{{ route('teacher.courses.show', $course->id) }}" class="btn btn-outline">Annuler</a>
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Creer l'exercice</button>
        </div>
    </form>
</div>

<script>
let questionIndex = 1;
function addQuestion() {
    const div = document.createElement('div');
    div.className = 'question-card';
    div.dataset.index = questionIndex;
    div.innerHTML = `
        <div class="question-header">
            <strong>Question ${questionIndex + 1}</strong>
            <button type="button" class="btn btn-outline btn-sm btn-danger" onclick="this.closest('.question-card').remove()"><i class="fa fa-times"></i></button>
        </div>
        <label>Question</label>
        <textarea name="questions[${questionIndex}][question]" rows="2" required></textarea>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
            <div>
                <label>Points</label>
                <input name="questions[${questionIndex}][points]" type="number" value="1" min="1" max="100">
            </div>
            <div>
                <label>Reponse correcte</label>
                <input name="questions[${questionIndex}][reponse_correcte]" required>
            </div>
        </div>`;
    document.getElementById('questions').appendChild(div);
    questionIndex++;
}
</script>
</body>
</html>
