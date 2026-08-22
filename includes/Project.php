<?php
declare(strict_types=1);

final class Project
{
    public string $slug;
    public string $title;
    public string $type;
    public string $file;
    public string $client;
    public string $service;
    public string $date;
    public string $location;
    public string $background;
    public string $challenges;
    public string $solution;
    /** @var list<string|array{type: string, url: string}> */
    public array $gallery;

    /** @param list<string|array{type: string, url: string}> $gallery */
    public function __construct(
        string $slug,
        string $title,
        string $type,
        string $file,
        string $client,
        string $service,
        string $date,
        string $location,
        string $background,
        string $challenges,
        string $solution,
        array $gallery
    ) {
        $this->slug = $slug;
        $this->title = $title;
        $this->type = $type;
        $this->file = $file;
        $this->client = $client;
        $this->service = $service;
        $this->date = $date;
        $this->location = $location;
        $this->background = $background;
        $this->challenges = $challenges;
        $this->solution = $solution;
        $this->gallery = $gallery;
    }

    /** @param array<string, mixed> $record */
    public static function fromRecord(string $slug, array $record): self
    {
        $required = ['title', 'type', 'file', 'client', 'service', 'date', 'location', 'background', 'challenges', 'solution', 'gallery'];
        foreach ($required as $field) {
            if (!array_key_exists($field, $record)) {
                throw new InvalidArgumentException("Project '{$slug}' is missing '{$field}'.");
            }
        }

        if (!is_array($record['gallery'])) {
            throw new InvalidArgumentException("Project '{$slug}' has an invalid gallery.");
        }

        foreach ($record['gallery'] as $item) {
            if (is_string($item) && trim($item) !== '') {
                continue;
            }
            if (!is_array($item) || !is_string($item['type'] ?? null) || !is_string($item['url'] ?? null) || trim($item['url']) === '' || !in_array($item['type'], ['image', 'video', 'website'], true)) {
                throw new InvalidArgumentException("Project '{$slug}' has an invalid gallery item.");
            }
        }

        $values = [];
        foreach (array_slice($required, 0, -1) as $field) {
            if (!is_string($record[$field]) || trim($record[$field]) === '') {
                throw new InvalidArgumentException("Project '{$slug}' has an invalid '{$field}'.");
            }
            $values[$field] = $record[$field];
        }

        return new self(
            $slug,
            $values['title'],
            $values['type'],
            $values['file'],
            $values['client'],
            $values['service'],
            $values['date'],
            $values['location'],
            $values['background'],
            $values['challenges'],
            $values['solution'],
            array_values($record['gallery'])
        );
    }
}
