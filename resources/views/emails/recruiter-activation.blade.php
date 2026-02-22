<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>body{font-family:sans-serif;line-height:1.6;color:#333;max-width:600px;margin:0 auto;padding:20px;} a{color:#059669;} .btn{display:inline-block;background:#059669;color:#fff!important;padding:12px 24px;text-decoration:none;border-radius:8px;margin-top:16px;}</style>
</head>
<body>
    <h1>Bienvenue sur {{ config('app.name') }}</h1>
    <p>Bonjour {{ $user->name }},</p>
    <p>Votre demande de création de compte recruteur a bien été enregistrée. Pour activer votre compte et définir votre mot de passe, cliquez sur le lien ci-dessous :</p>
    <p><a href="{{ $activationUrl }}" class="btn">Activer mon compte</a></p>
    <p>Si le bouton ne fonctionne pas, copiez ce lien dans votre navigateur :<br><small>{{ $activationUrl }}</small></p>
    <p>Ce lien expire dans 48 heures.</p>
    <p>Cordialement,<br>L'équipe {{ config('app.name') }}</p>
</body>
</html>
