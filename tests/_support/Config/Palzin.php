<?php

namespace Palzin\CodeIgniter\Tests\Support\Config;

use CodeIgniter\Config\BaseConfig;

/**
 * @internal
 */
final class Palzin extends BaseConfig
{
    public $AutoInspect  = true;
    public $PalzinMonitorAPMIngestionKey = 'test_ingestion_key_value';
}
