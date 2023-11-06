<?php

declare(strict_types = 1);

namespace App\Controller;

use App\Model\Entity\Card as CardEntity;
use App\Model\Exception\ScraperException;
use App\Model\Render;
use App\Model\Scraper;

class Cards
{
    private Render $render;

    public function __construct()
    {
        $this->render = new Render('Page');
    }

    private function getCards(array $words, $language): array
    {
        $token = base64_encode(random_bytes(35));
        $scraper = Scraper::getInstance();

        $deck = [];

        foreach ($words as $word) {
            try {
                $translation = $scraper->execute($word, $language, $token);
                $cardEntity = (new CardEntity())->setWord($word)
                                            ->setTranslation($translation);
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

        $card = $this->getCards($words, $language);
    }
}
