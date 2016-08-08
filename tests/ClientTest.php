<?php

namespace Itk\EventDatabaseClient;

use PHPUnit\Framework\TestCase;
// use \Itk\EventDatabaseClient\Client;

class ClientTest extends TestCase {
  public function testCanGetEvents() {
    $client = new Client('http://event-database-api.vm/', 'api-read', 'apipass');

    $result = $client->getEvents();
    $this->assertNotNull($result);

    $events = $result->getItems();
    $this->assertNotNull($events);
  }
}
