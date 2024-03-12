<!DOCTYPE html>
<html>
<head>
    <title><?php echo e($subject); ?></title>
</head>
<body>
<h1>Olá, <?php echo e($name); ?></h1>

<p><?php echo e($subject); ?></p>

Sua conta foi criada com sucesso! <br>

Navegue e lucre clicando em <a href="<?php echo e($extra['link']); ?>">Jogar agora</a>.<br><br>


<br><br>
<font color="#8b0000">Não foi você quem fez este pedido de criação de conta? entre em contato conosco
    em <?php echo e($extra['support_email']); ?> !</font>

<p>Obrigado.</p>
</body>
</html>

<?php /* /var/www/html/resources/views/emails/WelcomeMail.blade.php */ ?>