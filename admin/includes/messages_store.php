<?php
declare(strict_types=1);

/** Read + parse storage/messages.log into a list of enquiries, newest first. */
function read_messages(): array {
    $path = __DIR__ . '/../../storage/messages.log';
    if (!is_file($path)) return [];
    $raw = (string) file_get_contents($path);
    $chunks = preg_split('/\n{2,}/', trim($raw));
    $out = [];
    foreach ($chunks as $chunk) {
        $chunk = trim($chunk);
        if ($chunk === '') continue;

        // A "mail() failed" note gets attached to the entry right before it.
        if (preg_match('/^\[(.+?)\]\s*\^\s*mail\(\) failed/i', $chunk)) {
            if (!empty($out)) $out[count($out) - 1]['mail_failed'] = true;
            continue;
        }

        $lines = explode("\n", $chunk);
        $header = array_shift($lines);
        if (!preg_match('/^\[(.+?)\]\s+(.*?)\s+<(.*?)>\s+\|\s+(.*)$/', $header, $m)) {
            continue; // not a shape we recognise, skip rather than guess
        }
        // Drop the "----" separator line if present.
        if (isset($lines[0]) && preg_match('/^-{5,}$/', trim($lines[0]))) {
            array_shift($lines);
        }
        $out[] = [
            'date'    => $m[1],
            'name'    => $m[2],
            'email'   => $m[3],
            'company' => $m[4] === '—' ? '' : $m[4],
            'body'    => trim(implode("\n", $lines)),
            'mail_failed' => false,
        ];
    }
    return array_reverse($out);
}
