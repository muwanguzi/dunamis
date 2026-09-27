<?php
/**
 * Raw JSON content store used by the admin panel (unlike includes/data.php's
 * load_content(), these do NOT fall back to defaults — a missing file here
 * is a real problem the admin should see, not silently paper over).
 */

declare(strict_types=1);

function content_path(string $name): string {
    return __DIR__ . '/../../content/' . $name . '.json';
}

function content_load(string $name): array {
    $path = content_path($name);
    if (!is_file($path)) return [];
    $data = json_decode((string) file_get_contents($path), true);
    return is_array($data) ? $data : [];
}

function content_save(string $name, array $data): void {
    $path = content_path($name);
    $tmp = $path . '.tmp';
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "\n";
    file_put_contents($tmp, $json, LOCK_EX);
    rename($tmp, $path); // atomic-ish swap so a half-written file is never read
}

/**
 * Field schemas for every editable collection. Drives the generic list +
 * add/edit/delete/reorder screen in edit.php.
 */
function content_schemas(): array {
    return [
        'services' => [
            'label' => 'Services',
            'title_field' => 'title',
            'auto_number' => true,
            'fields' => [
                'title' => ['label' => 'Title', 'type' => 'text', 'required' => true],
                'desc'  => ['label' => 'Description', 'type' => 'textarea'],
                'img'   => ['label' => 'Photo', 'type' => 'image', 'dir' => 'assets/img/services'],
                'tint'  => ['label' => 'Fallback tint (used only if no photo)', 'type' => 'select',
                            'options' => ['a' => 'Gold', 'b' => 'Charcoal', 'c' => 'Amber', 'd' => 'Ink', 'e' => 'Gold (alt)']],
            ],
        ],
        'work' => [
            'label' => 'Work / portfolio',
            'title_field' => 'title',
            'fields' => [
                'title' => ['label' => 'Client / project', 'type' => 'text', 'required' => true],
                'kind'  => ['label' => 'Kind of work', 'type' => 'text', 'placeholder' => 'e.g. Brand refresh · Packaging'],
                'img'   => ['label' => 'Cover image', 'type' => 'image', 'dir' => 'assets/img/work'],
                'tint'  => ['label' => 'Fallback tint (used only if no image)', 'type' => 'select',
                            'options' => ['a' => 'Gold', 'b' => 'Cream', 'c' => 'Pale blue', 'd' => 'Sand', 'e' => 'Ink', 'f' => 'Gold (alt)']],
            ],
        ],
        'team' => [
            'label' => 'Team',
            'title_field' => 'name',
            'fields' => [
                'name'  => ['label' => 'Name', 'type' => 'text', 'required' => true],
                'role'  => ['label' => 'Role', 'type' => 'text'],
                'bio'   => ['label' => 'Short bio', 'type' => 'textarea'],
                'photo' => ['label' => 'Photo', 'type' => 'image', 'dir' => 'assets/img/team'],
            ],
        ],
        'values' => [
            'label' => 'Values',
            'title_field' => 'label',
            'fields' => [
                'label' => ['label' => 'Value', 'type' => 'text', 'required' => true],
                'score' => ['label' => 'Score (0–100)', 'type' => 'number', 'min' => 0, 'max' => 100],
            ],
        ],
        'stats' => [
            'label' => 'Studio stats',
            'title_field' => 'label',
            'fields' => [
                'value'  => ['label' => 'Number', 'type' => 'text', 'required' => true],
                'suffix' => ['label' => 'Suffix', 'type' => 'text', 'placeholder' => 'e.g. + or °'],
                'label'  => ['label' => 'Caption', 'type' => 'textarea'],
            ],
        ],
        'clients' => [
            'label' => 'Client logos',
            'title_field' => 'name',
            'fields' => [
                'name' => ['label' => 'Client name', 'type' => 'text', 'required' => true],
                'logo' => ['label' => 'Logo', 'type' => 'image', 'dir' => 'assets/img/clients'],
            ],
        ],
        'testimonials' => [
            'label' => 'Testimonials',
            'title_field' => 'name',
            'fields' => [
                'quote'  => ['label' => 'Quote', 'type' => 'textarea', 'required' => true],
                'name'   => ['label' => 'Name', 'type' => 'text', 'required' => true],
                'meta'   => ['label' => 'Role / title', 'type' => 'text'],
                'rating' => ['label' => 'Star rating (blank = 5)', 'type' => 'number', 'min' => 1, 'max' => 5],
            ],
        ],
        'events' => [
            'label' => 'Upcoming events',
            'title_field' => 'title',
            'fields' => [
                'title'      => ['label' => 'Event title', 'type' => 'text', 'required' => true],
                'day'        => ['label' => 'Day', 'type' => 'text', 'placeholder' => 'e.g. 14'],
                'month'      => ['label' => 'Month', 'type' => 'text', 'placeholder' => 'e.g. Nov'],
                'year'       => ['label' => 'Year', 'type' => 'text'],
                'tag'        => ['label' => 'Tag', 'type' => 'text', 'placeholder' => 'e.g. Workshop'],
                'location'   => ['label' => 'Location', 'type' => 'text'],
                'desc'       => ['label' => 'Description', 'type' => 'textarea'],
                'img'        => ['label' => 'Photo', 'type' => 'image', 'dir' => 'assets/img/events'],
                'link'       => ['label' => 'Link (usually #contact)', 'type' => 'text'],
                'link_label' => ['label' => 'Link button text', 'type' => 'text', 'placeholder' => 'e.g. RSVP'],
            ],
        ],
        'process' => [
            'label' => 'How we work (process steps)',
            'title_field' => 'title',
            'auto_number' => true,
            'fields' => [
                'title' => ['label' => 'Step title', 'type' => 'text', 'required' => true],
                'desc'  => ['label' => 'Description', 'type' => 'textarea'],
            ],
        ],
    ];
}
