<?php

namespace Eshop\FixturesBundle\DataFixtures;


trait FixturesProviderTrait
{
    private function getCategories(): array
    {
        return [
            'Computers', 'Notebooks and PC\'s', 'Computer Periferias', 'Phones', 'Smart Watches',
            'TV', 'Accessories for TV', 'Games', 'Consoles', 'Audio Technics', 'Foto Technics', 'Network Devices'
        ];
    }

    private function getManufactures(): array
    {
        return [
            'Lenovo', 'HP', 'Asus', 'Acer', 'Dell', 'Apple', 'Packard Bell', 'MSI', 'Panasonic', 'Xiaomi',
            'Samsung', 'Nokia', 'Accorp', 'Sony', 'Toshiba', 'Fujitsu'
        ];
    }

    private function getMeasures()
    {
        return ['Grammes', 'Pieces', 'Caps', 'Ml', 'Packs'];
    }

    private function getStaticPagesTitles()
    {
        return ['Information', 'Contacts', 'Discounts'];
    }

    private function getSlideTitles()
    {
        return ['slide1', 'slide2'];
    }

    private function getLongTextContent(): string
    {
        return <<<EOT
Lorem ipsum dolor sit amet consectetur adipisicing elit, sed do eiusmod tempor
incididunt ut labore et dolore magna aliqua: Duis aute irure dolor in
reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia
deserunt mollit anim id est laborum.

  - Ut enim ad minim veniam
  - Quis nostrud exercitation ullamco laboris
  - Nisi ut aliquip ex ea commodo consequat

Praesent id fermentum lorem. Ut est lorem, fringilla at accumsan nec, euismod at
nunc. Aenean mattis sollicitudin mattis. Nullam pulvinar vestibulum bibendum.
Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos
himenaeos. Fusce nulla purus, gravida ac interdum ut, blandit eget ex. Duis a
luctus dolor.

Integer auctor massa maximus nulla scelerisque accumsan. *Aliquam ac malesuada*
ex. Pellentesque tortor magna, vulputate eu vulputate ut, venenatis ac lectus.
Praesent ut lacinia sem. Mauris a lectus eget felis mollis feugiat. Quisque
efficitur, mi ut semper pulvinar, urna urna blandit massa, eget tincidunt augue
nulla vitae est.

Ut posuere aliquet tincidunt. Aliquam erat volutpat. Class aptent taciti
sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Morbi
arcu orci, gravida eget aliquam eu, suscipit et ante. Morbi vulputate metus vel
ipsum finibus, ut dapibus massa feugiat. Vestibulum vel lobortis libero. Sed
tincidunt tellus et viverra scelerisque. Pellentesque tincidunt cursus felis.
Sed in egestas erat.

Aliquam pulvinar interdum massa, vel ullamcorper ante consectetur eu. Vestibulum
lacinia ac enim vel placerat. Integer pulvinar magna nec dui malesuada, nec
congue nisl dictum. Donec mollis nisl tortor, at congue erat consequat a. Nam
tempus elit porta, blandit elit vel, viverra lorem. Sed sit amet tellus
tincidunt, faucibus nisl in, aliquet libero.
EOT;
    }

    private function getPhrases(): array
    {
        return [
            'Lorem ipsum dolor sit amet consectetur adipiscing elit',
            'Pellentesque vitae velit ex',
            'Mauris dapibus risus quis suscipit vulputate',
            'Eros diam egestas libero eu vulputate risus',
            'In hac habitasse platea dictumst',
            'Morbi tempus commodo mattis',
            'Ut suscipit posuere justo at vulputate',
            'Ut eleifend mauris et risus ultrices egestas',
            'Aliquam sodales odio id eleifend tristique',
            'Urna nisl sollicitudin id varius orci quam id turpis',
            'Nulla porta lobortis ligula vel egestas',
            'Curabitur aliquam euismod dolor non ornare',
            'Sed varius a risus eget aliquam',
            'Nunc viverra elit ac laoreet suscipit',
            'Pellentesque et sapien pulvinar consectetur',
            'Ubi est barbatus nix',
            'Abnobas sunt hilotaes de placidus vita',
            'Ubi est audax amicitia',
            'Eposs sunt solems de superbus fortis',
            'Vae humani generis',
            'Diatrias tolerare tanquam noster caesium',
            'Teres talis saepe tractare de camerarius flavum sensorem',
            'Silva de secundus galatae demitto quadra',
            'Sunt accentores vitare salvus flavum parses',
            'Potus sensim ad ferox abnoba',
            'Sunt seculaes transferre talis camerarius fluctuies',
            'Era brevis ratione est',
            'Sunt torquises imitari velox mirabilis medicinaes',
            'Mineralis persuadere omnes finises desiderium',
            'Bassus fatalis classiss virtualiter transferre de flavum',
        ];
    }

    private function getWords(): array
    {
        return ['Lorem', 'Ipsum', 'Dolor', 'Sit', 'Amet', 'Consectetur', 'Adipiscing', 'Elit', 'Pellentesque', 'Vitae',
            'Velit', 'Mauris', 'Dapibus', 'Risus', 'Quis', 'Suscipit', 'Vulputate', 'Eros', 'Diam', 'Egestas', 'Libero',
            'Vulputate', 'Risus', 'Hac', 'Habitasse', 'Platea', 'Dictumst'];
    }

    private function getRandomCategoryTitles(): array
    {
        $categories = $this->getCategories();

        //the first category in array will be 'Computers'
        $computersCategory = array_shift($categories);
        shuffle($categories);
        array_unshift($categories, $computersCategory);

        return $categories;
    }

    private function getRandomManufacturerTitles(): array
    {
        $manufacturers = $this->getManufactures();

        //the first category in array will be 'Lenovo'
        $lenovoManufacturer = array_shift($manufacturers);
        shuffle($manufacturers);
        array_unshift($manufacturers, $lenovoManufacturer);

        return $manufacturers;
    }

    private function getRandomNewsTitles(): array
    {
        $phrases = $this->getPhrases();

        // this ensures that the first title is always 'Lorem Ipsum...'
        $loremIpsumPhrase = array_shift($phrases);
        shuffle($phrases);
        array_unshift($phrases, $loremIpsumPhrase);

        return $phrases;
    }

    private function getRandomMetaKeysString(): string
    {
        $words = $this->getWords();
        $numWords = random_int(4, 7);
        shuffle($words);
        $words = \array_slice($words, 0, $numWords - 1);

        return implode(', ', $words);
    }

    private function getRandomMetaDescriptionString(): string
    {
        $phrases = $this->getPhrases();
        $numPhrases = random_int(2, 5);
        shuffle($phrases);
        $phrases = \array_slice($phrases, 0, $numPhrases - 1);

        return implode('. ', $phrases) . '.';
    }

    private function getRandomProductName(): string
    {
        $words = $this->getWords();
        $numWords = random_int(2, 5);
        shuffle($words);
        $words = \array_slice($words, 0, $numWords - 1);

        return implode(' ', $words);
    }

    private function getAllStaticPageTitles(): array
    {
        return $this->getStaticPagesTitles();
    }

    private function getMeasureTitles(): array
    {
        return $this->getMeasures();
    }
}
