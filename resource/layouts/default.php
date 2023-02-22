<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../../css/app.css" type="text/css">
    <title><?=  $title ?? null ?>  </title>
</head>
<body>
<div class="container">
    <header>
        <div class="logo">
            <a href="/"><img src="../../img/logo.png" alt=""></a>
        </div>
        <div class="title"><h1><?=  $title ?? null ?></h1></div>
    </header>
    <main>
            <?php echo $content ?? null ?>
            <form class="form">
                <div class="form-group">
                    <label for="name">Ваша Имя</label>
                    <input type="text" id="name" name="name">
                    <span class="danger">error text</span>
                </div>
                <div class="form-group">
                    <label for="email">Почта</label>
                    <input type="email" id="email" name="email">
                    <span class="danger">error text</span>
                </div>
                <div class="form-group">
                    <label for="comment">Коментария</label>
                    <textarea name="comment" id="comment" ></textarea>
                    <span class="danger">error text</span>
                </div>
                <button class="btn" type="button"> Отпровит </button>
            </form>

    </main>
    <footer>
        <div class="overwrite">
            <span class="loader"></span>
        </div>

        <button class="social_share" data-type="fb"><img src="/img/fb.png" alt="fb"></button>
        <button class="social_share" data-type="twitter"><img src="/img/twitter.png" alt="fb"></button>
        <button class="social_share" data-type="linkedin"><img src="/img/linkedin.png" alt="fb"></button>
        <button class="social_share" data-type="telegram"><img src="/img/telegram.png" alt="fb"></button>
        <button class="social_share" data-type="whatsapp"><img src="/img/whatsapp.png" alt="fb"></button>
        <button class="social_share" data-type="viber"><img src="/img/viber.png" alt="fb"></button>

    </footer>
</div>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js" ></script>
<script src="../../js/jsshare.js" type="module"></script>
<script src="../../js/app.js" type="application/javascript"></script>
</body>
</html>