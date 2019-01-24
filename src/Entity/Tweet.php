<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TweetRepository")
 */
class Tweet
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $source;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $link;

    /**
     * @ORM\Column(type="integer")
     */
    private $retweet_count;

    /**
     * @ORM\Column(type="integer")
     */
    private $favourite_count;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $tweet_text;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created_at;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Place", inversedBy="tweets")
     */
    private $place;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Geo", inversedBy="tweets")
     */
    private $geo;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TweeterUser", inversedBy="tweets")
     */
    private $tweet_user;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Hashtag", inversedBy="tweets")
     */
    private $hashtags;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Media", inversedBy="tweets")
     */
    private $medias;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Url", inversedBy="tweets")
     */
    private $urls;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\TweeterUser", inversedBy="mentions")
     */
    private $mentions;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $oid;

    public function __construct()
    {
        $this->hashtags = new ArrayCollection();
        $this->medias = new ArrayCollection();
        $this->urls = new ArrayCollection();
        $this->mentions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(?string $source): self
    {
        $this->source = $source;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getRetweetCount(): ?int
    {
        return $this->retweet_count;
    }

    public function setRetweetCount(int $retweet_count): self
    {
        $this->retweet_count = $retweet_count;

        return $this;
    }

    public function getFavouriteCount(): ?int
    {
        return $this->favourite_count;
    }

    public function setFavouriteCount(int $favourite_count): self
    {
        $this->favourite_count = $favourite_count;

        return $this;
    }

    public function getTweetText(): ?string
    {
        return $this->tweet_text;
    }

    public function setTweetText(string $tweet_text): self
    {
        $this->tweet_text = $tweet_text;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getPlace(): ?Place
    {
        return $this->place;
    }

    public function setPlace(?Place $place): self
    {
        $this->place = $place;

        return $this;
    }

    public function getGeo(): ?Geo
    {
        return $this->geo;
    }

    public function setGeo(?Geo $geo): self
    {
        $this->geo = $geo;

        return $this;
    }

    public function getTweetUser(): ?TweeterUser
    {
        return $this->tweet_user;
    }

    public function setTweetUser(?TweeterUser $tweet_user): self
    {
        $this->tweet_user = $tweet_user;

        return $this;
    }

    /**
     * @return Collection|Hashtag[]
     */
    public function getHashtags(): Collection
    {
        return $this->hashtags;
    }

    public function addHashtag(Hashtag $hashtag): self
    {
        if (!$this->hashtags->contains($hashtag)) {
            $this->hashtags[] = $hashtag;
        }

        return $this;
    }

    public function removeHashtag(Hashtag $hashtag): self
    {
        if ($this->hashtags->contains($hashtag)) {
            $this->hashtags->removeElement($hashtag);
        }

        return $this;
    }

    /**
     * @return Collection|Media[]
     */
    public function getMedias(): Collection
    {
        return $this->medias;
    }

    public function addMedia(Media $media): self
    {
        if (!$this->medias->contains($media)) {
            $this->medias[] = $media;
        }

        return $this;
    }

    public function removeMedia(Media $media): self
    {
        if ($this->medias->contains($media)) {
            $this->medias->removeElement($media);
        }

        return $this;
    }

    /**
     * @return Collection|Url[]
     */
    public function getUrls(): Collection
    {
        return $this->urls;
    }

    public function addUrl(Url $url): self
    {
        if (!$this->urls->contains($url)) {
            $this->urls[] = $url;
        }

        return $this;
    }

    public function removeUrl(Url $url): self
    {
        if ($this->urls->contains($url)) {
            $this->urls->removeElement($url);
        }

        return $this;
    }

    /**
     * @return Collection|TweeterUser[]
     */
    public function getMentions(): Collection
    {
        return $this->mentions;
    }

    public function addMention(TweeterUser $mention): self
    {
        if (!$this->mentions->contains($mention)) {
            $this->mentions[] = $mention;
        }

        return $this;
    }

    public function removeMention(TweeterUser $mention): self
    {
        if ($this->mentions->contains($mention)) {
            $this->mentions->removeElement($mention);
        }

        return $this;
    }

    public function getOid(): ?string
    {
        return $this->oid;
    }

    public function setOid(string $oid): self
    {
        $this->oid = $oid;

        return $this;
    }
}
