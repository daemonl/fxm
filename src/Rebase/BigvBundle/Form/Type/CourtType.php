<?php
namespace Rebase\BigvBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class CourtType extends AbstractType
{
  public function buildForm(FormBuilder $builder, array $options)
  {
      $builder->add('name', 'text', array('required'=>false, 'label'=>''));
      
  }
  public function getDefaultOptions(array $options)
  {
    return array(
        'data_class' => 'Rebase\BigvBundle\Entity\Court',
    );
  }
  public function getName()
  {
    return 'court';
  }
}
?>