<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TweeterUserRepository")
 */
class TweeterUser
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $friends_count;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $screen_name;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $followers_count;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $location;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Tweet", mappedBy="tweet_user")
     */
    private $tweets;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Tweet", mappedBy="mentions")
     */
    private $mentions;

    /**
     * @ORM\Column(type="bigint")
     */
    private $id_user;

    public function __construct()
    {
        $this->tweets = new ArrayCollection();
        $this->mentions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFriendsCount(): ?int
    {
        return $this->friends_count;
    }

    public function setFriendsCount(?int $friends_count): self
    {
        $this->friends_count = $friends_count;

        return $this;
    }

    public function getScreenName(): ?string
    {
        return $this->screen_name;
    }

    public function setScreenName(?string $screen_name): self
    {
        $this->screen_name = $screen_name;

        return $this;
    }

    public function getFollowersCount(): ?string
    {
        return $this->followers_count;
    }

    public function setFollowersCount(?string $followers_count): self
    {
        $this->followers_count = $followers_count;

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

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): self
    {
        $this->location = $location;

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
            $tweet->setTweetUser($this);
        }

        return $this;
    }

    public function removeTweet(Tweet $tweet): self
    {
        if ($this->tweets->contains($tweet)) {
            $this->tweets->removeElement($tweet);
            // set the owning side to null (unless already changed)
            if ($tweet->getTweetUser() === $this) {
                $tweet->setTweetUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Tweet[]
     */
    public function getMentions(): Collection
    {
        return $this->mentions;
    }

    public function addMention(Tweet $mention): self
    {
        if (!$this->mentions->contains($mention)) {
            $this->mentions[] = $mention;
            $mention->addMention($this);
        }

        return $this;
    }

    public function removeMention(Tweet $mention): self
    {
        if ($this->mentions->contains($mention)) {
            $this->mentions->removeElement($mention);
            $mention->removeMention($this);
        }

        return $this;
    }

    public function getIdUser(): ?int
    {
        return $this->id_user;
    }

    public function setIdUser(int $id_user): self
    {
        $this->id_user = $id_user;

        return $this;
    }
}
