<?php
declare(strict_types=1);

/** @var string $slug */
/** @var array<string, mixed> $project */
$detailUrl = 'project-details.php?project=' . rawurlencode($slug);
?>
<div class="gallery-item">
    <div class="gallery-style-one">
        <?php if (($project['type'] ?? 'image') === 'video'): ?>
            <video controls preload="metadata" class="project-media">
                <source src="<?= htmlspecialchars((string) $project['file'], ENT_QUOTES, 'UTF-8') ?>" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        <?php else: ?>
            <a href="<?= htmlspecialchars($detailUrl, ENT_QUOTES, 'UTF-8') ?>">
                <img src="<?= htmlspecialchars((string) $project['file'], ENT_QUOTES, 'UTF-8') ?>"
                     alt="<?= htmlspecialchars((string) $project['title'], ENT_QUOTES, 'UTF-8') ?> project preview"
                     class="project-media" loading="lazy" decoding="async">
            </a>
        <?php endif; ?>
        <div class="info">
            <div class="overlay"><div class="content"></div></div>
            <h4><a href="<?= htmlspecialchars($detailUrl, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars((string) $project['title'], ENT_QUOTES, 'UTF-8') ?></a></h4>
        </div>
    </div>
</div>
