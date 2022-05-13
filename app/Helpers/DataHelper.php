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
        $dbFile = base_path() . env('ZIP_CSV_DB');
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
            $lineCount = 1;

            while (($line = fgetcsv($file, 0, $delimiter)) !== FALSE) {
                $lineCount++;
                /**
                 * Search requested zip
                 */
                if (isset($line[0]) && $line[0] === $zip) {
                    $settlements[] = $lineCount;
                    $found = true;
                } elseif ($found === true) {
                    break;
                }

            }

            if ($found) {
                $response = DataHelper::format($settlements);
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

    public static function format($settlements): array
    {
        $settlementsToShow = [];
        $count = 0;
        $response = null;

        foreach ($settlements as $settlement) {
            $count = $count + 1;
            if ($count === 1) {
                $response = $settlement;
            }
            $settlementsToShow[] = [
                'key' => $settlement->id_asenta_cpcons,
                'name' => strtoupper(DataHelper::clean($settlement->d_asenta)),
                'zone_type' => strtoupper(DataHelper::clean($settlement->d_zona)),
                'settlement_type' => [
                    'name' => DataHelper::clean($settlement->d_tipo_asenta),
                ],
            ];
        }

        if (!$response) {
            return [];
        }

        return [
            'zip_code' => $response->d_codigo,
            'locality' => strtoupper(DataHelper::clean($response->d_ciudad)),
            'federal_entity' => [
                'key' => $response->c_estado,
                'name' => strtoupper(DataHelper::clean($response->d_estado)),
                'code' => $response->c_CP,
            ],
            'settlements' => $settlementsToShow,
            'municipality' => [
                'key' => $response->c_mnpio,
                'name' => strtoupper(DataHelper::clean($response->D_mnpio)),
            ],
        ];
    }

    public static function clean($string)
    {

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
