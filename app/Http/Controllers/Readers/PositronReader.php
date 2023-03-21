<?php

namespace App\Http\Controllers\Readers;

use App\Models\Position;
use Exception;
use Illuminate\Support\Facades\DB;
use Stomp\Client;
use Stomp\StatefulStomp;
use Stomp\Transport\Frame;
use Stomp\Transport\Message;
use Throwable;

class PositronReader
{
    /**
     * @throws Exception
     */
    public function integra(): void
    {
        try {
            $login = 'CARGAPESADA_WS';
            $password = 'TxIyMbEe';
            $broker = "tcp://integracao-cargo.positronrt.com.br:63616";

            // Change the connection string to use the STOMP protocol
            $client = new Client($broker);
            // var_dump($client);
            // $client = new Client("stomp://CTS1C100165:61613");

            $client->setLogin($login, $password);

            $client->connect();
            $stomp = new StatefulStomp($client);
            $stomp->subscribe('/queue/pos_02843420000165');
            $stomp->subscribe('/queue/ema_02843420000165');
            echo "Listening to the Positron Queues...\n";

            while (true) {
                $msg = $stomp->read();
                if ($msg != null) {
                    try {
                        $this->processMessage($msg);
                        $stomp->ack($msg);
                    } catch (Throwable $e) {
                        echo "Error processing message: " . $e->getMessage() . "\n";
                        $stomp->nack($msg);
                    }
                }
                sleep(1);
            }
        } catch (Throwable $e) {
            echo "Erro ao processar a fila: " . $e->getMessage() . "\n";
        }
    }

    public function processMessage(Frame $msg): void
    {
        $xml = $msg->getBody();
        $data = simplexml_load_string($xml);

        if (isset($data->vehicle->plate) && isset($data->latitude) && isset($data->longitude)) {
            $placa = (string)$data->vehicle->plate;
            $latitude = (float)$data->latitude;
            $longitude = (float)$data->longitude;

            if ($placa && $latitude && $longitude) {
                echo "Inserting or updating data in the database: placa={$placa}, latitude={$latitude}, longitude={$longitude}\n";
                DB::transaction(function () use ($placa, $latitude, $longitude) {
                    Position::updateOrCreate(
                        ['placa' => $placa],
                        [
                            'latitude' => $latitude,
                            'longitude' => $longitude,
                        ]
                    );
                });
            }
        } else {
            echo "Message received but missing required data.\n";
        }
    }
}
