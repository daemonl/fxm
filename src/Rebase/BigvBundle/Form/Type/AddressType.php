<?php
namespace Sl\PractBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class AddressType extends AbstractType
{
  public function buildForm(FormBuilder $builder, array $options)
  {
      $builder->add('firstline', 'text', array('required'=>false));
      $builder->add('secondline', 'text', array('required'=>false));
      $builder->add('city', 'text');
      $builder->add('postcode', 'text', array('required'=>false));
      $builder->add('state', 'text');
      $builder->add('country', 'text');
      
  }
  public function getDefaultOptions(array $options)
  {
    return array(
        'data_class' => 'Sl\PractBundle\Entity\Address',
    );
  }
  public function getName()
  {
    return 'address';
  }
}
?>
