<?php

namespace App\Helpers;

class PlantaHelper
{
    /**
     * Parses the 'Planta' field value to extract a label, URL, and determine if it is a direct file.
     *
     * Format supported: "Label | URL" or just "URL".
     *
     * @param string|null $value
     * @return array|null
     */
    public static function parse($value)
    {
        $value = trim((string) $value);
        if (empty($value)) {
            return null;
        }

        $label = 'Visualizar planta';
        $url = $value;

        if (strpos($value, '|') !== false) {
            $parts = explode('|', $value, 2);
            $label = trim($parts[0]);
            $url = trim($parts[1]);
        }

        if (empty($url)) {
            return null;
        }

        $parsedUrl = parse_url($url);
        $path = isset($parsedUrl['path']) ? $parsedUrl['path'] : '';
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        // Direct files that should open in a lightbox (images & PDFs)
        $isDirectFile = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'pdf']);

        return [
            'label' => $label,
            'url' => $url,
            'is_direct_file' => $isDirectFile,
        ];
    }
}
