<?php

namespace App\Service;

use App\Entity\Country;
use App\Entity\Geo;
use App\Entity\Language;
use App\Entity\Place;
use App\Entity\PlaceType;
use App\Entity\TweeterUser;
use App\Repository\CountryRepository;
use App\Repository\LanguageRepository;
use App\Repository\PlaceRepository;
use App\Repository\PlaceTypeRepository;
use App\Repository\TweeterUserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
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

    //endregion


    public function __construct(LanguageRepository $languageRepository, CountryRepository $countryRepository, PlaceTypeRepository $placeTypeRepository, PlaceRepository $placeRepository, TweeterUserRepository $tweeterUserRepository, EntityManagerInterface $entityManager)
    {
        $this->languageRepository = $languageRepository;
        $this->countryRepository = $countryRepository;
        $this->placeRepository = $placeRepository;
        $this->placeTypeRepository = $placeTypeRepository;
        $this->tweeterUserRepository = $tweeterUserRepository;
        $this->entityManager = $entityManager;
    }

    public function processInput($line)
    {
        $json = json_decode($line, true);
        $language = $this->processLanguage($json);
        $geo = $this->processGeo($json);
        $country = $this->processCountry($json);
        $placeType = $this->processPlaceType($json);
        $place = $this->processPlace($json, $country, $placeType);
        $user = $this->processUser($json);
    }

    /**
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
     * @param $json
     * @param Country $country
     * @param PlaceType $placeType
     * @return Place|null
     */
    protected function processPlace($json, $country, $placeType)
    {
        if (isset($json['place'])) {
            $id = $json['place']['id'];
            $place = $this->placeRepository->findOneBy(
                array(
                    'id_place' => $id
                )
            );

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
                $user  = new TweeterUser();
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
}