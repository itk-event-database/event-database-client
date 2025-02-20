<?php

namespace Itk\EventDatabaseClient;

use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\ClientException as GuzzleClientException;
use Itk\EventDatabaseClient\Exception\ClientException;
use Itk\EventDatabaseClient\Item\Event;
use Itk\EventDatabaseClient\Item\Occurrence;
use Itk\EventDatabaseClient\Item\Place;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Encoding\JoseEncoder;

class Client
{
    protected $url;
    protected $apiKey;

    /**
     * Client constructor.
     *
     * @param $url The API url.
     * @param $apiKey The API apikey.
     */
    public function __construct($url, $apiKey)
    {
        $this->url = rtrim($url, '/') . '/';
        $this->apiKey = $apiKey;
    }

    /**
     * Get all events by an optional query.
     *
     * @param array $query
     *   The event query.
     *
     * @return Collection
     *   A event collection.
     */
    public function getEvents(array $query = null)
    {
        $url = $this->getUrl('events', $query);
        $res = $this->request('GET', $url);
        $json = json_decode($res->getBody(), true);
        $collection = new Collection($json, Event::class);

        return $collection;
    }

  /**
   * Get all events by an optional query presented as string.
   * 
   * @param string|NULL $queryString
   *
   * @return \Itk\EventDatabaseClient\Collection
   */
    public function getEventsFromString(string $queryString = null)
    {
      $res = $this->request('GET', $queryString);
      $json = json_decode($res->getBody(), true);
      $collection = new Collection($json, Event::class);

      return $collection;
    }

    /**
     * Create an event.
     *
     * @param array $data
     *   The event data.
     *
     * @return \Itk\EventDatabaseClient\Item\Event|null
     *   The event if successfully created. Otherwise null.
     */
    public function createEvent(array $data)
    {
        $eventData = $this->linkId($data);

        $res = $this->request('POST', 'events', [
            'json' => $eventData,
        ]);

        if ($res->getStatusCode() == 201) {
            $data = json_decode($res->getBody(), true);
            return new Event($data);
        }

        return null;
    }

    /**
     * Recursively 'link' data by converting 'id' keys to '@id' for JSON-LD compliance
     *
     * @param $data
     *
     * @return array
     */
    private function linkId($data) {
        $linkedData = [];
        foreach ($data as $name => $value) {
            if ($name === 'id') {
                $linkedData['@id'] = $value;
            } elseif (is_array($value)) {
                $linkedData[$name] = $this->linkId($value);
            } else {
                $linkedData[$name] = $value;
            }
        }

        return $linkedData;
    }

    /**
     * Read an event.
     *
     * The $event parameter can be a numeric event id, an API @id (e.g. /api/events/87)
     * or an object with an '@id' property.
     *
     * @param mixed $event
     *   The event or an event id.
     * @return \Itk\EventDatabaseClient\Item\Event|null
     *   The event if it exists. Otherwise null.
     */
    public function readEvent($event)
    {
        if (is_numeric($event)) {
            $url = 'events/' . $event;
        } elseif (is_string($event)) {
            $url = $event;
        } else {
            $url = $event->{'@id'};
        }

        $res = $this->request('GET', $url);

        if ($res->getStatusCode() == 200) {
            $data = json_decode($res->getBody(), true);
            return new Event($data);
        }

        return null;
    }

    /**
     * Read an occurrence.
     *
     * The $occurrence parameter can be a numeric occurrence id, an API @id (e.g. /api/occurrences/89)
     * or an object with an '@id' property.
     *
     * @param mixed $occurrence
     *   The occurrence or an occurrence id.
     * @return \Itk\EventDatabaseClient\Item\Occurrence|null
     *   The occurrence if it exists. Otherwise null.
     */
    public function readOccurrence($occurrence)
    {
        if (is_numeric($occurrence)) {
            $url = 'occurrences/' . $occurrence;
        } elseif (is_string($occurrence)) {
            $url = $occurrence;
        } else {
            $url = $occurrence->{'@id'};
        }

        $res = $this->request('GET', $url);

        if ($res->getStatusCode() == 200) {
            $data = json_decode($res->getBody(), true);
            return new Occurrence($data);
        }

        return null;
    }

    /**
     * Update an event.
     *
     * @see readEvent for details on the $event parameter.
     *
     * @param mixed $event
     *   The event or an event id.
     * @param array $data
     *   The data to update on the event.
     *
     * @return bool
     *   Whether the event was successfully updated.
     */
    public function updateEvent($event, array $data)
    {
        $data = $this->linkId($data);
        $event = $this->readEvent($event);

        if ($event) {
            $url = $event->{'@id'};
            $eventData = [];
            foreach ($event as $name => $value) {
                if (!preg_match('/^@/', $name)) {
                    $eventData[$name] = $value;
                }
            }

            $res = $this->request('PUT', $url, [
                       'json' => $data + $eventData,
                   ]);

            return $res->getStatusCode() == 200;
        }

        return false;
    }

    /**
     * Delete an event.
     *
     * @param $event
     *   The event or an event id.
     * @return bool
     *   Whether the event was succesfully deleted.
     */
    public function deleteEvent($event)
    {
        $event = $this->readEvent($event);

        if ($event) {
            $url = $event->{'@id'};
            $res = $this->request('DELETE', $url);

            return $res->getStatusCode() == 204;
        }

        return false;
    }

    /**
     * Get all occurrences by an optional query.
     *
     * @param array $query
     *   The occurrence query.
     *
     * @return Collection
     *   A occurrence collection.
     */
    public function getOccurrences(array $query = null)
    {
        $url = $this->getUrl('occurrences', $query);
        $res = $this->request('GET', $url);
        $json = json_decode($res->getBody(), true);
        $collection = new Collection($json, Occurrence::class);

        return $collection;
    }

    /**
     * Get all places by an optional query.
     *
     * @param array $query
     *   The place query.
     *
     * @return Collection
     *   A place collection.
     */
    public function getPlaces(array $query = null)
    {
        $url = $this->getUrl('places', $query);
        $res = $this->request('GET', $url);
        $json = json_decode($res->getBody(), true);
        $collection = new Collection($json, Place::class);

        return $collection;
    }

    /**
     * Create an place.
     *
     * @param array $data
     *   The place data.
     *
     * @return \Itk\PlaceDatabaseClient\Item\Place|null
     *   The place if successfully created. Otherwise null.
     */
    public function createPlace(array $data)
    {
        $res = $this->request('POST', 'places', [
            'json' => $data,
        ]);

        if ($res->getStatusCode() == 201) {
            $data = json_decode($res->getBody(), true);
            return new Place($data);
        }

        return null;
    }

    /**
     * Read an place.
     *
     * The $place parameter can be a numeric place id, an API @id  (e.g. /api/places/87)
     * or an object with an '@id' property.
     *
     * @param mixed $place
     *   The place or an place id.
     * @return \Itk\PlaceDatabaseClient\Item\Place|null
     *   The place if it exists. Otherwise null.
     */
    public function readPlace($place)
    {
        if (is_numeric($place)) {
            $url = 'places/' . $place;
        } elseif (is_string($place)) {
            $url = $place;
        } else {
            $url = $place->{'@id'};
        }

        $res = $this->request('GET', $url);

        if ($res->getStatusCode() == 200) {
            $data = json_decode($res->getBody(), true);
            return new Place($data);
        }

        return null;
    }

    /**
     * Update an place.
     *
     * @see readPlace for details on the $place parameter.
     *
     * @param mixed $place
     *   The place or an place id.
     * @param array $data
     *   The data to update on the place.
     *
     * @return bool
     *   Whether the place was successfully updated.
     */
    public function updatePlace($place, array $data)
    {
        $place = $this->readPlace($place);

        if ($place) {
            $url = $place->{'@id'};
            $placeData = [];
            foreach ($place as $name => $value) {
                if (!preg_match('/^@/', $name)) {
                    $placeData[$name] = $value;
                }
            }

            $res = $this->request('PUT', $url, [
                       'json' => $data + $placeData,
                   ]);

            return $res->getStatusCode() == 200;
        }

        return false;
    }

    /**
     * Delete an place.
     *
     * @param $place
     *   The place or an place id.
     * @return bool
     *   Whether the place was succesfully deleted.
     */
    public function deletePlace($place)
    {
        $place = $this->readPlace($place);

        if ($place) {
            $url = $place->{'@id'};
            $res = $this->request('DELETE', $url);

            return $res->getStatusCode() == 204;
        }

        return false;
    }

    private function getUrl($url, array $query = null)
    {
        if ($query) {
          foreach ($query as $key => $value) {
            if (is_array($value)) {
              $query[$key] = implode(',', $value);
            }
            if (is_bool($value)) {
              $query[$key] = ($value) ? 'true' : 'false';
            }
          }
            $url .= '?' . http_build_query($query);
        }
        
        return $url;
    }

    private function request($method, $url, array $data = [])
    {
        $data['headers'] = [
          'X-Api-Key' => $this->apiKey,
        ];

        if($method === 'POST' || $method === 'PUT') {
            $data['headers']['Content-Type'] = 'application/ld+json';
            $data['headers']['Accept'] = 'application/ld+json';
        }

        return $this->doRequest($method, $url, $data);
    }

    private function doRequest($method, $url, array $data)
    {
        try {
            $client = new GuzzleHttpClient([
                          'base_uri' => $this->url,
                      ]);
            $res = $client->request($method, $url, $data);

            return $res;
        } catch (GuzzleClientException $e) {
            throw new ClientException($e->getMessage(), $e->getCode(), $e);
        }
    }

}
