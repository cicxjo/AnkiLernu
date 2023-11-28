<?php

namespace App\Model;

use App\Model\Exception\ScraperException;
use DOMDocument;

class Scraper
{
    private static ?self $instance = null;
    private string $word;
    private array $curlOptions;
    private string $token;

    private function __construct()
    {
        $this->token = base64_encode(random_bytes(35));
    }

    public static function getInstance(): self
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function fetch(): string|false
    {
        $curlHandle = curl_init();
        curl_setopt_array($curlHandle, $this->curlOptions);
        $data = curl_exec($curlHandle);
        $httpCode = curl_getinfo($curlHandle, CURLINFO_HTTP_CODE);

        if ($httpCode !== 200) {
            throw new ScraperException($httpCode, 'Error: Oops, I got an HTTP code. ' . $httpCode);
        }

        return $data;
    }

    private function parse(string $data): string|false
    {
        $dom = new DOMDocument();
        @$dom->loadHTML($data);

        $word = @$dom->getElementById('dictionary-search-results')
                     ->getElementsByTagName('ul')[0]
                     ->getElementsByTagName('li')[0]
                     ->getElementsByTagName('b')[0]
                     ->nodeValue;

        $translation = @$dom->getElementById('dictionary-search-results')
                            ->getElementsByTagName('ul')[0]
                            ->getElementsByTagName('li')[0]
                            ->getElementsByTagName('li')[0]
                            ->nodeValue;

        if (is_string($word) && is_string($translation)) {
            $word = trim($word);
            $translation = trim($translation);
        } else {
            throw new ScraperException('Error: Oops, I can’t parse the HTML received from Lernu!.', $this->word);
            return false;
        }

        if ($word !== $this->word) {
            throw new ScraperException('Error: Oops, I received several results from Lernu!.', $this->word);
            return false;
        }

        return $translation;
    }

    public function execute(string $word, string $language): string
    {
        $this->word = $word;
        $this->curlOptions = [
            CURLOPT_URL => 'https://lernu.net/vortaro',
            CURLOPT_POST => true,
            CURLOPT_COOKIE => 'YII_CSRF_TOKEN=' . $this->token,
            CURLOPT_POSTFIELDS => 'YII_CSRF_TOKEN=' . $this->token
                . '&DictWords[dictionary]=eo|' . $language
                . '&DictWords[word]=' . $word,
            CURLOPT_RETURNTRANSFER => true,
        ];

        $data = $this->parse($this->fetch());

        return $data;
    }
}

/* Résultat à parser pour le mot « amiko » :
 *
 * <div id="dictionary-results">
 *   <div id="dictionary-search-results" class="list-view">
 *
 *     <ul class="list-unstyled dictionary-items">
 *
 *       <li>
 *         <b class="orig">amiko</b>
 *         <span class="dictionary-structure">(amik·o)</span>
 *         <ul class="list-unstyled">
 *           <li>ami</li>
 *         </ul>
 *       </li>
 *
 *     </ul>
 *
 *   </div>
 * </div>
 */

/* Résultat à parser pour le mot incomplet « amik » :
 *
 * <div id="dictionary-results">
 *   <div id="dictionary-search-results" class="list-view">
 *
 *     <ul class="list-unstyled dictionary-items">
 *
 *       <li>
 *         <b class="orig">amika</b>
 *         <span class="dictionary-structure">(amik·a ← amik·o)</span>
 *         <ul class="list-unstyled">
 *           <li>entente, amical</li>
 *         </ul>
 *       </li>
 *
 *       <li>
 *         <b class="orig">amike</b>
 *         <span class="dictionary-structure">(amik·e ← amik·o)</span>
 *         <ul class="list-unstyled">
 *           <li>amicalement</li>
 *         </ul>
 *       </li>
 *
 *       <li>
 *         <b class="orig">amiki</b>
 *         <span class="dictionary-structure">(amik·i ← amik·o)</span>
 *         <ul class="list-unstyled">
 *           <li>être amis</li>
 *         </ul>
 *       </li>
 *
 *       <li>
 *         <b class="orig">amiko</b>
 *         <span class="dictionary-structure">(amik·o)</span>
 *         <ul class="list-unstyled">
 *           <li>ami</li>
 *         </ul>
 *       </li>
 *
 *       <li>
 *         <b class="orig">amikaro</b>
 *         <span class="dictionary-structure">(amik·ar·o ← amik·o)</span>
 *         <ul class="list-unstyled">
 *           <li>cercle d'amis, groupe d'amis</li>
 *         </ul>
 *       </li>
 *
 *     </ul>
 *
 *   </div>
 * </div>
 */
