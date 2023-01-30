<?php

namespace Itk\EventDatabaseClient\Item;

class Event extends Item
{
    protected $occurrences;

    public function __construct(array $data)
    {
        parent::__construct($data);

        $this->occurrences = [];
        if ($this->get('occurrences')) {
            $this->occurrences = array_map(function ($item) {
                return new Occurrence($item);
            }, $this->get('occurrences'));
        }
    }

    public function __toString()
    {
        return $this->getName() ?: __CLASS__;
    }

    /**
     * Name of the event
     *
     * @return array|mixed|null
     */
    public function getName()
    {
        return $this->get('name');
    }

    /**
     * Url of the event
     *
     * @return array|mixed|null
     */
    public function getUrl()
    {
        return $this->get('url');
    }

    /**
     * Description of the event
     *
     * @return array|mixed|null
     */
    public function getDescription()
    {
        return $this->get('description');
    }

    /**
     * All occurences of the event
     *
     * @return array|mixed|null
     */
    public function getOccurrences()
    {
        return $this->occurrences;
    }

    /**
     * Latest update of the event
     *
     * @return array|mixed|null
     */
    public function getUpdatedAt()
    {
        return $this->get('UpdatedAt');
    }

    /**
     * Whether the event is published in the event db.
     *
     * @return array|mixed|null
     */
    public function getIsPublished()
    {
        return $this->get('isPublished');
    }

    /**
     * A purchase URL for the event.
     *
     * @return array|mixed|null
     */
    public function getTicketPurchaseUrl()
    {
        return $this->get('ticketPurchaseUrl');
    }

    /**
     * The original url of the event.
     *
     * @return array|mixed|null
     */
    public function getEventUrl()
    {
        return $this->get('eventUrl');
    }

    /**
     * A small teaser for the event.
     *
     * @return array|mixed|null
     */
    public function getExcerpt()
    {
        return $this->get('excerpt');
    }

    /**
     * The organizer of the event.
     *
     * @return array|mixed|null
     */
    public function getOrganizer()
    {
        return $this->get('organizer');
    }

    /**
     * All tags of the event.
     *
     * @return array|mixed|null
     */
    public function getTags()
    {
        return $this->get('tags');
    }

    /**
     * Custom tags of the event.
     *
     * @return array|mixed|null
     */
    public function getCustomTags()
    {
        return $this->get('customTags');
    }

    /**
     * An image related to the event.
     *
     * @return array|mixed|null
     */
    public function getImage()
    {
      return $this->get('image');
    }

    /**
     * A list of images in different sizes.
     *
     * @return array|mixed|null
     */
    public function getImages()
    {
      return $this->get('images');
    }

    /**
     * A url for a video.
     *
     * @return array|mixed|null
     */
    public function getVideoUrl()
    {
        return $this->get('videoUrl');
    }

    /**
     * The language code of the event.
     *
     * @return array|mixed|null
     */
    public function getLangcode()
    {
        return $this->get('langcode');
    }

    /**
     * The author id.
     *
     * @return array|mixed|null
     */
    public function getCreatedBy()
    {
        return $this->get('createdBy');
    }


}
