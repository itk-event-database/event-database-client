<?php

namespace Itk\EventDatabaseClient;

class Collection {
  protected $data;
  protected $items;

  public function __construct(array $data, $memberClassName = null) {
    $this->data = $data;

    $this->items = [];
    if ($memberClassName) {
      foreach ($this->data['hydra:member'] as $item) {
        $this->items[] = new $memberClassName($item);
      }
    } else {
      $this->items = $this->data['hydra:member'];
    }
  }

  public function getItems() {
    return $this->items;
  }

  public function getCount() {
    return count($this->items);
  }

  public function __call($name, array $arguments) {
    if (preg_match('/^get(?<name>.+)/', $name, $matches)) {
      $key = lcfirst($matches['name']);
      switch ($key) {
        case 'first':
        case 'next':
        case 'previous':
        case 'last':
          $hydraKey = 'hydra:' . $key;
          return isset($this->data['hydra:view'][$hydraKey]) ? $this->data['hydra:view'][$hydraKey] : null;
        case 'totalItems':
          $hydraKey = 'hydra:' . $key;
          return isset($this->data[$hydraKey]) ? $this->data[$hydraKey] : null;
      }
    }

    throw new \Exception('Call to undefined method ' . get_class($this) . '::' . $name . '()');
  }
}

/*
{
  "@context": "/api/contexts/Event",
  "@id": "/api/events",
  "@type": "hydra:Collection",
  "hydra:member": [
    {
      "@id": "/api/events/2",
      "@type": "http://schema.org/Event",
      "occurrences": [],
      "description": null,
      "image": null,
      "name": "AdminBundle\\Controller\\FeedController::indexAction",
      "url": null
    },
    {
      "@id": "/api/events/3",
      "@type": "http://schema.org/Event",
      "occurrences": [],
      "description": null,
      "image": null,
      "name": "AdminBundle\\Controller\\FeedController::indexAction",
      "url": null
    },
    {
      "@id": "/api/events/4",
      "@type": "http://schema.org/Event",
      "occurrences": [],
      "description": null,
      "image": null,
      "name": "AdminBundle\\Controller\\FeedController::indexAction",
      "url": null
    },
    {
      "@id": "/api/events/5",
      "@type": "http://schema.org/Event",
      "occurrences": [],
      "description": null,
      "image": null,
      "name": "AdminBundle\\Controller\\FeedController::indexAction",
      "url": null
    },
    {
      "@id": "/api/events/6",
      "@type": "http://schema.org/Event",
      "occurrences": [],
      "description": null,
      "image": null,
      "name": "AdminBundle\\Controller\\FeedController::indexAction",
      "url": null
    },
    {
      "@id": "/api/events/7",
      "@type": "http://schema.org/Event",
      "occurrences": [],
      "description": null,
      "image": null,
      "name": "AdminBundle\\Controller\\FeedController::indexAction",
      "url": null
    },
    {
      "@id": "/api/events/8",
      "@type": "http://schema.org/Event",
      "occurrences": [],
      "description": null,
      "image": null,
      "name": "AdminBundle\\Controller\\FeedController::indexAction",
      "url": null
    },
    {
      "@id": "/api/events/9",
      "@type": "http://schema.org/Event",
      "occurrences": [
        {
          "@id": "/api/occurrences/1",
          "@type": "Occurrence",
          "event": "/api/events/9",
          "startDate": "2000-01-01T00:00:00+01:00",
          "endDate": null,
          "venue": null
        }
      ],
      "description": "The first event",
      "image": null,
      "name": "Big bang",
      "url": null
    },
    {
      "@id": "/api/events/10",
      "@type": "http://schema.org/Event",
      "occurrences": [
        {
          "@id": "/api/occurrences/1540",
          "@type": "Occurrence",
          "event": "/api/events/10",
          "startDate": "2015-08-29T14:00:00+02:00",
          "endDate": "2015-08-29T14:00:00+02:00",
          "venue": null
        }
      ],
      "description": "<div>Komponisterne Niels Lyhne Løkkegaard og Jacob Kirkegaard har ladet sig inspirere af danskfærøske Niels Finsen, som modtog Nobelprisen for sit arbejde med lysets indvirkning på kroppen, og har skabt fællesværket ’LIGHTS’, som har urpremiere på Dokk1. ",
      "image": null,
      "name": "Festugen på Dokk1: LIGHTS",
      "url": "https://www.aakb.dk/node/7649"
    },
    {
      "@id": "/api/events/11",
      "@type": "http://schema.org/Event",
      "occurrences": [
        {
          "@id": "/api/occurrences/1541",
          "@type": "Occurrence",
          "event": "/api/events/11",
          "startDate": "2015-08-27T12:00:00+02:00",
          "endDate": "2015-08-27T14:30:00+02:00",
          "venue": null
        }
      ],
      "description": "<div class=\"DocumentAbstract\"> </div>
<div class=\"DocumentAbstract\"> </div>
<div class=\"DocumentAbstract\"> </div>
<div class=\"DocumentAbstract\"> </div>
<div class=\"DocumentAbstract\"> </div>
<div class=\"DocumentAbstract\"> </div>
<div class=\"DocumentAbstrac",
      "image": null,
      "name": "Fars Legestue",
      "url": "https://www.aakb.dk/node/7662"
    },
    {
      "@id": "/api/events/12",
      "@type": "http://schema.org/Event",
      "occurrences": [
        {
          "@id": "/api/occurrences/1542",
          "@type": "Occurrence",
          "event": "/api/events/12",
          "startDate": "2015-09-03T12:00:00+02:00",
          "endDate": "2015-09-03T14:30:00+02:00",
          "venue": null
        }
      ],
      "description": "<div class=\"DocumentAbstract\"> </div>
<div class=\"DocumentAbstract\"> </div>
<div class=\"DocumentAbstract\"> </div>
<div class=\"DocumentAbstract\"> </div>
<div class=\"DocumentAbstract\"> </div>
<div class=\"DocumentAbstract\"> </div>
<div class=\"DocumentAbstrac",
      "image": null,
      "name": "Fars Legestue",
      "url": "https://www.aakb.dk/node/7664"
    },
    {
      "@id": "/api/events/13",
      "@type": "http://schema.org/Event",
      "occurrences": [
        {
          "@id": "/api/occurrences/1543",
          "@type": "Occurrence",
          "event": "/api/events/13",
          "startDate": "2016-11-14T10:00:00+01:00",
          "endDate": "2016-11-14T10:45:00+01:00",
          "venue": null
        }
      ],
      "description": "<p>Til babyrytmik stimuleres alle sanser gennem sang, bevægelse, lydlege med rasleæg og meget mere. Babyer synger, før de taler! De leger med lyde og synger med i deres eget pludrende sprog. Når man synger og danser med sit barn, skabes et unikt samvær, h",
      "image": null,
      "name": "Babyrytmik",
      "url": "https://www.aakb.dk/node/11399"
    },
    {
      "@id": "/api/events/14",
      "@type": "http://schema.org/Event",
      "occurrences": [
        {
          "@id": "/api/occurrences/1544",
          "@type": "Occurrence",
          "event": "/api/events/14",
          "startDate": "2016-12-06T10:30:00+01:00",
          "endDate": "2016-12-06T11:30:00+01:00",
          "venue": null
        }
      ],
      "description": "<p>Forlaget Klim og Aarhus Kommunes Biblioteker byder indenfor til fire formiddage med oplæsning for voksne. Der er god kaffe til ganen og stor litteratur til øregangene.</p>
<p>Sidste roman vi kaster os over er Nathaniel Hawthornes <em>Huset med de syv g",
      "image": null,
      "name": "Bruger du ord i kaffen?",
      "url": "https://www.aakb.dk/node/11268"
    },
    {
      "@id": "/api/events/15",
      "@type": "http://schema.org/Event",
      "occurrences": [
        {
          "@id": "/api/occurrences/1545",
          "@type": "Occurrence",
          "event": "/api/events/15",
          "startDate": "2016-11-01T10:30:00+01:00",
          "endDate": "2016-11-01T11:30:00+01:00",
          "venue": null
        }
      ],
      "description": "<p>Forlaget Klim og Aarhus Kommunes Biblioteker byder indenfor til fire formiddage med oplæsning for voksne. Der er god kaffe til ganen og stor litteratur til øregangene.</p>
<p>Vi lytter til den prisbelønnede kinesiske forfatters roman fra 2015 <em>At le",
      "image": null,
      "name": "Bruger du ord i kaffen?",
      "url": "https://www.aakb.dk/node/11269"
    },
    {
      "@id": "/api/events/16",
      "@type": "http://schema.org/Event",
      "occurrences": [
        {
          "@id": "/api/occurrences/1546",
          "@type": "Occurrence",
          "event": "/api/events/16",
          "startDate": "2016-02-15T13:00:00+01:00",
          "endDate": "2016-02-15T15:00:00+01:00",
          "venue": null
        }
      ],
      "description": "<p>Vi tilbyder individuel hjælp til, at du kan lære din digitale postkasse bedre at kende. Hvordan får du f.eks. besked, når der er nyt i din postkasse? Påmindelser om din tid på hospitalet? Kan du modtage post fra andre end fra det offentlige? Og hvordan",
      "image": null,
      "name": "Lær din digitale postkasse bedre at kende",
      "url": "https://www.aakb.dk/node/8578"
    },
    {
      "@id": "/api/events/17",
      "@type": "http://schema.org/Event",
      "occurrences": [
        {
          "@id": "/api/occurrences/1547",
          "@type": "Occurrence",
          "event": "/api/events/17",
          "startDate": "2015-10-22T12:00:00+02:00",
          "endDate": "2015-10-22T14:30:00+02:00",
          "venue": null
        }
      ],
      "description": "<div class=\"DocumentAbstract\">Fars Legestue er for fædre og børn i alderen 0-3 år. Aktiviteterne i Fars Legestue er handlingsprægede, og der er mulighed for at lege og tumle.</div>
<div class=\"Document\" lang=\"da\" xml:lang=\"da\">
<div class=\"DocumentText\">
",
      "image": null,
      "name": "Fars Legestue",
      "url": "https://www.aakb.dk/node/7673"
    },
    {
      "@id": "/api/events/18",
      "@type": "http://schema.org/Event",
      "occurrences": [
        {
          "@id": "/api/occurrences/1548",
          "@type": "Occurrence",
          "event": "/api/events/18",
          "startDate": "2016-10-04T10:30:00+02:00",
          "endDate": "2016-10-04T11:30:00+02:00",
          "venue": null
        }
      ],
      "description": "<p>Forlaget Klim og Aarhus Kommunes Biblioteker byder indenfor til fire formiddage med oplæsning for voksne. Der er god kaffe til ganen og stor litteratur til øregangene.</p>
<p>Denne gang læses der op fra den japanske stjerneforfatter Haruki Murakamis sk",
      "image": null,
      "name": "Bruger du ord i kaffen?",
      "url": "https://www.aakb.dk/node/11270"
    },
    {
      "@id": "/api/events/19",
      "@type": "http://schema.org/Event",
      "occurrences": [
        {
          "@id": "/api/occurrences/1549",
          "@type": "Occurrence",
          "event": "/api/events/19",
          "startDate": "2016-02-18T10:00:00+01:00",
          "endDate": "2016-02-18T10:30:00+01:00",
          "venue": null
        }
      ],
      "description": "<p><strong>Børneteateravisen skrev:</strong> \"En lille sort teatervogn er Barkentins verden. Lidt lige som en lirekassevogn, men her med et akvarium bag et rødt teaterforhæng som hovedattraktion. Ovenpå står to gummistøvler, og så snart Barkentin får fat ",
      "image": null,
      "name": "Børneteaterforestilling: Indeni",
      "url": "https://www.aakb.dk/node/8565"
    },
    {
      "@id": "/api/events/20",
      "@type": "http://schema.org/Event",
      "occurrences": [
        {
          "@id": "/api/occurrences/1550",
          "@type": "Occurrence",
          "event": "/api/events/20",
          "startDate": "2016-09-06T10:30:00+02:00",
          "endDate": "2016-09-06T10:30:00+02:00",
          "venue": null
        }
      ],
      "description": "<p>Forlaget Klim og Aarhus Kommunes Biblioteker byder indenfor til fire formiddage med oplæsning for voksne. Der er god kaffe til ganen og stor litteratur til øregangene.</p>
<p>Første gang læses der op fra den gribende roman Jeg hedder ikke Miriam af den",
      "image": null,
      "name": "Bruger du ord i kaffen?",
      "url": "https://www.aakb.dk/node/11271"
    },
    {
      "@id": "/api/events/21",
      "@type": "http://schema.org/Event",
      "occurrences": [
        {
          "@id": "/api/occurrences/1551",
          "@type": "Occurrence",
          "event": "/api/events/21",
          "startDate": "2016-05-22T14:00:00+02:00",
          "endDate": "2016-05-22T15:45:00+02:00",
          "venue": null
        }
      ],
      "description": "<p>Janus Metz Pedersens film fra 2010, følger en gruppe danske soldater under deres udstationering i Helmand-provinsen. Filmen er bruger bl.a. billeder fra soldaternes hjelmkamera, som gør vores indblik i soldaternes daglig dag og kampe meget personlige. ",
      "image": null,
      "name": "Søndagsdokumentar",
      "url": "https://www.aakb.dk/node/9991"
    },
    {
      "@id": "/api/events/22",
      "@type": "http://schema.org/Event",
      "occurrences": [
        {
          "@id": "/api/occurrences/1552",
          "@type": "Occurrence",
          "event": "/api/events/22",
          "startDate": "2016-07-18T16:00:00+02:00",
          "endDate": "2016-07-18T19:00:00+02:00",
          "venue": null
        }
      ],
      "description": "<p>Hvad er en Pokémon Liga?</p>
<p>En Pokémon liga er et sted hvor fans af Pokémon Trading Card Game har mulig for at mødes og have det sjovt. Har du ikke et deck eller ikke ved hvordan man spiller, så er der mulighed for at låne et deck og lære hvordan d",
      "image": null,
      "name": "Pokémon klub",
      "url": "https://www.aakb.dk/node/11128"
    },
    {
      "@id": "/api/events/23",
      "@type": "http://schema.org/Event",
      "occurrences": [
        {
          "@id": "/api/occurrences/1553",
          "@type": "Occurrence",
          "event": "/api/events/23",
          "startDate": "2016-02-18T15:00:00+01:00",
          "endDate": "2016-02-18T18:00:00+01:00",
          "venue": null
        }
      ],
      "description": "<p>Der kan I hygge jer sammen med at tegne og spille et spil. Vi har spil, papir, farveblyanter og ind imellem en kreativ ide. I står for den kunstneriske udførelse - god fornøjelse</p>
",
      "image": null,
      "name": " Hyggelig familietorsdag",
      "url": "https://www.aakb.dk/node/9343"
    },
    {
      "@id": "/api/events/24",
      "@type": "http://schema.org/Event",
      "occurrences": [
        {
          "@id": "/api/occurrences/1554",
          "@type": "Occurrence",
          "event": "/api/events/24",
          "startDate": "2015-10-08T16:30:00+02:00",
          "endDate": "2015-10-08T18:00:00+02:00",
          "venue": null
        }
      ],
      "description": "<p>Det bliver både tankevækkende og underholdende når leder af danmarkshistorien.dk, ph.d. Mette Frisk Jensen opruller den historiske udvikling for korruption i Danmark og dets betydning for vort samfund.</p>
<p>Alle deltagere modtager et gratis eksemplar",
      "image": null,
      "name": "Tænkepause: Korruption",
      "url": "https://www.aakb.dk/node/7697"
    },
    {
      "@id": "/api/events/25",
      "@type": "http://schema.org/Event",
      "occurrences": [
        {
          "@id": "/api/occurrences/1555",
          "@type": "Occurrence",
          "event": "/api/events/25",
          "startDate": "2015-11-12T16:30:00+01:00",
          "endDate": "2015-11-12T18:00:00+01:00",
          "venue": null
        }
      ],
      "description": "<p>Michael Bang Petersen kommer i sit foredrag blandt andet ind på at vores hjerne er ikke bygget til nutidens komplekse finansforhandlinger og paragraf 20-spørgsmål. Den tænker politik, som da vi levede i små grupper på den østafrikanske savanne for tusi",
      "image": null,
      "name": "Tænkepause: Politik",
      "url": "https://www.aakb.dk/node/7698"
    },
    {
      "@id": "/api/events/26",
      "@type": "http://schema.org/Event",
      "occurrences": [
        {
          "@id": "/api/occurrences/1556",
          "@type": "Occurrence",
          "event": "/api/events/26",
          "startDate": "2016-07-25T16:00:00+02:00",
          "endDate": "2016-07-25T19:00:00+02:00",
          "venue": null
        }
      ],
      "description": "<p>Hvad er en Pokémon Liga?</p>
<p>En Pokémon liga er et sted hvor fans af Pokémon Trading Card Game har mulig for at mødes og have det sjovt. Har du ikke et deck eller ikke ved hvordan man spiller, så er der mulighed for at låne et deck og lære hvordan d",
      "image": null,
      "name": "Pokémon klub",
      "url": "https://www.aakb.dk/node/11129"
    },
    {
      "@id": "/api/events/27",
      "@type": "http://schema.org/Event",
      "occurrences": [
        {
          "@id": "/api/occurrences/1557",
          "@type": "Occurrence",
          "event": "/api/events/27",
          "startDate": "2015-08-29T12:00:00+02:00",
          "endDate": "2015-08-29T14:30:00+02:00",
          "venue": null
        }
      ],
      "description": "<p>Aarhus Festuge tager pulsen på demokrati i tre samtaler, film og hvem ved, måske også en smule debat. Ordstyrer er journalist Mads Kastrup, der får besøg af en række toneangivende danske politikere og personligheder.</p>
<p>I samarbejde med Dokumania o",
      "image": null,
      "name": "Festugen på Dokk1: Aarhussamtale",
      "url": "https://www.aakb.dk/node/7704"
    },
    {
      "@id": "/api/events/28",
      "@type": "http://schema.org/Event",
      "occurrences": [
        {
          "@id": "/api/occurrences/1558",
          "@type": "Occurrence",
          "event": "/api/events/28",
          "startDate": "2016-08-01T16:00:00+02:00",
          "endDate": "2016-08-01T19:00:00+02:00",
          "venue": null
        }
      ],
      "description": "<p>Hvad er en Pokémon Liga?</p>
<p>En Pokémon liga er et sted hvor fans af Pokémon Trading Card Game har mulig for at mødes og have det sjovt. Har du ikke et deck eller ikke ved hvordan man spiller, så er der mulighed for at låne et deck og lære hvordan d",
      "image": null,
      "name": "Pokémon klub",
      "url": "https://www.aakb.dk/node/11130"
    },
    {
      "@id": "/api/events/29",
      "@type": "http://schema.org/Event",
      "occurrences": [
        {
          "@id": "/api/occurrences/1559",
          "@type": "Occurrence",
          "event": "/api/events/29",
          "startDate": "2015-09-01T14:00:00+02:00",
          "endDate": "2015-09-01T15:00:00+02:00",
          "venue": null
        }
      ],
      "description": "<p>Mandag, tirsdag og fredag kl. 14:00.</p>
<p>Onsdag og torsdag kl. 17:00</p>
<p>Rundvisning for enkeltpersoner og mindre grupper.</p>
<p>Der er i alt 20 billetter per rundvisning, og det er muligt at booke op til 15 billetter per person.</p>
<p>Turen ta",
      "image": null,
      "name": "Rundvisning på Dokk1, 1. september",
      "url": "https://www.aakb.dk/node/7710"
    },
    {
      "@id": "/api/events/30",
      "@type": "http://schema.org/Event",
      "occurrences": [
        {
          "@id": "/api/occurrences/1560",
          "@type": "Occurrence",
          "event": "/api/events/30",
          "startDate": "2015-09-04T14:00:00+02:00",
          "endDate": "2015-09-04T15:00:00+02:00",
          "venue": null
        }
      ],
      "description": "<p>Mandag, tirsdag og fredag kl. 14:00.</p>
<p>Onsdag og torsdag kl. 17:00</p>
<p>Rundvisning for enkeltpersoner og mindre grupper.</p>
<p>Der er i alt 20 billetter per rundvisning, og det er muligt at booke op til 15 billetter per person.</p>
<p>Turen ta",
      "image": null,
      "name": "Rundvisning på Dokk1, 4. september",
      "url": "https://www.aakb.dk/node/7711"
    },
    {
      "@id": "/api/events/31",
      "@type": "http://schema.org/Event",
      "occurrences": [
        {
          "@id": "/api/occurrences/1561",
          "@type": "Occurrence",
          "event": "/api/events/31",
          "startDate": "2015-09-07T14:00:00+02:00",
          "endDate": "2015-09-07T15:00:00+02:00",
          "venue": null
        }
      ],
      "description": "<p>Mandag, tirsdag og fredag kl. 14:00.</p>
<p>Onsdag og torsdag kl. 17:00</p>
<p>Rundvisning for enkeltpersoner og mindre grupper.</p>
<p>Der er i alt 20 billetter per rundvisning, og det er muligt at booke op til 15 billetter per person.</p>
<p>Turen ta",
      "image": null,
      "name": "Rundvisning på Dokk1, 7. september",
      "url": "https://www.aakb.dk/node/7712"
    }
  ],
  "hydra:totalItems": 796,
  "hydra:view": {
    "@id": "/api/events?page=1",
    "@type": "hydra:PartialCollectionView",
    "hydra:first": "/api/events?page=1",
    "hydra:last": "/api/events?page=27",
    "hydra:next": "/api/events?page=2"
  },
  "hydra:search": {
    "@type": "hydra:IriTemplate",
    "hydra:template": "/api/events{?order[occurrences.startDate],order[occurrences.endDate],name,description,occurrences.startDate[before],occurrences.startDate[after],occurrences.endDate[before],occurrences.endDate[after]}",
    "hydra:variableRepresentation": "BasicRepresentation",
    "hydra:mapping": [
      {
        "@type": "IriTemplateMapping",
        "variable": "order[occurrences.startDate]",
        "property": "occurrences.startDate",
        "required": false
      },
      {
        "@type": "IriTemplateMapping",
        "variable": "order[occurrences.endDate]",
        "property": "occurrences.endDate",
        "required": false
      },
      {
        "@type": "IriTemplateMapping",
        "variable": "name",
        "property": "name",
        "required": false
      },
      {
        "@type": "IriTemplateMapping",
        "variable": "description",
        "property": "description",
        "required": false
      },
      {
        "@type": "IriTemplateMapping",
        "variable": "occurrences.startDate[before]",
        "property": "occurrences.startDate",
        "required": false
      },
      {
        "@type": "IriTemplateMapping",
        "variable": "occurrences.startDate[after]",
        "property": "occurrences.startDate",
        "required": false
      },
      {
        "@type": "IriTemplateMapping",
        "variable": "occurrences.endDate[before]",
        "property": "occurrences.endDate",
        "required": false
      },
      {
        "@type": "IriTemplateMapping",
        "variable": "occurrences.endDate[after]",
        "property": "occurrences.endDate",
        "required": false
      }
    ]
  }
}
*/
