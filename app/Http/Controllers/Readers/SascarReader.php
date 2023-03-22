<?php

namespace App\Http\Controllers\Readers;

use App\Models\SascarPosition;
use SoapClient;
use Throwable;

class SascarReader
{
    public function readPositions(): void
    {
        try {
            $wsdlUrl = "http://sasintegra.sascar.com.br/SasIntegra/SasIntegraWSService?wsdl";
            $username = "GUINCHOSGR";
            $password = "sascar";

            $client = new SoapClient($wsdlUrl, [
                'trace' => 1,
                'exceptions' => true,
                'cache_wsdl' => WSDL_CACHE_NONE,
                'stream_context' => stream_context_create([
                    'http' => [
                        'header' => "Content-Type: text/xml;charset=UTF-8\r\n",
                    ],
                ]),
            ]);

            $result = $client->__soapCall('obterPacotePosicoesComPlaca', [
                'parameters' => [
                    'usuario' => $username,
                    'senha' => $password,
                    'quantidade' => '',
                ],
            ]);

            $positions = $result->return;

            foreach ($positions as $posicao) {
                $placa = (string) $posicao->placa;
                $latitude = (float) $posicao->latitude;
                $longitude = (float) $posicao->longitude;

                if ($placa && $latitude && $longitude) {
                    echo "Inserting data in the database: placa={$placa}, latitude={$latitude}, longitude={$longitude}\n";
                    SascarPosition::updateOrCreate([
                        //todo: array of parameters
                        'placa' => $placa,
                        'latitude' => $latitude,
                        'longitude' => $longitude,
                    ]);
                }
            }
        } catch (Throwable $e) {
            echo "Error reading Sascar positions: " . $e->getMessage() . "\n";
        }
    }
}
