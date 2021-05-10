<?php
use PHPUnit\Framework\TestCase;
use App\Entity\EquipmentBookedDate;
use App\Entity\StationEquipment;
use App\Entity\Equipment;

class EquipmentBookedDateTest extends TestCase
{
    public function testFromStationEquipmentSetsAvailable()
    {
        $stationEquipment = $this->getMockStationEquipment();

        $stationEquipment->expects($this->any())
            ->method('getAmount')
            ->willReturn(2);

        $stationEquipment->expects($this->any())
            ->method('getEquipment')
            ->willReturn($this->getMockEquipment());

        $sut = EquipmentBookedDate::fromStationEquipment($stationEquipment);

        $this->assertSame(2, $sut->getAvailable());
    }

    public function testIsEnoughTrue()
    {
        $sut = new EquipmentBookedDate();
        $sut->setAvailable(10);
        $this->assertTrue($sut->isEnough(4));
    }

    public function testIsEnoughFalse()
    {
        $sut = new EquipmentBookedDate();
        $sut->setAvailable(2);
        $this->assertFalse($sut->isEnough(4));
    }

    private function getMockEquipment()
    {
        $equipment = $this->getMockBuilder(Equipment::class)
            ->disableOriginalConstructor()
            ->getMock();
        return $equipment;
    }

    private function getMockStationEquipment()
    {
        $stationEquipment = $this->getMockBuilder(StationEquipment::class)
            ->disableOriginalConstructor()
            ->getMock();
        return $stationEquipment;
    }
}
