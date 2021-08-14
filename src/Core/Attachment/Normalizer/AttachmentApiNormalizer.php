<?php

namespace App\Core\Attachment\Normalizer;

use App\Entity\Attachment;
use App\Infrastructure\Image\ImageResizer;
use App\Normalizer\Normalizer;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class AttachmentApiNormalizer extends Normalizer
{
    private ImageResizer $resizer;
    private UploaderHelper $uploaderHelper;

    public function __construct(
        UploaderHelper $uploaderHelper,
        ImageResizer $resizer
    ) {
        $this->resizer = $resizer;
        $this->uploaderHelper = $uploaderHelper;
    }

    /**
     * @param Attachment $object
     */
    public function normalize($object, $format = null, array $context = []): array
    {
        $info = pathinfo($object->getFileName());
        $filenameParts = explode('-', $info['filename']);
        $filenameParts = array_slice($filenameParts, 0, -1);
        $filename = implode('-', $filenameParts);
        $extension = $info['extension'] ?? '';

        return [
            'id' => $object->getId(),
            'createdAt' => $object->getCreatedAt()->getTimestamp(),
            'name' => "{$filename}.{$extension}",
            'size' => $object->getFileSize(),
            'url' => $this->uploaderHelper->asset($object),
            'thumbnail' => $this->resizer->resize($this->uploaderHelper->asset($object), 250, 100),
        ];
    }

    /**
     * @param mixed $data ;
     */
    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof Attachment && 'json' === $format;
    }
}
