<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Form;

use AppBundle\Entity\OpeningHours;
use AppBundle\Form\Type\DateTimePickerType;
use AppBundle\Form\Type\TagsInputType;
use AppBundle\Repository\HoursRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Defines the form used to create and manipulate blog posts.
 *
 * @author Ryan Weaver <weaverryan@gmail.com>
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 * @author Yonel Ceruto <yonelceruto@gmail.com>
 */
class OpeningsHoursType extends AbstractType
{

    private $hRepo;
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->hRepo = new HoursRepository();
        // For the full reference of options defined by each form field type
        // see http://symfony.com/doc/current/reference/forms/types.html

        // By default, form fields include the 'required' attribute, which enables
        // the client-side form validation. This means that you can't test the
        // server-side validation errors from the browser. To temporarily disable
        // this validation, set the 'required' attribute to 'false':
        // $builder->add('title', null, ['required' => false, ...]);

        $builder
            ->add('dayOfWeek', ChoiceType::class, array(
                'choices' => array(
                    new OpeningHours("Maandag"),
                    new OpeningHours("Dinsdag"),
                    new OpeningHours("Woensdag"),
                    new OpeningHours("Donderdag"),
                    new OpeningHours("Vrijdag"),
                    new OpeningHours("Zaterdag"),
                    new OpeningHours("Zondag"),
                ),
                'choice_label' => function($openingHour){
                    /** @var OpeningHours $openingHour */
                    return $openingHour->getDayOfWeek();
                }
            ))

            ->add('openingTime')

            ->add('closingTime');

        $allResults = $this->hRepo->getAll();
        print_r($allResults);

    }


    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OpeningHours::class,
        ]);
    }
}
