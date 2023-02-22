
<div class="comments">
    <?php  if ($comments): ?>
        <?php foreach($comments as $comment): ?>
            <div class="cart">
                <div class="name"><span class="label">Имя: </span> <span class="value"> <?= $comment['name'] ?></span></div>
                <div class="email"><span class="label">Почта: </span> <span class="value"><?= $comment['email'] ?></span></div>
                <div class="comment"><span class="label">Коментария: </span> <span class="value"><?= $comment['comment'] ?></span></div>
                <div class="date"><span class="label">Дата: </span> <span class="value"><?= $comment['created_at'] ?></span> </div>

            </div>
        <?php endforeach; ?>
    <?php  endif; ?>

</div>