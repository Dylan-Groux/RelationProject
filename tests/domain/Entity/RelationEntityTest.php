<?php
use PHPUnit\Framework\TestCase;
use App\Domain\Entity\Relation;

class RelationEntityTest extends TestCase
{
    public function testStatutLabelDefaultForInvalidStatut()
    {
        $relation = new Relation();
        $relation->setStatut(5); // valeur supérieure à 4
        $this->assertEquals('Unknown', $relation->getStatutLabel());
    }

    public function testStatutLabelAvailable()
    {
        $relation = new Relation();
        $relation->setStatut(Relation::STATUS_AVAILABLE);
        $this->assertEquals('Available', $relation->getStatutLabel());
    }

    public function testStatutLabelPending()
    {
        $relation = new Relation();
        $relation->setStatut(Relation::STATUS_PENDING);
        $this->assertEquals('Pending', $relation->getStatutLabel());
    }

    public function testStatutLabelRejected()
    {
        $relation = new Relation();
        $relation->setStatut(Relation::STATUS_REJECTED);
        $this->assertEquals('Rejected', $relation->getStatutLabel());
    }

    public function testStatutLabelErrored()
    {
        $relation = new Relation();
        $relation->setStatut(Relation::STATUS_ERRORED);
        $this->assertEquals('Errored', $relation->getStatutLabel());
    }

    public function testDateTimeInitialization()
    {
        $relation = new Relation();
        $this->assertInstanceOf(\DateTime::class, $relation->getCreatedAt());
        $this->assertInstanceOf(\DateTime::class, $relation->getUpdatedAt());
    }

    public function testDateTimeNullInitialization()
    {
        $relation = new Relation();
        $this->assertNotNull($relation->getCreatedAt());
        $this->assertNotNull($relation->getUpdatedAt());
    }

    public function testSetCreatedAtWithInvalidTypeThrowsError()
    {
        $relation = new Relation();
        $this->expectException(TypeError::class);
        $relation->setCreatedAt('****'); // Doit lever une TypeError
    }
}
