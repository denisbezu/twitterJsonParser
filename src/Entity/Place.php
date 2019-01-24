<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PlaceRepository")
 */
class Place
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $id_place;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Country", inversedBy="places")
     */
    private $id_country;

    /**
     * @ORM\Column(type="string", length=120)
     */
    private $full_name;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PlaceType", inversedBy="places")
     */
    private $id_place_type;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Tweet", mappedBy="place")
     */
    private $tweets;

    public function __construct()
    {
        $this->tweets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdPlace(): ?string
    {
        return $this->id_place;
    }

    public function setIdPlace(string $id_place): self
    {
        $this->id_place = $id_place;

        return $this;
    }

    public function getIdCountry(): ?Country
    {
        return $this->id_country;
    }

    public function setIdCountry(?Country $id_country): self
    {
        $this->id_country = $id_country;

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->full_name;
    }

    public function setFullName(string $full_name): self
    {
        $this->full_name = $full_name;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getIdPlaceType(): ?PlaceType
    {
        return $this->id_place_type;
    }

    public function setIdPlaceType(?PlaceType $id_place_type): self
    {
        $this->id_place_type = $id_place_type;

        return $this;
    }

    /**
     * @return Collection|Tweet[]
     */
    public function getTweets(): Collection
    {
        return $this->tweets;
    }

    public function addTweet(Tweet $tweet): self
    {
        if (!$this->tweets->contains($tweet)) {
            $this->tweets[] = $tweet;
            $tweet->setPlace($this);
        }

        return $this;
    }

    public function removeTweet(Tweet $tweet): self
    {
        if ($this->tweets->contains($tweet)) {
            $this->tweets->removeElement($tweet);
            // set the owning side to null (unless already changed)
            if ($tweet->getPlace() === $this) {
                $tweet->setPlace(null);
            }
        }

        return $this;
    }
}
