<?php

namespace Palzin\CodeIgniter\Tests;

use Exception;
use Palzin\CodeIgniter\Palzin;
use Palzin\CodeIgniter\Tests\Support\TestCase;

/**
 * @internal
 */
final class HelperTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        helper(['palzin']);
    }

    public function testReturnsServiceByDefault()
    {
        $this->assertInstanceOf(Palzin::class, palzin());
    }

    public function testAddSegmentFromHelper()
    {
        $result = palzin(static function () {
            $data = [1, 2, 3, 4, 5];
            $res  = array_map(
                static fn ($el) => $el * 2,
                $data
            );

            return $res;
        }, 'test-helper');

        $this->assertSame([2, 4, 6], $result);
    }

    public function testThrownExceptions()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('A test exception from Palzin Codeigniter Package');

        throw palzin(static function () {
            $msg = 'A test exception';

            return new Exception($msg);
        }, 'test-helper-exception', 'Test Helper Exception from Palzin Codeigniter Package');
    }
}
