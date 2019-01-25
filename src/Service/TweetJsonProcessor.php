<?php

namespace App\Service;

use App\Entity\Country;
use App\Entity\Geo;
use App\Entity\Hashtag;
use App\Entity\Language;
use App\Entity\Media;
use App\Entity\Place;
use App\Entity\PlaceType;
use App\Entity\Tweet;
use App\Entity\TweeterUser;
use App\Entity\Url;
use App\Repository\CountryRepository;
use App\Repository\HashtagRepository;
use App\Repository\LanguageRepository;
use App\Repository\MediaRepository;
use App\Repository\PlaceRepository;
use App\Repository\PlaceTypeRepository;
use App\Repository\TweeterUserRepository;
use App\Repository\TweetRepository;
use App\Repository\UrlRepository;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use function Symfony\Component\DependencyInjection\Tests\Fixtures\factoryFunction;
use Symfony\Component\VarDumper\VarDumper;

class TweetJsonProcessor
{
    // region Fields
    protected $languageRepository;

    protected $countryRepository;

    protected $placeTypeRepository;

    protected $placeRepository;

    protected $tweeterUserRepository;

    protected $entityManager;

    protected $urlRepository;

    protected $mediaRepository;

    protected $hashtagRepository;

    protected $tweetsRepository;

    protected $skipVerification;
    //endregion

    public function __construct(LanguageRepository $languageRepository, CountryRepository $countryRepository, PlaceTypeRepository $placeTypeRepository, PlaceRepository $placeRepository, TweeterUserRepository $tweeterUserRepository, UrlRepository $urlRepository, MediaRepository $mediaRepository, HashtagRepository $hashtagRepository, TweetRepository $tweetsRepository, EntityManagerInterface $entityManager)
    {
        $this->languageRepository = $languageRepository;
        $this->countryRepository = $countryRepository;
        $this->placeRepository = $placeRepository;
        $this->placeTypeRepository = $placeTypeRepository;
        $this->tweeterUserRepository = $tweeterUserRepository;
        $this->urlRepository = $urlRepository;
        $this->mediaRepository = $mediaRepository;
        $this->hashtagRepository = $hashtagRepository;
        $this->tweetsRepository = $tweetsRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * Process single json line
     * @param $line
     * @param bool $skipVerification
     * @return AddResult
     * @throws \Exception
     */
    public function processInput($line, $skipVerification = false)
    {
        $this->skipVerification = $skipVerification;
        $json = json_decode($line, true);
        $language = $this->processLanguage($json);
        $geo = $this->processGeo($json);
        $country = $this->processCountry($json);
        $placeType = $this->processPlaceType($json);
        $place = $this->processPlace($json, $country, $placeType);
        $user = $this->processUser($json);
        $urls = $this->processUrls($json);
        $medias = $this->processMedia($json);
        $hashtags = $this->processHashtags($json);
        $mentions = $this->processUserMentions($json);
        return $this->processTweets($json, $user, $place, $geo, $mentions, $hashtags, $urls, $medias, $language);
    }

    /**
     * Add or retrieve data about language
     * @param $json
     * @return Language|null
     */
    protected function processLanguage($json)
    {
        if (isset($json['lang'])) {
            $lang = $json['lang'];
            $language = $this->languageRepository->findOneBy(
                array(
                    'language' => $lang
                )
            );

            if ($language == null) {
                $language = new Language();
                $language->setLanguage(trim($lang));
                $this->entityManager->persist($language);
                $this->entityManager->flush();
            }

            return $language;
        }

        return null;
    }

    /**
     * Add or retrieve data about geo
     * @param $json
     * @return Geo|null
     */
    protected function processGeo($json)
    {
        if (isset($json['geo']) && $json['geo'] != null) {
            if (isset($json['geo']['type']) && isset($json['geo']['coordinates'])) {
                $geo = new Geo();
                $geo->setType($json['geo']['type']);
                $geo->setLongitude($json['geo']['coordinates'][0]);
                $geo->setLatitude($json['geo']['coordinates'][1]);
                $this->entityManager->persist($geo);
                $this->entityManager->flush();
                return $geo;
            }
        }

        return null;
    }

    /**
     * Add or retrieve data about country
     * @param $json
     * @return Country|null
     */
    protected function processCountry($json)
    {
        if (isset($json['place']) && isset($json['place']['country_code'])) {
            $countryCode = $json['place']['country_code'];
            $country = $this->countryRepository->findOneBy(
                array(
                    'code' => $countryCode
                )
            );

            if ($country == null) {
                $country = new Country();
                $country->setName($json['place']['country']);
                $country->setCode($json['place']['country_code']);
                $this->entityManager->persist($country);
                $this->entityManager->flush();
            }

            return $country;
        }

        return null;
    }

    /**
     * Add or retrieve data about place
     * @param $json
     * @param Country $country
     * @param PlaceType $placeType
     * @return Place|null
     */
    protected function processPlace($json, $country, $placeType)
    {
        if (isset($json['place'])) {
            $id = $json['place']['id'];
            if (!$this->skipVerification) {
                $place = $this->placeRepository->findOneBy(
                    array(
                        'id_place' => $id
                    )
                );
            } else {
                $place = null;
            }
            if ($place == null) {
                $place = new Place();
                $place->setIdPlaceType($placeType);
                $place->setIdCountry($country);
                $place->setIdPlace($id);
                $place->setName($json['place']['name']);
                $place->setFullName($json['place']['full_name']);
                $this->entityManager->persist($place);
                $this->entityManager->flush();
            }

            return $place;
        }
        return null;
    }

    /**
     * Add or retrieve data about place type
     * @param $json
     * @return PlaceType|null
     */
    protected function processPlaceType($json)
    {
        if (isset($json['place']) && isset($json['place']['place_type'])) {
            $placeT = $json['place']['place_type'];
            $placeType = $this->placeTypeRepository->findOneBy(
                array(
                    'name' => $placeT
                )
            );

            if ($placeType == null) {
                $placeType = new PlaceType();
                $placeType->setName($placeT);
                $this->entityManager->persist($placeType);
                $this->entityManager->flush();
            }

            return $placeType;
        }

        return null;
    }

    /**
     * Add or retrieve data about user
     * @param $json
     * @return TweeterUser|null
     */
    protected function processUser($json)
    {
        if (isset($json['user'])) {
            $id_user = $json['user']['id_str'];
            $user = $this->tweeterUserRepository->findOneBy(
                array(
                    'id_user' => $id_user
                )
            );

            if ($user == null) {
                $user = new TweeterUser();
                $user->setIdUser($id_user);
                $user->setName($json['user']['name']);
                $user->setScreenName($json['user']['screen_name']);
                $user->setFollowersCount($json['user']['followers_count']);
                $user->setFriendsCount($json['user']['friends_count']);
                $user->setLocation($json['user']['location']);
                $this->entityManager->persist($user);
                $this->entityManager->flush();
            }

            return $user;
        }

        return null;
    }

    /**
     * Add or retrieve data about urls
     * @param $json
     * @return ArrayCollection
     */
    protected function processUrls($json)
    {
        $urls = new ArrayCollection();
        if (isset($json['entities']) && isset($json['entities']['urls'])) {
            foreach ($json['entities']['urls'] as $url => $urlData) {
                if (!$this->skipVerification) {
                    $urlObj = $this->urlRepository->findOneBy(
                        array(
                            'url' => $urlData['url']
                        )
                    );
                } else {
                    $urlObj = null;
                }

                if ($urlObj == null) {
                    $urlObj = new Url();
                    $urlObj->setUrl($urlData['url']);
                    $this->entityManager->persist($urlObj);
                    $this->entityManager->flush();
                }
                $urls->add($urlObj);
            }
        }

        return $urls;
    }

    /**
     * Add or retrieve data about media
     * @param $json
     * @return ArrayCollection
     */
    protected function processMedia($json)
    {
        $medias = new ArrayCollection();
        if (isset($json['entities']) && isset($json['entities']['media'])) {
            foreach ($json['entities']['media'] as $mediaIndex => $mediaData) {
                if (!$this->skipVerification) {
                    $mediaObj = $this->mediaRepository->findOneBy(
                        array(
                            'url' => $mediaData['url']
                        )
                    );
                } else {
                    $mediaObj = null;
                }

                if ($mediaObj == null) {
                    $mediaObj = new Media();
                    $mediaObj->setUrl($mediaData['url']);
                    $mediaObj->setType($mediaData['type']);
                    $this->entityManager->persist($mediaObj);
                    $this->entityManager->flush();
                }

                $medias->add($mediaObj);
            }
        }

        return $medias;
    }

    /**
     * Add or retrieve data about hashtags added
     * @param $json
     * @return ArrayCollection
     */
    protected function processHashtags($json)
    {
        $hashtags = new ArrayCollection();
        if (isset($json['entities']) && isset($json['entities']['hashtags'])) {
            foreach ($json['entities']['hashtags'] as $hashtagIndex => $hashtagData) {
                if (!$this->skipVerification) {
                    $hashtagObj = $this->hashtagRepository->findOneBy(
                        array(
                            'name' => $hashtagData['text']
                        )
                    );
                } else {
                    $hashtagObj = null;
                }

                if ($hashtagObj == null) {
                    $hashtagObj = new Hashtag();
                    $hashtagObj->setName($hashtagData['text']);
                    $this->entityManager->persist($hashtagObj);
                    $this->entityManager->flush();
                }

                $hashtags->add($hashtagObj);
            }
        }

        return $hashtags;
    }

    /**
     * Add or retrieve data about user mentions
     * @param $json
     * @return ArrayCollection
     */
    protected function processUserMentions($json)
    {
        $mentions = new ArrayCollection();
        if (isset($json['entities']) && isset($json['entities']['user_mentions'])) {
            foreach ($json['entities']['user_mentions'] as $userMentionIndex => $userMentionData) {
                $user = $this->tweeterUserRepository->findOneBy(
                    array(
                        'id_user' => $userMentionData['id_str']
                    )
                );

                if ($user == null) {
                    $user = new TweeterUser();
                    $user->setName($userMentionData['name']);
                    $user->setScreenName($userMentionData['screen_name']);
                    $user->setIdUser($userMentionData['id_str']);
                    $this->entityManager->persist($user);
                    $this->entityManager->flush();
                }

                $mentions->add($user);
            }
        }

        return $mentions;
    }

    /**
     * Add or retrieve the tweet
     * @param $json
     * @param $user
     * @param $place
     * @param $geo
     * @param $mentions
     * @param $hashtags
     * @param $urls
     * @param $medias
     * @param $language
     * @return AddResult
     * @throws \Exception
     */
    protected function processTweets($json, $user, $place, $geo, $mentions, $hashtags, $urls, $medias, $language)
    {
        if (isset($json['_id']['$oid'])) {
            if (!$this->skipVerification) {
                $tweet = $this->tweetsRepository->findOneBy(
                    array(
                        'oid' => $json['_id']['$oid']
                    )
                );
            } else {
                $tweet = null;
            }

            if ($tweet == null) {
                $tweet = new Tweet();
                $tweet->setOid($json['_id']['$oid']);
                $tweet->setTweetText($json['text']);
                $tweet->setFavouriteCount($json['favorite_count']);
                $tweet->setRetweetCount($json['retweet_count']);
                $tweet->setSource($json['source']);
                $tweet->setLink($json['link'][0]);
                $tweet->setCreatedAt(new DateTime($json['created_at']));
                $tweet->setLanguage($language);

                $tweet->setPlace($place);
                $tweet->setGeo($geo);
                $tweet->setTweetUser($user);

                foreach ($hashtags as $hashtag) {
                    $tweet->addHashtag($hashtag);
                }

                foreach ($mentions as $mention) {
                    $tweet->addMention($mention);
                }

                foreach ($urls as $url) {
                    $tweet->addUrl($url);
                }

                foreach ($medias as $media) {
                    $tweet->addMedia($media);
                }

                $this->entityManager->persist($tweet);
                $this->entityManager->flush();
                return new AddResult(AddResult::ADDED, $tweet->getId());
            }

            return new AddResult(AddResult::SKIPPED);
        }

        return new AddResult(AddResult::FAILED);
    }

}