<?php declare(strict_types=1);

namespace AppBundle\Form\Type;

use AppBundle\Entity\NewsBoard;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @see NewsBoard
 */
class NewsBoardFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // optionally there is a possibility to submit own id
        $builder->add('id', TextType::class, [
            'required' => false,
        ]);

        $builder->add('name', TextType::class, [
            'required' => true,
            'empty_data' => '',
        ]);

        $builder->add('description', TextType::class, [
            'empty_data' => '',
            'required' => true,
        ]);

        $builder->add('icon', UrlType::class, [
            'empty_data' => '',
            'required'   => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => NewsBoard::class,
        ]);
    }
}
