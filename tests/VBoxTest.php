<?php
namespace ColorThief\Test;

use ColorThief\VBox;
use ColorThief\ColorThief;

class VBoxTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var VBox
     */
    protected $vbox;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->vbox = new VBox(0, 255, 0, 255, 0, 255, null);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        $this->vbox = null;
    }

    /**
     * @covers ColorThief\VBox::volume
     * @todo   Implement testVolume().
     */
    public function testVolume()
    {
        $this->vbox->r1 = 0;
        $this->vbox->r2 = 0;
        $this->vbox->g1 = 0;
        $this->vbox->g2 = 0;
        $this->vbox->b1 = 0;
        $this->vbox->b2 = 0;

        $this->assertSame(1, $this->vbox->volume());

        $this->vbox->r2 = 255;
        $this->vbox->g2 = 255;
        $this->vbox->b2 = 255;

        // Previous result should be cached.
        $this->assertSame(1, $this->vbox->volume());
        // Forcing refresh should now give the right result
        $this->assertSame(16777216, $this->vbox->volume(true));
    }

    /**
     * @covers ColorThief\VBox::copy
     */
    public function testCopy()
    {
        $this->vbox->histo = array (25 => 8);
        $copy = $this->vbox->copy();

        $this->assertInstanceOf('ColorThief\VBox', $copy);
        $this->assertSame($this->vbox->r1, $copy->r1);
        $this->assertSame($this->vbox->r2, $copy->r2);
        $this->assertSame($this->vbox->g1, $copy->g1);
        $this->assertSame($this->vbox->g2, $copy->g2);
        $this->assertSame($this->vbox->b1, $copy->b1);
        $this->assertSame($this->vbox->b2, $copy->b2);
        $this->assertSame($this->vbox->histo, $copy->histo);
    }

    /**
     * @covers ColorThief\VBox::contains
     */
    public function testContains()
    {
        $this->vbox->r1 = 225 >> ColorThief::RSHIFT;
        $this->vbox->r2 = 247 >> ColorThief::RSHIFT;
        $this->vbox->g1 = 180 >> ColorThief::RSHIFT;
        $this->vbox->g2 = 189 >> ColorThief::RSHIFT;
        $this->vbox->b1 = 158 >> ColorThief::RSHIFT;
        $this->vbox->b2 = 158 >> ColorThief::RSHIFT;

        $this->assertTrue($this->vbox->contains(array(225, 190, 158)));

        $this->assertFalse($this->vbox->contains(array(200, 189, 158)));
        $this->assertFalse($this->vbox->contains(array(255, 189, 158)));

        $this->assertFalse($this->vbox->contains(array(225, 50, 158)));
        $this->assertFalse($this->vbox->contains(array(225, 200, 158)));

        $this->assertFalse($this->vbox->contains(array(225, 189, 100)));
        $this->assertFalse($this->vbox->contains(array(225, 189, 200)));

    }
}
