<?php

declare(strict_types=1);

$repository = dirname(__DIR__, 2);
$guideDirectory = $repository . '/data/structure_guide';
$guideFiles = glob($guideDirectory . '/*.php') ?: [];
sort($guideFiles);

$errors = [];

$error = static function (string $file, string $message) use (&$errors): void {
    $errors[] = basename($file) . ': ' . $message;
};

foreach ($guideFiles as $file) {
    $collection = pathinfo($file, PATHINFO_FILENAME);
    $source = file_get_contents($file);

    if ($source === false) {
        $error($file, 'could not read file');
        continue;
    }

    foreach (['Title', 'Version', 'Collection', 'Description', 'Purpose'] as $metadataName) {
        if (!preg_match('/^\s*\*\s*' . preg_quote($metadataName, '/') . ':\s*\S+/m', $source)) {
            $error($file, "missing {$metadataName} metadata");
        }
    }

    if (preg_match('/^\s*\*\s*Author:/m', $source)) {
        $error($file, 'Author metadata is redundant and must be removed');
    }

    if (!preg_match('/^\s*\*\s*Collection:\s*' . preg_quote($collection, '/') . '\s*$/m', $source)) {
        $error($file, 'Collection metadata must exactly match the filename');
    }

    if (!preg_match('/^\s*\*\s*Version:\s*[0-9]+\.[0-9]{2}\s*$/m', $source)) {
        $error($file, 'Version metadata must use major.two-digit-minor format');
    }

    foreach ([
        '/\$' . preg_quote($collection, '/') . '_field_order\s*=\s*array_keys\s*\(/' => 'field order must be declared explicitly, not derived with array_keys()',
        '/\$' . preg_quote($collection, '/') . '_field_property\s*=\s*\[\s*\]\s*;[\s\S]*?foreach\s*\(/' => 'field properties must be declared explicitly, not generated with foreach',
        '/return\s+compact\s*\(/' => 'the final return map must be declared explicitly, not generated with compact()',
    ] as $pattern => $message) {
        if (preg_match($pattern, $source)) {
            $error($file, $message);
        }
    }

    foreach (['created_at', 'updated_at'] as $timestampName) {
        if (!preg_match('/^\$' . $timestampName . "\s*=\s*'([0-9]{4}-[0-9]{2}-[0-9]{2}T[0-9]{2}:[0-9]{2}:[0-9]{2}Z)';\s*$/m", $source)) {
            $error($file, "missing or invalid \${$timestampName}");
        }
    }

    $loaded = (static function (string $path): array {
        $returned = include $path;
        $variables = get_defined_vars();
        unset($variables['path'], $variables['returned']);

        return ['returned' => $returned, 'variables' => $variables];
    })($file);

    $variables = $loaded['variables'];
    $returned = $loaded['returned'];
    $requiredVariableNames = [
        "{$collection}_default",
        $collection,
        "{$collection}_field_order",
        "{$collection}_embedded_structure",
        "{$collection}_field_property",
        "{$collection}_subfield_property",
        "{$collection}_index_list",
        "{$collection}_boundary",
    ];

    $orderedNames = [
        $collection,
        "{$collection}_field_order",
        "{$collection}_embedded_structure",
        "{$collection}_field_property",
        "{$collection}_subfield_property",
        "{$collection}_index_list",
        "{$collection}_boundary",
    ];
    $lastPosition = -1;
    foreach ($orderedNames as $variableName) {
        $position = strpos($source, '$' . $variableName . ' =');
        if ($position !== false && $position < $lastPosition) {
            $error($file, "\${$variableName} is out of canonical declaration order");
        }
        if ($position !== false) {
            $lastPosition = $position;
        }
    }

    foreach ($requiredVariableNames as $variableName) {
        if (!isset($variables[$variableName]) || !is_array($variables[$variableName])) {
            $error($file, "missing array \${$variableName}");
        }
    }

    if (!is_array($returned)) {
        $error($file, 'final return value must be an array');
        continue;
    }

    $returnVariableNames = $requiredVariableNames;
    foreach (["{$collection}_legacy_name_map", 'multilingual_text_sample'] as $optionalVariableName) {
        if (isset($variables[$optionalVariableName])) {
            $returnVariableNames[] = $optionalVariableName;
        }
    }

    if (isset($variables['multilingual_text_sample'])
        && array_keys($variables['multilingual_text_sample']) !== ['eng']) {
        $error($file, '$multilingual_text_sample must use only eng as its generic example');
    }

    foreach ($returnVariableNames as $variableName) {
        if (!array_key_exists($variableName, $returned)) {
            $error($file, "return array is missing {$variableName}");
        } elseif (isset($variables[$variableName]) && $returned[$variableName] !== $variables[$variableName]) {
            $error($file, "return value for {$variableName} does not match its variable");
        }
    }

    foreach ($variables as $variableName => $value) {
        if (is_array($value) && !array_key_exists($variableName, $returned)) {
            $error($file, "return array is missing declared array {$variableName}");
        }
    }

    $record = $variables[$collection] ?? null;
    $defaults = $variables["{$collection}_default"] ?? null;
    $fieldOrder = $variables["{$collection}_field_order"] ?? null;
    $fieldProperties = $variables["{$collection}_field_property"] ?? null;
    $embeddedStructures = $variables["{$collection}_embedded_structure"] ?? null;
    $subfieldProperties = $variables["{$collection}_subfield_property"] ?? null;

    if (is_array($embeddedStructures) && $embeddedStructures !== []
        && is_array($subfieldProperties) && $subfieldProperties === []) {
        $error($file, 'embedded structures exist but subfield properties are empty');
    }

    if (is_array($fieldProperties)) {
        $hasTranslatableField = false;
        foreach ($fieldProperties as $metadata) {
            if (is_array($metadata) && ($metadata['is_translatable'] ?? false) === true) {
                $hasTranslatableField = true;
            }
        }
        if ($hasTranslatableField && !isset($variables['multilingual_text_sample'])) {
            $error($file, 'translatable fields require $multilingual_text_sample');
        }
    }

    if (is_array($record) && is_array($defaults)) {
        foreach (array_keys($defaults) as $fieldName) {
            if (!array_key_exists($fieldName, $record)) {
                $error($file, "default field {$fieldName} is absent from the sample record");
            }
        }
    }

    if (is_array($record) && is_array($fieldOrder)) {
        if (array_values(array_keys($record)) !== array_values($fieldOrder)) {
            $recordKeys = array_keys($record);
            $missingFromOrder = array_values(array_diff($recordKeys, $fieldOrder));
            $missingFromRecord = array_values(array_diff($fieldOrder, $recordKeys));
            $error($file, 'sample-record keys and field order differ; missing from order: '
                . implode(', ', $missingFromOrder) . '; missing from record: ' . implode(', ', $missingFromRecord));
        }
    }

    if (is_array($record) && is_array($fieldProperties)) {
        $recordKeys = array_keys($record);
        $propertyKeys = array_keys($fieldProperties);
        sort($recordKeys);
        sort($propertyKeys);
        if ($recordKeys !== $propertyKeys) {
            $missingFromProperties = array_values(array_diff(array_keys($record), array_keys($fieldProperties)));
            $missingFromRecord = array_values(array_diff(array_keys($fieldProperties), array_keys($record)));
            $error($file, 'sample-record keys and top-level field-property keys differ; missing from properties: '
                . implode(', ', $missingFromProperties) . '; missing from record: ' . implode(', ', $missingFromRecord));
        }
    }

    $indexList = $variables["{$collection}_index_list"] ?? null;
    if (is_array($indexList)) {
        foreach ($indexList as $position => $index) {
            if (!is_array($index)) {
                $error($file, "index entry {$position} must be an array");
                continue;
            }

            foreach (['index_key', 'index_name', 'type_index', 'is_unique', 'is_sparse', 'index_field_list', 'sort_order'] as $key) {
                if (!array_key_exists($key, $index)) {
                    $error($file, "index entry {$position} is missing {$key}");
                }
            }

            foreach (($index['index_field_list'] ?? []) as $fieldPosition => $indexField) {
                foreach (['field_name', 'type_index_mode', 'sort_order'] as $key) {
                    if (!is_array($indexField) || !array_key_exists($key, $indexField)) {
                        $error($file, "index entry {$position} field {$fieldPosition} is missing {$key}");
                    }
                }
            }
        }
    }
}

if ($errors !== []) {
    fwrite(STDERR, implode(PHP_EOL, $errors) . PHP_EOL);
    exit(1);
}

echo 'Validated ' . count($guideFiles) . ' structure guides.' . PHP_EOL;
