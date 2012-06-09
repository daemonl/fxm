<?php
namespace Rebase\BigvBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

use Doctrine\ORM\EntityRepository;

class TeamType extends AbstractType
{
  public function buildForm(FormBuilder $builder, array $options)
  {   
    $builder->add('name');
    $builder->add('division', 'entity', array('class' => 'RebaseBigvBundle:Division', 'property' => 'name', 'query_builder' => function(EntityRepository $er) {
        return $er->createQueryBuilder('funder')
                ->add('select', 'division')
                ->add('from', 'RebaseBigvBundle:Division division')
                ->add('where', 'division.season = ?1')
                ->setParameter(1, \Rebase\BigvBundle\Statics\Context::$season->getId());
        }));
  }
  public function getDefaultOptions(array $options)
  {
    return array(
        'data_class' => 'Rebase\BigvBundle\Entity\Team',
    );
  }
  public function getName()
  {
    return 'team';
  }
}
?>
