<?php
namespace Rebase\BigvBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class RoundType extends AbstractType
{
  public function buildForm(FormBuilder $builder, array $options)
  {
    $builder->add('number', 'number', array('required'=>true));
    $builder->add('start', 'date', array('required'=>true));
    $builder->add('end', 'date', array('required'=>true));     
  }
  public function getDefaultOptions(array $options)
  {
    return array(
        'data_class' => 'Rebase\BigvBundle\Entity\Round',
    );
  }
  public function getName()
  {
    return 'round';
  }
}
?>
