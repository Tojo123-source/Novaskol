<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Modifier - {{ $exercice->titre }}</title>
<link rel="stylesheet" href="{{ asset('legacy/assets/fontawesome/css/all.min.css') }}">
<style>:root{--bg:#f1f5f9;--surface:#fff;--text:#1e293b;--muted:#64748b;--line:#e2e8f0;--blue:#2563eb}*{box-sizing:border-box}body{margin:0;font-family:Inter,system-ui,sans-serif;background:var(--bg);color:var(--text)}.wrap{max-width:800px;margin:0 auto;padding:24px 16px}.back{color:var(--muted);text-decoration:none;font-size:.9rem;display:inline-flex;align-items:center;gap:6px;margin-bottom:16px}.back:hover{color:var(--text)}h1{font-size:1.2rem;margin:0 0 20px}.card{background:var(--surface);border:1px solid var(--line);border-radius:12px;padding:24px;margin-bottom:16px}label{display:block;font-size:.85rem;font-weight:600;margin-bottom:4px}input,select,textarea{width:100%;margin-bottom:14px;border:1px solid var(--line);border-radius:8px;padding:10px 12px;font:inherit;outline:none}.question-card{background:#f8fafc;border:1px solid var(--line);border-radius:10px;padding:16px;margin-bottom:12px}.btn{display:inline-flex;align-items:center;gap:6px;padding:8px 14px;border-radius:8px;font-weight:600;font-size:.85rem;border:0;cursor:pointer;text-decoration:none}.btn-primary{background:var(--blue);color:#fff}.btn-outline{background:transparent;border:1px solid var(--line);color:var(--text)}.btn-success{background:#16a34a;color:#fff}.btn-danger{color:#dc2626}.btn-sm{padding:5px 10px;font-size:.78rem}</style>
</head>
<body>
<div class="wrap">
    <a href="{{ route('teacher.exercices.index', ['course_id' => $course->id]) }}" class="back"><i class="fa fa-arrow-left"></i> Exercices</a>
    <h1><i class="fa fa-edit" style="color:var(--blue)"></i> {{ $exercice->titre }}</h1>

    <form method="POST" action="{{ route('teacher.exercices.update', $exercice->id) }}">
        @csrf
        <div class="card">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                <div>
                    <label>Titre</label>
                    <input name="titre" value="{{ $exercice->titre }}" required>
                </div>
                <div>
                    <label>Type</label>
                    <select name="type">
                        <option value="qcm" {{ $exercice->type === 'qcm' ? 'selected' : '' }}>QCM</option>
                        <option value="vrai_faux" {{ $exercice->type === 'vrai_faux' ? 'selected' : '' }}>Vrai/Faux</option>
                        <option value="texte" {{ $exercice->type === 'texte' ? 'selected' : '' }}>Texte</option>
                        <option value="appariement" {{ $exercice->type === 'appariement' ? 'selected' : '' }}>Appariement</option>
                    </select>
                </div>
            </div>
            <label>Description</label>
            <textarea name="description" rows="2">{{ $exercice->description }}</textarea>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                <div>
                    <label>Temps limite (s)</label>
                    <input name="temps_limite" type="number" value="{{ $exercice->temps_limite }}">
                </div>
                <div>
                    <label style="display:flex;align-items:center;gap:8px;margin-top:24px">
                        <input type="checkbox" name="publie" value="1" {{ $exercice->publie ? 'checked' : '' }} style="width:auto;margin:0"> Publie
                    </label>
                </div>
            </div>
        </div>

        <div class="card">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
                <strong>Questions ({{ $questions->count() }})</strong>
                <button type="button" class="btn btn-success btn-sm" onclick="addQuestion()"><i class="fa fa-plus"></i></button>
            </div>
            <div id="questions">
                @foreach ($questions as $i => $q)
                    <div class="question-card" data-index="{{ $i }}">
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">
                            <strong>Question {{ $i + 1 }}</strong>
                            <button type="button" class="btn btn-outline btn-sm btn-danger" onclick="this.closest('.question-card').remove()"><i class="fa fa-times"></i></button>
                        </div>
                        <label>Question</label>
                        <textarea name="questions[{{ $i }}][question]" rows="2" required>{{ $q->question }}</textarea>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                            <div>
                                <label>Points</label>
                                <input name="questions[{{ $i }}][points]" type="number" value="{{ $q->points }}" min="1">
                            </div>
                            <div>
                                <label>Reponse correcte</label>
                                <input name="questions[{{ $i }}][reponse_correcte]" value="{{ $q->reponse_correcte }}" required>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div style="display:flex;gap:8px;margin-top:16px">
            <a href="{{ route('teacher.exercices.index', ['course_id' => $course->id]) }}" class="btn btn-outline">Annuler</a>
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Enregistrer</button>
        </div>
    </form>
</div>

<script>
let qi = {{ $questions->count() }};
function addQuestion() {
    const div = document.createElement('div');
    div.className = 'question-card';
    div.innerHTML = `
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">
            <strong>Question ${qi + 1}</strong>
            <button type="button" class="btn btn-outline btn-sm btn-danger" onclick="this.closest('.question-card').remove()"><i class="fa fa-times"></i></button>
        </div>
        <label>Question</label>
        <textarea name="questions[${qi}][question]" rows="2" required></textarea>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
            <div>
                <label>Points</label>
                <input name="questions[${qi}][points]" type="number" value="1" min="1">
            </div>
            <div>
                <label>Reponse correcte</label>
                <input name="questions[${qi}][reponse_correcte]" required>
            </div>
        </div>`;
    document.getElementById('questions').appendChild(div);
    qi++;
}
</script>
</body>
</html>
