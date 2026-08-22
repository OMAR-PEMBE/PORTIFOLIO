<?php
declare(strict_types=1);

require_once __DIR__ . '/Project.php';
require_once __DIR__ . '/project-store.php';

/** @return array<string, Project> */
function allProjects(): array
{
    static $projects;

    if ($projects === null) {
        $loadedProjects = (new ProjectStore(dirname(__DIR__)))->records();
        if (!is_array($loadedProjects)) {
            throw new RuntimeException('Project data must be an array.');
        }
        $projects = [];
        foreach ($loadedProjects as $slug => $record) {
            if (!is_string($slug) || !is_array($record)) {
                throw new RuntimeException('Project data contains an invalid record.');
            }
            $projects[$slug] = Project::fromRecord($slug, $record);
        }
    }

    return $projects;
}

function project(string $slug): ?Project
{
    return allProjects()[$slug] ?? null;
}

function findProject(string $slug): ?Project
{
    return project($slug);
}

function projectUrl(string $slug): string
{
    return 'project-details.php?project=' . rawurlencode($slug);
}

function escapeHtml(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

