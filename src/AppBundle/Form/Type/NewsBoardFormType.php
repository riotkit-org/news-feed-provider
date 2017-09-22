<?php declare(strict_types=1);

namespace AppBundle\Form\Type;

use AppBundle\Entity\NewsBoard;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @see NewsBoard
 */
class NewsBoardFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, [
            'required' => true,
        ]);
        $builder->add('description', TextType::class, [
            'empty_data' => '',
            'required' => true,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => NewsBoard::class,
        ]);
    }
}
