<?php

namespace AppBundle\Form;

use AppBundle\Entity\Rol;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\Valid;

class UsuarioType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre',TextType::class,[
                'label' => 'Nombre',
                'required' => false
            ])
            ->add('apellido',TextType::class,[
                'label' => 'Apellido',
                'required' => false
            ])
            ->add('correo',TextType::class,[
                'label' => 'Correo',
                'required' => false
            ])
            ->add('username',TextType::class,[
                'label' => 'Nombre de usuario',
                'required' => false
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Password',
                'required' => false
            ])
            ->add('rol', EntityType::class, [
                'label' => 'Nivel',
                'class' => Rol::class
            ]);
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $user = $event->getData();
            $form = $event->getForm();
            if (!$user || null === $user->getId()) {
                $form->add('password', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'required' => false,
                    'first_options' => ['label' => 'Password'],
                    'second_options' => ['label' => 'Repetir password'],
                    'invalid_message' => 'Los passwords no concuerdan'
                ]);
            } else {
                $form->add('password', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'required' => false,
                    'first_options' => ['label' => 'Password', 'empty_data' => $user->getPassword()],
                    'second_options' => ['label' => 'Repetir password', 'empty_data' => $user->getPassword()],
                    'invalid_message' => 'Los passwords no concuerdan'
                ]);
            }
        });

    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Usuario'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_usuario';
    }


}
