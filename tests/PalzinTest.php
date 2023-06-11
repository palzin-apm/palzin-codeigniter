<?php

namespace Palzin\CodeIgniter\Tests;

use Config\Services;
use Palzin\CodeIgniter\Palzin;
use Palzin\CodeIgniter\Tests\Support\TestCase;
use Palzin\Models\Segment;

/**
 * @internal
 */
final class PalzinTest extends TestCase
{
    protected function simulateEventStart(): void
    {
        \CodeIgniter\Events\Events::trigger('post_controller_constructor');
    }

    protected function simulateEventEnd(): void
    {
        \CodeIgniter\Events\Events::trigger('post_system');
    }

    public function testAutoInspectStartsTransaction()
    {
        $config    = config('Palzin');
        $palzin = Palzin::getInstance($config);
        $result    = $palzin->hasTransaction();

        $this->assertTrue($result);
    }

    public function testSetSegmentIsCorrect()
    {
        Services::resetSingle('palzin');

        $this->simulateEventStart();

        $config    = config('Palzin');
        $palzin = service('palzin');

        $this->assertInstanceOf(Segment::class, $palzin->getSegment());

        $this->simulateEventEnd();
    }

    public function testGetSegmentReturnsValidSegmentInstance()
    {
        Services::resetSingle('palzin');

        $this->simulateEventStart();

        $config    = config('Palzin');
        $palzin = service('palzin');
        $result    = $palzin->getSegment();

        $this->assertInstanceOf(Segment::class, $result);

        $this->simulateEventEnd();
    }
}
