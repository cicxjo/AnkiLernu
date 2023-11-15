<?php

declare(strict_types = 1);

namespace App\Controller;

use App\Model\Entity\Card as CardEntity;
use App\Model\Exception\ScraperException;
use App\Model\Manager\Card as CardManager;
use App\Model\Render;
use App\Model\Scraper;

class Cards
{
    private Render $render;
    private int $cacheTime = 7 * 24 * 60 * 60;

    public function __construct()
    {
        $this->render = new Render('Raw');
    }

    private function getCards(array $words, $language): array
    {
        $cardManager = new CardManager();

        $token = base64_encode(random_bytes(35));
        $scraper = Scraper::getInstance();

        $deck = [];

        foreach ($words as $word) {
            $cardEntity = $cardManager->get($language, $word);

            try {
                if ($cardEntity) {
                    $currentTime = time();
                    $cardDate = strtotime($cardEntity->getSyncAt());

                    if (($cardDate - $currentTime) > $this->cacheTime) {
                        $translation = $scraper->execute($word, $language, $token);
                        $cardEntity = (new CardEntity())->setWord($word)
                                                    ->setTranslation($translation)
                                                    ->setSyncAt(date('Y-m-d H:i:s'));
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

        // header('Content-Type: text/plain');
        header('Content-Type: text/tab-separated-values');

        $this->render->setTemplate('Tsv')
                     ->process(['cards' => $this->getCards($words, $language)]);
    }
}
