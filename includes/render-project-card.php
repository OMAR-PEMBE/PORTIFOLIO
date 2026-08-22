<?php
declare(strict_types=1);

require_once __DIR__ . '/project-repository.php';

/** @var string $slug */
/** @var Project $project */
$detailUrl = projectUrl($slug);
?>
<div class="gallery-item">
    <div class="gallery-style-one">
        <?php if ($project->type === 'video'): ?>
            <video controls preload="metadata" class="project-media">
            <source src="<?= escapeHtml($project->file) ?>" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        <?php else: ?>
            <a href="<?= escapeHtml($detailUrl) ?>">
                 <img src="<?= escapeHtml($project->file) ?>"
                     alt="<?= escapeHtml($project->title) ?> project preview"
                     class="project-media" loading="lazy" decoding="async">
            </a>
        <?php endif; ?>
        <div class="info">
            <div class="overlay"><div class="content"></div></div>
            <h4><a href="<?= escapeHtml($detailUrl) ?>"><?= escapeHtml($project->title) ?></a></h4>
        </div>
    </div>
</div>
