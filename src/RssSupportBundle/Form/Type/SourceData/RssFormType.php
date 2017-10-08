<?php declare(strict_types=1);

namespace RssSupportBundle\Form\Type\SourceData;

use AppBundle\Form\Type\ProvidesSourceDataFormInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;

class RssFormType extends AbstractType implements ProvidesSourceDataFormInterface
{
    /**
     * @see FormTypeInterface
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('url', UrlType::class);
    }

    /**
     * @see ProvidesSourceDataFormInterface
     *
     * @param string $sourceData
     * @param string $sourceType
     *
     * @return bool
     */
    public function isAbleToHandle($sourceData, string $sourceType) : bool
    {
        return $sourceType === 'rss';
    }

    /**
     * @see ProvidesSourceDataFormInterface
     * @param FormInterface $form
     */
    public function appendToMainForm(FormInterface $form)
    {
        $form->add('sourceData', get_called_class());
    }
}
