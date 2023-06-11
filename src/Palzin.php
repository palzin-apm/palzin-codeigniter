<?php

namespace Palzin\CodeIgniter;

use CodeIgniter\Config\BaseConfig;
use Palzin\Configuration;
use Palzin\Palzin as PalzinLibrary;

use Palzin\Models\Segment;
use Throwable;

/**
 * Allows developers to use the palzin library in CI4
 */
class Palzin extends PalzinLibrary
{
    /**
     * The latest version of the client library.
     *
     * @var string
     */
    public const VERSION = '0.2.1';

    private Segment $Segment;

    public static function getInstance(BaseConfig $config)
    {
        $requestURI = $_SERVER['REQUEST_URI'] ?? '';

        $configuration = (new Configuration($config->PalzinMonitorAPMIngestionKey))
            ->setEnabled($config->Enable ?? true)
            ->setUrl($config->URL ?? 'https://demo.palzin.app')
            ->setVersion(self::VERSION)
            ->setTransport($config->Transport ?? 'async')
            ->setOptions($config->Options ?? [])
            ->setMaxItems($config->MaxItems ?? 100);

        $palzin = new self($configuration);

        // Only start a transation if AutoInspect is set to true
        if ($config->AutoInspect) {
            $pathInfo = explode('?', $requestURI);
            $path     = array_shift($pathInfo);

            $palzin->startTransaction($path);

            if ($config->LogUnhandledExceptions) {
                set_exception_handler([$palzin, 'recordUnhandledException']);
                restore_exception_handler();
            }
        }

        return $palzin;
    }

    public function recordUnhandledException(Throwable $exception)
    {
        $this->reportException($exception);
    }

    public function setSegment(Segment $segment)
    {
        $this->Segment = $segment;
    }

    public function getSegment()
    {
        return $this->Segment;
    }
}
