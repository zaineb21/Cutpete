<?php

namespace App\Form;

use App\Entity\Produit;
use App\Entity\Promotion;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PromotionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libellePromotion')
            ->add('pourcentage')
            ->add('dateDebutPromotion')
            ->add('dateFinPourcentage')
            ->add('description')
            ->add('idProduit',EntityType::class,
                ['label'=>'id produit','class'=>Produit::class,
                    'choice_label'=>'id_produit','multiple'=>false,'expanded'=>false])
        ;
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Promotion::class,
        ]);
    }
}
