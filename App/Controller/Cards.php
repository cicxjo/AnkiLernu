<?php

declare(strict_types = 1);

namespace App\Controller;

use App\Data\Languages;
use App\Model\Entity\Card as CardEntity;
use App\Model\Exception\HTTPException;
use App\Model\Exception\ScraperException;
use App\Model\Manager\Card as CardManager;
use App\Model\Render;
use App\Model\Scraper;
use DateTime;
use DateTimeZone;

class Cards
{
    private Render $render;
    private int $cacheTime = 30 * 24 * 60 * 60;

    public function __construct()
    {
        $this->render = new Render('Raw');
    }

    private function formatCard(CardEntity $cardEntity): string
    {
        return $cardEntity->getWord() . "\t" . $cardEntity->getTranslation();
    }

    private function getCardDeck(array $words, string $language): array
    {
        $cardManager = new CardManager();
        $token = base64_encode(random_bytes(35));
        $scraper = Scraper::getInstance();
        $deck = [];
        $utc = new DateTimeZone('UTC');

        foreach ($words as $word) {
            if (empty($word) || ctype_space($word)) {
                continue;
            }

            $word = trim($word);
            $cardEntity = $cardManager->get($language, $word);

            try {
                if ($cardEntity) {
                    $currentDate = (new DateTime())->setTimezone($utc)
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
                $deck[] = $this->formatCard($cardEntity);
            } catch (ScraperException $exception) {
                $deck[] = '#' . $word . ' -- ' . $exception->getMessage();
            }
        }

        return $deck;
    }

    public function generate(): void
    {
        if (isset($_POST['language']) && array_key_exists($_POST['language'], Languages::$all)) {
            $language = $_POST['language'];
            $words = array_values(array_filter(
                $_POST,
                fn($key) => preg_match('/^word-[0-9]+$/', $key) && !empty($_POST[$key]),
                ARRAY_FILTER_USE_KEY
            ));

            if (!empty($words)) {
                header('Content-Type: text/plain');
                // header('Content-Type: text/tab-separated-values');

                $this->render->setTemplate('Tsv')->process([
                    'cards' => $this->getCardDeck($words, $language),
                ]);

                return;
            }
        }

        throw new HTTPException(400);
    }
}
