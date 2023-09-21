<?php

namespace App\Services;

use Illuminate\Contracts\Filesystem\FileNotFoundException;

class EnvService
{
    public function read()
    {

    }

    /**
     * Bulk updates the .env with an array of key/value pairs
     *
     * @param array $array
     * @throws FileNotFoundException
     */
    public function bulkUpdate(array $array): void
    {
        collect($array)->each(fn ($value, $key) => $this->update($key, $value));
    }

    /**
     * Updates the .env with key/value pair
     *
     * @param string $key
     * @param string $value
     * @return bool
     * @throws FileNotFoundException
     */
    public function update(string $key, string $value): void
    {
        $path = base_path('.env');

        if (!file_exists($path)) {
            throw new FileNotFoundException('.env does not exist');
        }

        $currentContent = file_get_contents($path);

        if (str_contains($currentContent, $key . '=')) {
            $newContent = preg_replace('/' . $key . '=(.*)/', $key . '=' . sprintf('"%s"', $value), $currentContent);
        } else {
            $newContent = $currentContent . PHP_EOL . $key . '=' . sprintf('"%s"', $value);
        }

        file_put_contents($path, $newContent);
    }
}
