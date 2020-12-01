<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
// Importing the CaptchaType class 
// use Captcha\Bundle\CaptchaBundle\Form\Type\CaptchaType;

class RegistrationFormType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {

        parent::buildForm($builder, $options);
        $builder
                ->add('firtName', TextType::class, ['label' => 'FirtName'])
                ->add('lastName', TextType::class, ['label' => 'LastName'])
//                ->add('captchaCode', CaptchaType::class, [
//                    'captchaConfig' => 'RegisterCaptcha',
//                    'label' => 'register.input.captcha'
//                ])
        ;
//        $builder->remove('username');
    }

    public function getParent() {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function getBlockPrefix() {
        return 'app_user_registration';
    }

}
