<?php

namespace App\Service\Structs;

class ProductLikeResult
{
    /** @var string */
    public $message;
    /** @var bool */
    public $success;
    /** @var bool */
    public $isLiked;

    /**
     * @param string $message
     * @param bool $success
     * @param bool $isLiked
     */
    public function __construct(string $message = '', bool $success = true, bool $isLiked = true)
    {
        $this->message = $message;
        $this->success = $success;
        $this->isLiked = $isLiked;
    }
}
