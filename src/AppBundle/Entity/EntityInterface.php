<?php declare(strict_types=1);

namespace AppBundle\Entity;

interface EntityInterface extends \JsonSerializable
{
    public function getId() : string;

    public function getPublicTypeName() : string;
}
