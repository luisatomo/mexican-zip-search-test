<?php

namespace App\Helpers;

use Exception;

class DataHelper
{

    /**
     * CSV To Array without validation...
     *
     * @param $zip
     * @return array
     */
    public static function search($zip): array
    {
        $dbFile = base_path() . env('ZIP_TXT_DB');
        $delimiter = '|';
        $response = null;
        $settlements = [];

        try {
            $file = fopen($dbFile, 'r');
            // Description row
            fgetcsv($file, 0, $delimiter);
            // Headers row
            $headers = fgetcsv($file, 0, $delimiter);
            $found = false;

            while (($line = fgetcsv($file, 0, $delimiter)) !== FALSE) {
                /**
                 * Search requested zip
                 */
                if (isset($line[0]) && $line[0] === $zip) {
                    $settlements[] = $line;
                    $found = true;
                } elseif ($found === true) {
                    break;
                }
            }

            if ($found) {
                $response = DataHelper::format($headers, $settlements[0], $settlements);
            }
            fclose($file);
        } catch (Exception $e) {
            return [
                'Error' => [
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                ],
            ];
        }

        return $response;
    }

    public static function format($headers, $response, $settlements): array
    {
        $settlementsToShow = [];

        foreach ($settlements as $settlement) {
            $settlementsToShow[] = [
                'key' => DataHelper::intOrNull($settlement[array_search('id_asenta_cpcons', $headers)]),
                'name' => strtoupper(DataHelper::clean($settlement[array_search('d_asenta', $headers)])),
                'zone_type' => strtoupper(DataHelper::clean($settlement[array_search('d_zona', $headers)])),
                'settlement_type' => [
                    'name' => DataHelper::clean($settlement[array_search('d_tipo_asenta', $headers)]),
                ]
            ];
        }

        return [
            'zip_code' => $response[array_search('d_codigo', $headers)],
            'locality' => strtoupper(DataHelper::clean($response[array_search('d_ciudad', $headers)])),
            'federal_entity' => [
                'key' => DataHelper::intOrNull($response[array_search('c_estado', $headers)]),
                'name' => strtoupper(DataHelper::clean($response[array_search('d_estado', $headers)])),
                'code' => DataHelper::intOrNull($response[array_search('c_CP', $headers)]),
            ],
            'settlements' => $settlementsToShow,
            'municipality' => [
                'key' => DataHelper::intOrNull($response[array_search('c_mnpio', $headers)]),
                'name' => strtoupper(DataHelper::clean($response[array_search('D_mnpio', $headers)])),
            ],
        ];
    }

    public static function clean($string)
    {
        $string = utf8_encode($string);

        $string = str_replace(
            array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
            array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
            $string
        );

        $string = str_replace(
            array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
            array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
            $string);

        $string = str_replace(
            array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
            array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
            $string);

        $string = str_replace(
            array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
            array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
            $string);

        $string = str_replace(
            array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
            array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
            $string);

        $string = str_replace(
            array('ñ', 'Ñ', 'ç', 'Ç'),
            array('n', 'N', 'c', 'C'),
            $string
        );

        return $string;
    }

    public static function intOrNull($value): ?int
    {
        if ($value) {
            return (int)$value;
        }

        return null;
    }


}
