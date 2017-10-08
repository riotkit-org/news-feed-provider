<?php declare(strict_types=1);

namespace AppBundle\Form\Type;

use AppBundle\Entity\FeedSource;
use AppBundle\Entity\NewsBoard;
use AppBundle\Manager\NewsBoardManager;
use AppBundle\Repository\NewsBoardRepository;
use AppBundle\Service\Form\FeedSourceDataFormHandler;
use AppBundle\Service\HarvestingMachine;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\LanguageType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @see FeedSource
 */
class FeedSourceFormType extends AbstractType
{
    protected $collectors;
    protected $boardRepository;
    protected $formHandler;

    public function __construct(
        HarvestingMachine $machine,
        NewsBoardRepository $boardRepository,
        FeedSourceDataFormHandler $formHandler
    ) {
        $this->collectors = $machine->getCollectors();
        $this->boardRepository = $boardRepository;
        $this->formHandler = $formHandler;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // optionally there is a possibility to submit own id
        $builder->add('id', TextType::class, [
            'required' => false,
        ]);

        $builder->add('collectorName', ChoiceType::class, [
            'required' => true,
            'choices' => $this->collectors->getNames(),
        ]);

        $builder->add('newsBoard', EntityType::class, [
            'class' => NewsBoard::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('n')
                    ->orderBy('n.name', 'ASC');
            },

            'choice_label' => 'id',
        ]);

        $builder->add('sourceData', TextType::class, [
            'empty_data' => '{}',
            'required'   => true,
        ]);

        $builder->add('defaultLanguage', LanguageType::class, [
            'required' => true,
        ]);

        $builder->add('title', TextType::class, [
            'required' => true,
        ]);

        $builder->add('description', TextType::class);

        $builder->add('enabled', CheckboxType::class, [
            'empty_data' => false,
        ]);

        $builder->add('icon', UrlType::class, [
            'empty_data' => '',
            'required'   => false,
        ]);

        $builder->add('scrapingSpecification', TextType::class, [
            'empty_data' => [],
            'required'   => false,
        ]);

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $this->formHandler->preSubmit($event);
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FeedSource::class,
        ]);
    }
}
