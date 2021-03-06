<?php

declare(strict_types=1);

namespace App\Form;

use App\Component\Product\Model\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AddItemType extends AbstractType {

    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator) {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->setAction($this->urlGenerator->generate('cart.addItem', ['id' => $builder->getData()->getId()]));

        $builder->add(
                'id',
                HiddenType::class
        );

        $builder->add(
                'submit',
                SubmitType::class,
                [
                    'label' => false,
                    'icon' => '<span class="mdi mdi-cart-plus"></span>',
//		      'icon_before' => '<span class="mdi mdi-cart-plus"></span>',
//	              'icon_after' => '<span class="mdi mdi-cart-plus"></span>',
                    'attr' => [
                        'title' => 'app.cart.addItem.button',
                        'class' => 'cco-product-addtocar btn btn-primary bmd-btn-fab bmd-btn-fab-sm',
                        'style' => 'top:7.5em;'
                    ]
                ]
        );
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => Product::class,
        ));
    }

}
