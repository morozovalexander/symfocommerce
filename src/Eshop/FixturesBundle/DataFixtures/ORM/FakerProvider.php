<?php

namespace Eshop\FixturesBundle\DataFixtures\ORM;

class FakerProvider
{
    /**
     * Sources: {@link http://siliconvalleyjobtitlegenerator.tumblr.com/}
     *
     * @var array List of job titles.
     */
    const TITLE_PROVIDER = [
        'firstname' => [
            'Audience Recognition',
            'Big Data',
            'Bitcoin',
            '...',
            'Video Experience',
            'Wearables',
            'Webinar',
        ],
        'lastname' => [
            'Advocate',
            'Amplifier',
            'Architect',
            '...',
            'Warlock',
            'Watchman',
            'Wizard',
        ],
        'fullname' => [
            'Conductor of Datafication',
            'Crowd-Funder-in-Residence',
            'Quantified-Self-in-Residence',
            '...',
            'Tech-Svengali-in-Residence',
            'Tech-Wizard-in-Residence',
            'Thought-Leader-in-Residence',
        ],
    ];

    /**
     * Sources: {@link http://sos.uhrs.indiana.edu/Job_Code_Title_Abbreviation_List.htm}
     *
     * @var array List of job abbreviations.
     */
    const ABBREVIATION_PROVIDER = [
        'ABATE',
        'ACAD',
        'ACCT',
        '...',
        'WCTR',
        'WSTRN',
        'WKR',
    ];

    /**
     * @return string Random job title.
     */
    public function jobTitle()
    {
        $names = [
            sprintf(
                '%s %s',
                self::randomElement(self::TITLE_PROVIDER['firstname']),
                self::randomElement(self::TITLE_PROVIDER['lastname'])
            ),
            self::randomElement(self::TITLE_PROVIDER['fullname']),
        ];

        return self::randomElement($names);
    }

    /**
     * @return string Random job abbreviation title
     */
    public function jobAbbreviation()
    {
        return self::randomElement(self::ABBREVIATION_PROVIDER);
    }

    public function category()
    {
        $categories = [
            'Notebooks and PC\'s', 'Computers', 'Computer Periferias', 'Phones', 'Smart Watches',
            'TV', 'TV Accessoirs', 'Games', 'Consoles', 'Audio Technics', 'Foto Technics', 'Network Devices'
        ];
        return $categories[array_rand($categories)];
    }

    public function manufacturer()
    {
        $manufacturers = [
            'HP', 'Lenovo', 'Asus', 'Acer', 'Dell', 'Apple', 'Packard Bell', 'MSI', 'Panasonic', 'Xiaomi',
            'Samsumng', 'Nokia', 'Accorp', 'Sony', 'Toshiba', 'Fujitsu'
        ];
        return $manufacturers[array_rand($manufacturers)];
    }

    public function measure()
    {
        $measures = ['Grammes', 'Pieces', 'Caps', 'Ml', 'Packs'];
        return $measures[array_rand($measures)];
    }

    public function staticPagesTitle()
    {
        $titles = ['Information', 'Contacts', 'Discounts'];
        return $titles[array_rand($titles)];
    }

    public function slideTitle()
    {
        $titles = ['slide1', 'slide2'];
        return $titles[array_rand($titles)];
    }

    public function productImage()
    {
        $images = ['product1.jpg', 'product2.jpg', 'product3.jpg', 'product4.jpg', 'product5.jpg'];
        return $images[array_rand($images)];
    }
}