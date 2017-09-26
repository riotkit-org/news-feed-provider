<?php declare(strict_types=1);

namespace AppBundle\Entity;

interface EntityInterface extends \JsonSerializable
{
    /**
     * Unique identifier, mostly UUID
     *
     * @return string
     */
    public function getId(): string;

    /**
     * Name of the entity that will be shown to public
     *
     * @return string
     */
    public static function getPublicTypeName(): string;

    /**
     * List of related objects
     * Useful for JSON aggregated responses
     *
     * @return array
     */
    public function getRelations(): array;
}
