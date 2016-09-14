<?php

namespace Itk\EventDatabaseClient;

use GuzzleHttp\Client as GuzzleHttpClient;
use Itk\EventDatabaseClient\Item\Event;
use Lcobucci\JWT\Parser;

class Client {
  protected $url;
  protected $username;
  protected $password;

  /**
   * Client constructor.
   *
   * @param $url The API url.
   * @param $username The API username.
   * @param $password The API password.
   */
  public function __construct($url, $username, $password) {
    $this->url = rtrim($url, '/') . '/';
    $this->username = $username;
    $this->password = $password;
  }

  /**
   * Get all events.
   */
  public function getEvents(array $query = null) {
    $this->checkToken();

    $url =  'events';
    if ($query) {
      $url .= '?' . http_build_query($query);
    }

    $res = $this->request('GET', $url);
    $json = json_decode($res->getBody(), true);
    $collection = new Collection($json, Event::class);

    return $collection;
  }

  public function createEvent(array $data) {
    $res = $this->request('POST', 'events', [
      'json' => $data,
    ]);

    if ($res->getStatusCode() == 201) {
      $data = json_decode($res->getBody(), true);
      return new Event($data);
    }

    return null;
  }

  public function readEvent($event) {
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

  public function updateEvent($event, array $data) {
    if (is_string($event) || is_numeric($event)) {
      $event = $this->readEvent($event);
    }

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

  public function deleteEvent($event) {
    $this->checkToken();

    if (is_string($event) || is_numeric($event)) {
      $event = $this->readEvent($event);
    }
    if ($event) {
      $url = $event->{'@id'};
      $res = $this->request('DELETE', $url);

      return $res->getStatusCode() == 204;
    }

    return false;
  }

	private function request($method, $url, array $data = []) {
    $this->checkToken();

    if ($this->token) {
      $data['headers'] = ['Authorization' => 'Bearer ' . $this->token];
    }

    return $this->doRequest($method, $url, $data);
	}

  private function doRequest($method, $url, array $data) {
    $client = new GuzzleHttpClient([
      'base_uri' => $this->url,
    ]);
    $res = $client->request($method, $url, $data);

    return $res;
  }

  private function renewToken() {
    $res = $this->doRequest('POST', 'login_check', [
      'form_params' => [
        '_username' => $this->username,
        '_password' => $this->password,
      ],
    ]);
    $this->token = json_decode($res->getBody())->token;
    $this->writeToken();
  }

  private function getTokenFile() {
    $filename = md5($this->url . '|' . $this->username . '|' . $this->password) . '.apitoken';
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
      } catch (\Exception $e) {
      }

      $renew = $expirationTime < new \DateTime();
    }

    if ($renew) {
      $this->renewToken();
    }
  }
}
