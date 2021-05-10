<?php
use App\Entity\EquipmentBookedDateCollection;
use PHPUnit\Framework\TestCase;
use App\Entity\EquipmentBookedDate;
use App\Entity\StationEquipment;
use App\Entity\Equipment;

class EquipmentBookedDateCollectionTest extends TestCase
{
    public function testGenerateNextEquipmentBookedDateSetsAvailableAmount()
    {

        $bookedEquipment = $this->getMockEquipmentBookedDate();

        $sut = new EquipmentBookedDateCollection();
        $sut->setInitialAmounts([$this->getMockStationEquipment()]);

        $result = $sut->generateNextEquipmentBookedDate($this->getMockStationEquipment());

        $this->assertEquals(3, $result->getAvailable());
    }

    public function testFindByDateAndEquipment()
    {
        $date = new \DateTimeImmutable('2021-05-10');

        $sut = new EquipmentBookedDateCollection();
        $sut->setInitialAmounts([$this->getMockStationEquipment()]);

        $bookedEquipment = $this->getMockEquipmentBookedDate();
        $sut->add($date, $bookedEquipment);

        $this->assertSame($bookedEquipment, $sut->findByDateAndEquipment($date, $this->getMockEquipment()));
    }

    public function testAddSetsAmountsIndex()
    {
        $date = new \DateTimeImmutable('2021-05-10');
        $bookedEquipment = $this->getMockEquipmentBookedDate();

        $sut = new EquipmentBookedDateCollection();
        $sut->setInitialAmounts([$this->getMockStationEquipment()]);
        $sut->add($date, $bookedEquipment);

        // Initial 3 plus added Equipment amount 2
        $this->assertEquals(5, $sut->getAmounts()[1]);
    }

    private function getMockEquipment()
    {
        $equipment = $this->getMockBuilder(Equipment::class)
            ->disableOriginalConstructor()
            ->getMock();

        $equipment->expects($this->any())
            ->method('getId')
            ->willReturn(1);

        return $equipment;
    }

    private function getMockStationEquipment()
    {
        $stationEquipment = $this->getMockBuilder(StationEquipment::class)
            ->disableOriginalConstructor()
            ->getMock();

        $stationEquipment->expects($this->any())
            ->method('getEquipment')
            ->willReturn($this->getMockEquipment());

        $stationEquipment->expects($this->any())
            ->method('getAmount')
            ->willReturn(3);

        return $stationEquipment;
    }

    private function getMockEquipmentBookedDate()
    {
        $eqBookedDate = $this->getMockBuilder(EquipmentBookedDate::class)
            ->disableOriginalConstructor()
            ->getMock();

        $eqBookedDate->expects($this->any())
            ->method('getEquipment')
            ->willReturn($this->getMockEquipment());

        $eqBookedDate->expects($this->any())
            ->method('getBooked')
            ->willReturn(2);

        return $eqBookedDate;
    }
}
