<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>body{font-family:sans-serif;line-height:1.6;color:#333;max-width:600px;margin:0 auto;padding:20px;}</style>
</head>
<body>
    <h1>Réponse à votre candidature</h1>
    <p>Bonjour,</p>
    <p>Merci pour votre candidature au poste de <strong>{{ $application->jobOffer->title }}</strong> ({{ $application->jobOffer->company->name }}).</p>
    <p>Après étude de votre profil, certains éléments ne correspondent pas totalement aux critères du poste pour cette offre.</p>
    <p>Votre profil reste intéressant et nous vous encourageons à postuler à d'autres offres correspondant davantage à votre expérience.</p>
    <p>Bonne continuation.</p>
    <p>Cordialement,<br>L'équipe {{ config('app.name') }}</p>
</body>
</html>
