<!DOCTYPE html>
<html>
<head>
    <title>{{ $subject }}</title>
</head>
<body>
<h1>Olá, {{ $name }}</h1>

<p>{{ $subject }}</p>

Sua conta foi criada com sucesso! <br>

Navegue e lucre clicando em <a href="{{ $extra['link'] }}">Jogar agora</a>.<br><br>


<br><br>
<font color="#8b0000">Não foi você quem fez este pedido de criação de conta? entre em contato conosco
    em {{ $extra['support_email'] }} !</font>

<p>Obrigado.</p>
</body>
</html>
