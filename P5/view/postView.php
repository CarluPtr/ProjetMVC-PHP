<?php $title = 'Blog de Carlu';
?>

<?php ob_start();
?>
        <div class="container">
            <p><a href="index.php">Retour à la liste des billets</a></p>

            <div class="card">
                <h3>
                    <?= htmlspecialchars($post['title']) ?>
                    <em>le <?= $post['creation_date_fr'] ?></em>
                </h3>
                
                <p>
                    <?= nl2br(htmlspecialchars($post['content'])) ?>
                </p>
            </div>
                <h2 class="py-5">Commentaires</h2>
        </div>

        <?php
        while ($comment = $comments->fetch())
        {
        ?>
            <div class="container">
                <div class="card">
                    <p><strong><?= htmlspecialchars($comment['author']) ?></strong> le <?= $comment['comment_date_fr'] ?></p>
                    <p><?= nl2br(htmlspecialchars($comment['comment'])) ?></p>
                </div>
            </div>
            
        <?php
        }
        ?>
        <?php $content = ob_get_clean(); ?>

<?php require ('view/template.php'); ?>
