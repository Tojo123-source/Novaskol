@php
    $contactStatus = request('contact');
@endphp

@if ($contactStatus === 'success')
    <div class="success-msg">Message envoye ! Merci, on vous repond tres vite.</div>
@elseif ($contactStatus === 'queued')
    <div class="success-msg">Pas de connexion ? Votre message est enregistre localement.<br>Il sera envoye des que possible.</div>
@elseif ($contactStatus === 'error')
    <div class="error-msg">Erreur lors de l'envoi. Reessayez ou contactez-nous directement.</div>
@elseif ($contactStatus === 'pending_sent')
    <div class="success-msg">Tous les messages en attente ont ete envoyes avec succes !<br>Le fichier a ete vide.</div>
@elseif ($contactStatus === 'pending_failed')
    <div class="error-msg">Erreur lors de l'envoi des messages en attente.<br>Reessayez plus tard ou contactez-nous directement.</div>
@elseif ($contactStatus === 'no_pending')
    <div style="text-align:center; padding:1rem; background:rgba(100,100,100,0.15); border-radius:10px; color:#aaa;">
        Aucun message en attente a envoyer.
    </div>
@endif
