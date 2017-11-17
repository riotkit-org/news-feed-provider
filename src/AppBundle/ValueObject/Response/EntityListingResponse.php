<?php declare(strict_types=1);

namespace AppBundle\ValueObject\Response;

use AppBundle\Entity\EntityInterface;
use AppBundle\ValueObject\PaginationInformation;
use Symfony\Component\HttpFoundation\JsonResponse;

class EntityListingResponse extends JsonResponse
{
    /**
     * @param EntityInterface[] $objects
     * @param array $headers
     * @param PaginationInformation|null $pagination
     */
    public function __construct(array $objects, array $headers = [], PaginationInformation $pagination = null)
    {
        $data = [
            'data' => array_map([$this, 'transformEntityToArray'], $objects),
            'relations' => [],
        ];

        $data = $this->applyPagination($data, $pagination);
        $data = $this->applyRelations($data, $objects);

        parent::__construct($data, 200, $headers, false);
    }

    protected function transformEntityToArray(EntityInterface $entity) : array
    {
        return [
            'type'       => $entity::getPublicTypeName(),
            'id'         => $entity->getId(),
            'attributes' => $entity,
        ];
    }

    protected function applyPagination(array $response, PaginationInformation $pagination = null)
    {
        if (!$pagination) {
            return $response;
        }

        $response['meta']['total-pages'] = $pagination->getMaxPages();
        $response['meta']['current-page'] = $pagination->getCurrentPage();
        $response['meta']['returned-count'] = $pagination->getReturnedResults();
        $response['meta']['total-count'] = $pagination->getTotalResults();
        $response['meta']['offset'] = $pagination->getCurrentPosition();

        // override this method to apply routed links
        return $response;
    }

    /**
     * @param array $response
     * @param EntityInterface[] $entities
     *
     * @return array
     */
    protected function applyRelations(array $response, array $entities)
    {
        $response['relations'] = [];
        
        foreach ($entities as $entity) {
            $entityRelations = $entity->getRelations();
            
            foreach ($entityRelations as $groupName => $innerElements) {
                // initially create a group
                if (!isset($response['relations'][$groupName])) {
                    $response['relations'][$groupName] = [];
                }
                
                $response['relations'][$groupName] = array_merge(
                    $response['relations'][$groupName],
                    $innerElements
                );
            }
        }

        return $response;
    }
}
