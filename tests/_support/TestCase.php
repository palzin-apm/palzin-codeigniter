<?php

namespace Palzin\CodeIgniter\Tests\Support;

use CodeIgniter\Test\CIUnitTestCase;
use Config\Services;
use Palzin\CodeIgniter\Palzin;

abstract class TestCase extends CIUnitTestCase
{
    /**
     * @var Palzin
     */
    protected $palzin;

    /**
     * Sets up the ArrayHandler for faster & easier tests.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $config          = config('Palzin');
        $this->palzin = Palzin::getInstance($config);

        Services::injectMock('palzin', $this->palzin);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->resetServices();
    }
}
