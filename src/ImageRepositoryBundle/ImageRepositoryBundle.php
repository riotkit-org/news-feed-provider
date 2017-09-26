<?php declare(strict_types=1);

namespace ImageRepositoryBundle;

use ImageRepositoryBundle\DependencyInjection\ImageRepositoryExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ImageRepositoryBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new ImageRepositoryExtension();
    }
}
