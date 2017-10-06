<?php

namespace Itk\EventDatabaseClient\Item;

class Place extends Item
{
    public function __toString()
    {
        return $this->getName() ?: '';
    }

    public function getName()
    {
        return $this->get('name');
    }


    /**
     * Telephone of the place.
     *
     * @return array|mixed|null
     */
    public function getTelephone()
    {
        return $this->get('telephone');
    }

    /**
     * Email of the place.
     *
     * @return array|mixed|null
     */
    public function getEmail()
    {
        return $this->get('email');
    }

    /**
     * Logo of the place.
     *
     * @return array|mixed|null
     */
    public function getLogo()
    {
        return $this->get('logo');
    }

    /**
     * Disability access of the place.
     *
     * @return array|mixed|null
     */
    public function getDisabilityAccess()
    {
        return $this->get('disabilityAccess');
    }

    /**
     * Tags of the place.
     *
     * @return array|mixed|null
     */
    public function getTags()
    {
        return $this->get('tags');
    }

    /**
     * Description of the place.
     *
     * @return array|mixed|null
     */
    public function getDescription()
    {
        return $this->get('description');
    }

    /**
     * Image of the place.
     *
     * @return array|mixed|null
     */
    public function getImage()
    {
        return $this->get('image');
    }

    /**
     * Url of the place.
     *
     * @return array|mixed|null
     */
    public function getUrl()
    {
        return $this->get('url');
    }

    /**
     * VideoUrl of the place.
     *
     * @return array|mixed|null
     */
    public function getVideoUrl()
    {
        return $this->get('videoUrl');
    }

    /**
     * Language code o the place.
     *
     * @return array|mixed|null
     */
    public function getlangcode()
    {
        return $this->get('langcode');
    }

}
