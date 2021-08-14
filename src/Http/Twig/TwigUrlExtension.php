<?php

namespace App\Http\Twig;

use ApiPlatform\Core\Api\UrlGeneratorInterface;
use App\Entity\Content;
use App\Entity\User;
use App\Entity\Post;
use Symfony\Component\Serializer\SerializerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class TwigUrlExtension extends AbstractExtension
{
    private UrlGeneratorInterface $urlGenerator;
    private SerializerInterface $serializer;
    private UploaderHelper $uploaderHelper;

    public function __construct(
        UrlGeneratorInterface $urlGenerator,
        UploaderHelper $uploaderHelper,
        SerializerInterface $serializer
    ) {
        $this->urlGenerator = $urlGenerator;
        $this->serializer = $serializer;
        $this->uploaderHelper = $uploaderHelper;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('content_path', [$this, 'contentPath']),
            new TwigFunction('path', [$this, 'pathFor']),
            new TwigFunction('url', [$this, 'urlFor']),
        ];
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('avatar', [$this, 'avatarPath']),
            new TwigFilter('autolink', [$this, 'autoLink']),
        ];
    }

    public function contentPath(Content $content): ?string
    {
        if ($content instanceof Post) {
            return $this->urlGenerator->generate('blog_show', ['slug' => $content->getSlug()]);
        }

        return null;
    }

    public function avatarPath(User $user): ?string
    {
        if (null === $user->getAvatarName()) {
            return '/images/default.png';
        }

        return $this->uploaderHelper->asset($user, 'avatarFile');
    }

    /**
     * @param string|object $path
     */
    public function pathFor($path, array $params = []): string
    {
        if (is_string($path)) {
            return $this->urlGenerator->generate($path, $params);
        }

        return $this->serializer->serialize($path, 'path', ['url' => false]);
    }

    /**
     * @param string|object $path
     */
    public function urlFor($path, array $params = []): string
    {
        if (is_string($path)) {
            return $this->urlGenerator->generate($path, $params, \Symfony\Component\Routing\Generator\UrlGeneratorInterface::ABSOLUTE_URL);
        }

        return $this->serializer->serialize($path, 'path', ['url' => true]);
    }

    public function autoLink(string $string): string
    {
        $regexp = '/(<a.*?>)?(https?:)?(\/\/)(\w+\.)?(\w+\.[\w\/\-_.~&=?]+)(<\/a>)?/i';
        $anchor = '<a href="%s//%s" target="_blank" rel="noopener noreferrer">%s</a>';

        preg_match_all($regexp, $string, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            if (empty($match[1]) && empty($match[6])) {
                $protocol = $match[2] ? $match[2] : 'https:';
                $replace = sprintf($anchor, $protocol, $match[5], $match[0]);
                $string = str_replace($match[0], $replace, $string);
            }
        }

        return $string;
    }
}
