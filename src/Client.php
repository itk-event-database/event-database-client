<?php

namespace Itk\EventDatabaseClient;

use Itk\EventDatabaseClient\Item\Event;
use Lcobucci\JWT\Parser;

class Client {
  protected $url;
  protected $apiUrl;
  protected $username;
  protected $password;

  private function renewToken() {
    $url = $this->apiUrl . '/login_check';

    $client = new \GuzzleHttp\Client();
    $res = $client->request('POST', $url, [
             'form_params' => [
               '_username' => $this->username,
               '_password' => $this->password,
             ],
           ]);

    $this->token = json_decode($res->getBody())->token;

    $this->writeToken();
  }

  private function getTokenFile() {
    $filename = md5($this->apiUrl . '|' . $this->username) . '.apitoken';
    return sys_get_temp_dir() . '/' . $filename;
  }

  private function writeToken() {
    file_put_contents($this->getTokenFile(), $this->token);
  }

  private function readToken() {
    $this->token = file_exists($this->getTokenFile()) ? file_get_contents($this->getTokenFile()) : null;
  }

  private function checkToken() {
    $renew = false;
    $this->readToken();
    if (!$this->token) {
      $renew = true;
    } else {
      $token = (new Parser())->parse((string)$this->token);
      $expirationTime = new \DateTime();
      try {
        $timestamp = $token->getClaim('exp');
        $expirationTime->setTimestamp($timestamp);
      } catch (\Exception $e) {}

      $renew = $expirationTime < new \DateTime();
    }

    if ($renew) {
      $this->renewToken();
    }
  }

  public function __construct($url, $username, $password) {
    $this->url = $url;
    $this->apiUrl = rtrim($this->url, '/') . '/api';
    $this->username = $username;
    $this->password = $password;
  }

  public function getEvents(array $query = null) {
    $this->checkToken();

    $url = $this->apiUrl . '/events';
    if ($query) {
      $url .= '?' . http_build_query($query);
    }

    $client = new \GuzzleHttp\Client();
    $res = $client->request('GET', $url, [
             'headers' => [
               'Authorization' => 'Bearer ' . $this->token,
             ],
           ]);

    $json = json_decode($res->getBody(), true);
    $collection = new Collection($json, Event::class);

    return $collection;
  }

  public function getEvent($id) {
    $this->checkToken();

    $url = $this->apiUrl . '/events/' . $id;

    try {
      $client = new \GuzzleHttp\Client();
      $res = $client->request('GET', $url, [
               'headers' => [
                 'Authorization' => 'Bearer ' . $this->token,
               ],
             ]);

      $json = json_decode($res->getBody(), true);
      $event = new Event($json);

      return $event;
    } catch (\GuzzleHttp\Exception\ClientException $e) {
      throw $e;
    }

    return null;
  }

  public function createEvent(array $data) {
    $this->checkToken();

    $url = $this->apiUrl . '/events';

    $client = new \GuzzleHttp\Client();
    $res = $client->request('POST', $url, [
             'headers' => [
               'Authorization' => 'Bearer ' . $this->token,
             ],
             'json' => $data,
           ]);

    if ($res->getStatusCode() == 201) {
      return $this->parseEvent($res->getBody());
    }

    return null;
  }

  private function parseEvent($json) {
    return json_decode($json);
  }

  public function updateEvent($event, array $data) {
    if (is_string($event) || is_numeric($event)) {
      $event = $this->getEvent($event);
    }

    if ($event) {
      $url = $this->url . $event->{'@id'};

      $eventData = [];
      foreach ($event as $name => $value) {
        if (!preg_match('/^@/', $name)) {
          $eventData[$name] = $value;
        }
      }

      $client = new \GuzzleHttp\Client();
      $res = $client->request('PUT', $url, [
               'headers' => [
                 'Authorization' => 'Bearer ' . $this->token,
               ],
               'json' => $data + $eventData,
             ]);

      return $res->getStatusCode() == 200;
    }

    return false;
  }

  public function deleteEvent($event) {
    if (is_string($event) || is_numeric($event)) {
      $event = $this->getEvent($event);
    }
    if ($event) {
      $url = $this->url . $event->{'@id'};

      $client = new \GuzzleHttp\Client();
      $res = $client->request('DELETE', $url, [
               'headers' => [
                 'Authorization' => 'Bearer ' . $this->token,
               ],
             ]);

      return $res->getStatusCode() == 204;
    }

    return false;
  }
}
