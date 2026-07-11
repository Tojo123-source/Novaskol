@php($pendingFile = storage_path('app/messages_en_attente.txt'))

@if (file_exists($pendingFile) && filesize($pendingFile) > 0)
    <p style="color:#ffc107; font-weight:600; margin-bottom:1.2rem;">
        Des messages sont en attente d'envoi.
    </p>
    <form action="{{ route('contact.pending') }}" method="post">
        @csrf
        <button type="submit" class="btn btn-outline" style="background:transparent; border:2px solid #ffc107; color:#ffc107; padding:1rem 2rem;">
            Envoyer les messages en attente maintenant
        </button>
    </form>
@else
    <p style="opacity:0.7; font-style:italic;">
        Aucun message en attente.
    </p>
@endif
