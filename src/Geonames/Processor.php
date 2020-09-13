<?php

namespace GeoStack\Support\Geonames;

use Illuminate\Support\Collection;

abstract class Processor
{
    public function process($path, $length = 1024 * 32, $delimiter = "\t"): Collection
    {
        $collection = Collection::make();
        if (($handle = fopen($path, "r")) !== false) {
            $row = 0;
            while (($line = $line = fgets($handle, $length)) !== false) {
                // ignore empty lines and comments
                if (!$line or $line === '' or strpos($line, '#') === 0) continue;

                // Split line into array.
                $line = explode($delimiter, $line);

                $formatted = $this->format($line, $row);
                if (!is_null($formatted)) {
                    $collection->add($formatted);
                }

                $row++;
            }
        }

        return $collection;
    }

    abstract public function format(array $data, $row): ?array;
}