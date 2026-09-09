<?php

namespace App\Http\Controllers;
use phpSerial;
use Illuminate\Http\Request;
use lepiaf\SerialPort\SerialPort;
use lepiaf\SerialPort\Parser\SeparatorParser;
use lepiaf\SerialPort\Configure\TTYConfigure;

class SerialPortReadController extends Controller
{

    public function readSerialPort(){
        $serialPort = new SerialPort(new SeparatorParser(), new TTYConfigure());

        $serialPort->open("/dev/ttyACM0");
        while ($data = $serialPort->read()) {
            echo $data."\n";

            if ($data === "OK") {
                $serialPort->write("1\n");
                $serialPort->close();
            }
        }
    }


    public function getWeight()
    {
        $port = dio_open('COM4:', O_RDWR | O_NOCTTY | O_NONBLOCK);
        if (!$port) {
            die('Failed to open serial port');
        }

        $options = array(
            'baud' => 9600,
            'bits' => 8,
            'stop' => 1,
            'parity' => 0
        );

        if (!dio_tcsetattr($port, $options)) {
            die('Failed to set serial port settings');
        }

        while (true) {
            $data = dio_read($port, 256); // Read up to 256 bytes of data
            if ($data !== false) {
                // Process the received data
                // echo $data;
                 return response()->json([
                'status' => 'success',
                'data' => $data
                 ]);
            }
            usleep(100000); // Sleep for 100 milliseconds
        }

        dio_close($port);
    }

}
