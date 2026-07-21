<?php

namespace App\Http\Controllers;

use App\Services\Novaskol\ModuleRegistry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountingController extends Controller
{
    private array $months = ['Janvier', 'Fevrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Aout', 'Septembre', 'Octobre', 'Novembre', 'Decembre'];

    public function details(Request $request, ModuleRegistry $modules)
    {
        $this->ensureSession();
        $annee = (string) $request->query('annee_scolaire', $this->currentYear());
        $classeId = (int) $request->query('classe_id', 0);
        $type = (string) $request->query('type_selection', 'etudiant');
        $reminderStats = $this->paymentReminderStats($annee);
        $createdReminders = $this->createDuePaymentReminders($annee, $reminderStats);

        $studentTypes = collect();
        if (in_array($type, ['etudiant', 'enseignant', 'staff'], true)) {
            $studentTypes = DB::table('types_paiements')
                ->where('annee_scolaire', $annee)
                ->when($classeId > 0 && $type === 'etudiant', fn ($q) => $q->where('id_classe', $classeId))
                ->orderByDesc('id')
                ->get()
                ->map(function ($item) use ($annee, $type) {
                    if ($type === 'etudiant') {
                        $assigned = DB::table('paiements_assignes as pa')
                            ->join('eleves as e', 'e.id', '=', 'pa.eleve_id')
                            ->leftJoin('classes as c', 'c.id', '=', 'e.id_classe')
                            ->where('pa.type_id', $item->id)
                            ->select('e.id', 'e.nom', 'e.prenom', 'c.nom as classe')
                            ->orderBy('e.nom')
                            ->get();
                    } elseif ($type === 'enseignant') {
                        $assigned = DB::table('paiements_assignes as pa')
                            ->join('professeurs as p', 'p.id', '=', 'pa.professeur_id')
                            ->where('pa.type_id', $item->id)
                            ->select('p.id', 'p.nom', 'p.prenom', DB::raw("'' as classe"))
                            ->orderBy('p.nom')
                            ->get();
                    } else {
                        $assigned = DB::table('paiements_assignes as pa')
                            ->join('staff as s', 's.id', '=', 'pa.person_id')
                            ->where('pa.type_id', $item->id)
                            ->where('pa.type_personne', 'staff')
                            ->select('s.id', 's.nom', 's.prenom', DB::raw("'' as classe"))
                            ->orderBy('s.nom')
                            ->get();
                    }
                    $item->total_assignes = $assigned->count();
                    $item->total_complet = 0;
                    $item->total_partiel = 0;
                    $item->total_non_paye = 0;
                    $item->unpaid = $assigned->map(function ($person) use ($item, $annee, $type) {
                        $paidAmount = (float) DB::table('revenus')
                            ->where('type_id', $item->id)
                            ->where('personne_id', $person->id)
                            ->where('type_personne', $type === 'etudiant' ? 'eleve' : ($type === 'enseignant' ? 'professeur' : 'staff'))
                            ->where('annee_scolaire', $annee)
                            ->sum('montant');
                        $remaining = max(0, (float) $item->montant - $paidAmount);
                        $status = $paidAmount >= (float) $item->montant && (float) $item->montant > 0
                            ? 'complet'
                            : ($paidAmount > 0 ? 'partiel' : 'non_paye');
                        $person->montant_paye = $paidAmount;
                        $person->montant_restant = $remaining;
                        $person->payment_status = $status;
                        return $person;
                    })->filter(fn ($person) => $person->payment_status !== 'complet')->values();
                    $item->total_partiel = $item->unpaid->where('payment_status', 'partiel')->count();
                    $item->total_non_paye = $item->unpaid->where('payment_status', 'non_paye')->count();
                    $item->total_complet = $item->total_assignes - $item->total_partiel - $item->total_non_paye;
                    $item->total_paye = $item->total_complet;
                    return $item;
                });
        }

        $salaryMonths = collect();
        if (in_array($type, ['enseignant', 'staff'], true)) {
            $personType = $type === 'enseignant' ? 'professeur' : 'staff';
            $salaryMonths = DB::table('salaires_assignes')
                ->select('mois')
                ->where('type_personne', $personType)
                ->where('annee_scolaire', $annee)
                ->distinct()
                ->get()
                ->map(function ($item) use ($personType, $annee) {
                    $table = $personType === 'professeur' ? 'professeurs' : 'staff';
                    $salaryColumn = $personType === 'professeur' ? 'salaire_horaire' : 'salaire_base';
                    $assigned = DB::table('salaires_assignes as sa')
                        ->join($table.' as p', 'p.id', '=', 'sa.personne_id')
                        ->where('sa.type_personne', $personType)
                        ->where('sa.annee_scolaire', $annee)
                        ->where('sa.mois', $item->mois)
                        ->select('p.id', 'p.nom', 'p.prenom', 'p.'.$salaryColumn.' as montant_attendu')
                        ->orderBy('p.nom')
                        ->get();
                    $item->total_assignes = $assigned->count();
                    $item->unpaid = $assigned->map(function ($person) use ($personType, $annee, $item) {
                        $paidAmount = (float) DB::table('depenses')
                            ->where('personne_id', $person->id)
                            ->where('type_personne', $personType)
                            ->where('annee_scolaire', $annee)
                            ->where('mois', $item->mois)
                            ->sum('montant');
                        $expected = (float) ($person->montant_attendu ?? 0);
                        $remaining = max(0, $expected - $paidAmount);
                        $status = $expected > 0 && $paidAmount >= $expected
                            ? 'complet'
                            : ($paidAmount > 0 ? 'partiel' : 'non_paye');
                        $person->montant_paye = $paidAmount;
                        $person->montant_restant = $remaining;
                        $person->payment_status = $status;
                        return $person;
                    })->filter(fn ($person) => $person->payment_status !== 'complet')->values();
                    $item->total_partiel = $item->unpaid->where('payment_status', 'partiel')->count();
                    $item->total_non_paye = $item->unpaid->where('payment_status', 'non_paye')->count();
                    $item->total_complet = $item->total_assignes - $item->total_partiel - $item->total_non_paye;
                    $item->total_paye = $item->total_complet;
                    return $item;
                });
        }

        return $this->view('modules.accounting.details', $modules, 'detail_paiement', [
            'annees' => $this->years(),
            'selectedAnnee' => $annee,
            'selectedClasse' => $classeId,
            'selectedType' => $type,
            'classes' => DB::table('classes')->select('id', 'nom')->orderBy('nom')->get(),
            'months' => $this->months,
            'studentTypes' => $studentTypes,
            'salaryMonths' => $salaryMonths,
            'reminderStats' => $reminderStats,
            'createdReminders' => $createdReminders,
        ]);
    }

    public function storeType(Request $request)
    {
        $this->ensureSession();
        $data = $request->validate([
            'nom_type' => ['required', 'string', 'max:100'],
            'montant' => ['required', 'numeric', 'min:0'],
            'mois' => ['required', 'array'],
            'classe' => ['required', 'integer'],
            'date_debut' => ['required', 'date'],
            'date_fin' => ['required', 'date'],
            'annee_scolaire' => ['required', 'string', 'max:20'],
            'type_selection' => ['required', 'in:etudiant,enseignant,staff'],
        ]);

        DB::transaction(function () use ($data) {
            $classeNom = (string) DB::table('classes')->where('id', $data['classe'])->value('nom');
            $typeId = DB::table('types_paiements')->insertGetId([
                'nom' => $data['nom_type'],
                'montant' => $data['montant'],
                'mois' => json_encode($data['mois'], JSON_UNESCAPED_UNICODE),
                'date_creation' => now(),
                'annee_scolaire' => $data['annee_scolaire'],
                'classe' => $classeNom,
                'date_debut' => $data['date_debut'],
                'date_fin' => $data['date_fin'],
                'id_classe' => $data['classe'],
            ]);
            $montant = (float) $data['montant'];

            if ($data['type_selection'] === 'etudiant') {
                $personIds = DB::table('eleves')->where('id_classe', $data['classe'])->where('annee_scolaire', $data['annee_scolaire'])->pluck('id');
                foreach ($personIds as $personId) {
                    $paiementId = DB::table('paiements')->insertGetId([
                        'type_id' => $typeId,
                        'annee_scolaire' => $data['annee_scolaire'],
                        'montant' => $montant,
                        'categorie' => $data['nom_type'],
                        'statut' => 'non_paye',
                        'mois' => json_encode($data['mois'], JSON_UNESCAPED_UNICODE),
                    ]);
                    DB::table('paiements_assignes')->updateOrInsert(
                        ['type_id' => $typeId, 'eleve_id' => $personId],
                        ['paiement_id' => $paiementId, 'montant' => $montant, 'statut' => 'non_paye']
                    );
                }
            } elseif ($data['type_selection'] === 'enseignant') {
                $personIds = DB::table('professeurs')->where('annee_scolaire', $data['annee_scolaire'])->pluck('id');
                foreach ($personIds as $personId) {
                    $paiementId = DB::table('paiements')->insertGetId([
                        'type_id' => $typeId,
                        'annee_scolaire' => $data['annee_scolaire'],
                        'montant' => $montant,
                        'categorie' => $data['nom_type'],
                        'statut' => 'non_paye',
                        'mois' => json_encode($data['mois'], JSON_UNESCAPED_UNICODE),
                    ]);
                    DB::table('paiements_assignes')->updateOrInsert(
                        ['type_id' => $typeId, 'professeur_id' => $personId],
                        ['paiement_id' => $paiementId, 'montant' => $montant, 'statut' => 'non_paye']
                    );
                }
            } else {
                $personIds = DB::table('staff')->where('annee_scolaire', $data['annee_scolaire'])->pluck('id');
                foreach ($personIds as $personId) {
                    $paiementId = DB::table('paiements')->insertGetId([
                        'type_id' => $typeId,
                        'annee_scolaire' => $data['annee_scolaire'],
                        'montant' => $montant,
                        'categorie' => $data['nom_type'],
                        'statut' => 'non_paye',
                        'mois' => json_encode($data['mois'], JSON_UNESCAPED_UNICODE),
                    ]);
                    DB::table('paiements_assignes')->updateOrInsert(
                        ['type_id' => $typeId, 'person_id' => $personId, 'type_personne' => 'staff'],
                        ['paiement_id' => $paiementId, 'montant' => $montant, 'statut' => 'non_paye']
                    );
                }
            }
        });

        return redirect()->route('modules.detail-paiement', ['type_selection' => $data['type_selection'], 'annee_scolaire' => $data['annee_scolaire'], 'classe_id' => $data['classe']])->with('accounting_msg', ['type' => 'success', 'text' => 'Type de paiement ajoute et assigne.']);
    }

    public function assignSalaryMonths(Request $request)
    {
        $this->ensureSession();
        $data = $request->validate(['type_selection' => ['required', 'in:enseignant,staff'], 'mois' => ['required', 'array'], 'annee_scolaire' => ['required', 'string', 'max:20']]);
        $personType = $data['type_selection'] === 'enseignant' ? 'professeur' : 'staff';
        $table = $personType === 'professeur' ? 'professeurs' : 'staff';
        $added = 0;
        foreach (DB::table($table)->where('annee_scolaire', $data['annee_scolaire'])->select('id', 'salaire_base', 'salaire_horaire')->get() as $person) {
            $salaire = (float) ($person->salaire_base ?? $person->salaire_horaire ?? 0);
            foreach ($data['mois'] as $month) {
                $exists = DB::table('salaires_assignes')->where('personne_id', $person->id)->where('type_personne', $personType)->where('mois', $month)->where('annee_scolaire', $data['annee_scolaire'])->exists();
                if (! $exists) {
                    DB::table('salaires_assignes')->insert(['staff_id' => $person->id, 'montant' => $salaire, 'personne_id' => $person->id, 'type_personne' => $personType, 'mois' => $month, 'annee_scolaire' => $data['annee_scolaire'], 'statut' => 'non_paye', 'date_creation' => now()]);
                    $added++;
                }
            }
        }

        return redirect()->route('modules.detail-paiement', ['type_selection' => $data['type_selection'], 'annee_scolaire' => $data['annee_scolaire']])->with('accounting_msg', ['type' => 'success', 'text' => "$added mois assignes."]);
    }

    public function deleteType(int $id)
    {
        $this->ensureSession();
        DB::transaction(function () use ($id) {
            DB::table('paiements_assignes')->where('type_id', $id)->delete();
            DB::table('types_paiements')->where('id', $id)->delete();
        });
        return back()->with('accounting_msg', ['type' => 'success', 'text' => 'Type de paiement supprime.']);
    }

    public function payment(Request $request, ModuleRegistry $modules)
    {
        $this->ensureSession();
        $annee = (string) $request->query('annee_scolaire', $this->currentYear());
        return $this->view('modules.accounting.payment', $modules, 'comptable', [
            'annees' => $this->years(),
            'selectedAnnee' => $annee,
            'months' => $this->months,
            'students' => DB::table('eleves as e')->leftJoin('classes as c', 'c.id', '=', 'e.id_classe')->select('e.id', 'e.nom', 'e.prenom', 'e.annee_scolaire', 'c.nom as classe')->where('e.annee_scolaire', $annee)->orderBy('e.nom')->limit(500)->get(),
            'paymentTypes' => DB::table('types_paiements')->where('annee_scolaire', $annee)->orderByDesc('id')->get(),
            'teachers' => DB::table('professeurs')->select('id', 'nom', 'prenom', 'annee_scolaire')->where('annee_scolaire', $annee)->orderBy('nom')->get(),
            'staff' => DB::table('staff')->select('id', 'nom', 'prenom', 'annee_scolaire')->where('annee_scolaire', $annee)->orderBy('nom')->get(),
            'prefill' => [
                'kind' => (string) $request->query('kind', ''),
                'eleve_id' => (int) $request->query('eleve_id', 0),
                'type_id' => (int) $request->query('type_id', 0),
                'personne_id' => (int) $request->query('personne_id', 0),
                'type_personne' => (string) $request->query('type_personne', ''),
                'mois' => (string) $request->query('mois', ''),
                'montant' => (float) $request->query('montant', 0),
            ],
        ]);
    }

    public function storePayment(Request $request)
    {
        $this->ensureSession();
        $kind = (string) $request->input('kind');
        if ($kind === 'ecolage') {
            $baseRules = ['type_id' => ['required', 'integer'], 'mois' => ['required', 'string'], 'annee_scolaire' => ['required', 'string'], 'montant' => ['required', 'numeric'], 'description' => ['nullable', 'string'], 'mode_paiement' => ['required', 'string'], 'statut' => ['required', 'in:complet,partiel'], 'categorie' => ['required', 'string']];
            if ($request->has('personne_id') && $request->has('type_personne')) {
                $data = $request->validate(array_merge($baseRules, ['personne_id' => ['required', 'integer'], 'type_personne' => ['required', 'in:professeur,staff']]));
                $personTable = $data['type_personne'] === 'professeur' ? 'professeurs' : 'staff';
                $person = DB::table($personTable)->where('id', $data['personne_id'])->first();
                abort_unless($person, 404);
                DB::table('revenus')->insert(['type_id' => $data['type_id'], 'personne_id' => $person->id, 'type_personne' => $data['type_personne'], 'mois' => $data['mois'], 'annee_scolaire' => $data['annee_scolaire'], 'montant' => $data['montant'], 'description' => $data['description'] ?? '', 'mode_paiement' => $data['mode_paiement'], 'statut' => $data['statut'], 'categorie' => $data['categorie'], 'nom_personne' => trim($person->nom.' '.$person->prenom), 'date_enregistrement' => now(), 'source' => 'ecolage']);
                if ($data['statut'] === 'complet') {
                    if ($data['type_personne'] === 'professeur') {
                        DB::table('paiements_assignes')->where('type_id', $data['type_id'])->where('professeur_id', $person->id)->update(['statut' => 'paye']);
                    } else {
                        DB::table('paiements_assignes')->where('type_id', $data['type_id'])->where('person_id', $person->id)->where('type_personne', 'staff')->update(['statut' => 'paye']);
                    }
                }
                $this->notify('Paiement', 'Paiement ecolage enregistre pour '.trim($person->nom.' '.$person->prenom).'.');
            } else {
                $data = $request->validate(array_merge($baseRules, ['eleve_id' => ['required', 'integer']]));
                $student = DB::table('eleves as e')->leftJoin('classes as c', 'c.id', '=', 'e.id_classe')->select('e.*', 'c.nom as classe')->where('e.id', $data['eleve_id'])->first();
                abort_unless($student, 404);
                DB::table('revenus')->insert(['type_id' => $data['type_id'], 'personne_id' => $student->id, 'type_personne' => 'eleve', 'classes' => $student->classe, 'mois' => $data['mois'], 'annee_scolaire' => $data['annee_scolaire'], 'montant' => $data['montant'], 'description' => $data['description'] ?? '', 'mode_paiement' => $data['mode_paiement'], 'statut' => $data['statut'], 'categorie' => $data['categorie'], 'nom_personne' => trim($student->nom.' '.$student->prenom), 'date_enregistrement' => now(), 'source' => 'ecolage']);
                if ($data['statut'] === 'complet') {
                    DB::table('paiements_assignes')->where('type_id', $data['type_id'])->where('eleve_id', $student->id)->update(['statut' => 'paye']);
                }
                $this->notify('Paiement', 'Paiement ecolage enregistre pour '.trim($student->nom.' '.$student->prenom).'.');
            }
        } elseif ($kind === 'type_paiement') {
            $data = $request->validate(['personne_id' => ['required', 'integer'], 'type_personne' => ['required', 'in:professeur,staff'], 'type_id' => ['required', 'integer'], 'mois' => ['required', 'string'], 'annee_scolaire' => ['required', 'string'], 'montant' => ['required', 'numeric'], 'description' => ['nullable', 'string'], 'mode_paiement' => ['required', 'string'], 'statut' => ['required', 'in:complet,partiel'], 'categorie' => ['required', 'string']]);
            $personTable = $data['type_personne'] === 'professeur' ? 'professeurs' : 'staff';
            $person = DB::table($personTable)->where('id', $data['personne_id'])->first();
            abort_unless($person, 404);
            DB::table('revenus')->insert(['type_id' => $data['type_id'], 'personne_id' => $person->id, 'type_personne' => $data['type_personne'], 'mois' => $data['mois'], 'annee_scolaire' => $data['annee_scolaire'], 'montant' => $data['montant'], 'description' => $data['description'] ?? '', 'mode_paiement' => $data['mode_paiement'], 'statut' => $data['statut'], 'categorie' => $data['categorie'], 'nom_personne' => trim($person->nom.' '.$person->prenom), 'date_enregistrement' => now(), 'source' => 'type_paiement']);
            if ($data['statut'] === 'complet') {
                if ($data['type_personne'] === 'professeur') {
                    DB::table('paiements_assignes')->where('type_id', $data['type_id'])->where('professeur_id', $person->id)->update(['statut' => 'paye']);
                } else {
                    DB::table('paiements_assignes')->where('type_id', $data['type_id'])->where('person_id', $person->id)->where('type_personne', 'staff')->update(['statut' => 'paye']);
                }
            }
            $this->notify('Paiement', 'Paiement type enregistre pour '.trim($person->nom.' '.$person->prenom).'.');
        } elseif ($kind === 'salaire') {
            $data = $request->validate(['personne_id' => ['required', 'integer'], 'type_personne' => ['required', 'in:professeur,staff'], 'mois' => ['required', 'string'], 'annee_scolaire' => ['required', 'string'], 'montant' => ['required', 'numeric'], 'description' => ['nullable', 'string'], 'mode_paiement' => ['required', 'string'], 'statut' => ['required', 'in:complet,partiel'], 'categorie' => ['required', 'string']]);
            $person = DB::table($data['type_personne'] === 'professeur' ? 'professeurs' : 'staff')->where('id', $data['personne_id'])->first();
            abort_unless($person, 404);
            DB::table('depenses')->insert(['type_id' => 0, 'personne_id' => $person->id, 'type_personne' => $data['type_personne'], 'mois' => $data['mois'], 'annee_scolaire' => $data['annee_scolaire'], 'montant' => $data['montant'], 'description' => $data['description'] ?? '', 'mode_paiement' => $data['mode_paiement'], 'statut' => $data['statut'], 'categorie' => $data['categorie'], 'nom_personne' => trim($person->nom.' '.$person->prenom), 'date_enregistrement' => now()]);
            if ($data['statut'] === 'complet') {
                DB::table('salaires_assignes')->where('personne_id', $person->id)->where('type_personne', $data['type_personne'])->where('mois', $data['mois'])->where('annee_scolaire', $data['annee_scolaire'])->update(['statut' => 'paye']);
            }
            $this->notify('Paiement', 'Paiement salaire enregistre pour '.trim($person->nom.' '.$person->prenom).'.');
        } else {
            $data = $request->validate(['flow' => ['required', 'in:revenu,depense'], 'nom_personne' => ['required', 'string'], 'type_personne' => ['required', 'string'], 'mois' => ['required', 'string'], 'annee_scolaire' => ['required', 'string'], 'montant' => ['required', 'numeric'], 'description' => ['nullable', 'string'], 'mode_paiement' => ['required', 'string'], 'statut' => ['required', 'in:complet,partiel'], 'categorie' => ['required', 'string']]);
            $payload = ['type_id' => 0, 'personne_id' => $data['nom_personne'], 'type_personne' => $data['type_personne'], 'mois' => $data['mois'], 'annee_scolaire' => $data['annee_scolaire'], 'montant' => $data['montant'], 'description' => $data['description'] ?? '', 'mode_paiement' => $data['mode_paiement'], 'statut' => $data['statut'], 'categorie' => $data['categorie'], 'nom_personne' => $data['nom_personne'], 'date_enregistrement' => now()];
            if ($data['flow'] === 'revenu') {
                $payload['classes'] = null;
                $payload['source'] = $data['categorie'];
                DB::table('revenus')->insert($payload);
            } else {
                DB::table('depenses')->insert($payload);
            }
        }

        return back()->with('accounting_msg', ['type' => 'success', 'text' => 'Operation enregistree.']);
    }

    public function searchStudents(Request $request)
    {
        $this->ensureSession();
        $q = (string) $request->query('q', '');
        $annee = (string) $request->query('annee_scolaire', $this->currentYear());

        return DB::table('eleves as e')
            ->leftJoin('classes as c', 'c.id', '=', 'e.id_classe')
            ->select('e.id', 'e.nom', 'e.prenom', 'c.nom as classe')
            ->where('e.annee_scolaire', $annee)
            ->when($q !== '', fn ($query) => $query->where(fn ($sub) => $sub->where('e.nom', 'like', "%$q%")->orWhere('e.prenom', 'like', "%$q%")))
            ->orderBy('e.nom')
            ->limit(30)
            ->get();
    }

    public function list(Request $request, ModuleRegistry $modules)
    {
        $this->ensureSession();
        $annee = (string) $request->query('annee_scolaire', '');
        $mois = (string) $request->query('mois', '');
        $revenus = DB::table('revenus')->when($annee, fn ($q) => $q->where('annee_scolaire', $annee))->when($mois, fn ($q) => $q->where('mois', $mois))->orderByDesc('date_enregistrement')->limit(500)->get();
        $depenses = DB::table('depenses')->when($annee, fn ($q) => $q->where('annee_scolaire', $annee))->when($mois, fn ($q) => $q->where('mois', $mois))->orderByDesc('date_enregistrement')->limit(500)->get();
        return $this->view('modules.accounting.list', $modules, 'liste_paiements', ['annees' => $this->years(), 'selectedAnnee' => $annee, 'months' => $this->months, 'selectedMonth' => $mois, 'revenus' => $revenus, 'depenses' => $depenses, 'totalRevenus' => $revenus->sum('montant'), 'totalDepenses' => $depenses->sum('montant')]);
    }

    public function invoice(Request $request, ModuleRegistry $modules)
    {
        $this->ensureSession();
        $annee = (string) $request->query('annee_scolaire', $this->currentYear());
        $type = (string) $request->query('type_personne', 'eleve');
        $id = (string) $request->query('personne_id', '');
        $mois = (string) $request->query('mois', '');
        $blank = $request->boolean('blank');
        $doc = (string) $request->query('type_document', 'facture');
        $query = $type === 'eleve' ? DB::table('revenus')->where('personne_id', $id)->where('annee_scolaire', $annee) : DB::table('depenses')->where('personne_id', $id)->where('type_personne', $type)->where('annee_scolaire', $annee);
        $transactions = $blank || $id === '' ? collect() : $query->when($mois, fn ($q) => $q->where('mois', $mois))->orderByDesc('date_enregistrement')->get();
        return $this->view('modules.accounting.invoice', $modules, 'facture', ['annees' => $this->years(), 'selectedAnnee' => $annee, 'months' => $this->months, 'selectedMonth' => $mois, 'typePersonne' => $type, 'personneId' => $id, 'docType' => $doc, 'blank' => $blank, 'transactions' => $transactions, 'people' => $this->invoicePeople($annee), 'numero' => strtoupper(substr($doc, 0, 4)).'-'.date('Ymd').'-'.sprintf('%03d', random_int(1, 999))]);
    }

    public function searchInvoicePeople(Request $request)
    {
        $this->ensureSession();
        $annee = (string) $request->query('annee_scolaire', $this->currentYear());
        $q = mb_strtolower((string) $request->query('q', ''));
        return $this->invoicePeople($annee)
            ->filter(fn ($p) => $q === '' || str_contains(mb_strtolower($p->nom), $q))
            ->take(40)
            ->values();
    }

    private function invoicePeople(string $annee)
    {
        $concat = novaskol_concat('nom', "' '", 'prenom').' as nom';
        return collect()
            ->merge(DB::table('eleves')->select('id', DB::raw($concat), DB::raw("'eleve' as type_personne"))->where('annee_scolaire', $annee)->get())
            ->merge(DB::table('professeurs')->select('id', DB::raw($concat), DB::raw("'professeur' as type_personne"))->where('annee_scolaire', $annee)->get())
            ->merge(DB::table('staff')->select('id', DB::raw($concat), DB::raw("'staff' as type_personne"))->where('annee_scolaire', $annee)->get());
    }

    private function years()
    {
        return DB::table('eleves')->select('annee_scolaire')->whereNotNull('annee_scolaire')->union(DB::table('revenus')->select('annee_scolaire')->whereNotNull('annee_scolaire'))->union(DB::table('depenses')->select('annee_scolaire')->whereNotNull('annee_scolaire'))->distinct()->orderByDesc('annee_scolaire')->pluck('annee_scolaire');
    }

    private function currentYear(): string
    {
        return (string) ($this->years()->first() ?: now()->format('Y').'-'.(now()->year + 1));
    }

    private function view(string $name, ModuleRegistry $modules, string $activeModule, array $data = [])
    {
        return view($name, $data + ['modules' => $modules->all(), 'userPermissions' => $this->userPermissions(), 'ecole' => $this->school(), 'activeModule' => $activeModule]);
    }

    private function notify(string $type, string $message): void
    {
        DB::table('notifications')->insert(['type' => $type, 'message' => $message, 'destinataire_id' => null, 'date_creation' => now(), 'statut' => 'non lu', 'date_envoi' => now(), 'lu' => 0, 'user_type' => session('utilisateur.role', 'admin'), 'user_id' => session('utilisateur.id', 0), 'titre' => $type]);
    }

    private function paymentReminderStats(string $annee): array
    {
        $types = DB::table('types_paiements')
            ->where('annee_scolaire', $annee)
            ->whereNotNull('date_fin')
            ->get();

        $overdue = [];
        $soon = [];

        foreach ($types as $type) {
            $unpaid = DB::table('paiements_assignes')
                ->where('type_id', $type->id)
                ->where('statut', '!=', 'paye')
                ->count();

            if ($unpaid <= 0) {
                continue;
            }

            $days = now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($type->date_fin)->startOfDay(), false);
            $item = [
                'id' => (int) $type->id,
                'nom' => (string) $type->nom,
                'classe' => (string) ($type->classe ?? ''),
                'date_fin' => (string) $type->date_fin,
                'unpaid' => $unpaid,
                'days' => $days,
            ];

            if ($days < 0) {
                $overdue[] = $item;
            } elseif ($days <= 3) {
                $soon[] = $item;
            }
        }

        return [
            'overdue' => $overdue,
            'soon' => $soon,
            'overdue_count' => array_sum(array_column($overdue, 'unpaid')),
            'soon_count' => array_sum(array_column($soon, 'unpaid')),
        ];
    }

    private function createDuePaymentReminders(string $annee, array $stats): int
    {
        if (! DB::getSchemaBuilder()->hasTable('notifications')) {
            return 0;
        }

        $created = 0;
        foreach (array_merge($stats['overdue'] ?? [], $stats['soon'] ?? []) as $item) {
            $status = $item['days'] < 0 ? 'en retard' : 'proche';
            $message = sprintf(
                'Rappel paiement %s : %d eleve(s) doivent encore payer %s (%s), echeance %s.',
                $status,
                $item['unpaid'],
                $item['nom'],
                $item['classe'] ?: $annee,
                \Carbon\Carbon::parse($item['date_fin'])->format('d/m/Y')
            );

            $existsToday = DB::table('notifications')
                ->where('type', 'rappel_paiement')
                ->where('message', $message)
                ->whereDate('date_creation', today())
                ->exists();

            if ($existsToday) {
                continue;
            }

            DB::table('notifications')->insert([
                'type' => 'rappel_paiement',
                'message' => $message,
                'destinataire_id' => null,
                'date_creation' => now(),
                'date_envoi' => now(),
                'statut' => 'non lu',
                'lu' => 0,
                'user_type' => session('utilisateur.role', 'admin'),
                'user_id' => session('utilisateur.id', 0),
                'titre' => 'rappel_paiement',
            ]);
            $created++;
        }

        return $created;
    }

    private function ensureSession(): void
    {
        abort_unless(session()->has('utilisateur') && in_array(session('utilisateur.role'), ['admin', 'enseignant', 'parent', 'staff'], true), 403);
    }

    private function userPermissions(): array
    {
        $id = (int) session('utilisateur.id', 0);
        return $id ? DB::table('permissions')->where('utilisateur_id', $id)->pluck('acces', 'module')->all() : [];
    }

    private function school(): object
    {
        return DB::table('ecole')->select('nom', 'logo')->first() ?: (object) ['nom' => 'Ecole', 'logo' => 'novaskol.png'];
    }
}
