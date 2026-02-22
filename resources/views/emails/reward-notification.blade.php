<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>body{font-family:sans-serif;line-height:1.6;color:#333;max-width:600px;margin:0 auto;padding:20px;} .highlight{background:#ecfdf5;padding:16px;border-radius:8px;margin:16px 0;}</style>
</head>
<body>
    <h1>Récompense de recrutement</h1>
    <p>Bonjour,</p>
    <p>Félicitations ! Votre période d'essai a été validée et vous avez été recruté(e) via notre plateforme.</p>
    <div class="highlight">
        <strong>Montant de la récompense :</strong> {{ number_format($reward->amount_mad, 0, ',', ' ') }} MAD<br>
        <strong>Statut :</strong> {{ $reward->status === 'paid' ? 'Versée' : 'En cours de traitement' }}
    </div>
    @if($reward->status !== 'paid')
    <p>Le versement est en cours de traitement. Si nous avons besoin de vos coordonnées bancaires (IBAN), nous vous contacterons.</p>
    @endif
    <p>Cordialement,<br>L'équipe {{ config('app.name') }}</p>
</body>
</html>
