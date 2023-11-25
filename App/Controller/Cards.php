<?php

declare(strict_types = 1);

namespace App\Controller;

use App\Data\Languages;
use App\Model\Entity\Card as CardEntity;
use App\Model\Exception\ScraperException;
use App\Model\Manager\Card as CardManager;
use App\Model\Render;
use App\Model\Scraper;
use DateTime;
use DateTimeZone;

class Cards
{
    private Render $render;
    private int $cacheTime = 7 * 24 * 60 * 60;
    private bool $languageIsValid;

    public function __construct()
    {
        $this->render = new Render('Raw');
    }

    private function languageExists()
    {
        return isset($_POST['language']) && key_exists($_POST['language'], Languages::$all);
    }

    private function getCards(array $words, $language): array
    {
        $cardManager = new CardManager();

        $token = base64_encode(random_bytes(35));
        $scraper = Scraper::getInstance();

        $deck = [];

        if ($this->languageExists()) {
            $this->languageIsValid = true;

            foreach ($words as $word) {
                if (empty($word) || ctype_space($word)) {
                    continue;
                } else {
                    $word = trim($word);
                }

                $cardEntity = $cardManager->get($language, $word);

                try {
                    if ($cardEntity) {
                        $currentDate = (new DateTime())->setTimezone(new DateTimeZone('UTC'))
                                                       ->format('Y-m-d H:i:s');
                        $cardSyncDate = $cardEntity->getSyncAt();

                        if (strtotime($currentDate) > strtotime($cardSyncDate) + $this->cacheTime) {
                            $translation = $scraper->execute($word, $language, $token);
                            $cardEntity = (new CardEntity())->setWord($word)
                                                        ->setTranslation($translation)
                                                        ->setSyncAt($currentDate);
                            $cardManager->update($cardEntity, $language);
                        }
                    } else {
                        $translation = $scraper->execute($word, $language, $token);
                        $cardEntity = (new CardEntity())->setWord($word)
                                                    ->setTranslation($translation);
                        $cardManager->insert($language, $cardEntity);
                    }
                        $deck[] = $cardEntity;
                } catch (ScraperException $exception) {
                    $deck[] = $exception;
                }
            }
        } else {
            $this->languageIsValid = false;
        }

        return $deck;
    }

    public function generate(): void
    {
        $language = $_POST['language'];
        $words = array_values(array_filter(
            $_POST,
            fn($key) => preg_match('/^word-/', $key),
            ARRAY_FILTER_USE_KEY
        ));

        header('Content-Type: text/plain');
        // header('Content-Type: text/tab-separated-values');

        $this->render->setTemplate('Tsv')
                     ->process([
                        'cards' => $this->getCards($words, $language),
                        'languageIsValid' => $this->languageIsValid,
                    ]);
    }
}
