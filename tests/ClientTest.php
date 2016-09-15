<?php

namespace Itk\EventDatabaseClient;

use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase {
  public function testCanGetEvents() {
    $client = new Client('http://event-database-api.vm/api', 'api-read', 'apipass');

    $collection = $client->getEvents();
    $this->assertNotNull($collection);

    $events = $collection->getItems();
    $this->assertNotNull($events);
  }

  public function testCanManipulateEvents() {
    $client = $this->getClient();

    $collection = $client->getEvents();
    $this->assertNotNull($collection);
    $events = $collection->getItems();
    $this->assertNotNull($events);

    $numberOfEvents = count($events);

    $event = $client->createEvent([
      'name' => 'Event name',
      'description' => __METHOD__,
    ]);
    $this->assertNotNull($event);
    $this->assertEquals('Event name', $event->name);

    $collection = $client->getEvents();
    $this->assertNotNull($collection);
    $events = $collection->getItems();
    $this->assertEquals($numberOfEvents + 1, count($events));

    $success = $client->updateEvent($event, [
      'name' => 'Event name updated',
    ]);
    $this->assertTrue($success);

    $collection = $client->getEvents();
    $this->assertNotNull($collection);
    $events = $collection->getItems();
    $this->assertEquals($numberOfEvents + 1, count($events));

    $event = $client->readEvent($event);
    $this->assertEquals('Event name updated', $event->name);

    $success = $client->deleteEvent($event);
    $this->assertTrue($success);

    $collection = $client->getEvents();
    $this->assertNotNull($collection);
    $events = $collection->getItems();
    $this->assertEquals($numberOfEvents, count($events));
  }

  public function testCanGetEventsByQuery() {
    $client = $this->getClient();

    $name = uniqid(__FUNCTION__);

    $collection = $client->getEvents(['name' => $name]);
    $this->assertNotNull($collection);
    $this->assertEquals(0, $collection->getCount());

    $event = $client->createEvent([
               'name' => $name,
             ]);

    $collection = $client->getEvents(['name' => $name]);
    $this->assertNotNull($collection);
    $this->assertEquals(1, $collection->getCount());
  }

  public function testCanGetOccurrences() {
    $client = $this->getClient();

    $collection = $client->getOccurrences();
    $this->assertNotNull($collection);
    $occurrences = $collection->getItems();
    $this->assertNotNull($occurrences);

    $numberOfOccurrences = count($occurrences);

    $event = $client->createEvent([
      'name' => 'Event name',
      'occurrences' => [
        [
          'startDate' => '2000-01-01T12:00:00',
          'endDate' => '2000-01-01T13:00:00',
        ],
        [
          'startDate' => '2100-01-01T12:00:00',
          'endDate' => '2100-01-01T13:00:00',
        ],
      ],
    ]);
    $this->assertNotNull($event);

    $collection = $client->getOccurrences();
    $this->assertNotNull($collection);
    $occurrences = $collection->getItems();
    $this->assertNotNull($occurrences);
    $this->assertEquals($numberOfOccurrences + 2, count($occurrences));
  }

  public function testCanManipulatePlaces() {
    $client = $this->getClient();

    $collection = $client->getPlaces();
    $this->assertNotNull($collection);
    $places = $collection->getItems();
    $this->assertNotNull($places);

    $numberOfPlaces = count($places);

    $place = $client->createPlace([
      'name' => 'Place name',
      'description' => __METHOD__,
    ]);
    $this->assertNotNull($place);
    $this->assertEquals('Place name', $place->name);

    $collection = $client->getPlaces();
    $this->assertNotNull($collection);
    $places = $collection->getItems();
    $this->assertEquals($numberOfPlaces + 1, count($places));

    $success = $client->updatePlace($place, [
      'name' => 'Place name updated',
    ]);
    $this->assertTrue($success);

    $collection = $client->getPlaces();
    $this->assertNotNull($collection);
    $places = $collection->getItems();
    $this->assertEquals($numberOfPlaces + 1, count($places));

    $place = $client->readPlace($place);
    $this->assertEquals('Place name updated', $place->name);

    $success = $client->deletePlace($place);
    $this->assertTrue($success);

    $collection = $client->getPlaces();
    $this->assertNotNull($collection);
    $places = $collection->getItems();
    $this->assertEquals($numberOfPlaces, count($places));
  }

  private function getClient($readOnly = false) {
    return new Client('http://event-database-api.vm/api/', $readOnly ? 'api-read' : 'api-write', 'apipass');
  }
}
