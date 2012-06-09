<?php
namespace Rebase\BigvBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class SlotType extends AbstractType
{
  public function buildForm(FormBuilder $builder, array $options)
  {
   // $builder->add('round', 'entity', array('required'=>true));
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
