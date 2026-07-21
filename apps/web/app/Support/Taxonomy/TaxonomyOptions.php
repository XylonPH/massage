<?php

namespace App\Support\Taxonomy;

use Illuminate\Support\Facades\File;

class TaxonomyOptions
{
    /** @return array<string, string> option code => label */
    public static function for(string $fieldName): array
    {
        $repositoryRoot = dirname(base_path(), 2);
        $paths = [
            $repositoryRoot.'/data/taxonomy/massage_nexus/establishment_classification.json',
            $repositoryRoot.'/data/taxonomy/shared/person_identity_and_contact.json',
        ];

        foreach ($paths as $path) {
            if (! File::exists($path)) {
                continue;
            }

            $data = json_decode(File::get($path), true);

            foreach ($data as $field) {
                if ($field['field_name'] === $fieldName) {
                    $options = [];
                    foreach ($field['field_option'] ?? [] as $option) {
                        $options[$option['option_code']] = $option['option_label'];
                    }

                    return $options;
                }
            }
        }

        return [];
    }
}
