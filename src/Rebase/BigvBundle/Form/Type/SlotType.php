<?php
namespace Rebase\BigvBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

use Doctrine\ORM\EntityRepository;


class SlotType extends AbstractType
{
  public function buildForm(FormBuilder $builder, array $options)
  {
  $builder->add('round', 'entity', array('class' => 'RebaseBigvBundle:Round', 'property' => 'number', 'query_builder' => function(EntityRepository $er) {
        return $er->createQueryBuilder('r')
                ->add('select', 'r')
                ->add('from', 'RebaseBigvBundle:Round r')
                ->add('where', 'r.season = ?1')
                ->setParameter(1, \Rebase\BigvBundle\Statics\Context::$season->getId());
        }));
    $builder->add('start', 'date', array('required'=>true));
    $builder->add('end', 'date', array('required'=>true));     
    $builder->add('priority', 'choice', array('required'=>true, 'choices'=>array(
        0=>'OK',
        1=>'Good',
        2=>'Great'
    )));  
  }
  public function getDefaultOptions(array $options)
  {
    return array(
        'data_class' => 'Rebase\BigvBundle\Entity\Slot',
    );
  }
  public function getName()
  {
    return 'slot';
  }
}
?>
