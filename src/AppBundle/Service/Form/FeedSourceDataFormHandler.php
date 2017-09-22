<?php declare(strict_types=1);

namespace AppBundle\Service\Form;

use AppBundle\Form\Type\FeedSourceFormType;
use AppBundle\Form\Type\ProvidesSourceDataFormInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

/**
 * Transforms "sourceData" field to a subform
 * when a proper handler is found
 */
class FeedSourceDataFormHandler implements EventSubscriberInterface
{
    /**
     * @var FormInterface[]|ProvidesSourceDataFormInterface[] $forms
     */
    protected $forms;

    public function __construct(array $forms)
    {
        $this->forms = $forms;
    }

    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SUBMIT => 'preSubmit',
        ];
    }

    public function preSubmit(FormEvent $event)
    {
        if ($event->getForm()->getName() !== 'feed_source_form') {
            return;
        }

        $data = $event->getData();
        $sourceData = isset($data['sourceData']) && is_array($data['sourceData']) ? $data['sourceData'] : [];
        $collectorName = $data['collectorName'] ?? '';

        foreach ($this->forms as $form) {
            if ($form->isAbleToHandle($sourceData, $collectorName)) {
                $form->appendToMainForm($event->getForm());
                return;
            }
        }

        $event->getForm()->addError(new FormError('sourceData does not contain a recognized structure'));
    }
}
