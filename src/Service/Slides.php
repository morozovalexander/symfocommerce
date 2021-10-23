<?php

namespace App\Service;

use App\Repository\SlideRepository;

class Slides
{
    /** @var SlideRepository */
    private $slideRepository;

    /**
     * @param SlideRepository $slideRepository
     */
    public function __construct(SlideRepository $slideRepository)
    {
        $this->slideRepository = $slideRepository;
    }

    /**
     * @param bool $enabled
     * @param bool $asc
     * @return array
     */
    public function getSlides(bool $enabled = true, bool $asc = true): array
    {
        $order = $asc ? 'ASC' : 'DESC';
        return $this->slideRepository->findBy(
            ['enabled' => $enabled],
            ['slideOrder' => $order]
        );
    }
}
