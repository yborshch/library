<?php

namespace App\ValueObject;

class Image
{
    const MAX_HEIGHT = 600;
    const MAX_WIDTH = 400;
    const MAX_IMAGE_SIZE_IN_BYTE = 10485760;

    const JPEG = 'jpeg';
    const PNG = 'png';

    const EXTENSIONS = [
        self::JPEG,
        self::PNG,
    ];

    /**
     * @var string
     */
    public string $filename = "noimage";

    /**
     * @var string
     */
    public string $height;

    /**
     * @var string
     */
    public string $width;

    /**
     * @var bool
     */
    public bool $isVertical = true;

    /**
     * @var string
     */
    public string $path;

    /**
     * @var string
     */
    public string $size;

    /**
     * @var string
     */
    public string $extension;

    /**
     * @param bool $or
     * @return string
     */
    public function extensionsToString(bool $or = false): string
    {
        $result = '';
        foreach (self::EXTENSIONS as $index => $type) {
            $result .= $or && $index ? ' or ' . $type : ' ' . $type;
        }
        return $result;
    }
}
